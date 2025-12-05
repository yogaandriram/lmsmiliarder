<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\UserEbookLibrary;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $courses = Enrollment::where('user_id', Auth::id())
            ->with('course')
            ->orderByDesc('enrolled_at')
            ->paginate(10);

        $ebooks = UserEbookLibrary::where('user_id', Auth::id())
            ->with('ebook')
            ->orderByDesc('purchased_at')
            ->paginate(10);

        return view('pages.member.subscriptions.index', compact('courses','ebooks'));
    }
}

