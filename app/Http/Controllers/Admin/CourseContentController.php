<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseContentController extends Controller
{
    public function index()
    {
        $courses = Course::with(['author','category'])
            ->withCount('enrollments')
            ->orderByDesc('created_at')
            ->paginate(12);
        return view('pages.admin.courses.index', compact('courses'));
    }
}
