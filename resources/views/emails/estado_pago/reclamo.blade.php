@component('mail::message')
# Reclamo en guia de despacho {{ $guiaDespacho->folio  }}
# Centro: {{ $guiaDespacho->nombre_centro  }}
# Empresa: {{ $guiaDespacho->razon_social_receptor }}

Se ha generado por parte del cliente {{ $user->name }}({{ $user->email }}) un reclamo para el siguiente producto: <br />
{{ $producto->detalle }} con la siguiente observacion: {{ $observacion }}. <br />

@isset($mensaje)
Adicionalmente el cliente dejo el siguiente mensaje: <br />
{{ $mensaje  }}
@endisset

Gracias,<br>
{{ config('app.name') }}
@endcomponent