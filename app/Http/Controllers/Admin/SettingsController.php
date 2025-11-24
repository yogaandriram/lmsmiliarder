<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        $members = \App\Models\User::orderBy('name')->get();
        $accounts = \App\Models\AdminBankAccount::orderBy('bank_name')->get();
        $coupons = \App\Models\Coupon::orderByDesc('expires_at')->get();
        $tab = request('tab', 'accounts');
        return view('pages.admin.settings.index', compact('members','accounts','coupons','tab'));
    }
}