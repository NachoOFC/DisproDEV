@extends('layouts.app')

@section('title', 'Crear Orden de Pedido | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="card">
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->userable->nombre }}: Nueva Orden de Pedido</h3>
        <div class="card-body">
          <div class="row justify-content-center align-items-center">
            <div class="col-md-10">
                <requerimiento-generation-component
                    formato-download="{{ route('requerimientos.formato') }}"
                    :productos='@json($productos)'
                    :empresa='@json($empresa)'
                    :centro='@json($centro)'
                    nombre-requerimiento="{{ $nombre }}"
                    numero-requerimiento="{{ $numeroRequerimiento }}"
                    store-route="{{ route('requerimientos.store')  }}"
                ></requerimiento-generation-component>
            </div>

          </div>
        </div>

    </div>
@endsection
