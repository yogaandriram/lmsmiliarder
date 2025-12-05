<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\MentorBankAccount;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $tab = request('tab', 'accounts');
        $accounts = MentorBankAccount::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->orderBy('bank_name')
            ->get();
        return view('pages.mentor.settings', compact('accounts','tab'));
    }
}
