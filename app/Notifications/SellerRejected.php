<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class SellerRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $reason;
    public $sellerSnapshot;

    public function __construct(?string $reason = null, array $sellerSnapshot = [])
    {
        $this->reason = $reason;
        $this->sellerSnapshot = $sellerSnapshot;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Pendaftaran Seller Ditolak')
            ->greeting("Halo {$notifiable->name},")
            ->line('Maaf, pengajuan seller Anda tidak dapat kami setujui.');

        if ($this->reason) {
            $mail->line('Alasan: ' . $this->reason);
        }

        if (!empty($this->sellerSnapshot['company_name'])) {
            $mail->line('Nama Perusahaan: ' . $this->sellerSnapshot['company_name']);
        }

        $mail->action('Daftar Ulang Seller', url('/register-seller'))
             ->line('Silakan lengkapi data dan coba daftar kembali.');

        return $mail;
    }
}