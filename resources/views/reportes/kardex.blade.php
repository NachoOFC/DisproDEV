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
                <v-card-title>Kardex</v-card-title>
                <v-card-text>
                    <v-simple-table dense fixed-header>
                        <template v-slot:default>
                            <thead>
                                <tr>
                                    <th class="text-left">Fecha</th>
                                    <th class="text-left">Tipo Docto</th>
                                    <th class="text-left">Folio</th>
                                    <th class="text-left">Concepto</th>
                                    <th class="text-left">Glosa</th>
                                    <th class="text-left">Entrada</th>
                                    <th class="text-left">Salida</th>
                                    <th class="text-left">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($items)
                                @foreach($items as $index=>$item)
                                <tr>
                                    <td>{{ $item->fecha_ingreso }}</td>
                                    <td>{{ $item->tipoDocto }}</td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->concepto }}</td>
                                    <td>{{ $item->bidon->proveedor->razon_social . " " . $item->bidon->nombre }}</td>
                                    <td>
                                        @switch(get_class($item))
                                        @case("App\Entrada")
                                            {{ $item->cantidad }}
                                        @break
                                        @case("App\CargaInicial")
                                            {{ $item->cantidad }}
                                        @break
                                        @default
                                        @if($item instanceof \App\Ajuste && boolval($item->suma))
                                            {{ $item->cantidad }}
                                        @endif
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch(get_class($item))
                                        @case("App\Salida")
                                            {{ $item->cantidad * -1 }}
                                        @break
                                        @case("App\NotaCredito")
                                            {{ $item->cantidad * -1 }}
                                        @break
                                        @default
                                        @if($item instanceof \App\Ajuste && !boolval($item->suma))
                                            {{ $item->cantidad * -1 }}
                                        @endif
                                        @endswitch
                                    </td>
                                    <td>{{ $saldos[$index] }}</td>
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
                    <form action="{{ route('kardex') }}" method="POST">
                        @csrf
                        <div class="container">
                            <proveedor-producto-component get-route="{{ route("get-productos") }}"></proveedor-producto-component>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fecha_inicio">Fecha Inicio:</label>
                                <input type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror">
                                @error('fecha_inicio')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="fecha_fin">Fecha Fin:</label>
                                <input type="date" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror">
                                @error('fecha_fin')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" name="pdf" value="0" class="btn btn-info">Filtrar</button>
                        <button type="submit" name="pdf" value="1" class="btn btn-info">Generar PDF</button>
                    </form>
                </v-card-text>
            </v-card>
        </div>
    </div>
</div>
@endsection
