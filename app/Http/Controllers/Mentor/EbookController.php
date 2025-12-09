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
    private function ensureOwned(Ebook $ebook): void
    {
        if ($ebook->author_id !== Auth::id()) {
            abort(403);
        }
    }
    private function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        while (Ebook::where('slug', $slug)->when($ignoreId, function($q) use ($ignoreId){ $q->where('id','!=',$ignoreId); })->exists()) {
            $slug = $base.'-'.substr(str_shuffle($chars), 0, 1);
        }
        return $slug;
    }
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
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image_url' => 'nullable|url',
            'file' => 'required|file|mimes:pdf,epub,txt|max:20480',
            'price' => 'required|numeric|min:0',
            'mentor_share_percent' => 'required|integer|min:0|max:100',
            'status' => 'required|in:draft,published,archived',
        ]);

        $slug = $this->makeUniqueSlug($validated['title']);
        $authorId = Auth::id();
        $storedUrl = null;
        $coverUrl = $validated['cover_image_url'] ?? null;
        if ($request->hasFile('file')) {
            $original = $request->file('file')->getClientOriginalName();
            $safeName = now()->format('YmdHis').'_'.Str::slug(pathinfo($original, PATHINFO_FILENAME)).'.'.strtolower($request->file('file')->getClientOriginalExtension());
            $path = $request->file('file')->storeAs('ebooks/'.$authorId.'/'.$slug, $safeName, 'public');
            $storedUrl = Storage::url($path);
        }
        if ($request->hasFile('cover')) {
            $coverOriginal = $request->file('cover')->getClientOriginalName();
            $coverSafe = now()->format('YmdHis').'_'.Str::slug(pathinfo($coverOriginal, PATHINFO_FILENAME)).'.'.strtolower($request->file('cover')->getClientOriginalExtension());
            $coverPath = $request->file('cover')->storeAs('ebook_covers/'.$authorId.'/'.$slug, $coverSafe, 'public');
            $coverUrl = Storage::url($coverPath);
        }

        $ebook = Ebook::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'cover_image_url' => $coverUrl,
            'file_url' => $storedUrl,
            'price' => $validated['price'],
            'mentor_share_percent' => (int)$validated['mentor_share_percent'],
            'status' => $validated['status'],
            'verification_status' => 'pending',
            'author_id' => $authorId,
        ]);

        return redirect()->route('mentor.ebooks.index')->with('success', 'E-book berhasil dibuat!');
    }

    public function show(Ebook $ebook)
    {
        $this->ensureOwned($ebook);

        return view('pages.mentor.ebooks.show', compact('ebook'));
    }

    public function edit(Ebook $ebook)
    {
        $this->ensureOwned($ebook);

        return view('pages.mentor.ebooks.edit', compact('ebook'));
    }

    public function update(Request $request, Ebook $ebook)
    {
        $this->ensureOwned($ebook);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image_url' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,epub,txt|max:20480',
            'file_url' => 'nullable|url',
            'price' => 'required|numeric|min:0',
            'mentor_share_percent' => 'required|integer|min:0|max:100',
            'status' => 'required|in:draft,published,archived',
        ]);

        $slug = $this->makeUniqueSlug($validated['title'], $ebook->id);
        $authorId = Auth::id();
        $coverUrl = $ebook->cover_image_url;
        $fileUrl = $ebook->file_url;

        if ($request->hasFile('cover')) {
            $coverOriginal = $request->file('cover')->getClientOriginalName();
            $coverSafe = now()->format('YmdHis').'_'.Str::slug(pathinfo($coverOriginal, PATHINFO_FILENAME)).'.'.strtolower($request->file('cover')->getClientOriginalExtension());
            $coverPath = $request->file('cover')->storeAs('ebook_covers/'.$authorId.'/'.$slug, $coverSafe, 'public');
            $coverUrl = Storage::url($coverPath);
        } elseif (!empty($validated['cover_image_url'])) {
            $coverUrl = $validated['cover_image_url'];
        }

        if ($request->hasFile('file')) {
            $original = $request->file('file')->getClientOriginalName();
            $safeName = now()->format('YmdHis').'_'.Str::slug(pathinfo($original, PATHINFO_FILENAME)).'.'.strtolower($request->file('file')->getClientOriginalExtension());
            $path = $request->file('file')->storeAs('ebooks/'.$authorId.'/'.$slug, $safeName, 'public');
            $fileUrl = Storage::url($path);
        } elseif (!empty($validated['file_url'])) {
            $fileUrl = $validated['file_url'];
        }

        $data = [
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'cover_image_url' => $coverUrl,
            'file_url' => $fileUrl,
            'price' => $validated['price'],
            'mentor_share_percent' => (int)$validated['mentor_share_percent'],
            'status' => $validated['status'],
        ];
        if ($data['status'] === 'published' && ($ebook->verification_status ?? 'pending') !== 'approved') {
            $data['status'] = 'draft';
            session()->flash('warning', 'E-book belum diverifikasi admin. Status diubah menjadi Draft.');
        }
        $ebook->update($data);

        return redirect()->route('mentor.ebooks.index')->with('success', 'E-book berhasil diperbarui!');
    }

    public function destroy(Ebook $ebook)
    {
        $this->ensureOwned($ebook);

        $ebook->delete();

        return redirect()->route('mentor.ebooks.index')->with('success', 'E-book berhasil dihapus!');
    }
}
