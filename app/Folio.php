<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{

    /**
     * Returns the next usable folio and updates the current record
     * @params int $requiredAmt
     * @returns Array
     */
    public static function getLastFolio($requiredAmt)
    {
        $noFolios = $requiredAmt;
        $folio = Folio::where('activo', true)->latest()->first();
        $folioActual = $folio->ultimo + $noFolios;

        if (($folio->ultimo >= $folio->hasta) || ($folioActual >= $folio->hasta)) {
            $folio->activo = false;
            $folio->saveOrFail();

            return [
                'meta' => [
                    'title' => '¡Sin Folios Disponibles!',
                    'msg' => 'Se necesitan cargar Folios para poder generar la guia de despacho'
                ]
            ];
        }

        if ($folioActual == $folio->hasta) {
            $folio->activo = false;
        }

        $folios = collect([]);
        for ($i = $folio->ultimo; $i < ($folio->ultimo + $noFolios); $i++) {
            $folios->push(intval($i));
        }

        $folio->ultimo = $folioActual;
        $folio->saveOrFail();

        return $folios;
    }
}
