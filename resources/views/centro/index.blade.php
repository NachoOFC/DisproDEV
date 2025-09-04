@extends('layouts.app')

@section('title', 'Lista de Centros | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\Empresa))
  @section('home-route', route('cliente.home'))
@elseif (Auth::user()->userable instanceof \App\CompassRole)
    @section('home-route', route('compass.home'))
@endif

@section('nav-menu')
  @if ((Auth::user()->userable instanceof \App\Empresa))
    @include('cliente.menu')
  @elseif (Auth::user()->userable instanceof \App\CompassRole)
    @include('compass.menu')
  @endif
@endsection

@section('main')
  <div class="container">
    <div class="card">
      <h3 class="card-header font-bold text-xl">Lista de Centros</h3>
      <div class="card-body">
        <div class="container mt-2">
          <div class="table-responsive">
            <table id="datatable" class="table table-sm">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Empresa</th>
                  <th scope="col">Estado</th>
                  <th scope="col">Accion</th>
                </tr>
              </thead>
              <tbody>
                @foreach($centros as $centro)
                  <tr>
                    <th scope="row">{{ $loop->index }}</th>
                    <td>{{ $centro->nombre }}</td>
                    <td>{{ $centro->empresa->razon_social }}</td>
                    <td>
                        {{ isset($centro->habilitado) ? (($centro->habilitado) ? 'Habilitado' : 'Inhabilitado') : 'Segun Horario'}}
                        </td>
                    <td>
                      <a class="btn btn-warning" href="{{ route('centros.habilitar.get', $centro) }}">Cambiar estado</a>
                      <a class="btn btn-primary" href="{{route('centros.edit', $centro)}}"><i class="fas fa-edit"></i></a>
                      <delete-btn-component action="{{ route('centros.destroy', $centro) }}"></delete-btn-component>
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
