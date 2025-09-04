@extends('layouts.app')

@section('title', 'Libreria de Ordenes | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">

        <div class="card">
            <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Libreria de Ordenes de Pedido</h3>
            <div class="card-body">
                <div class="container table-responsive">
                    <table id="datatable" class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Ver</th>
                                <th scope="col">Usar</th>
                        </thead>
                        <tbody>
                            @foreach ($requerimientos as $requerimiento)
                                <tr>
                                    <td>{{ $requerimiento->pivot->nombre }}</td>
                                    <td>
                                                <modal-btn-component
                                                    title="Orden de Pedido"
                                                    :message='[
                                                    { data:
                                                    @json($requerimiento->productos),
                                                    type: "Array", keys: ["sku",
                                                    "detalle", "precio",
                                                    "pivot", "total"], pivot: "cantidad"},
                                                    { data: @json(["total" => "$" . number_format($requerimiento->getTotal()) ]), type: "Object", keys: ["total"]}
                                                    ]'>Detalles</modal-btn-component>
                                    </td>
                                    <td>
                                        <a href="{{
                                        route('requerimientos.edit',
                                    $requerimiento)}}" class="btn
                                    btn-info text-white">Usar</a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
