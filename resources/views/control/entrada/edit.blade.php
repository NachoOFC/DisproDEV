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

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cantidad">Fecha del Documento:</label>
                            <input type="date" name="fecha_documento" class="form-control @error('fecha_documento') is-invalid @enderror" value="{{ $item->fecha_documento }}">
                            @error('fecha_documento')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="folio_documento">Folio del Documento:</label>
                            <input type="text" name="folio_documento" class="form-control @error('folio_documento') is-invalid @enderror" value="{{ $item->folio_documento }}">
                            @error('folio_documento')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="producto_id">Bidon:</label>
                        <input type="text" class="form-control" disabled value="{{ $item->bidon->nombre }}" />
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
                            <input type="text" name="cantidad" class="form-control @error('cantidad') is-invalid @enderror" value="{{ $item->qty }}">
                            @error('cantidad')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
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
