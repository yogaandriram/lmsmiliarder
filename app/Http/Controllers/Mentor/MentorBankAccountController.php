<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\MentorBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorBankAccountController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'bank_name' => ['required','string','max:80'],
            'account_number' => ['required','string','max:60'],
            'account_holder_name' => ['required','string','max:120'],
            'is_active' => ['nullable'],
            'is_default' => ['nullable'],
        ]);
        $data['user_id'] = Auth::id();
        $data['is_active'] = $request->boolean('is_active');
        $data['is_default'] = $request->boolean('is_default');
        if ($data['is_default']) {
            MentorBankAccount::where('user_id', Auth::id())->update(['is_default' => false]);
        }
        MentorBankAccount::create($data);
        return redirect()->route('mentor.settings', ['tab' => 'accounts'])->with('success','Rekening ditambahkan');
    }

    public function update(Request $request, MentorBankAccount $mentor_bank_account)
    {
        if ($mentor_bank_account->user_id !== Auth::id()) abort(403);
        $data = $request->validate([
            'bank_name' => ['required','string','max:80'],
            'account_number' => ['required','string','max:60'],
            'account_holder_name' => ['required','string','max:120'],
            'is_active' => ['nullable'],
            'is_default' => ['nullable'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_default'] = $request->boolean('is_default');
        if ($data['is_default']) {
            MentorBankAccount::where('user_id', Auth::id())->where('id','!=',$mentor_bank_account->id)->update(['is_default' => false]);
        }
        $mentor_bank_account->update($data);
        return back()->with('success','Rekening diperbarui');
    }

    public function destroy(MentorBankAccount $mentor_bank_account)
    {
        if ($mentor_bank_account->user_id !== Auth::id()) abort(403);
        $mentor_bank_account->delete();
        return redirect()->route('mentor.settings', ['tab' => 'accounts'])->with('success','Rekening dihapus');
    }

    public function setDefault(MentorBankAccount $mentor_bank_account)
    {
        if ($mentor_bank_account->user_id !== Auth::id()) abort(403);
        MentorBankAccount::where('user_id', Auth::id())->update(['is_default' => false]);
        $mentor_bank_account->is_default = true;
        $mentor_bank_account->save();
        return back()->with('success','Rekening default diperbarui');
    }
}
