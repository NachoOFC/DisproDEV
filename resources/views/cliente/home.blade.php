@extends('layouts.app')

@section('title', 'Cliente SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
@include('cliente.menu')
@endsection

@section('main')
<div class="w-100">
    <div class="card w-100">
        <h3 class="card-header font-bold text-xl bg-color-text-white"  >{{ Auth::user()->getNombreRelacionado() }}: Dashboard</h3>
        <div class="card-body">
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-tachometer-alt"></i> Accesos Directo:</h3>
                    <div class="d-flex flex-row justify-content-around align-items-end">
                        @if (Auth::user()->userable instanceof \App\Centro)
                        <a class="btn  bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('requerimientos.create') }}">
                            <i class="fas fa-tasks"></i>
                            Nueva Orden de Pedido
                        </a>
                        <a class="btn  bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('libreria.index') }}">
                            <i class="fas fa-list"></i>
                            Libreria Ordenes de Pedido
                        </a>
                        <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('presupuesto.indexCentro') }}">
                            <i class="fas fa-money-check-alt"></i>
                            Revisar Cuenta Corriente
                        </a>
                        @elseif (Auth::user()->userable instanceof \App\Empresa)
                        @if (!Auth::user()->logistica)
                        <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('pedidos.validar')}}">
                            <i class="fas fa-tasks"></i>
                            Validar Ordenes de Pedido
                        </a>
                         <!-- Botón para Validar Ordenes de Pedido Nivel 2 -->
                        @if (Auth::user()->centros->count() === 0)
                        <a class="btn bg-green-500 hover:bg-green-700 text-white" href="{{ route('pedidos.validarNivel2') }}">
                            <i class="fas fa-check-double"></i>
                            Validar Ordenes de Pedido Nivel 2
                        </a>
                        @endif
                        <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('presupuesto.indexEmpresa') }}">
                            <i class="fas fa-money-check-alt"></i>
                            Revisar Cuenta Corriente
                        </a>
                        <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('presupuesto.create') }}">
                            <i class="fas fa-wallet"></i>
                            Cargar Presupuesto
                        </a>
                        @else
                        <a class="btn bg-blue-500 hover:bg-blue-700 text-white" href="{{ route('pedidos.listaLogistica')}}">
                            Lista Logistica
                        </a>
                        @endif
                        @endif
                      <!--   <a class="btn btn-info text-white" href="{{ asset('rrhh/minuta.pdf') }}" target="_BLANK">
                            <i class="fas fa-clock mr-2" style='color: white'></i>
                            Minuta del Mes
                        </a>
                        <a class="btn btn-success" href="{{ asset('rrhh/saludable.pdf') }}" target="_BLANK">
                            <i class="fas fa-leaf mr-2"></i>
                            Una Vida Saludable
                        </a>-->
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-chart-line"></i> Reportes:</h3>
                    <div class="container">
                        <div class="row">
                            <div class="col table-responsive">
                                @switch (get_class(Auth::user()->userable))
                                @case('App\Centro')
                                @component('partials.index',
                                ['type' => 0,
                                'requerimientos' =>
                                Auth::user()->userable
                                ->requerimientos()
                                ->where("created_at", ">=", date("Y-m-d", strtotime("-3 Months")))
                                ->get()])
                                @endcomponent
                                @break
                                @case('App\Empresa')
                                @if (Auth::user()->centro)
                                    <!-- Mostrar requerimientos del centro asignado -->
                                    @component('partials.index',
                                    ['type' => 1,
                                    'requerimientos' => $requerimientos])
                                    @endcomponent
                                @else
                                    <!-- Mostrar todos los centros de la empresa -->
                                    @component('partials.index',
                                    ['type' => 1,
                                    'centros' => Auth::user()->userable->centros()->get()])
                                    @endcomponent
                                @endif
                                @break
                                @case('App\Logistica')
                                @component('partials.index',
                                ['type' => 1,
                                'centros' => Auth::user()->userable->centros()->get()])
                                @endcomponent
                                @break
                                @case('App\Holding')
                                @component('partials.index',
                                ['type' => 2,
                                'empresas' => Auth::user()->userable->empresas()->get()])
                                @endcomponent
                                @break
                                @endswitch
                            </div>
                        </div>
                    </div>
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