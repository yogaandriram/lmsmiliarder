<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;

class EbookContentController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::with('author')
            ->orderByDesc('created_at')
            ->paginate(12);
        return view('pages.admin.ebooks.index', compact('ebooks'));
    }
}

