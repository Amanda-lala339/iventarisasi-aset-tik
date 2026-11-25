<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subdomain;
use App\Models\User; // Pastikan model User ada jika ingin kirim ke admin
use Carbon\Carbon;

class CheckExpiringSubdomains extends Command
{
    protected $signature = 'subdomains:check-expiring';
    protected $description = 'Mengecek dan mengirim notifikasi untuk subdomain yang akan expired';

    public function handle()
    {
        // Cari subdomain yang statusnya Expiring ATAU ssl_expiry <= 30 hari dari sekarang
        $expiringSubdomains = Subdomain::where('status', 'Expiring')
            ->orWhere(function ($query) {
                $query->where('ssl_expiry', '<=', Carbon::now()->addDays(30))
                      ->where('ssl_expiry', '>=', Carbon::now());
            })
            ->get();

        if ($expiringSubdomains->isEmpty()) {
            $this->info('Tidak ada subdomain yang akan expired dalam 30 hari ke depan.');
            return;
        }

        $this->info("Ditemukan {$expiringSubdomains->count()} subdomain yang akan expired. Mengirim notifikasi...");

        foreach ($expiringSubdomains as $subdomain) {
            // Kirim notifikasi (Bisa via Email, Database, atau WhatsApp)
            // Contoh kirim ke semua user dengan role admin (sesuaikan dengan logic aplikasimu)
            $admins = User::where('role', 'admin')->get(); 
            
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\SubdomainExpiringNotification($subdomain));
            }
        }

        $this->info('Notifikasi berhasil dikirim!');
    }
}