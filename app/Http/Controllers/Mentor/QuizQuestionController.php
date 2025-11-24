<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuizQuestionController extends Controller
{
    private function ensureOwned(Course $course): void
    {
        if ($course->author_id !== Auth::id()) abort(403);
    }

    public function store(Request $request, Course $course, Module $module)
    {
        $this->ensureOwned($course);
        if ($module->course_id !== $course->id) abort(404);
        $quiz = Quiz::where('module_id', $module->id)->firstOrFail();

        Log::info('quiz_question.store:start', [
            'course_id' => $course->id,
            'module_id' => $module->id,
            'quiz_id' => $quiz->id,
            'payload' => $request->all(),
        ]);

        $validated = $request->validate([
            'question_text' => ['required','string'],
            'question_type' => ['required','in:multiple_choice,essay'],
            'order' => ['nullable','integer','min:1'],
            'options' => ['array'],
            'options.*.text' => ['nullable','string'],
            // Checkbox "on" tidak lolos rule boolean; longgarkan rule agar validasi tidak menggagalkan proses
            'options.*.is_correct' => ['nullable'],
        ]);
        Log::info('quiz_question.store:validated', [
            'question_type' => $validated['question_type'],
            'options_count' => is_array($request->input('options')) ? count($request->input('options')) : 0,
        ]);

        $type = str_replace('-', '_', strtolower($validated['question_type']));
        if (!in_array($type, ['multiple_choice','essay'])) { $type = 'essay'; }
        $q = null;
        try {
        DB::transaction(function() use (&$q, $quiz, $validated, $type, $request) {
            // Always use raw insert with backticks to avoid reserved keyword issues
            $pdo = DB::getPdo();
            Log::info('quiz_question.store:before_insert', ['quiz_id' => $quiz->id]);
            $stmt = $pdo->prepare('INSERT INTO `quiz_questions` (`quiz_id`,`question_text`,`question_type`,`question_order`) VALUES (?,?,?,?)');
            $stmt->execute([
                $quiz->id,
                $validated['question_text'],
                $type,
                $validated['order'] ?? (($quiz->questions()->max('question_order') ?? 0) + 1),
            ]);
            $id = (int)$pdo->lastInsertId();
            $q = QuizQuestion::find($id);
            Log::info('quiz_question.store:created_question', ['question_id' => $q ? $q->id : null, 'last_insert_id' => $id]);
            Log::info('quiz_question.store:created_question', ['question_id' => $q ? $q->id : null]);

            if ($type === 'multiple_choice') {
                $rawOptions = $request->input('options', []);
                $clean = [];
                foreach ($rawOptions as $opt) {
                    $text = isset($opt['text']) ? trim($opt['text']) : '';
                    if ($text !== '') {
                        $clean[] = [
                            'text' => $text,
                            'is_correct' => (bool)($opt['is_correct'] ?? false),
                        ];
                    }
                }
                if (count($clean) < 2) {
                    $clean = array_values($clean);
                    $clean[] = ['text' => 'Pilihan 1', 'is_correct' => true];
                    $clean[] = ['text' => 'Pilihan 2', 'is_correct' => false];
                }
                $hasCorrect = false;
                foreach ($clean as $opt) {
                    $isCorrect = (bool)$opt['is_correct'];
                    $hasCorrect = $hasCorrect || $isCorrect;
                    QuizOption::create([
                        'question_id' => $q->id,
                        'option_text' => $opt['text'],
                        'is_correct' => $isCorrect,
                    ]);
                }
                Log::info('quiz_question.store:created_options', ['count' => count($clean), 'question_id' => $q->id]);
                if (!$hasCorrect) {
                    $first = $q->options()->first();
                    if ($first) { $first->update(['is_correct' => true]); }
                }
            }
        });
        } catch (\Throwable $e) {
            Log::error('quiz_question.store:error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('mentor.courses.modules.show', [$course, $module])
                ->with('warning', 'Gagal menyimpan pertanyaan: '.$e->getMessage());
        }

        return redirect()->route('mentor.courses.modules.show', [$course, $module])->with('success','Pertanyaan kuis ditambahkan');
    }
}