<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\MentorVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = User::findOrFail(Auth::id());
        $verifications = MentorVerification::where('user_id', $user->id)
            ->orderBy('created_at','desc')
            ->get();
        $currentStatus = optional($verifications->first())->status ?? 'pending';
        $hasCv = $verifications->contains(function($v){ return is_string($v->notes ?? null) && str_contains($v->notes, 'type: cv'); });
        $hasPortfolio = $verifications->contains(function($v){ return is_string($v->notes ?? null) && str_contains($v->notes, 'type: portfolio'); });
        $hasDocs = $hasCv || $hasPortfolio;
        return view('pages.mentor.profile', compact('user','verifications','currentStatus','hasDocs'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'bio' => ['nullable','string'],
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars/'.$user->id, 'public');
            $validated['avatar_url'] = Storage::url($path);
        }

        $user->update([
            'name' => $validated['name'],
            'bio' => $validated['bio'] ?? $user->bio,
            'avatar_url' => $validated['avatar_url'] ?? $user->avatar_url,
        ]);

        return redirect()->route('mentor.profile')->with('success','Profil berhasil diperbarui');
    }

    public function storeDocument(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $validated = $request->validate([
            'type' => ['required','in:cv,portfolio'],
            'document' => ['required','file','mimes:pdf,doc,docx,jpg,jpeg,png,webp','max:5120'],
        ]);
        DB::transaction(function() use ($request, $user, $validated) {
            MentorVerification::where('user_id', $user->id)
                ->where('notes', 'like', '%type: '.$validated['type'].'%')
                ->delete();

            $path = $request->file('document')->store('mentor_docs/'.$user->id.'/'.$validated['type'], 'public');
            $url = Storage::url($path);

            MentorVerification::create([
                'user_id' => $user->id,
                'document_url' => $url,
                'status' => 'pending',
                'notes' => 'type: '.$validated['type'],
            ]);
        });

        return redirect()->route('mentor.profile')->with('success','Dokumen berhasil diunggah ulang');
    }

    public function storeDocumentsBulk(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $validated = $request->validate([
            'cv_document' => ['nullable','file','mimes:pdf,doc,docx,jpg,jpeg,png,webp','max:5120'],
            'portfolio_document' => ['nullable','file','mimes:pdf,doc,docx,jpg,jpeg,png,webp','max:5120'],
        ]);
        DB::transaction(function() use ($request, $user) {
            if ($request->hasFile('cv_document')) {
                MentorVerification::where('user_id', $user->id)
                    ->where('notes', 'like', '%type: cv%')
                    ->delete();
                $path = $request->file('cv_document')->store('mentor_docs/'.$user->id.'/cv', 'public');
                $url = Storage::url($path);
                MentorVerification::create([
                    'user_id' => $user->id,
                    'document_url' => $url,
                    'status' => 'pending',
                    'notes' => 'type: cv',
                ]);
            }

            if ($request->hasFile('portfolio_document')) {
                MentorVerification::where('user_id', $user->id)
                    ->where('notes', 'like', '%type: portfolio%')
                    ->delete();
                $path = $request->file('portfolio_document')->store('mentor_docs/'.$user->id.'/portfolio', 'public');
                $url = Storage::url($path);
                MentorVerification::create([
                    'user_id' => $user->id,
                    'document_url' => $url,
                    'status' => 'pending',
                    'notes' => 'type: portfolio',
                ]);
            }
        });

        return redirect()->route('mentor.profile')->with('success','Dokumen berhasil disimpan ulang');
    }
}