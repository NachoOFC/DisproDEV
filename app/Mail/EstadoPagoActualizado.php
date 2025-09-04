<?php

namespace App\Mail;

use App\GuiaDespacho;
use App\TipoObservacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EstadoPagoActualizado extends Mailable
{
    use Queueable, SerializesModels;

    public $guiaDespacho;
    public $tipoObservaciones;
    public $productos;
    public $concepto;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(GuiaDespacho $guiaDespacho, $tipoObservaciones)
    {
        $this->guiaDespacho = $guiaDespacho;
        $this->tipoObservaciones = $tipoObservaciones;
        $this->concepto =  [];

        foreach ($tipoObservaciones as $tipoObservacion) {
            $this->concepto[] = $tipoObservacion->id;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("compras@mline.cl")->markdown('emails.estado_pago.actualizado');
    }
}
