<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Request $request, $id)
    {
        $user = Auth::user();
        $notif = $user->notifications()->where('id', $id)->firstOrFail();
        if (!$notif->read_at) {
            $notif->markAsRead();
        }

        return redirect()->back();
    }

    public function markAllRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}
