@extends('layouts.app')

@section('title', 'Recibir Orden de Pedido | Mline Viveres')

@section('home-route', route('cliente.home'))

@section('nav-menu')
@include('cliente.menu')
@endsection

@section('main')
<div class="card">
    <h3 class="card-header font-bold text-xl">
        {{ Auth::user()->userable->nombre }}: Recibir Orden de Pedido
    </h3>
    <div class="card-body">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-12">
                <recepcion-requerimiento-component :guias-despacho='@json($guiasDespacho)' :observaciones='@json($observaciones)' store-route="{{ route("pedidos.recepcion.post", $requerimiento)  }}">
                </recepcion-requerimiento-component>
            </div>
        </div>
    </div>

</div>
@endsection