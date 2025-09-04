@extends('layouts.app')

@section('title', 'Armar Cajas')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Armar Cajas</h3>
            <div class="card-body">
                <form action="{{ route('cajas.reemplazar', $requerimiento) }}" method="POST" class="container mt-2">
                    @csrf
                    @method('PUT')

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right" for="productoReemplazado">Producto a Reemplazar:</label>
                        <div class="col-md">
                            <input type="hidden" readonly name="productoReemplazadoId" value="{{ $producto->id}}" class="form-control-plaintext"/>
                            <input type="text" readonly name="productoReemplazado" value="{{ $producto->detalle}}" class="form-control-plaintext"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-right" for="nuevoProducto">Producto de Reemplazo:</label>
                        <div class="col-md">
                            <filterable-select-component :options='@json($productos)'></filterable-select-component>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 mx-auto">
                            <button type="submit" class="btn btn-success">Reemplazar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection
