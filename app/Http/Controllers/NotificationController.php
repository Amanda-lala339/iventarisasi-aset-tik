<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tandai SEMUA notifikasi sebagai dibaca
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Tandai SATU notifikasi sebagai dibaca saat diklik, lalu arahkan ke URL tujuannya
     */
    public function markAsRead(Request $request, $id)
    {
        // Cari notifikasi milik user yang sedang login
        $notification = $request->user()->notifications()->findOrFail($id);
        
        // Tandai sebagai dibaca
        $notification->markAsRead();

        // Ambil URL tujuan dari data notifikasi, fallback ke dashboard jika tidak ada
        $url = $notification->data['url'] ?? route('dashboard');

        return redirect($url);
    }
}