<?php

namespace Modules\ModuleRelease2\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $otp) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        // return (new MailMessage)
        //     ->subject('Kode OTP Login Anda')
        //     ->line('Berikut adalah kode OTP untuk login:')
        //     ->line("**{$this->otp}**")
        //     ->line('Kode ini berlaku 5 menit.')
        //     ->line('Jika Anda tidak merasa melakukan login, abaikan email ini.');

        return (new MailMessage)
            ->subject('Kode OTP Login Anda')
            ->view(module_release_2_meta('kebab').'::emails.verify-otp', [
                'otp' => $this->otp,
                'user' => $notifiable,
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
