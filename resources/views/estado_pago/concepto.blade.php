@extends('layouts.app')

@section('title', 'Mline SIGER')

@if ((Auth::user()->userable instanceof \App\CompassRole))
@section('home-route', route('compass.home'))
@section('nav-menu')
@include('compass.menu')
@endsection
@else
@section('home-route', route('cliente.home'))
@section('nav-menu')
@include('cliente.menu')
@endsection
@endif

@section('main')
<div class="container">
    <div class="card">
        <h3 class="card-header font-bold text-xl">
            @foreach($tipoObservaciones as $observacion)
            {{ $observacion->nombre  }} /
            @endforeach
        </h3>
        <div class="card-body">
            <span>
                <b>Requerimiento:</b>
                {{ $guiaDespacho->requerimiento->id  }}
            </span>
            <span>
                <b>Guia Despacho:</b>
                {{ $guiaDespacho->folio  }}
            </span>
            <span>
                <b>Centro:</b>
                {{ $guiaDespacho->nombre_centro  }}
            </span>
            <span>
                <b>Area:</b>
                {{ $guiaDespacho->nombre_centro  }}
            </span>
            <div class="container mt-2 table-responsive">
                <concepto-component :guia-despacho='@json($guiaDespacho)' :productos='@json($productos)' :observaciones='@json($observaciones)' @if ((Auth::user()->userable instanceof \App\CompassRole))
                    actualizacion-route="{{ $actualizacionRoute  }}"
                    store-route="{{ $storeRoute }}"
                    massive-route="{{ route("estado_pago_concepto_masivo", $guiaDespacho) }}"
                    @else
                    :genera-reclamos="true"
                    reclamo-route="{{ route("estado_pago_reclamo", [$guiaDespacho]) }}"
                    @endif
                    >
                </concepto-component>
            </div>
        </div>
    </div>
</div>
@endsection