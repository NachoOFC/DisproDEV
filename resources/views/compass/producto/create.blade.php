@extends('layouts.app')

@section('title', 'Nuevo Producto | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h5 class="card-header font-bold text-xl">Nuevo Producto</h5>
            <div class="card-body">
                <form action="{{ route('productos.store') }}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-2" for="sku">SKU:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="sku">
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="detalle">Detalle:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="detalle">
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="costo">Precio Costo:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="costo">
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

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
