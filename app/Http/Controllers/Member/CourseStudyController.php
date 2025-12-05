<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class CourseStudyController extends Controller
{
    public function show(Course $course)
    {
        $enrolled = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->exists();
        if (!$enrolled) abort(403);

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

        return view('pages.member.courses.learn', compact('course','current','progress','total','currentIndex','moduleCompleted'));
    }
}
