@extends('layouts.app')

@section('title', 'Ver Observaciones | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
@include('cliente.menu')
@endsection

@section('main')
<div class="card">
    <h3 class="card-header font-bold text-xl">
        {{ Auth::user()->userable->nombre }}:
        Productos con Observaciones en Guia de Despachos
    </h3>
    <div class="card-body">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-12">
                <form method="POST" action="{{ route('rechazos.export', $requerimiento) }}">
                    @csrf
                    <button class="btn btn-primary mb-2">Exportar a Excel</button>
                </form>
                <index-component :headers="[
                { text: 'Folio Guia', value: 'guia.folio' },
                { text: 'Detalle', value: 'producto.detalle' },
                { text: 'Despachado', value: 'producto.pivot.real' },
                { text: 'Recibido', value: 'producto.pivot.cantidad_recibido' },
                { text: 'Tipo', value: 'motivo.estado' },
                { text: 'Motivo', value: 'motivo.nombre' },
                { text: 'Comentarios', value: 'producto.pivot.observacion' },
                ]" :items='@json($observados)'></index-component>
            </div>

        </div>
    </div>

</div>
@endsection