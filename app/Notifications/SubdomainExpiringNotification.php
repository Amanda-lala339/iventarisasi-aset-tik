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
    // ✨ Untuk tes lokal: database saja dulu (hindari error SMTP)
    // Nanti kalau email sudah siap, ganti jadi ['database', 'mail']
    return ['database'];
}

public function toDatabase($notifiable)
{
    $daysUntilExpiry = now()->startOfDay()->diffInDays($this->subdomain->ssl_expiry->startOfDay(), false);
    $type = $daysUntilExpiry < 0 ? 'expired' : 'expiring';

    // ✨ Cek apakah kolom 'subdomain' sudah berisi nama lengkap (ada titik)
    // Jika ya, pakai langsung. Jika tidak, gabung dengan domain.
    $fullName = str_contains($this->subdomain->subdomain, '.')
        ? $this->subdomain->subdomain
        : $this->subdomain->subdomain . '.' . $this->subdomain->domain;

    // ✨ URL dinamis: pakai url() helper agar ikut APP_URL yang benar
    $detailUrl = url('/subdomains/' . $this->subdomain->id);

    $message = $daysUntilExpiry < 0
        ? "⚠️ SSL {$fullName} SUDAH EXPIRED ({$this->subdomain->ssl_expiry->format('d M Y')})."
        : "⚠️ SSL {$fullName} akan expired dalam {$daysUntilExpiry} hari ({$this->subdomain->ssl_expiry->format('d M Y')}).";

    return [
        'type'           => $type,
        'subdomain_id'   => $this->subdomain->id,
        'url'            => $detailUrl,
        'subdomain_name' => $fullName,
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