<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    private function ensureOwned(Course $course): void
    {
        if ($course->author_id !== Auth::id()) abort(403);
    }

    public function storeForModule(Request $request, Course $course, Module $module)
    {
        $this->ensureOwned($course);
        if ($module->course_id !== $course->id) abort(404);

        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'time_limit_minutes' => ['nullable','integer','min:0'],
        ]);

        if (Quiz::where('module_id', $module->id)->exists()) {
            return redirect()->route('mentor.courses.modules.show', [$course, $module])
                ->with('warning', 'Modul ini sudah memiliki kuis. Hanya satu kuis per modul.');
        }

        Quiz::create([
            'module_id' => $module->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'time_limit_minutes' => $validated['time_limit_minutes'] ?? null,
        ]);

        return redirect()->route('mentor.courses.modules.show', [$course, $module])
            ->with('success', 'Kuis modul berhasil dibuat');
    }
}