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
        $pendingUserIds = MentorVerification::where('status','pending')->pluck('user_id')->unique();
        $pendingUsers = User::whereIn('id', $pendingUserIds)->get();
        return view('pages.admin.mentor_verifications.index', [
            'pendingUsers' => $pendingUsers,
        ]);
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

    public function show(User $user)
    {
        $documents = MentorVerification::where('user_id', $user->id)
            ->orderBy('created_at','desc')
            ->get();
        return view('pages.admin.mentor_verifications.show', compact('user','documents'));
    }

    public function approveUser(User $user, Request $request)
    {
        MentorVerification::where('user_id', $user->id)
            ->where('status','pending')
            ->update(['status' => 'approved']);
        $user->update(['role' => 'mentor']);
        return redirect()->route('admin.mentor_verifications.index')->with('success','Mentor disetujui');
    }

    public function rejectUser(User $user, Request $request)
    {
        MentorVerification::where('user_id', $user->id)
            ->where('status','pending')
            ->update(['status' => 'rejected']);
        return redirect()->route('admin.mentor_verifications.index')->with('success','Pengajuan mentor ditolak');
    }
}