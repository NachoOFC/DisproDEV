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
            <v-card-title>Crear nuevo {{ $title }}</v-card-title>
            <v-card-text>
                <form method="POST" action="{{ $storeRoute }}">
                    @csrf

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cantidad">Fecha del Documento:</label>
                            <input type="date" name="fecha_documento" class="form-control @error('fecha_documento') is-invalid @enderror">
                            @error('fecha_documento')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="folio_documento">Folio del Documento:</label>
                            <input type="text" name="folio_documento" class="form-control @error('folio_documento') is-invalid @enderror">
                            @error('folio_documento')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <proveedor-producto-component get-route="{{ route("get-productos") }}"></proveedor-producto-component>
                            @error('bidon_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="fecha_ingreso">Fecha de Ingreso:</label>
                            <input type="date" name="fecha_ingreso" class="form-control @error('fecha_ingreso') is-invalid @enderror">
                            @error('fecha_ingreso')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group col-md-6">
                            <label for="cantidad">Cantidad:</label>
                            <input type="text" name="cantidad" class="form-control @error('cantidad') is-invalid @enderror">
                            @error('cantidad')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
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
        @endsection
