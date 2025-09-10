<?php

namespace Modules\DesaModuleTemplate\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected string $guard = 'web') {}

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    protected function verificationUrl($notifiable)
    {
        $routeName = $this->guard === 'api'
                            ? desa_module_template_meta('kebab') . '.api.verification.verify'
                            : desa_module_template_meta('kebab') . '.verification.verify';


        return URL::temporarySignedRoute(
            $routeName,
            Carbon::now()->addMinutes(60),
            [
                'user' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        // return (new MailMessage)
        //     ->subject('Verify Email Address')
        //     ->line('Please click the button below to verify your email address.')
        //     ->action('Verify Email Address', $this->verificationUrl($notifiable))
        //     ->line('If you did not create an account, no further action is required.');

        return (new MailMessage)
            ->subject('Verifikasi Email Anda')
            ->view(desa_module_template_meta('kebab').'::emails.verify-email', [
                'user' => $notifiable,
                'url' => $this->verificationUrl($notifiable),
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
