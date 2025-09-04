@extends('layouts.app')

@section('title', 'Crear holding | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Nuevo Holding</h3>
            <div class="card-body">
                <form-component
                    :form="{
                    action: '{{ route('holdings.store') }}',
                    method: 'POST',
                    items: [
                    {name: 'nombre', label: 'Nombre', type: 'text'}
                    ]
                    }"
                    ></form-component>
            </div>
        </div>
    </div>
@endsection
