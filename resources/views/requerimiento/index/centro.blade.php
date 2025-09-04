@extends('layouts.app')

@section('title', 'Lista de Pedidos | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\CompassRole))
    @section('home-route', route('compass.home'))
@else
    @section('home-route', route('cliente.home'))
@endif

@section('nav-menu')
    @if (Auth::user()->userable instanceof \App\CompassRole)
        @include('compass.menu')
    @else
        @include('cliente.menu')
    @endif
@endsection

@section('main')
  <div class="container">
  <a class="btn btn-secondary" style="color: #fff;"  href="{{route('pedidos.indexEmpresa')}}"><i class='fas fa-arrow-alt-circle-left'></i></a>

    <div class="card">
            <h3 class="card-header font-bold text-xl">Lista de Ordenes de Pedido</h3>
      <div class="card-body">
                <div class="container mt-2">
                    <div class="table-responsive">
        <table id="datatable" class="table table-sm">
          <thead>
            <tr>
              <th scope="col" rowspan="2">Nombre</th>
              <th scope="col" rowspan="2">Accion</th>
              <th class="text-center" scope="row" colspan="7">Estados</th>
            </tr>
            <tr>
              @foreach(\App\Estado::all() as $estado)
                <th scope="col">{{ $estado->nombre }}</th>
            @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach ($centros as $centro)
              <tr>
                <td>{{ $centro->nombre }}</td>
                <td>
                    <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '0']) }}">
                        Ver Todas
                    </a>
                </td>
                @foreach(\App\Estado::all() as $estado)
                <td>
                    <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => $estado->id]) }}">
                        {{ count($centro->requerimientos()->where('estado', $estado->nombre)->get()) }}
                    </a>
                </td>
                @endforeach
                
              </tr>
            @endforeach
          </tbody>
        </table>
        </div>
        </div>
      </div>
    </div>

  </div>
@endsection
