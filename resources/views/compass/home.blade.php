@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="w-100">
        <div class="card w-100">
            <div class="card-header font-bold text-xl"> Dashboard</div>
            <div class="card-body">
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-tachometer-alt"></i> Accesos Directos</h3>
                        <div class="d-flex flex-row justify-content-around align-items-end">
                            @if (Auth::user()->userable->name == 'Compras')
                                <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('compass.pedidos.verificar')}}">
                                    <i class="fas fa-tasks"></i>
                                    Verificar Ordenes de Pedido
                                </a>
                                <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('cargarFolios') }}">
                                    <i class="fas fa-tasks" style='color: white'></i>
                                    Cargar Folios
                                </a>
                                <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('usuarios.index') }}">
                                    <i class="fas fa-tasks" style='color: white'></i>
                                    Lista de Usuarios
                                </a>
                            @else
                                <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('compass.pedidos.cajasIndex')}}">
                                    <i class="fas fa-tasks "  style='color: white'></i>
                                    Armar Cajas
                                </a>
                                <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('compass.pedidos.programarDespachos') }}">
                                    <i class="fas fa-tasks "  style='color: white'></i>
                                    Programar Despachos
                                </a>
                                <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('compass.pedidos.despachar') }}">
                                    <i class="fas fa-tasks "  style='color: white'></i>
                                    Despachar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-chart-line"></i> Reportes</h3>
                        @component('partials.index',
                            ['type' => 2,
                            'empresas' =>
                            \App\Empresa::all()])
                        @endcomponent
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-bell"></i> Ultimas Notificaciones:</h3>
                        @component('partials.notifications', ['notifications' => $notifications])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
