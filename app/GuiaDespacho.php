<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GuiaDespacho extends Model
{
    protected $fillable = [
        "folio", "fecha", "rut_receptor",
        "razon_social_receptor", "giro_receptor",
        "direccion_receptor", "comuna_receptor",
        "ciudad_receptor", "nombre_centro", "nombre_receptor",
        "direccion_destino", "comuna_destino",
        "ciudad_destino", "transporte_rut",
        "transporte_nombre", "febos_id",
    ];

    protected $appends = ["showRoute"];

    public function requerimiento()
    {
        return $this->belongsTo('App\Requerimiento');
    }

    public function productos()
    {
        return $this->belongsToMany('App\Producto')
            ->withPivot(
                'cantidad',
                'precio',
                'real',
                'observacion',
                'fecha_vencimiento',
                "tipo_observacion_id",
                "cantidad_recibido",
                "genera_nc",
                "liquidado",
                "contenedor",
                "comentario_centro",
                "comentario_reclamo",
            );
    }

    public function rechazos()
    {
        return $this->hasMany("App\Rechazo");
    }

    public function getShowRouteAttribute()
    {
        return route('guia-despacho.show', $this->id);
    }

    public function getEmpresaIdAttribute()
    {
        return $this->requerimiento->centro->empresa->id;
    }

    public function getCentroIdAttribute()
    {
        return $this->requerimiento->centro->id;
    }

    public function getZonaIdAttribute()
    {
        return $this->requerimiento->transporte->abastecimiento->id;
    }

    public function hasAceptadas(): bool
    {
        $productos = $this->productos()->wherePivot("tipo_observacion_id", 1)->wherePivot("contenedor", false)->get();

        return $productos->count() > 0;
    }

    public function hasRechazadas(): bool
    {
        $productos = $this->productos()->wherePivotIn("tipo_observacion_id", [2, 3])->wherePivot("contenedor", false)->get();

        return $productos->count() > 0;
    }

    public function hasObservadas(): bool
    {
        $productos = $this->productos()->wherePivotIn("tipo_observacion_id", [4, 5, 6, 7])->wherePivot("contenedor", false)->get();

        return $productos->count() > 0;
    }

    public function hasContenedor(): bool
    {
        return $this->getContenedorCount() > 0;
    }

    public function getObservacionesCountById(int $id): int
    {
        $productos = $this->productos()->wherePivot("tipo_observacion_id", $id)->wherePivot("contenedor", false)->get();

        return $productos->count();
    }

    public function getProductosByObservacionesId(int $id)
    {
        $productos = $this->productos()->wherePivot("tipo_observacion_id", $id)->wherePivot("contenedor", false)->get();

        return $productos;
    }

    public function getContenedorCount(): int
    {
        $productos = $this->productos()->wherePivot("contenedor", true)->get();

        return $productos->count();
    }

    public function getNetoAttribute()
    {
        return $this->productos->reduce(function ($carry, $producto) {
            $cantidad = $producto->pivot->real;
            return $carry + ($producto->pivot->precio * $cantidad);
        });
    }

    public function getSinNotaCreditoAttribute()
    {
        return $this->productos->filter(function ($producto) {
            return !$producto->pivot->genera_nc && !$producto->pivot->contenedor && $producto->pivot->tipo_observacion_id >= 2;
        })->reduce(function ($carry, $producto) {
            $cantidad = $producto->pivot->cantidad_recibido ?? $producto->pivot->real;
            return $carry + ($producto->pivot->precio * $cantidad);
        });
    }


    public function getNotaCreditoAttribute()
    {
        return $this->productos->filter(function ($producto) {
            return $producto->pivot->genera_nc && !$producto->pivot->contenedor;
        })->reduce(function ($carry, $producto) {
            $cantidad = abs($producto->pivot->real - $producto->pivot->cantidad_recibido);
            if ($producto->pivot->tipo_observacion_id == 2) {
                $cantidad = $producto->pivot->real;
            }
            return $carry + ($producto->pivot->precio * $cantidad);
        });
    }

    public function getNotaCreditoContenedorAttribute()
    {
        return $this->productos->filter(function ($producto) {
            return $producto->pivot->genera_nc && $producto->pivot->contenedor;
        })->reduce(function ($carry, $producto) {
            $cantidad = abs($producto->pivot->real - $producto->pivot->cantidad_recibido);
            if ($producto->pivot->tipo_observacion_id == 2) {
                $cantidad = 0;
            }
            return $carry + (1 * $cantidad);
        });
    }

    public function getLiquidacionAttribute()
    {
        return $this->neto - $this->notaCredito - $this->notaCreditoContenedor;
    }

    public function getNoLiquidadosRechazados()
    {
        return $this->productos()->wherePivotIn("tipo_observacion_id", [2, 3])->wherePivotNull("liquidado")->count();
    }


    public function getNoLiquidadosObservados()
    {
        return $this->productos()->wherePivotIn("tipo_observacion_id", [4, 5, 6, 7])->wherePivotNull("liquidado")->count();
    }

    public function getMontoRechazosAttribute()
    {
        if ($this->rechazos->count() > 0) {
            return $this->rechazos->reduce(function ($carry, $rechazo) {
                return $carry + $rechazo->producto->venta;
            });
        } else {
            return 0;
        }
    }

    public function crearDTE()
    {
        $endpoint = \App::environment("local") ? "https://api.febos.cl/certificacion/documentos" : "https://api.febos.cl/produccion/documentos";

        $body = [
            "entrada" => "F2",
            "tipo" => "52",
            "foliar" => "no",
            "timbrar" => "si",
            "firmar" => "no",
            "enviar" => "si",
            "payload" => base64_encode($this->generarDocumento())
        ];

        if ($this->febos_id == null) {
            $request = Http::withHeaders([
                "token" => config("febos.token"),
                "empresa" => config("febos.empresa"),
            ])->retry(3, 100)->post($endpoint, $body);

            if ($request->failed()) {
                Log::error($request->json());
                Log::error($this->toJson());
                Log::error($this->encabezado());
                return false;
            } else {
                $response = $request->json();
                if (isset($response["febosId"])) {
                    $this->febos_id = $response["febosId"];
                    $this->save();
                    Log::info($response);
                    Log::info($this->toJson());
                    return true;
                } else {
                    Log::error($response);
                    Log::error($this->toJson());
                    return false;
                }
            }
        } else {
            return true;
        }
    }

    public function obtenerDTE()
    {
        $endpoint = \App::environment("local") ? "https://api.febos.cl/certificacion/documentos/{$this->febos_id}" : "https://api.febos.cl/produccion/documentos/{$this->febos_id}";

        if ($this->febos_id != null) {

            $request = Http::withHeaders([
                "token" => config("febos.token"),
                "empresa" => config("febos.empresa"),
            ])->retry(3, 100)->get($endpoint, [
                "xml" => "no",
                "imagen" => "si",
                "tipoImagen" => "0",
                "regenerar" => "no",
                "xmlFirmado" => "no",
                "json" => "no"
            ]);
            Log::info($request);
            Log::info($this->toJson());
            Log::error($this->encabezado());


            if ($request->failed()) {
                return false;
            } else {
                return $request->json();
            }
        } else {
            return false;
        }
    }


    public function agregarProductos($productos)
    {
        $CONTENEDORES = collect([
            "10500010010",
            "10500010011",
            "10500010013",
            "10500010014",
            "10500010020",
            "20500010010",
            "20500010013",
            "20500010011",
            "20500010014",
            "20500010020",
            "40500010012",
            "40500010020",
            "40500010010",
            "40500010011",
            "40500010013",
            "40500010014",
            "40500010021",
        ]);
        foreach ($productos as $producto) {
            $attach = null;
            if ($CONTENEDORES->contains($producto->sku)) {
                $attach = [$producto->id => [
                    "cantidad" => floatval($producto->pivot->cantidad),
                    "precio" => $producto->pivot->precio,
                    "real" => floatval($producto->pivot->real),
                    "observacion" => $producto->pivot->observacion,
                    "fecha_vencimiento" => $producto->pivot->fecha_vencimiento,
                    "genera_nc" => true,
                    "contenedor" => true,
                ]];
            } else {
                $attach = [$producto->id => [
                    "cantidad" => floatval($producto->pivot->cantidad),
                    "precio" => $producto->pivot->precio,
                    "real" => floatval($producto->pivot->real),
                    "observacion" => $producto->pivot->observacion,
                    "fecha_vencimiento" => $producto->pivot->fecha_vencimiento,
                ]];
            }
            $this->productos()->attach($attach);
        }
    }

    public function generarDocumento()
    {
        return $this->encabezado() . $this->detalleProductos() . $this->finDocumento();
    }

    private function encabezado()
    {
        $transporte = $this->requerimiento->transporte;
        $neto = round($this->neto);
        return
            "XXX INICIO DOCUMENTO
========== AREA IDENTIFICACION DEL DOCUMENTO
Tipo Documento Tributario Electronico            : 52
Folio Documento                                  : {$this->folio}
Fecha de Emision                                 : {$this->fecha}
Indicador No rebaja                              :
Tipo de Despacho                                 : 2
Indicador de Traslado                            : 5
Tipo Impresion                                   :
Indicador de servicio                            :
Indicador de Montos Brutos                       :
Indicador de Montos Netos                        :
Forma de Pago                                    :
Forma de Pago Exportacion                        :
Fecha de Cancelacion                             :
Monto Cancelado                                  :
Saldo Insoluto                                   :
Fecha de Pago                                    :
Fecha de Pago                                    :
Fecha de Pago                                    :
Fecha de Pago                                    :
Periodo Desde                                    :
Periodo Hasta                                    :
Medio de Pago                                    :
Tipo de Cuenta de Pago                           :
Numero de Cuenta de Pago                         :
Banco de Pago                                    :
Codigo Terminos de Pago                          :
Glosa del Termino de Pago                        :
Dias del Termino de Pago                         :
Fecha de Vencimiento                             :
========== AREA EMISOR
Rut Emisor                                       : 96651910-K
Razon Social Emisor                              : COMPASS CATERING SA
Giro del Emisor                                  : Servicios de Alimentacion
Telefono                                         : 225910600
Correo Emisor                                    :
ACTECO                                           : 602300
Codigo Emisor Traslado Excepcional               :
Folio Autorizacion                               :
Fecha Autorizacion                               :
Direccion de Origen Emisor                       : AV. DEL VALLE N° 787, OF. 501
Comuna de Origen Emisor                          : HUECHARUBA
Ciudad de Origen Emisor                          : SANTIAGO
Nombre Sucursal                                  : 
Codigo Sucursal                                  :
Codigo Adicional Sucursal                        :
Codigo Vendedor                                  :
Identificador Adicional del Emisor               :
RUT Mandante                                     :
========== AREA RECEPTOR
Rut Receptor                                     : {$this->rut_receptor}
Codigo Interno Receptor                          :
Nombre o Razon Social Receptor                   : {$this->razon_social_receptor}
Numero Identificador Receptor Extranjero         :
Nacionalidad del Receptor Extranjero             :
Identificador Adicional Receptor Extranjero      :
Giro del negocio del Receptor                    : {$this->giro_receptor}
Contacto                                         :
Correo Receptor                                  :
Direccion Receptor                               : {$this->direccion_receptor}
Comuna Receptor                                  : {$this->comuna_receptor}
Ciudad Receptor                                  : {$this->ciudad_receptor}
Direccion Postal Receptor                        : {$this->nombre_receptor}
Comuna Postal Receptor                           :
Ciudad Postal Receptor                           :
Rut Solicitante de Factura                       :
========== AREA TRANSPORTES
Patente                                          : {$transporte->patente}
RUT Transportista                                : {$transporte->rut_empresa}
Rut Chofer                                       : {$transporte->rut_chofer}
Nombre del Chofer                                : {$transporte->nombre_chofer}
Direccion Destino                                : {$this->ciudad_destino}
Comuna Destino                                   : {$this->comuna_destino}
Ciudad Destino                                   : {$this->ciudad_destino}
Modalidad De Ventas                              :
Clausula de Venta Exportacion                    :
Total Clausula de Venta Exportacion              :
Via de Transporte                                :
Nombre del Medio de Transporte                   :
RUT Compania de Transporte                       :
Nombre Compania de Transporte                    :
Identificacion Adicional Compania de Transporte  :
Booking                                          :
Operador                                         :
Puerto de Embarque                               :
Identificador Adicional Puerto de Embarque       :
Puerto Desembarque                               :
Identificador Adicional Puerto de Desembarque    :
Tara                                             :
Unidad de Medida Tara                            :
Total Peso Bruto                                 :
Unidad de Peso Bruto                             :
Total Peso Neto                                  :
Unidad de Peso Neto                              :
Total Items                                      :
Total Bultos                                     :
Codigo Tipo de Bulto                             :
Codigo Tipo de Bulto                             :
Codigo Tipo de Bulto                             :
Codigo Tipo de Bulto                             :
Flete                                            :
Seguro                                           :
Codigo Pais Receptor                             :
Codigo Pais Destino                              :
========== AREA TOTALES
Tipo Moneda Transaccion                          :
Monto Neto                                       : {$neto}
Monto Exento                                     :
Monto Base Faenamiento de Carne                  :
Monto Base de Margen de  Comercializacion        :
Tasa IVA                                         :
IVA                                              :
IVA Propio                                       :
IVA Terceros                                     :
Impuesto Adicional                               :
Impuesto Adicional                               :
Impuesto Adicional                               :
Impuesto Adicional                               :
Impuesto Adicional                               :
Impuesto Adicional                               :
IVA no Retenido                                  :
Credito Especial Emp. Constructoras              :
Garantia Deposito Envases                        :
Valor Neto Comisiones                            :
Valor Exento Comisiones                          :
IVA Comisiones                                   :
Monto Total                                      : {$neto}
Monto No Facturable                              :
Monto Periodo                                    :
Saldo Anterior                                   :
Valor a Pagar                                    :
========== AREA OTRA MONEDA
Tipo Moneda                                      :
Tipo Cambio                                      :
Monto Neto Otra Moneda                           :
Monto Exento Otra Moneda                         :
Monto Base Faenamiento de Carne Otra Moneda      :
Monto Margen Comerc. Otra Moneda                 :
IVA Otra Moneda                                  :
IVA Propio                                       :
Tasa Imp. Otra Moneda                            :
Valor Imp. Otra Moneda                           :
IVA No Retenido Otra Moneda                      :
Monto Total Otra Moneda                          :\n";
    }

    private function detalleProductos()
    {
        $productos = $this->productos()->orderBy("detalle")->get();
        $detalles = $productos->map(function ($producto) {
            if ($producto->pivot->real > 0) {
                $sku = str_pad($producto->sku, 35);
                $detalle = str_pad($producto->detalle, 80);
                $cantidad = str_pad(number_format($producto->pivot->real, 2, ".", ""), 18, " ", STR_PAD_BOTH);
                $precio = str_pad(number_format($producto->pivot->precio, 2, ".", ""), 18, " ", STR_PAD_BOTH);
                $total = str_pad(round($producto->pivot->precio * $producto->pivot->real), 18, " ", STR_PAD_LEFT);
                $vencimiento = isset($producto->pivot->vencimiento) ? $producto->pivot->vencimiento : '          ';
                return "                                                 INTERNO   $sku                                                                                                                                                                                                                                                                                                                                                         $detalle                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $cantidad                                                                                                                                                                                                                                                                                                                           $precio          $vencimiento                                                                                                                                                                                                                                                                                                                                                                                                                                      $total";
            }
        });


        while ($detalles->count() < 30) {
            $detalles->push("");
        }

        $detalles->prepend("========== DETALLE DE PRODUCTOS Y SERVICIOS");
        $detalles->push("========== FIN DETALLE\n");


        $detalles = $detalles->implode("\n");

        return $detalles;
    }

    private function finDocumento()
    {
        $documento = "========== SUB TOTALES INFORMATIVO";
        $documento .= $this->padLn(21);
        $documento .= "========== DESCUENTOS Y RECARGOS";
        $documento .= $this->padLn(21);
        $documento .= "========== INFORMACION DE REFERENCIA";
        $documento .= $this->padLn(41);
        $documento .= "========== COMISIONES Y OTROS CARGOS";
        $documento .= $this->padLn(21);
        $documento .= "========== CAMPOS PERSONALIZADOS";
        $documento .= $this->padLn(66);
        $documento .= "XXX FIN DOCUMENTO  ";

        return $documento;
    }

    private function padLn($lineNo)
    {
        $lines = "";
        for ($i = 0; $i < $lineNo; $i++) {
            $lines .= "\n";
        }
        return $lines;
    }
}
