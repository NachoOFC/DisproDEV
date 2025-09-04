<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RealizarProgramacion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $programacion;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->programacion = \App\ProgramacionPrecio::where('realizado', false)->whereDate('fecha', date("Y-m-d"))->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->programacion->count() > 0) {
            $this->programacion->map(function ($asignacion) {
                Excel::import(new PreciosMasivaImport($asignacion->empresa()->get()), storage_path($asignacion->precios));
            });
        }
    }
}
