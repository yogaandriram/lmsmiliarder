<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = User::findOrFail(Auth::id());
        return view('pages.member.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'bio' => ['nullable','string'],
            'job_title' => ['nullable','string','max:255'],
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars/'.$user->id, 'public');
            $validated['avatar_url'] = Storage::url($path);
        }

        $user->update([
            'name' => $validated['name'],
            'bio' => $validated['bio'] ?? $user->bio,
            'job_title' => $validated['job_title'] ?? $user->job_title,
            'avatar_url' => $validated['avatar_url'] ?? $user->avatar_url,
        ]);

        return redirect()->route('member.profile')->with('success','Profil berhasil diperbarui');
    }
}
