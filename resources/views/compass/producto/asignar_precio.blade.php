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
            Auth::user()->getNombreRelacionado() }}: Asignar Precios a Productos</div>
            <div class="card-body">
                <producto-precio-component
                    :empresas='@json($empresas)'
                    :productos='@json($productos)'
                    action="{{ route('productos.asignarPrecio.post') }}"
                    :precios-actual='@json($precios)'
                    >
                </producto-precio-component>
            </div>
        </div>
    </div>
@endsection
