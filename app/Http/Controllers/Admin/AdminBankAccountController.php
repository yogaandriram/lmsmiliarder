<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminBankAccount;
use Illuminate\Http\Request;

class AdminBankAccountController extends Controller
{
    public function index() {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'bank_name' => ['required','string','max:80'],
            'account_number' => ['required','string','max:60'],
            'account_holder_name' => ['required','string','max:120'],
            'is_active' => ['nullable'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        AdminBankAccount::create($data);
        return redirect()->route('admin.settings.index')->with('success','Rekening bank ditambahkan');
    }

    public function destroy(AdminBankAccount $admin_bank_account)
    {
        $admin_bank_account->delete();
        return redirect()->route('admin.settings.index')->with('success','Rekening bank dihapus');
    }

    public function update(Request $request, AdminBankAccount $admin_bank_account)
    {
        $data = $request->validate([
            'bank_name' => ['required','string','max:80'],
            'account_number' => ['required','string','max:60'],
            'account_holder_name' => ['required','string','max:120'],
            'is_active' => ['nullable'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $admin_bank_account->update($data);
        return back()->with('success','Rekening bank diperbarui');
    }
}