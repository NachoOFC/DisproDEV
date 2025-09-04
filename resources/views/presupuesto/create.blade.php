@extends('layouts.app')

@section('title', 'Manejar Presupuesto | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Manejar Presupuestos</h3>
            <div class="card-body">
                <create-presupuesto-component :items='@json($centros)' action="{{ route('presupuesto.store') }}"></create-presupuesto-component>
            </div>
        </div>
    </div>
@endsection
