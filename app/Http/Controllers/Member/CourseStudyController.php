<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LearningProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseStudyController extends Controller
{
    public function show(Course $course)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();
        if (!$enrollment) abort(403);

        $course->load(['modules.lessons' => function($q){ $q->orderBy('order'); }]);

        $lessonId = request()->query('lesson');
        $allLessons = $course->modules->flatMap(function($m){ return $m->lessons; })->values();
        $current = null;
        if ($lessonId) {
            $current = $allLessons->firstWhere('id', (int)$lessonId);
        }
        if (!$current) {
            $current = $allLessons->first();
        }

        $total = max(1, $allLessons->count());
        $currentIndex = $current ? (int)$allLessons->search(fn($l) => $l->id === $current->id) + 1 : 1;
        $progress = round($currentIndex * 100 / $total);

        $moduleLessons = $current && $current->module ? $current->module->lessons()->orderBy('order')->get() : collect();
        $moduleTotal = max(1, $moduleLessons->count());
        $moduleIndex = $current ? (int)$moduleLessons->search(fn($l) => $l->id === $current->id) + 1 : 1;
        $moduleCompleted = $moduleIndex >= $moduleTotal;
        $lessonCompleted = false;
        try {
            $lessonCompleted = $current ? LearningProgress::where('enrollment_id', $enrollment->id)
                ->where('lesson_id', $current->id)
                ->whereNotNull('completed_at')
                ->exists() : false;
        } catch (\Throwable $e) {
            $lessonCompleted = false;
        }

        return view('pages.member.courses.learn', compact('course','current','progress','total','currentIndex','moduleCompleted','lessonCompleted'));
    }

    public function complete(Request $request, Course $course, Lesson $lesson)
    {
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();
        if (!$enrollment) abort(403);
        if ($lesson->module && $lesson->module->course_id !== $course->id) abort(404);
        LearningProgress::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'completed_at' => now(),
                'updated_at' => now(),
            ]
        );
        return redirect()->route('member.courses.learn', [$course, 'lesson' => $lesson->id])
            ->with('success', 'Pelajaran ditandai selesai');
    }
}
