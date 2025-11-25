<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class SellerApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public $sellerSnapshot;
    public $signedUrl;

    public function __construct(array $sellerSnapshot = [], string $signedUrl = null)
    {
        $this->sellerSnapshot = $sellerSnapshot;
        $this->signedUrl = $signedUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Akun Seller Anda Disetujui')
            ->greeting("Halo {$notifiable->name},")
            ->line('Selamat! Pengajuan seller Anda telah disetujui oleh tim kami. Silakan verifikasi melalui tombol di bawah untuk mengaktifkan akun Anda.');

        if (!empty($this->sellerSnapshot['company_name'])) {
            $mail->line('Nama Perusahaan: ' . $this->sellerSnapshot['company_name']);
        }

           $actionUrl = $this->signedUrl ?? url('/activate-seller?user=' . $notifiable->user_id);

           $mail->action('Verifikasi & Aktifkan Akun', $actionUrl)
               ->line('Setelah Anda klik tombol verifikasi, akun Anda akan aktif dan Anda dapat login.');

        return $mail;
    }
}
