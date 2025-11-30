<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;

class EbookVerificationController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::where('verification_status', 'pending')
            ->with('author')
            ->orderByDesc('created_at')
            ->get();
        return view('pages.admin.ebook_verifications.index', compact('ebooks'));
    }

    public function show(Ebook $ebook)
    {
        $ebook->load('author');
        return view('pages.admin.ebook_verifications.show', compact('ebook'));
    }

    public function approve(Ebook $ebook)
    {
        $ebook->update([
            'verification_status' => 'approved',
            'verified_at' => now(),
        ]);
        return redirect()->route('admin.ebook_verifications.index')->with('success','E-book disetujui');
    }

    public function reject(Ebook $ebook)
    {
        $ebook->update([
            'verification_status' => 'rejected',
            'verified_at' => null,
        ]);
        return redirect()->route('admin.ebook_verifications.index')->with('success','E-book ditolak');
    }
}

