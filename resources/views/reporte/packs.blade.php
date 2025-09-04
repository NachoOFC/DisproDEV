@extends('layouts.app')

@section('title', 'Generar Packs | MLine SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
@include('compass.menu')
@endsection

@section('main')
<div class="container">
    <div class="card">
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Generar Packs</h3>
        <div class="card-body">
            <div class="d-flex flex-row mb-2">
            </div>
            <div class="container mt-2">
                <form action="{{ route('reportes.packs.generar') }}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="inicio">Fecha de Inicio:</label>
                            <span>
                                <input class="form-control" required type="date" name="inicio">
                                <p class="text-muted">Obligatorio</p>
                            </span>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="fin">Fecha de Fin:</label>
                            <span>
                                <input class="form-control" required type="date" name="fin">
                                <p class="text-muted">Obligatorio</p>
                            </span>
                        </div>

                        <div class="form-group col-md-8">
                            <v-expansion-panels>

                                <v-expansion-panel>
                                    <v-expansion-panel-header>Filtrar por Empresa</v-expansion-panel-header>
                                    <v-expansion-panel-content>
                                        <div class="form-group row">
                                            <label class="col-sm-2" for="empresas">Empresa:</label>
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
                        </div>
                    </div>


                    <button class="btn btn-primary mt-2">Generar Pack</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection