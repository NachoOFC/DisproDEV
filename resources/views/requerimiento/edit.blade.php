@extends('layouts.app')

@section('title', 'Crear Orden de Pedido | Mline SIGER')

@section('home-route', route('cliente.home'))

  @section('nav-menu')
    @include('cliente.menu')
  @endsection

  @section('main')
    <div class="card">
      <h3 class="card-header font-bold text-xl">{{ $empresa->razon_social }}: Editar orden de pedido</h3>
      <crear-requerimiento-component
        :index-productos='@json($productos)'
        presupuesto="{{ $presupuesto }}"
        :empresa='@json($empresa)'
        :centro='@json($centro)'
        nombre="{{ $requerimiento->nombre }}"
        :libreria='@json($productosLibreria)'
        action="{{ $requerimiento->updateRoute }}"
      ></crear-requerimiento-component>
    </div>
  @endsection
