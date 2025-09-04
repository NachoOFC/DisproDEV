@extends('layouts.app')

@section('title', 'Verificar Pedidos')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Verificar Ordenes de Pedidos</h3>
            <div class="card-body">
                @if ($productos->count() > 0 || $requerimientos->count() > 0)
                    <div class="d-flex flex-row mb-2">
                        <form action="{{ route('compass.verificar')}}" method="POST" accept-charset="utf-8">
                            @csrf

                            <button class="btn btn-primary">Enviar todo a Bodega</button>
                        </form>
                    </div>
                    <div class="container mt-2">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">SKU</th>
                                        <th scope="col">Detalle</th>
                                        <th scope="col">Cantidad Solicitada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productos as $producto)
                                        <tr>
                                            <td>{{ $producto->sku }}</td>
                                            <td>{{ $producto->detalle }}</td>
                                            <td>{{ $producto->cantidad}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="container mt-2">
                        <div class="table-responsive">
                            <form action="" method="POST">
                                @csrf

                                <button class="btn btn-primary">Enviar seleccionados a Bodega</button>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Bodega</th>
                                            <th scope="col">Folio</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Empresa</th>
                                            <th scope="col">Centro</th>
                                            <th scope="col">Productos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requerimientos as $requerimiento)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="seleccionados">
                                                        <label class="form-check-label">
                                                            Enviar
                                                        </label>
                                                        <input type="hidden" value="{{ $requerimiento->id }}" name="requerimientos[]"/>
                                                    </div>
                                                </td>
                                                <td>{{ $requerimiento->id }}</td>
                                                <td>{{ $requerimiento->nombre }}</td>
                                                <td>{{ $requerimiento->centro->empresa->razon_social }}</td>
                                                <td>{{ $requerimiento->centro->nombre }}</td>
                                                <td>
                                    <a href="{{ route('pedidos.show', $requerimiento) }}">{{ $requerimiento->nombre }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-dark">No hay ordenes de pedidos pendientes</div>

                @endif
            </div>
        </div>
    </div>
@endsection
