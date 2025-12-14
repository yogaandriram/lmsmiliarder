<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Support\Facades\Auth;

class EbookController extends Controller
{
    public function show(string $slug)
    {
        $ebook = Ebook::with(['author'])
            ->where('slug', $slug)
            ->firstOrFail();

        $isPublished = $ebook->status === 'published';
        $canPreview = Auth::check() && (Auth::id() === $ebook->author_id || Auth::user()->role === 'admin');
        if (!($isPublished || $canPreview)) {
            abort(404);
        }

        return view('pages.public.ebook.show', compact('ebook'));
    }
}
