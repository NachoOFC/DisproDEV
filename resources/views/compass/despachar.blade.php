@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
    <a class="btn btn-secondary" style="color: #fff;"  href="{{ route('compass.home')}}">Pagina anterior</a>
        <div class="card">
            <div class="card-header font-bold text-xl">Despachar</div>
            <div class="card-body table-responsive">
                <table class="table table-sm" id="datatable">
                    <thead>
                        <tr>
                            <th scope="col">Folio</th>
                            <th scope="col">Nombre Transportista</th>
                            <th scope="col">Contacto Transportista</th>
                            <th scope="col">Cajas</th>
                            <th scope="col">Fecha Programada</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($despachos as $despacho)
                            <tr>
                                <th scope="row">{{ $despacho->id }}</th>
                                <td>{{ $despacho->nombre_chofer }}</td>
                                <td>{{ $despacho->contacto }}</td>
                                <td>
                                    <modal-btn-component
                                        title="Despacho Programado"
                                        :message='[
                                        { data: @json($despacho->requerimientos)
                                        , type: "Array", keys: ["id", "nombre",
                                        "created_at"]}
                                        ]'>Ver Pedidos</modal-btn-component>
                                </td>
                                <td>{{ $despacho->fecha_programada }}</td>
                                <td>
                                    <despachar-component
                                        action-guia="{{ route('guia-despacho.create', $despacho->id) }}"
                                        action-despachar="{{ route('compass.despachar', $despacho) }}"
                                        action-eliminar="{{
                                            route('compass.eliminarDespacho',
                                        $despacho)}}"
                                        ></despachar-component>
                                        <a class="btn btn-info text-white" href="{{ route('guia-despacho.index', $despacho)}}">Ver guias de despacho</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
