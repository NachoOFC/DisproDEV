@extends('layouts.app')
@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

  @section('nav-menu')
    @include('compass.menu')
  @endsection

@section('content')
    <v-card class="m-5 p-2">
        <v-card-title>Resumen de {{ $title }}</v-card-title>

        <v-card-text>
            <v-btn class="mb-2" depressed color="indigo" href="{{ $createRoute }}">Crear {{ $title }}</v-btn>
            <index-component :headers="[
                { text: 'Fecha de Ingreso', value: 'fecha_ingreso' },
                { text: 'Detalle', value: 'bidon.nombre' },
                { text: 'Cantidad', value: 'qty' },
                { text: 'Acciones', value: 'actions' }
                ]" :items='@json($items)'></index-component>
        </v-card-text>
    </v-card>
@endsection
