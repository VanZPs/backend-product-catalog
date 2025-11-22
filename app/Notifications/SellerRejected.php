<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct($seller, $reason)
    {
        $this->seller  = $seller;
        $this->reason  = $reason;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pengajuan Akun Seller Ditolak')
            ->greeting('Halo ' . $notifiable->name)
            ->line('Mohon maaf, pengajuan akun seller Anda tidak dapat kami proses.')
            ->line('Alasan penolakan:')
            ->line('🛑 ' . $this->reason)
            ->line('Silakan daftar ulang setelah memperbaiki data Anda.')
            ->action('Daftar Ulang', url('/seller/register'))
            ->line('Terima kasih.');
    }
}
