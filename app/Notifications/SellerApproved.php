<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct($seller)
    {
        $this->seller = $seller;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Akun Seller Anda Telah Disetujui')
            ->greeting('Halo ' . $notifiable->name . ' 👋')
            ->line('Pengajuan akun seller Anda telah disetujui oleh tim admin.')
            ->line('Anda sekarang dapat mulai mengelola toko & menambahkan produk.')
            ->action('Buka Dashboard', url('/dashboard'))
            ->line('Terima kasih telah bergabung!');
    }
}
