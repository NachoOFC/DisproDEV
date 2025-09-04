@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
@include('compass.menu')
@endsection

@section('main')
<div class="container">
    <div class="card">
        <div class="card-header font-bold text-xl">{{
          $empresa->razon_social }}: Cargar productos</div>
        <div class="card-body">

            @if(isset($status))
            <div class="row">
                <div class="alert alert-danger">
                    @foreach($status["errors"] as $error)
                    {{ _("Error en la fila: ". $error["sku"]) }}
                    <br />
                    @endforeach
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="container">
                        <div class="d-flex flex-column">
                            <strong>Descarga el formato de carga masiva de productos</strong>
                            <form method="POST" id="" action="{{ route("productos.formato-carga-masiva") }}">
                                @csrf

                                <button class="btn btn-info">Aqui</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="container">
                        <form method="POST" action="{{ route('empresas.productos.store', $empresa) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-row">
                                <label for="">Ingrese el Archivo:</label>
                                <input name="productos" type="file" class="form-control" />
                            </div>

                            <div class="form-row mt-3">
                                <button class="btn btn-primary" type="submit">Cargar</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection