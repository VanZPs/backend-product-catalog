<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SellerRejected extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pendaftaran Seller Ditolak')
            ->greeting("Halo {$notifiable->name},")
            ->line('Maaf, pengajuan seller Anda tidak dapat kami setujui.')
            ->line('Silakan lengkapi data dan coba daftar kembali.');
    }
}
