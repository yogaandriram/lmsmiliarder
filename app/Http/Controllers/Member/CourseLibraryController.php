<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class CourseLibraryController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with('course')
            ->orderByDesc('enrolled_at')
            ->get();

        return view('pages.member.courses.index', [
            'enrollments' => $enrollments,
        ]);
    }
}

