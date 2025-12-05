<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\UserEbookLibrary;

class SubscriptionController extends Controller
{
    public function index()
    {
        $courseSubs = Enrollment::with('user','course')->orderByDesc('enrolled_at')->paginate(10, ['*'], 'courses_page');
        $ebookSubs = UserEbookLibrary::with('user','ebook')->orderByDesc('created_at')->paginate(10, ['*'], 'ebooks_page');
        return view('pages.admin.subscriptions.index', compact('courseSubs','ebookSubs'));
    }
}

