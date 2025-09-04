<?php

namespace App\Mail;

use App\GuiaDespacho;
use App\Producto;
use App\TipoObservacion;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Reclamo extends Mailable
{
    use Queueable, SerializesModels;

    public $producto;
    public $guiaDespacho;
    public $user;
    public $mensaje;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(GuiaDespacho $guiaDespacho, Producto $producto, User $user, string $mensaje = null)
    {
        $this->producto = $producto;
        $this->guiaDespacho = $guiaDespacho;
        $this->user = $user;
        if ($mensaje) {
            $this->mensaje = $mensaje;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pivot = $this->guiaDespacho->productos()->where("producto_id", $this->producto->id)->first();
        $observacion = TipoObservacion::find($pivot->pivot->tipo_observacion_id)->nombre;
        return $this->from("compras@mline.cl")->markdown('emails.estado_pago.reclamo')->with([
            "observacion" => $observacion,
        ]);
    }
}
