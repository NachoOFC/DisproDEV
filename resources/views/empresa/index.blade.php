@extends('layouts.app')

@section('title', 'Lista de Empresas | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\Holding))
  @section('home-route', route('cliente.home'))
@elseif (Auth::user()->userable instanceof \App\CompassRole)
    @section('home-route', route('compass.home'))
@endif

@section('nav-menu')
  @if ((Auth::user()->userable instanceof \App\Holding))
    @include('cliente.menu')
  @elseif (Auth::user()->userable instanceof \App\CompassRole)
    @include('compass.menu')
  @endif
@endsection

@section('main')
  <div class="container">
    <div class="card">
      <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Lista de Empresas</h3>
      <div class="card-body">
        <div class="container mt-2">
          <div class="table-responsive">
            <table id="datatable" class="table table-sm">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Razon Social</th>
                  <th scope="col">RUT</th>
                  <th scope="col">Direccion</th>
                  <th scope="col">Estado</th>
                  <th scope="col">Productos</th>
                  <th scope="col">Accion</th>
                </tr>
              </thead>
              <tbody>
                @foreach($empresas as $empresa)
                  <tr>
                    <th scope="row">{{ $loop->index }}</th>
                    <td>{{ $empresa->razon_social }}</td>
                    <td>{{ $empresa->rut }}</td>
                    <td>{{ $empresa->direccion }}</td>
                    <td>{{ isset($empresa->habilitado) ? (($empresa->habilitado) ?  'Habilitado' : 'Inhabilitado') : 'Segun Horario'}}</td>
                    <td><a class="btn btn-success" href="{{ $empresa->productosIndexRoute }}">Lista de Productos</a></td>
                    <td>
                      <a class="btn btn-warning" href="{{ route('empresas.habilitar.get', $empresa) }}">Estado</a>
                      <a class="btn btn-primary" href="{{route('empresas.edit', $empresa)}}"><i class="fas fa-edit"></i></a>
                      <delete-btn-component action="{{ route('empresas.destroy', $empresa) }}"></delete-btn-component>
                    </td>
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
