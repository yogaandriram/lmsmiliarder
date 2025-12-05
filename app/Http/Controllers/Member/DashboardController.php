<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\UserEbookLibrary;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $courseCount = Enrollment::where('user_id', $userId)->count();
        $ebookCount = UserEbookLibrary::where('user_id', $userId)->count();

        $latestCourses = Enrollment::where('user_id', $userId)
            ->with('course')
            ->orderByDesc('enrolled_at')
            ->limit(5)
            ->get();

        $latestEbooks = UserEbookLibrary::where('user_id', $userId)
            ->with('ebook')
            ->orderByDesc('purchased_at')
            ->limit(5)
            ->get();

        return view('pages.member.dashboard', compact('courseCount','ebookCount','latestCourses','latestEbooks'));
    }
}

