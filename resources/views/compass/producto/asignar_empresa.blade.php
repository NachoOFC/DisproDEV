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
            Auth::user()->getNombreRelacionado() }}: Asignar Productos a Empresas</div>
            <div class="card-body">
                <producto-empresa-component :empresas='@json($empresas)' :productos='@json($productos)' :asignaciones='@json($asignaciones)' action="{{ route('productos.asignarEmpresa.post') }}"></producto-empresa-component>
            </div>
        </div>
    </div>
@endsection
