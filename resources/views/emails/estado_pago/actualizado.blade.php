@component('mail::message')
# Actualizado el estado de pago para la guia de despacho {{ $guiaDespacho->folio  }}


@foreach($tipoObservaciones as $tipoObservacion)
Esta actualizacion se realizo bajo el concepto de {{ $tipoObservacion->nombre  }}. <br />
Los productos con este concepto ahora son: <br />
|SKU|DETALLE|DESPACHADO|RECIBIDO|GENERA NOTA CREDITO|
|---|---|---|---|---|
@foreach($guiaDespacho->getProductosByObservacionesId($tipoObservacion->id) as $producto)
|{{$producto->sku}}|{{$producto->detalle}}|{{$producto->pivot->real}}|{{$producto->pivot->cantidad_recibido}}|{{$producto->pivot->genera_nc ? "SI" : "NO"}}|
@endforeach
@endforeach


@component('mail::button', ['url' => route("estado_pago_concepto", ["guiaDespacho" => $guiaDespacho, "concepto" => $concepto])])
Puede ver el detalle aqui.
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent