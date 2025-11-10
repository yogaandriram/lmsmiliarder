<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Ebook;
use App\Models\MentorVerification;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'courses' => Course::count(),
            'ebooks' => Ebook::count(),
            'mentor_pending' => MentorVerification::where('status', 'pending')->count(),
            'transactions_pending' => Transaction::where('payment_status', 'pending')->count(),
        ];

        return view('pages.admin.dashboard', compact('stats'));
    }
}