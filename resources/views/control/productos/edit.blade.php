@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
@include('compass.menu')
@endsection

@section('main')
<div class="container">
    <div class="card">
        <div class="card-header font-bold text-xl">Editar producto</div>
        <div class="card-body">

          <form action="{{ route('productos.update', $producto) }}" method="POST">
            @csrf

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="">SKU:</label>
                <input
                  name="sku"
                  type="text"
                  value="{{ $producto->sku }}"
                  class="form-control
                        @error("sku")
                        is-invalid
                        @enderror
                        "
                />
                @error("sku")
                  <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group col-md-6">
                <label for="">Detalle</label>
                <input
                  name="detalle"
                  type="text"
                  value="{{ $producto->detalle }}"
                  class="form-control
                        @error("detalle")
                        is-invalid
                        @enderror
                        "
                />
                @error("detalle")
                  <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="">Precio Costo:</label>
                <input
                  name="costo"
                  type="text"
                  value="{{ $producto->costo }}"
                  class="form-control
                        @error("costo")
                        is-invalid
                        @enderror
                        "
                />
                @error("costo")
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group col-md-6">
                <label for="">Precio Venta</label>
                <input
                  name="venta"
                  type="text"
                  value="{{ $producto->venta }}"
                  class="form-control
                        @error("venta")
                        is-invalid
                        @enderror
                        "
                />
                @error("venta")
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="">Vigente Desde:</label>
                <input
                  name="desde"
                  type="date"
                  value="{{ $producto->desde }}"
                  class="form-control
                        @error("desde")
                        is-invalid
                        @enderror
                        "
                />
                @error("desde")
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group col-md-6">
                <label for="">Vigente Hasta:</label>
                <input
                  name="hasta"
                  type="date"
                  value="{{ $producto->hasta }}"
                  class="form-control
                        @error("hasta")
                        is-invalid
                        @enderror
                        "
                />
                @error("hasta")
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <button class="btn btn-info">Guardar</button>

          </form>

        </div>
    </div>
</div>
@endsection
