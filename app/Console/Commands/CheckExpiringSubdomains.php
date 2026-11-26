<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subdomain;
use App\Models\User;
use App\Notifications\SubdomainExpiringNotification;

class CheckExpiringSubdomains extends Command
{
    protected $signature = 'subdomains:check-expiring';
    protected $description = 'Mengecek dan mengirim notifikasi untuk subdomain yang akan/sudah expired';

    public function handle()
    {
        // Ambil semua subdomain yang punya tanggal SSL,
        // lalu pakai ACCESSOR $subdomain->status (sudah dihitung benar dari tanggal hari ini)
        $candidates = Subdomain::whereNotNull('ssl_expiry')->get()
            ->filter(fn($s) => in_array($s->status, ['Expiring', 'Expired']));

        if ($candidates->isEmpty()) {
            $this->info('Tidak ada subdomain yang perlu dinotifikasi.');
            return Command::SUCCESS;
        }

        // Penenerima: admin dulu, fallback ke semua user kalau kolom role tidak ada/kosong
        $recipients = User::all();

        $sent = 0;
        foreach ($candidates as $subdomain) {
            foreach ($recipients as $admin) {
                // ✨ Cegah duplikat: skip kalau masih ada notifikasi BELUM DIBACA untuk subdomain ini
                $alreadyNotified = $admin->notifications()
                    ->whereNull('read_at')
                    ->whereJsonContains('data->subdomain_id', $subdomain->id)
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                $admin->notify(new SubdomainExpiringNotification($subdomain));
                $sent++;
            }
        }

        $this->info("Selesai! {$sent} notifikasi baru terkirim (dari {$candidates->count()} subdomain bermasalah).");
        return Command::SUCCESS;
    }
}