<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;

class SettingsController extends Controller
{
    public function index()
    {
        $members = \App\Models\User::orderBy('name')->get();
        $accounts = \App\Models\AdminBankAccount::orderBy('bank_name')->get();
        $coupons = \App\Models\Coupon::orderByDesc('expires_at')->get();
        $tab = request('tab', 'accounts');
        $admin_fee = PlatformSetting::get('commission_admin_fee', '0');
        return view('pages.admin.settings.index', compact('members','accounts','coupons','tab','admin_fee'));
    }

    public function saveCommission()
    {
        $fee = request('commission_admin_fee');
        PlatformSetting::set('commission_admin_fee', $fee ?? '0');
        return redirect()->route('admin.settings.index', ['tab' => 'commissions'])->with('success','Pengaturan komisi disimpan');
    }
}
