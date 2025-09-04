<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EstadoUpdated extends Notification implements ShouldQueue
{
    use Queueable;
    private $requerimiento;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(\App\Requerimiento $requerimiento)
    {
        $this->requerimiento = $requerimiento;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Este correo es una Prueba')
                    ->line('El estado de la orden de pedido: '.$this->requerimiento->nombre.' fue actualizado ha: '.$this->requerimiento->estado)
                    ->line('Gracias por usar nuestra plataforma');
                    
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'requerimiento_id' => $this->requerimiento->id,
            'estado' => $this->requerimiento->estado,
        ];
    }
}
