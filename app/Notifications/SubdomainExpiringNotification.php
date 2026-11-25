<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class SubdomainExpiringNotification extends Notification
{
    use Queueable;

    protected $subdomain;

    public function __construct($subdomain)
    {
        $this->subdomain = $subdomain;
    }

    /**
     * Tentukan channel pengiriman notifikasi.
     * Gunakan 'database' untuk notifikasi di dalam aplikasi (lonceng).
     * Tambahkan 'mail' jika konfigurasi email SMTP sudah siap.
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; 
    }

    /**
     * Format untuk notifikasi di dalam aplikasi (Disimpan di tabel `notifications`)
     */
    public function toDatabase($notifiable)
    {
        // Hitung selisih hari (nilai negatif berarti tanggal sudah lewat / expired)
        $daysUntilExpiry = now()->diffInDays($this->subdomain->ssl_expiry, false);
        
        // Tentukan tipe notifikasi agar UI bisa memilih ikon yang tepat (expired = merah, expiring = kuning)
        $type = $daysUntilExpiry < 0 ? 'expired' : 'expiring';
        
        // Buat pesan yang dinamis dan informatif
        $message = $daysUntilExpiry < 0 
            ? "⚠️ SSL untuk subdomain **{$this->subdomain->subdomain}.{$this->subdomain->domain}** sudah EXPIRED sejak {$this->subdomain->ssl_expiry->format('d M Y')}."
            : "⚠️ SSL untuk subdomain **{$this->subdomain->subdomain}.{$this->subdomain->domain}** akan expired dalam {$daysUntilExpiry} hari ({$this->subdomain->ssl_expiry->format('d M Y')}).";

        return [
            'type'           => $type, // Digunakan oleh Blade untuk memilih ikon (merah/kuning/biru)
            'url'            => route('subdomains.show', $this->subdomain->id), // Link saat notifikasi diklik
            'subdomain_name' => $this->subdomain->subdomain . '.' . $this->subdomain->domain,
            'ssl_expiry'     => $this->subdomain->ssl_expiry->format('d M Y'),
            'message'        => $message,
        ];
    }

    /**
     * Format untuk pengiriman via Email
     */
    public function toMail($notifiable)
    {
        $daysUntilExpiry = now()->diffInDays($this->subdomain->ssl_expiry, false);
        $statusText = $daysUntilExpiry < 0 ? 'SUDAH EXPIRED' : 'AKAN EXPIRED';

        return (new MailMessage)
            ->subject("⚠️ Peringatan: Subdomain {$statusText}")
            ->greeting('Halo Administrator,')
            ->line("Subdomain **{$this->subdomain->subdomain}.{$this->subdomain->domain}** memiliki status SSL yang memerlukan perhatian Anda.")
            ->line("Tanggal Expired SSL: **{$this->subdomain->ssl_expiry->format('d M Y')}**")
            ->action('Lihat Detail Subdomain', route('subdomains.show', $this->subdomain->id, [], true)) // true = URL absolut untuk email
            ->line('Segera perbarui sertifikat SSL atau hubungi PIC terkait untuk menghindari downtime.')
            ->salutation('Terima kasih, Tim IT.');
    }
}