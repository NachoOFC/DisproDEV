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
            Auth::user()->getNombreRelacionado() }}: Carga Masiva de Productos</div>
            <div class="card-body">
                <ol>
                    <li>1. Descarga el formato de Excel: 
                        <a class="dropdown-item" href="{{ route('productos.formato') }}" target="_blank">Aqui</a>
                    </li>
                    <li>2. Ingresa los datos en el archivo:
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th scope="row">SKU</th>
                                <td>
                                    <p class="text-danger">OBLIGATORIO.</p>
                                    Valor de referencia del sistema para identificar producto.
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Detalle</th>
                                <td>
                                    <p class="text-danger">OBLIGATORIO.</p>
                                    Nombre o identifacion del producto.
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Precio Costo</th>
                                <td>
                                    <p class="text-danger">OBLIGATORIO.</p>
                                    Costo de ese producto.
                                </td>
                            </tr>
                        </table>
                    </li>
                    <li>3. Sube el archivo modificado aqui:
                        <form action="{{ route('productos.asignacionMasivaProductos') }}" method="POST"  enctype="multipart/form-data">
                            @csrf
                            <div class="form-row align-items-end mt-4">
                                <div class="form-group col-md">
                                    <label for="formato">Ingrese el archivo modificado:</label>
                                    <input type="file" class="form-control-file" name="productos">
                                </div>
                            </div>
                            <div class="col-md">
                                <button class="btn btn-primary" type="submit">Guardar</button>
                            </div>
                        </form>
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection
