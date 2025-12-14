<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $q = request('q');
        $courses = Course::where('status', 'published')
            ->when($q, function ($query, $q) {
                return $query->where('title', 'like', "%{$q}%");
            })
            ->with(['author', 'category'])
            ->withCount(['enrollments', 'modules'])
            ->withSum('lessons as total_duration', 'duration_minutes')
            ->latest()
            ->paginate(12);

        $categories = \App\Models\Category::all();
        $tags = \App\Models\Tag::all();

        return view('courses', compact('courses', 'categories', 'tags'));
    }

    public function show(string $slug)
    {
        $course = Course::with(['author','category','tags','modules.lessons'])
            ->withCount('enrollments')
            ->where('slug', $slug)
            ->firstOrFail();

        $isPublished = $course->status === 'published';
        $canPreview = Auth::check() && (Auth::id() === $course->author_id || Auth::user()->role === 'admin');
        if (!($isPublished || $canPreview)) {
            abort(404);
        }

        $totalDurationMinutes = 0;
        foreach (($course->modules ?? []) as $module) {
            foreach (($module->lessons ?? []) as $lesson) {
                $totalDurationMinutes += (int)($lesson->duration_minutes ?? 0);
            }
        }

        return view('pages.public.courses.show', compact('course', 'totalDurationMinutes'));
    }

    public function showByAuthorCourse(string $mentor, string $course)
    {
        $user = User::whereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [Str::slug($mentor)])->firstOrFail();
        $model = Course::with(['author','category','tags','modules.lessons'])
            ->withCount('enrollments')
            ->where('author_id', $user->id)
            ->where('slug', $course)
            ->firstOrFail();

        $isPublished = $model->status === 'published';
        $canPreview = Auth::check() && (Auth::id() === $model->author_id || Auth::user()->role === 'admin');
        if (!($isPublished || $canPreview)) {
            abort(404);
        }

        $totalDurationMinutes = 0;
        foreach (($model->modules ?? []) as $module) {
            foreach (($module->lessons ?? []) as $lesson) {
                $totalDurationMinutes += (int)($lesson->duration_minutes ?? 0);
            }
        }

        return view('pages.public.courses.show', ['course' => $model, 'totalDurationMinutes' => $totalDurationMinutes]);
    }
}