@extends('layouts.app')

@section('title', 'Resumen Estado de Pago | MLine SIGER')

@if ((Auth::user()->userable instanceof \App\CompassRole))
@section('home-route', route('compass.home'))
@section('nav-menu')
@include('compass.menu')
@endsection
@else
@section('home-route', route('cliente.home'))
@section('nav-menu')
@include('cliente.menu')
@endsection
@endif

@section('main')
<div class="container">
    <div class="card">
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Resumen Estado de Pago</h3>
        <div class="card-body">
            <div class="d-flex flex-row mb-2">
            </div>
            <div class="container mt-2">
                <form action="{{ route("estado_pago_generar_resumen") }}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="row">
                        <div class="form-group col-md-2 d-flex flex-col">
                            <label class="" for="inicio">Fecha de Inicio:</label>
                            <span class="">
                                <input class="form-control" required type="date" name="inicio">
                                <p class="text-muted">Obligatorio</p>
                            </span>
                        </div>

                        <div class="form-group col-md-2 d-flex flex-col">
                            <label class="" for="fin">Fecha de Fin:</label>
                            <span class="">
                                <input class="form-control" required type="date" name="fin">
                                <p class="text-muted">Obligatorio</p>
                            </span>
                        </div>
                    </div>

                    <v-expansion-panels>

                        @isset($empresas)
                        <v-expansion-panel>
                            <v-expansion-panel-header>Filtrar por Empresas</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <div class="form-group row">
                                    <label class="col-sm-2" for="empresas">Empresas:</label>
                                    <span class="col-sm-6">
                                        <autoselect :items='@json($empresas)' item-text="razon_social" item-value="id" name="empresas" :multiple="true"></autoselect>
                                    </span>
                                </div>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        @endisset

                        @isset($centros)
                        <v-expansion-panel>
                            <v-expansion-panel-header>Filtrar por Centros</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <div class="form-group row">
                                    <label class="col-sm-2" for="centros">Centros:</label>
                                    <span class="col-sm-6">
                                        <autoselect :items='@json($centros)' item-text="nombre" item-value="id" name="centros" :multiple="true"></autoselect>
                                    </span>
                                </div>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        @endisset($centros)

                        @isset($zonas)
                        <v-expansion-panel>
                            <v-expansion-panel-header>Filtrar por Zonas</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <div class="form-group row">
                                    <label class="col-sm-2" for="zonas">Zonas:</label>
                                    <span class="col-sm-6">
                                        <autoselect :items='@json($zonas)' item-text="nombre" item-value="nombre" name="zonas" :multiple="true"></autoselect>
                                    </span>
                                </div>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        @endisset($zonas)
                    </v-expansion-panels>

                    <button type="submit" class="btn btn-primary my-5">Ver Estado de Pago</button>
                    <button type="submit" class="btn btn-info ml-5" name="excel" value="1">Generar Excel</button>
                </form>

                @isset($guiasDespacho)
                @if($guiasDespacho->count() > 0)
                <table class="table table-sm" id="datatable">
                    <thead>
                        <tr>
                            <th>CENTRO</th>
                            <th>ID REQ.</th>
                            <th>FOLIO</th>
                            <th>FECHA</th>
                            <th>MONTO</th>
                            <th>NOTA CREDITO</th>
                            <th>NC CONTENEDORES</th>
                            <th>SIN NOTA CREDITO</th>
                            <th>LIQUIDACION</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($guiasDespacho as $guia)
                        <tr>
                            <td>{{ $guia->nombre_centro  }}</td>
                            <td>
                                {{ $guia->requerimiento_id  }}
                            </td>
                            <td>
                                {{ $guia->folio  }}
                            </td>
                            <td>{{ $guia->fecha  }}</td>
                            <td>{{ number_format($guia->neto)  }}</td>
                            <td>{{ number_format($guia->notaCredito)  }}</td>
                            <td>{{ number_format($guia->notaCreditoContenedor)  }}</td>
                            <td>{{ number_format($guia->sinNotaCredito)  }}</td>
                            <td>{{ number_format($guia->liquidacion)  }}</td>
                            <td>
                                <form method="POST" action="{{ route("estado_pago_liquidado", $guia) }}">
                                    @csrf

                                    @isset($guia->liquidado)
                                    <button @if($isLogistica) disabled @endif class="btn btn-info">LIQUIDADO</button>
                                    @else
                                    <button @if($isLogistica) disabled @endif type="submit" class="btn btn-warning">NO LIQUIDADO</button>
                                    @endisset
                                </form>
                                <a href="{{ route("estado_pago_nota_credito", $guia) }}" target="_blank">Ver NC Proforma</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="jumbotron">
                    No hay resultados para los filtros utilizados!.
                </div>
                @endif
                @endisset

            </div>
        </div>
    </div>
</div>
@endsection