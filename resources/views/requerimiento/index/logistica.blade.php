@extends('layouts.app')

@section('title', 'Lista de Pedidos | Mline SIGER')

@section('home-route', route('cliente.home'))

    @section('nav-menu')
        @include('cliente.menu')
    @endsection

    @section('main')
        <div class="container">
        <a class="btn btn-secondary" style="color: #fff;"  href="{{ route('compass.pedidos.cajasIndex')}}"><i class='fas fa-arrow-alt-circle-left'></i></a>


            <div class="card">
                <h3 class="card-header font-bold text-xl">Lista de Ordenes de Pedido</h3>
                <div class="card-body">
                    <div class="container mt-2">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Nombre Pedido</th>
                                        <th scope="col">Centro</th>
                                        <th scope="col">Folio</th>
                                        <th scope="col">Fecha de solicitud</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Fecha de estado</th>
                                        <th scope="col">Ultima Actualizacion</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requerimientos as $requerimiento)
                                        <tr>
                                            <td>{{ $requerimiento->nombre }}</td>
                                            <td>{{ $requerimiento->centro->nombre  }}</td>
                                            <td>{{ $requerimiento->folio ?? "S/F"  }}</td>
                                            <td>{{ $requerimiento->created_at }}</td>
                                            <td>{{ $requerimiento->estado  }}</td>
                                            <td>{{ $requerimiento->estadoActual->created_at ?? $requerimiento->updated_at }}</td>
                                            <td>{{ $requerimiento->updated_at  }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if ( $requerimiento->estado === 'RECIBIDO CON OBSERVACIONES')
                                                        <a
                                                            class="btn btn-outline-info"
                                                            href="{{ route(
                                                                     'rechazos.show',
                                                                     $requerimiento) }}"
                                                        >
                                                            Ver Observaciones
                                                        </a>
                                                    @endif
                                                    @if ( $requerimiento->estado === 'DESPACHADO')
                                                        <modal-btn-component
                                                            title="Orden de Pedido"
                                                            :message='[
                                                                   { data: @json([
                                                                           "nombre" => $requerimiento->transporte->nombre_chofer,
                                                                           "rut" => $requerimiento->transporte->rut_chofer,
                                                                           "contacto" => $requerimiento->transporte->contacto
                                                                           ])
                                                                   , type: "Object", keys: ["nombre",
                                                                   "rut", "contacto"]}
                                                                   ]'
                                                        >
                                                            Ver Transporte
                                                        </modal-btn-component>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endsection
