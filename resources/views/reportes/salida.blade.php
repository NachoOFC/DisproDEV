@extends('layouts.app')
@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

  @section('nav-menu')
    @include('compass.menu')
  @endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md">
            <v-card>
                <v-card-title>Salidas de Productos por Proveedor</v-card-title>
                <v-card-text>
                    <v-simple-table dense fixed-header>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th class="text-left">Producto</th>
                                    <th class="text-left">Salidas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($inventario)
                                @foreach($inventario as $index=>$item)
                                <tr>
                                    <td>{{ $item["bidon"]->nombre }}</td>
                                    <td>{{ abs($item["salidas"]) }}</td>
                                </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-card-text>
            </v-card>
        </div>
        <div class="col-md-4">
            <v-card>
                <v-card-title>Filtros</v-card-title>
                <v-card-text>
                    <form action="{{ route('filter-salida') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md">
                                <label for="proveedor_id">Proveedor:</label>
                                <select required name="proveedor_id" class="form-control">
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}">{{ $proveedor->razon_social }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fecha_inicio">Fecha Inicio:</label>
                                <input required type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror">
                                @error('fecha_inicio')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="fecha_fin">Fecha Fin:</label>
                                <input required type="date" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror">
                                @error('fecha_fin')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button class="btn btn-info">Filtrar</button>
                    </form>
                </v-card-text>
            </v-card>
        </div>
    </div>
</div>
@endsection
