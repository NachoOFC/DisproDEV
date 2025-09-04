@extends('layouts.app')
@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

  @section('nav-menu')
    @include('compass.menu')
  @endsection

@section('content')
    <v-card class="m-5 p-2">
        <v-card-title>Resumen de productos</v-card-title>

        <v-card-text>
            <v-btn
                class="mb-2"
                depressed
                color="indigo"
                href="{{ route('bidones.create') }}"
            >
                Crear producto
            </v-btn>
            <index-component :headers="[
                { text: 'Codigo', value: 'codigo' },
                { text: 'Nombre', value: 'nombre' },
                { text: 'Acciones', value: 'actions' }
                ]" :items='@json($bidones)'></index-component>
        </v-card-text>
    </v-card>
@endsection
