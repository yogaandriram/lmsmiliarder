<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::where('author_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pages.mentor.ebooks.index', compact('ebooks'));
    }

    public function create()
    {
        return view('pages.mentor.ebooks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cover_image_url' => 'nullable|url',
            'file' => 'required|file|mimes:pdf,epub,txt|max:20480',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:draft,published,archived',
        ]);

        $slug = Str::slug($validated['title']);
        $authorId = Auth::id();
        $storedUrl = null;
        if ($request->hasFile('file')) {
            $original = $request->file('file')->getClientOriginalName();
            $safeName = now()->format('YmdHis').'_'.Str::slug(pathinfo($original, PATHINFO_FILENAME)).'.'.strtolower($request->file('file')->getClientOriginalExtension());
            $path = $request->file('file')->storeAs('ebooks/'.$authorId.'/'.$slug, $safeName, 'public');
            $storedUrl = Storage::url($path);
        }

        $ebook = Ebook::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'cover_image_url' => $validated['cover_image_url'],
            'file_url' => $storedUrl,
            'price' => $validated['price'],
            'status' => $validated['status'],
            'author_id' => $authorId,
        ]);

        return redirect()->route('mentor.ebooks.index')->with('success', 'E-book berhasil dibuat!');
    }

    public function show(Ebook $ebook)
    {
        $this->authorize('view', $ebook);

        return view('pages.mentor.ebooks.show', compact('ebook'));
    }

    public function edit(Ebook $ebook)
    {
        $this->authorize('update', $ebook);

        return view('pages.mentor.ebooks.edit', compact('ebook'));
    }

    public function update(Request $request, Ebook $ebook)
    {
        $this->authorize('update', $ebook);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cover_image_url' => 'nullable|url',
            'file_url' => 'required|url',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:draft,published,archived',
        ]);

        $ebook->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'],
            'cover_image_url' => $validated['cover_image_url'],
            'file_url' => $validated['file_url'],
            'price' => $validated['price'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('mentor.ebooks.index')->with('success', 'E-book berhasil diperbarui!');
    }

    public function destroy(Ebook $ebook)
    {
        $this->authorize('delete', $ebook);

        $ebook->delete();

        return redirect()->route('mentor.ebooks.index')->with('success', 'E-book berhasil dihapus!');
    }
}