<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\DiscussionGroup;
use App\Models\Enrollment;
use App\Models\DiscussionMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    public function index()
    {
        $groups = DiscussionGroup::with(['course' => function($q){ $q->withCount('enrollments'); }])
            ->whereHas('course', function($q){ $q->where('author_id', Auth::id()); })
            ->orderByDesc('id')
            ->get();

        return view('pages.mentor.discussions.index', compact('groups'));
    }


    public function chat(DiscussionGroup $group)
    {
        $user = Auth::user();
        $course = $group->course;
        $isOwner = $course && $course->author_id === $user->id;
        $isAdmin = $user->role === 'admin';
        $isMember = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists();
        if (!($isOwner || $isAdmin || $isMember)) abort(403);
        $messages = DiscussionMessage::where('group_id', $group->id)->with('user')->orderBy('id','asc')->limit(200)->get();
        return view('pages.mentor.discussions.chat', compact('group','messages'));
    }

    public function postMessage(Request $request, DiscussionGroup $group)
    {
        $user = Auth::user();
        $course = $group->course;
        $isOwner = $course && $course->author_id === $user->id;
        $isAdmin = $user->role === 'admin';
        $isMember = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists();
        if (!($isOwner || $isAdmin || $isMember)) abort(403);
        $validated = $request->validate([
            'content' => ['nullable','string'],
            'attachment' => ['nullable','file','max:5120','mimes:jpg,jpeg,png,webp,gif,pdf,doc,docx,xls,xlsx,zip']
        ]);

        $fileUrl = null; $mime = null; $orig = null;
        if ($request->file('attachment')) {
            $file = $request->file('attachment');
            $mime = $file->getMimeType();
            $orig = $file->getClientOriginalName();
            $path = $file->store('discussion_uploads/'.$group->id, 'public');
            $fileUrl = '/storage/'.$path;
        }

        DiscussionMessage::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'content' => $validated['content'] ?? '',
            'file_url' => $fileUrl,
            'mime_type' => $mime,
            'original_name' => $orig,
        ]);
        return redirect()->route('mentor.discussions.chat', $group);
    }

    public function fetchMessages(DiscussionGroup $group)
    {
        $user = Auth::user();
        $course = $group->course;
        $isOwner = $course && $course->author_id === $user->id;
        $isAdmin = $user->role === 'admin';
        $isMember = Enrollment::where('user_id', $user->id)->where('course_id', $course->id)->exists();
        if (!($isOwner || $isAdmin || $isMember)) abort(403);
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