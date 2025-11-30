<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class MentorManageController extends Controller
{
    public function index()
    {
        $mentors = User::where('role', 'mentor')
            ->orderBy('name')
            ->paginate(10);
        return view('pages.admin.mentors.index', compact('mentors'));
    }
}

