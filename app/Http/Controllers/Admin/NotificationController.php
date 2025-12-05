<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $items = Notification::where('recipient_id', Auth::id())
            ->orderByDesc('created_at')
            ->simplePaginate(20)
            ->withQueryString();
        return view('pages.admin.notifications.index', compact('items'));
    }
}
