@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
  @include('compass.menu')
@endsection

@section('main')
  <div class="container">
    <div class="card">
      <h3 class="card-header font-bold text-xl">Lista de Productos</h3>
      <div class="card-body">
        <div class="container mt-2">
        <div class="table-responsive">
            <table id="datatable" class="table table-sm">
              <thead>
                <tr>
                  <th scope="col">SKU</th>
                  <th scope="col">Detalle</th>
                  <th scope="col">Precio Costo</th>
                  <th scope="col">Accion</th>
                </tr>
              </thead>
              <tbody>
                @foreach($productos as $producto)
                  <tr>
                    <td>{{ $producto->sku }}</td>
                    <td>{{ $producto->detalle }}</td>
                    <td>{{ $producto->costo }}</td>
                    <td>
                      <a class="btn btn-primary" href="{{route('productos.edit', $producto)}}" title="Editar producto" aria-label="Editar producto {{ $producto->nombre }}"><i class="fas fa-edit" aria-hidden="true"></i><span class="sr-only">Editar</span></a>
                      <delete-btn-component action="{{ route('productos.destroy', $producto) }}" title="Eliminar producto" aria-label="Eliminar producto {{ $producto->nombre }}"></delete-btn-component>
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

