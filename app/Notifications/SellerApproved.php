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

    public function __construct(array $sellerSnapshot = [])
    {
        $this->sellerSnapshot = $sellerSnapshot;
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
            ->line('Selamat! Akun seller Anda telah disetujui oleh tim kami.');

        if (!empty($this->sellerSnapshot['company_name'])) {
            $mail->line('Nama Perusahaan: ' . $this->sellerSnapshot['company_name']);
        }

        $mail->action('Aktivasi Akun', url('/activate-seller?user=' . $notifiable->id))
             ->line('Terima kasih sudah bergabung sebagai seller.');

        return $mail;
    }
}
