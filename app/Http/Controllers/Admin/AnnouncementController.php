<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('created_at','desc')->get();
        return view('pages.admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:200'],
            'content' => ['required','string'],
        ]);
        Announcement::create($data);
        return redirect()->route('admin.announcements.index')->with('success','Pengumuman ditambahkan');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success','Pengumuman dihapus');
    }
}