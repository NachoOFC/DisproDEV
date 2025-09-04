@extends('layouts.app')
@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

  @section('nav-menu')
    @include('compass.menu')
  @endsection
@section('content')

<div class="row">
    <div class="col-md-6 offset-md-3">
        <v-card class="">
            <v-card-title>Editar {{ $title }}</v-card-title>
            <v-card-text>
                <form method="POST" action="{{ $updateRoute }}">
                    @csrf
                    @method("PUT")

                    <div class="form-group">
                        <label for="producto_id">Producto:</label>
                        <input
                            type="text"
                            disabled
                            class="form-control"
                            value="{{ $item->bidon->nombre }}" />
                        @error('producto_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fecha_ingreso">Fecha de Ingreso:</label>
                            <input type="text" name="fecha_ingreso" class="form-control @error('fecha_ingreso') is-invalid @enderror" value="{{ $item->fecha_ingreso }}">
                            @error('fecha_ingreso')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="cantidad">Cantidad:</label>
                            <input type="text" name="cantidad" class="form-control @error('cantidad') is-invalid @enderror" value="{{ $item->cantidad }}">
                            @error('cantidad')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="suma">Tipo de Ajuste:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="suma" value="0">
                            <label class="form-check-label" for="suma1">Reducir Inventario</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="suma" value="1">
                            <label class="form-check-label" for="suma2">Aumentar Inventario</label>
                        </div>
                        @error('suma')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row mb-5">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </v-card-text>
        </v-card>
        @endsection
