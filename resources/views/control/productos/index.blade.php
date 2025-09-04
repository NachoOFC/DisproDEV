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
          $empresa->razon_social }}: Lista de productos</div>
    <div class="card-body">
      <a class="btn btn-info mb-3" href="{{ route('empresas.productos.create', $empresa) }}">Cargar Productos</a>
      <form action="{{ route('empresas.productos.show', $empresa) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary mb-3 ml-3">Exportar a Excel</button>
      </form>
      <index-component :headers="[
                                     { text: 'SKU', value: 'sku' },
                                     { text: 'Familia', value: 'familia' },
                                     { text: 'Detalle', value: 'detalle' },
                                     { text: 'Marca', value: 'marca' },
                                     { text: 'Costo', value: 'costo' },
                                     { text: 'Venta', value: 'venta' },
                                     { text: 'Desde', value: 'desde' },
                                     { text: 'Hasta', value: 'hasta' },
                                     { text: 'Reemplazo', value: 'reemplazo' },
                                     ]" :items='@json($productos)'></index-component>
    </div>
  </div>
</div>
@endsection