<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
        // Tandai semua notifikasi user yang sedang login sebagai "sudah dibaca"
        $request->user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
