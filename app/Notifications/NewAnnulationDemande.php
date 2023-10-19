<?php

namespace App\Notifications;

use App\Models\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAnnulationDemande extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $annulation;

    public function __construct($annulation)
    {
        $this->annulation = $annulation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $motif = $this->annulation->motif; // Récupérez le motif de la demande

        return (new MailMessage)
            ->subject('Demande d\'annulation de séance de cours')
            ->greeting('Bonjour, Monsieur le Responsable Pédagogique')
            ->line('Je viens par cette présente vous soumettre ma demande pour les raisons suivantes:')
            ->line($motif) 
            ->action('Voir la demande', url('/'))
            ->line('Cordialement !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
