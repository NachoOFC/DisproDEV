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
            Auth::user()->getNombreRelacionado() }}: Asignacion Masiva de
            Productos - Empresa - Precio</div>
            <div class="card-body">
                <ol>
                    <li>1. Descarga el formato de Excel: 
                        <a class="dropdown-item" href="{{ route('productos.asignacionMasivaFormato') }}" target="_blank">Aqui</a>
                    </li>
                    <li>2. Ingresa los datos en el archivo:
                        <ul class="list-group">
                            <li class="list-group-item">
                                Coloca los valores requeridos:
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <th scope="row">SKU</th>
                                        <td>
                                            <p class="text-danger">NO MODIFICAR.</p>
                                            Valor de referencia del sistema para identificar producto.
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Detalle</th>
                                        <td>
                                            <p class="text-info">Identificacion. No necesita ingreso de datos.</p>
                                            Nombre del producto.
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Precio Costo</th>
                                        <td>
                                            <p class="text-danger">NO MODIFICAR.</p>
                                            Costo de ese producto.
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Porcentaje Ganancia</th>
                                        <td>
                                            <p class="text-success">INGRESAR DATO.</p>
                                            El margen de ganancia en porcentaje para ese producto
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Neto</th>
                                        <td>
                                            <p class="text-info">Calculo. No necesita ingreso de datos.</p> 
                                            Valor calculado entre el precio de costo y el porcentaje de ganancia.
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Precio Venta</th>
                                        <td>
                                            <p class="text-primary">Calculo. 
                                                <b>Si se ingresa un valor de 0 o un campo vacio entonces el producto no estara disponible para la empresa asociada.</b>
                                            </p>
                                            Valor calculado entre el neto y el IVA.
                                        </td>
                                    </tr>
                                </table>
                            </li>
                            <li class="list-group-item">
                                <p class="text-danger">
                                    Un valor de precio venta igual a 0 o vacio quiere decir que ese producto no estara
                                    disponible para la empresa asociada, por lo tanto este
                                    producto no saldra como opcion a los centros de esa empresa  al momento de
                                    crear ordenes de pedidos.
                                </p>
                            </li>
                        </ul>
                    </li>
                    <li>3. Sube el archivo modificado aqui:
                        <form action="{{ route('productos.asignacionMasiva') }}" method="POST"  enctype="multipart/form-data">
                            @csrf
                            <div class="form-row align-items-end mt-4">
                                <div class="form-group col-md">
                                    <label for="empresa">Seleccione la empresa:</label>
                                    <select name="empresa" class="form-control">
                                        @foreach ($empresas as $empresa)
                                            <option value="{{ $empresa->id }}">{{ $empresa->razon_social }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md">
                                    <label for="formato">Ingrese el archivo modificado:</label>
                                    <input type="file" class="form-control-file" name="formato">
                                </div>
                                <div class="form-group col-md">
                                    <label for="fecha">Ingrese la Fecha cuando se aplicaran los cambios:</label>
                                    <input type="date" class="form-control" name="fecha">
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
