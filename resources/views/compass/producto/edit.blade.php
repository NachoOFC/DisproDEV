@extends('layouts.app')

@section('title', 'Editar Producto | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h5 class="card-header font-bold text-xl">Editar Producto</h5>
            <div class="card-body">
                <form action="{{route('productos.update', $producto)}}" method="POST" accept-charset="utf-8">
                    @csrf
                    @method('PUT')

                    <div class="form-group row">
                        <label class="col-sm-2" for="sku">SKU:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="sku" value="{{$producto->sku}}">
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="detalle">Detalle:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="detalle" value="{{$producto->detalle}}">
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="costo">Costo:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="costo" value="{{$producto->costo}}">
                        </span>
                    </div>

                    <div class="row">
                        <div class="container p-3">
                            <div class="row border-bottom bg-dark text-light">
                                <div class="col-md text-center">Empresa</div>
                                <div class="col-md text-center">Costo ($)</div>
                                <div class="col-md text-center">Porcentaje Ganancia (%)</div>
                                <div class="col-md text-center">Precio Neto ($)</div>
                            </div>
                            @foreach ($empresas as $index => $empresa)
                                <producto-edit-precio
                                    empresa-id="{{ $empresa->id }}"
                                    precio-costo="{{ $producto->costo }}"
                                    venta-actual="{{ $precios[$index] }}">
                                    {{ $empresa->razon_social }}
                                </producto-edit-precio>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-danger">Si el valor de precio venta es igual a 0 entonces este producto no estara disponible para esa empresa</p>

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
