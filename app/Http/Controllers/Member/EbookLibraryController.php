<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\UserEbookLibrary;
use Illuminate\Support\Facades\Auth;

class EbookLibraryController extends Controller
{
    public function index()
    {
        $items = UserEbookLibrary::where('user_id', Auth::id())
            ->with(['ebook.author'])
            ->orderByDesc('purchased_at')
            ->get();

        return view('pages.member.ebooks.index', [
            'items' => $items,
        ]);
    }
}

