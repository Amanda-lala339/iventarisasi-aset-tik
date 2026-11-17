<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query ke tabel activity_log
        $query = Activity::with('causer')->latest();

        // 1. Filter berdasarkan Modul (Log Name)
        if ($request->filled('module')) {
            $query->where('log_name', $request->module);
        }

        // 2. Filter berdasarkan Jenis Aktivitas (created, updated, deleted)
        if ($request->filled('activity')) {
            $query->where('event', $request->activity);
        }

        // 3. Filter berdasarkan Pengguna (causer_id)
        if ($request->filled('user')) {
            $query->where('causer_id', $request->user);
        }

        // 4. Filter berdasarkan Tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Ambil data dengan pagination (15 per halaman)
        $activities = $query->paginate(15);

        // Hitung statistik untuk kartu di atas
        $stats = [
            'total' => Activity::count(),
            'today' => Activity::whereDate('created_at', today())->count(),
            'added' => Activity::where('event', 'created')->whereDate('created_at', today())->count(),
            'modified' => Activity::whereIn('event', ['updated', 'deleted'])->whereDate('created_at', today())->count(),
        ];

        // Ambil daftar user untuk dropdown filter
        $users = \App\Models\User::select('id', 'name')->get();

        return view('audit-log.index', compact('activities', 'stats', 'users'));
    }
}