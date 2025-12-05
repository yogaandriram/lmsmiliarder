<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\DiscussionGroup;
use App\Models\DiscussionMessage;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function index()
    {
        $groups = DiscussionGroup::with(['course' => function($q){ $q->withCount('enrollments'); }])
            ->whereHas('course', function($q){
                $q->whereIn('id', Enrollment::where('user_id', Auth::id())->pluck('course_id'));
            })
            ->orderByDesc('id')
            ->get();
        return view('pages.member.discussions.index', compact('groups'));
    }

    public function chat(DiscussionGroup $group)
    {
        $course = $group->course;
        $isMember = Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->exists();
        if (!$isMember) abort(403);
        $messages = DiscussionMessage::where('group_id', $group->id)->with('user')->orderBy('id','asc')->limit(200)->get();
        return view('pages.member.discussions.chat', compact('group','messages'));
    }

    public function postMessage(Request $request, DiscussionGroup $group)
    {
        $course = $group->course;
        $isMember = Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->exists();
        if (!$isMember) abort(403);
        $validated = $request->validate([
            'content' => ['nullable','string'],
            'file' => ['nullable','file','max:5120','mimes:jpg,jpeg,png,webp,pdf,doc,docx']
        ]);

        $fileData = [
            'file_url' => null,
            'mime_type' => null,
            'original_name' => null,
        ];
        if ($request->hasFile('file')) {
            $f = $request->file('file');
            $path = $f->store('discussion_files/'.$group->id.'/'.Auth::id(), 'public');
            $fileData['file_url'] = \Illuminate\Support\Facades\Storage::url($path);
            $fileData['mime_type'] = $f->getMimeType();
            $fileData['original_name'] = $f->getClientOriginalName();
        }

        DiscussionMessage::create([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'] ?? '',
            'file_url' => $fileData['file_url'],
            'mime_type' => $fileData['mime_type'],
            'original_name' => $fileData['original_name'],
            'created_at' => now(),
        ]);
        return redirect()->route('member.discussions.chat', $group);
    }

    public function fetchMessages(DiscussionGroup $group)
    {
        $course = $group->course;
        $isMember = Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->exists();
        if (!$isMember) abort(403);
        $messages = DiscussionMessage::where('group_id', $group->id)->with('user')->orderBy('id','asc')->limit(500)->get()->map(function($m){
            return [
                'id' => $m->id,
                'user' => $m->user->name,
                'avatar' => $m->user->avatar_url,
                'content' => $m->content,
                'time' => \Illuminate\Support\Carbon::parse($m->created_at)->format('H:i'),
                'file_url' => $m->file_url,
                'mime_type' => $m->mime_type,
                'original_name' => $m->original_name,
            ];
        });
        return response()->json(['messages' => $messages]);
    }
}
