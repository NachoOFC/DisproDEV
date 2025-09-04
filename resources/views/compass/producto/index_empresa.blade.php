@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">
                Lista de Productos de {{$empresa->razon_social}}
            </h3>
            <div class="card-body">
                <div class="container mt-2">
                    <div class="dropdown mb-4">
                        <button
                            class="btn btn-secondary dropdown-toggle"
                            type="button"
                            id="dropdownMenuButton"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                            Escoge la Empresa
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach ($empresas as $item)
                                <a
                                class="dropdown-item"
                                href="{{ route('productos.indexEmpresa', $item) }}">
                                    {{ $item->razon_social }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">SKU</th>
                                    <th scope="col">Detalle</th>
                                    <th scope="col">Precio Costo</th>
                                    <th scope="col">Precio Venta</th>
                                    <th scope="col">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $producto)
                                    <tr>
                                        <td>{{ $producto->sku }}</td>
                                        <td>{{ $producto->detalle }}</td>
                                        <td>{{ $producto->costo }}</td>
                                        <td>{{ $producto->pivot->precio }}</td>
                                        <td>
                                            <a class="btn btn-primary" href="{{route('productos.edit', $producto)}}"><i class="fas fa-edit"></i></a>
                                            <delete-btn-component action="{{ route('productos.destroy', $producto) }}"></delete-btn-component>
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
