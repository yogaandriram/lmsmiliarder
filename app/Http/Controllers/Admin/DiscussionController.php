<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscussionGroup;
use App\Models\DiscussionMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    public function index()
    {
        $groups = DiscussionGroup::with(['course' => function($q){ $q->withCount('enrollments'); }])
            ->orderByDesc('created_at')
            ->get();
        return view('pages.admin.discussions.index', compact('groups'));
    }

    public function chat(DiscussionGroup $group)
    {
        $messages = DiscussionMessage::where('group_id', $group->id)
            ->with('user')
            ->orderBy('created_at')
            ->get();
        return view('pages.admin.discussions.chat', compact('group','messages'));
    }

    public function postMessage(Request $request, DiscussionGroup $group)
    {
        $data = $request->validate([
            'content' => 'required|string',
            'attachment' => 'nullable|file|max:5120',
        ]);
        $filePath = null; $original = null; $mime = null;
        if($request->hasFile('attachment')){
            $f = $request->file('attachment');
            $original = $f->getClientOriginalName();
            $mime = $f->getMimeType();
            $filePath = $f->store('discussion_attachments','public');
        }
        DiscussionMessage::create([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'content' => $data['content'],
            'file_url' => $filePath ? \Illuminate\Support\Facades\Storage::url($filePath) : null,
            'original_name' => $original,
            'mime_type' => $mime,
        ]);
        return redirect()->route('admin.discussions.chat', $group);
    }

    public function fetchMessages(DiscussionGroup $group)
    {
        $messages = DiscussionMessage::where('group_id', $group->id)
            ->with('user')
            ->orderBy('created_at')
            ->get()
            ->map(function($m){
                return [
                    'user' => $m->user->name,
                    'avatar' => $m->user->avatar_url,
                    'time' => optional($m->created_at)->format('H:i'),
                    'content' => $m->content,
                    'file_url' => $m->file_url,
                    'original_name' => $m->original_name,
                    'mime_type' => $m->mime_type,
                ];
            });
        return response()->json(['messages' => $messages]);
    }
}

