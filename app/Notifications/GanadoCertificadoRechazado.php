<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GanadoCertificadoRechazado extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $producto,
        private readonly string $motivo
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => 'Publicación de ganado eliminada',
            'mensaje' => "Tu publicación \"{$this->producto}\" fue eliminada porque el certificado fue rechazado.",
            'motivo' => $this->motivo,
            'producto' => $this->producto,
        ];
    }
}
