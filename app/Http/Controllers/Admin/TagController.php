<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('name')->get();
        return view('pages.admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('pages.admin.tags.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'is_active' => ['nullable','boolean'],
        ]);
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        Tag::create($data);
        return redirect()->route('admin.tags.index')->with('success', 'Tag berhasil dibuat');
    }

    public function edit(Tag $tag)
    {
        return view('pages.admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'is_active' => ['nullable','boolean'],
        ]);
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $tag->update($data);
        return redirect()->route('admin.tags.index')->with('success', 'Tag diperbarui');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'Tag dihapus');
    }

    public function toggle(Tag $tag)
    {
        $tag->is_active = ! $tag->is_active;
        $tag->save();
        return back()->with('success', 'Status tag diperbarui');
    }
}