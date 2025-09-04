@extends('layouts.app')

@section('title', 'Cierre Estado de Pago | MLine SIGER')

@if ((Auth::user()->userable instanceof \App\CompassRole))
@section('home-route', route('compass.home'))
@section('nav-menu')
@include('compass.menu')
@endsection
@else
@section('home-route', route('cliente.home'))
@section('nav-menu')
@include('cliente.menu')
@endsection
@endif


@section('main')
<div class="container">
    <div class="card">
        <h3 class="card-header font-bold text-xl">Cierre Estado de Pago</h3>
        <div class="card-body">
            <div class="d-flex flex-row mb-2">
            </div>
            <div class="container mt-2">
                <form action="{{ route("estado_pago_generar_cierre") }}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="row">
                        <div class="form-group col-md-3 d-flex flex-col">
                            <label class="" for="inicio">Inicio:</label>
                            <input class="form-control" name="inicio" type="date" />
                        </div>
                        <div class="form-group col-md-3 d-flex flex-col">
                            <label class="" for="fin">Fin:</label>
                            <input class="form-control" name="fin" type="date" />
                        </div>

                        @isset($empresas)
                        <div class="form-group col-md-4 d-flex flex-col">
                            <label for="empresa">Empresa:</label>
                            <span>
                                <autoselect :items='@json($empresas)' item-text="razon_social" item-value="id" name="empresa"></autoselect>
                            </span>
                        </div>
                        @endisset
                    </div>

                    <button type="submit" name="closed" value="0" class="btn btn-primary my-5">Exportar a Excel</button>

                    @if ((Auth::user()->userable instanceof \App\CompassRole))
                    <button type="submit" name="closed" value="1" class="btn btn-primary my-5">Generar Cierrre</button>
                    @endif
                </form>

            </div>
        </div>
    </div>
</div>
@endsection