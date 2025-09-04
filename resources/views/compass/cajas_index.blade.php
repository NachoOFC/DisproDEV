@extends('layouts.app')

@section('title', 'Armar Cajas')

@section('home-route', route('compass.home'))

@section('nav-menu')
@include('compass.menu')
@endsection

@section('main')
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}" />
<div class="container">
    <div class="card">
        <h3 class="card-header font-bold text-xl">Armar Cajas</h3>
        <div class="card-body" style="overflow-x: auto;">
            @if ($centros->count() > 0)
            <a class="btn btn-secondary" style="color: #fff;"  href="{{ route('compass.home')}}"><i class='fas fa-arrow-alt-circle-left'></i></a>


            <tabs>
                @foreach ($centros as $centro)
                <tab title="{{$centro->nombre}}">
                    <div class="container mt-2">
                        <div class="row mb-2">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-2 text-right">Empresa:</div>
                                            <div class="col-3 font-bold">{{ $centro->empresa->razon_social }}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2 text-right">RUT Empresa:</div>
                                            <div class="col-3 font-bold">{{ $centro->empresa->rut }}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2 text-right">Centro:</div>
                                            <div class="col-3 font-bold">{{ $centro->nombre }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-sm ">
                                <thead>
                                    <tr>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Accion</th>
                                        <th scope="col">Eliminar</th>
                                        <th scope="col">Armar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($centro
                                    ->requerimientos()
                                    ->where('estado', 'EN BODEGA')
                                    ->where('folio', null)
                                    ->where('transporte_id', null)
                                    ->get() as $requerimiento)
                                    <tr>
                                        <td>
                                            <a href="{{ route('pedidos.show', $requerimiento) }}">{{ $requerimiento->nombre }}</a>
                                        </td>

                                        <td>{{$requerimiento->estado}}</td>

                                        <td> 
                                            <modal-btn-component  title="Orden de Pedido"  :message='[
                                                                           { data: @json($requerimiento->productos), type: "Array", keys: ["sku", "detalle", "pivot"], pivot: "cantidad"}
                                                                           ]'>Ver Orden de Pedido</modal-btn-component>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('requerimiento.eliminar', $requerimiento) }}">
                                                @csrf
                                                @method("DELETE")
                                                <button type="submit" class="btn bg-red-600 hover:bg-red-800  text-white">Eliminar Pedido</button>
                                            </form>
                                        </td>
                                        <td>
                                            <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('compass.pedidos.show', $requerimiento)}}">Armar Caja</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </tab>
                @endforeach
            </tabs>
            @else
            <div class="alert alert-dark">Sin Ordenes de Pedido pendientes por armar</div>
            @endif
        </div>
    </div>
</div>
@endsection