@extends('layouts.app')

@section('title', 'Carta de Requerimientos | MLine SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
@include('compass.menu')
@endsection

@section('main')
<div class="container">
    <div class="card">
        <h3 class="card-header font-bold text-xl"> Carta de Trabajo</h3>
        <div class="card-body">
            <div class="d-flex flex-row mb-2">
            </div>
            <div class="container mt-2">
                <form action="{{ route('reportes.carta.filter') }}" method="POST" accept-charset="utf-8">
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
                    </v-expansion-panels>

                    <button type="submit" class="btn bg-orange-500 hover:bg-orange-700 text-white my-5">Ver Historial</button>
                    <button type="submit" name="generate" value="1" class="btn btn-warning mx-2 my-5">Generar Excel Consolidado</button>
                </form>

                @isset($requerimientos)
                @if($requerimientos->count() > 0)
                <table class="table table-sm" id="datatable">
                    <thead>
                        <tr>
                            <th>Centro</th>
                            <th>Id</th>
                            <th>Requerimiento</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Descargar Carta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requerimientos as $requerimiento)
                        <tr>
                            <td>{{ $requerimiento->centro->nombre  }}</td>
                            <td>

                                <a href="{{ route('pedidos.show', $requerimiento) }}">
                                    {{ $requerimiento->id }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('pedidos.show', $requerimiento) }}">
                                    {{ $requerimiento->nombre }}
                                </a>
                            </td>
                            <td>{{ $requerimiento->created_at  }}</td>
                            <td>{{ number_format($requerimiento->getTotal(), 0) }}</td>
                            <td>
                                <form method="POST" action="{{ route('reportes.carta.download', $requerimiento) }}">
                                    @csrf

                                    <button class="btn bg-orange-500 hover:bg-orange-700 text-white" type="submit">Descargar Carta</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                @endisset

            </div>
        </div>
    </div>
</div>
@endsection