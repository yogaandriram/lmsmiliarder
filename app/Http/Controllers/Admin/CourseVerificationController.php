<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class CourseVerificationController extends Controller
{
    public function index()
    {
        $courses = Course::where('verification_status', 'pending')
            ->with(['author','category'])
            ->orderByDesc('created_at')
            ->get();
        return view('pages.admin.course_verifications.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load(['author','category','tags','modules.lessons'])
               ->loadCount('enrollments');
        return view('pages.admin.course_verifications.show', compact('course'));
    }

    public function approve(Course $course)
    {
        $course->update([
            'verification_status' => 'approved',
            'verified_at' => now(),
        ]);
        return redirect()->route('admin.course_verifications.index')->with('success','Kursus disetujui');
    }

    public function reject(Course $course)
    {
        $course->update([
            'verification_status' => 'rejected',
            'verified_at' => null,
        ]);
        return redirect()->route('admin.course_verifications.index')->with('success','Kursus ditolak');
    }

    public function showLesson(Course $course, Lesson $lesson)
    {
        if ($lesson->module->course_id !== $course->id) abort(404);
        $lesson->load('module.course');
        return view('pages.admin.course_verifications.lesson', compact('course','lesson'));
    }
}