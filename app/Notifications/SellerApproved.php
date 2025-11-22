<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SellerApproved extends Notification
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
            ->subject('Akun Seller Anda Disetujui')
            ->greeting("Halo {$notifiable->name},")
            ->line('Selamat! Akun seller Anda telah disetujui oleh tim kami.')
            ->action('Aktivasi Akun', url('/activate-seller?user=' . $notifiable->id))
            ->line('Terima kasih sudah bergabung sebagai seller.');
    }
}
