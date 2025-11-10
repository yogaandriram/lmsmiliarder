<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MentorVerification;
use App\Models\User;
use Illuminate\Http\Request;

class MentorVerificationController extends Controller
{
    public function index()
    {
        $pending = MentorVerification::with('user')
            ->where('status','pending')
            ->orderBy('created_at','desc')
            ->get();
        return view('pages.admin.mentor_verifications.index', compact('pending'));
    }

    public function approve(MentorVerification $verification, Request $request)
    {
        $verification->update([
            'status' => 'approved',
            'notes' => $request->input('notes')
        ]);
        if ($verification->user) {
            $verification->user->update(['role' => 'mentor']);
        }
        return redirect()->route('admin.mentor_verifications.index')->with('success','Pengajuan mentor disetujui');
    }

    public function reject(MentorVerification $verification, Request $request)
    {
        $verification->update([
            'status' => 'rejected',
            'notes' => $request->input('notes')
        ]);
        return redirect()->route('admin.mentor_verifications.index')->with('success','Pengajuan mentor ditolak');
    }
}