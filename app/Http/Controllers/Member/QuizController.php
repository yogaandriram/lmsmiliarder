<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\UserQuizAttempt;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class QuizController extends Controller
{
    private function ensureEnrolled(Course $course): void
    {
        $enrolled = Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->exists();
        if (!$enrolled) abort(403);
    }

    public function show(Course $course, Module $module)
    {
        $this->ensureEnrolled($course);
        if ($module->course_id !== $course->id) abort(404);
        $quiz = Quiz::where('module_id', $module->id)->with(['questions.options'])->firstOrFail();
        $attempt = UserQuizAttempt::where('user_id', Auth::id())->where('quiz_id', $quiz->id)->orderByDesc('completed_at')->first();
        if ($attempt) {
            return view('pages.member.courses.quiz-result', compact('course','module','quiz','attempt'));
        }
        return view('pages.member.courses.quiz', compact('course','module','quiz'));
    }

    public function submit(Request $request, Course $course, Module $module)
    {
        $this->ensureEnrolled($course);
        if ($module->course_id !== $course->id) abort(404);
        $quiz = Quiz::where('module_id', $module->id)->with(['questions.options'])->firstOrFail();
        if (UserQuizAttempt::where('user_id', Auth::id())->where('quiz_id', $quiz->id)->exists()) {
            return redirect()->route('member.courses.modules.quiz.show', [$course, $module])
                ->with('warning','Anda sudah menyelesaikan kuis modul ini.');
        }
        $answers = $request->input('answers', []);
        $correct = 0; $total = max(1, $quiz->questions->count());
        foreach ($quiz->questions as $q) {
            $ans = $answers[$q->id] ?? null;
            if (!$ans) continue;
            $opt = $q->options->firstWhere('id', (int)$ans);
            if ($opt && $opt->is_correct) $correct++;
        }
        $score = round($correct * 100 / $total, 2);
        UserQuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'started_at' => Carbon::now(),
            'completed_at' => Carbon::now(),
        ]);
        return redirect()->route('member.courses.modules.quiz.show', [$course, $module])
            ->with('success', 'Kuis selesai. Skor: '.$score.'%');
    }
}
