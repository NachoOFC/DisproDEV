@extends('layouts.app')

@section('title', 'Lista de Ordenes | Mline SIGER')

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
<input type="button"  class="btn btn-secondary" value="Página anterior" onClick="history.go(-1);">

    <div class="card">
        <h3 class="card-header font-bold text-xl">Lista de Ordenes de Pedido</h3>
        <div class="card-body">
            <h5 class="card-title h4 text-center border-bottom">{{$centro->nombre}}</h5>
            <table id="datatable" class="table">
                <thead>
                    <tr>
                        <th scope="col">Folio</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Estado</th>
                        @if ((Auth::user()->userable instanceof \App\Centro))
                        <th scope="col">Libreria</th>
                        @endif
                        <th scope="col">Fecha de Creacion</th>
                        <th scope="col">Accion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requerimientos as $requerimiento)
                    @if ( $requerimiento->estado != 'ELIMINADO')
                    <tr>
                        <td>
                            <a
                                href="{{ route('pedidos.show', $requerimiento) }}"
                            >
                                {{ $requerimiento->folio->implode(",") }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('pedidos.show', $requerimiento) }}">{{ $requerimiento->nombre }}</a>
                        </td>
                        <td><div>{{ $requerimiento->estado }}</div></td>
                        @if ((Auth::user()->userable instanceof \App\Centro))
                        <td>
                            <agregar-libreria-component action="{{ route('libreria.editar', $requerimiento) }}" :library='@json(Auth::user()->hasRequerimiento($requerimiento))'></agregar-libreria-component>
                        </td>
                        @endif
                        <td>
                            {{ $requerimiento->created_at }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <div style="margin-right: 10px; ">
                                <modal-btn-component title="Orden de Pedido" :message='[
                                            { data:
                                            @json($requerimiento->productos),
                                            type: "Array", keys: ["sku",
                                            "detalle",
                                            "pivot"], pivot: "cantidad"},
                                            { data: @json(["total" => "$" . number_format($requerimiento->getTotal()) ]), type: "Object", keys: ["total"]}
                                            ]'>
                                    <i class="fas fa-eye fa-lg btn btn-primary" style="cursor: pointer; padding: 8px 12px; color: black;" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver Orden de Pedido"></i>
                                </modal-btn-component>
                                            </div>
                                @if (Auth::user()->userable instanceof \App\Centro)
                                @if ( $requerimiento->estado === 'DESPACHADO')
                                <a class="btn btn-success" href="{{ route('pedidos.entregado', $requerimiento) }}">Recibido</a>
                                @endif
                                @endif
                                @if ( $requerimiento->estado === 'RECIBIDO CON OBSERVACIONES')
                                <a class="btn btn-outline-info" href="{{ route(
                                                                    'rechazos.show',
                                                                    $requerimiento) }}">
                                    Ver Observaciones
                                </a>
                                @endif
                                @if ( $requerimiento->estado === 'DESPACHADO')
                                <div style="margin-right: 10px;">
                                <modal-btn-component title="Orden de Pedido" :message='[
                                                { data: @json([
                                                "nombre" => $requerimiento->nombre_transportista,
                                                "rut" => $requerimiento->rut_transportista,
                                                "contacto" => $requerimiento->contacto_transportista
                                                ])
                                                , type: "Object", keys: ["nombre",
                                                "rut", "contacto"]}
                                                ]'>
                                    <i class="fas fa-truck fa-lg btn btn-primary" style="cursor: pointer; padding: 8px 12px; color: black;" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver transporte"></i>
                                </modal-btn-component>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection