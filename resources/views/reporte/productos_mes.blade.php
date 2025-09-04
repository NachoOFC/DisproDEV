@extends('layouts.app')

@section('title', 'Productos por Mes | MLine SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Productos Vendidos por Mes, {{ date("Y") }}</h3>
            <div class="card-body">
                <div class="d-flex flex-row mb-2">
                </div>
                <div class="container mt-2">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Producto</th>
                                    @for ($i = 1; $i < 13; $i++)
                                        <th scope="col" class="text-center">{{ ucfirst(\Carbon\Carbon::createFromDate(2020, $i, 1)->locale("es")->monthName) }}</th>
                                    @endfor
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report as $row)
                                    <tr>
                                        <th scope="row">
                                            <modal-btn-component
                                                :button="false"
                                                title="Producto"
                                                :message='[
                                                { data: @json($row["producto"]), type: "Object", keys: ["sku", "detalle", "costo"]},
                                                { data: @json($row["empresas"]), type: "Array", keys: ["razon_social"]},
                                                ]'>{{ $row["producto"]["detalle"] }}</modal-btn-component>
                                        </th>
                                        @php
                                            $total = 0;
                                        @endphp
                                        @for ($i = 1; $i < 13; $i++)
                                            <td class="text-center">
                                                <modal-btn-component
                                                    :button="false"
                                                    title="Ordenes de Pedido"
                                                    :message='[
                                                    { data: @json($row["requerimientos"][$i - 1]), type: "Array", keys: ["nombre", "fecha", "centro", "empresa"]}
                                                    ]'>{{ $row["cantidad"][$i - 1] }}</modal-btn-component>
                                                </td>
                                        @endfor
                                        <td class="text-center">{{ $row["cantidad"][12] }}</td>
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
