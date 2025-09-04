@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
@include('compass.menu')
@endsection

@section('main')
  <div class="container">
    <div class="card">
      <div class="card-header font-bold text-xl">Guias de Despacho</div>
      <div class="card-body">
        <guia-despacho-index :requerimientos='@json($requerimientos)'></guia-despacho-index>
      </div>
    </div>
  </div>
@endsection
