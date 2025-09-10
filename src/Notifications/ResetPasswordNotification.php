<?php

namespace Modules\ModuleRelease2\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $token, protected string $guard = 'web'){ }

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
        $routeName = $this->guard === 'api'
                            ? module_release_2_meta('kebab') . '.api.password.reset'
                            : module_release_2_meta('kebab') . '.password.reset';

        Log::info('ResetPasswordNotification', [
            'routeName' => $routeName,
            'guard' => $this->guard,
        ]);

        $url = url(route($routeName, [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // return (new MailMessage)
        //     ->subject('Reset Password')
        //     ->line('Klik tombol di bawah untuk mereset password Anda:')
        //     ->action('Reset Password', $url)
        //     ->line('Jika Anda tidak meminta reset password, abaikan email ini.');

        return (new MailMessage)
            ->subject('Reset Password Akun Desa Digital')
            ->view(module_release_2_meta('kebab').'::emails.reset-password', [
                'url' => $url,
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
