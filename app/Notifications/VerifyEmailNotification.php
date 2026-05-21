<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends BaseVerifyEmail
{
    /**
     * The user being notified (stored so buildMailMessage can access it).
     */
    protected ?object $notifiable = null;

    /**
     * Map the user's religion to a culturally appropriate greeting.
     */
    protected function getGreeting(?string $religion): string
    {
        return match ($religion) {
            'Islam'              => 'Assalamualaikum! 🙏',
            'Kristen Protestan'  => 'Shalom! ✝️',
            'Kristen Katolik'    => 'Shalom! ✝️',
            'Hindu'              => 'Om Swastiastu! 🙏',
            'Buddha'             => 'Namo Buddhaya! 🙏',
            'Konghucu'           => 'Wei De Dong Tian! 🙏',
            default              => 'Halo! 🙏',
        };
    }

    /**
     * Intercept toMail to capture the notifiable before building the message.
     */
    public function toMail($notifiable)
    {
        $this->notifiable = $notifiable;

        return parent::toMail($notifiable);
    }

    /**
     * Build the mail representation of the notification.
     */
    protected function buildMailMessage($url): MailMessage
    {
        $religion = $this->notifiable?->religion;
        $greeting = $this->getGreeting($religion);

        return (new MailMessage)
            ->subject('Verifikasi Email — Ruang Hening')
            ->greeting($greeting)
            ->line('Terima kasih telah mendaftar di **Ruang Hening**. Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda dan mulai perjalanan spiritual Anda.')
            ->action('Verifikasi Email Saya', $url)
            ->line('Jika Anda tidak merasa mendaftar di Ruang Hening, abaikan email ini.')
            ->salutation("Salam hangat,\nTim Ruang Hening 🍃");
    }
}
