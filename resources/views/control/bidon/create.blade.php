@extends('layouts.app')
@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

  @section('nav-menu')
    @include('compass.menu')
  @endsection
@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <v-card class="">
            <v-card-title>Crear bidon</v-card-title>
            <v-card-text>
                <form method="POST" action="{{ route('bidones.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="proveedor_id">Proveedor:</label>
                        <select class="form-control @error('proveedor_id') is-invalid @enderror" name="proveedor_id">
                            @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}">{{ $proveedor->razon_social }}</option>
                            @endforeach

                        </select>
                        @error('proveedor_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="codigo">Codigo:</label>
                        <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror">
                        @error('codigo')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror">
                        @error('nombre')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row mb-5">
                        <div class="col-md-4">
                            <button type="submit" name="continue" value="0" class="btn btn-primary">Guardar y Salir</button>
                        </div>
                        <div class="col-md-4 offset-md-4">
                            <button type="submit" name="continue" value="1" class="btn btn-primary">Guardar y Continuar</button>
                        </div>
                    </div>
                </form>
            </v-card-text>
        </v-card>
    </div>
    @endsection
