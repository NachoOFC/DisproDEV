@extends('layouts.app')

@section('title', 'Asignar Horario a Empresas | Mline Siger')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Horario de Empresas</h3>
            <div class="card-body">
                <!-- Mostrar horarios asignados -->
                <div class="mb-4">
                    <h4 class="font-bold text-lg">Horarios Asignados</h4>
                    @if($horarios->isEmpty())
                        <p class="text-muted">No hay horarios asignados.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Empresa</th>
                                    <th>Creación de Pedidos</th>
                                    <th>Validación de Pedidos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($horarios as $horario)
                                    <tr>
                                        <td>{{ $horario->empresa->razon_social }}</td>
                                        <td>
                                            {{ obtenerNombreDia($horario->fecha_creacion_inicio) }} - {{ obtenerNombreDia($horario->fecha_creacion_fin) }}<br>
                                            {{ formatearHora($horario->hora_creacion_inicio) }} - {{ formatearHora($horario->hora_creacion_fin) }}
                                        </td>
                                        <td>
                                            {{ obtenerNombreDia($horario->fecha_validacion_inicio) }} - {{ obtenerNombreDia($horario->fecha_validacion_fin) }}<br>
                                            {{ formatearHora($horario->hora_validacion_inicio) }} - {{ formatearHora($horario->hora_validacion_fin) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <!-- Formulario para asignar nuevos horarios -->
                <form action="{{ route('horarios.store') }}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-2 text-right" for="empresa">Empresa:</label>
                        <span class="col-sm-6">
                            <select class="form-control" name="empresa" required>
                                @foreach ($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">{{ $empresa->razon_social }}</option>
                                @endforeach
                            </select>
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <div class="form-row justify-content-around">
                        <fieldset class="col-md-5 border p-4">
                            <legend class="text-sm font-black">Rango para la creación de Pedidos</legend>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="fechaCreacionInicio">Desde:</label>
                                    <select class="form-control" name="fechaCreacionInicio" required>
                                        @for ($i = 1; $i <= 7; $i++)
                                            <option value="{{ $i }}">{{ obtenerNombreDia($i) }}</option>
                                        @endfor
                                    </select>
                                    <p class="text-muted">Obligatorio</p>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="horaCreacionInicio">A las:</label>
                                    <input class="form-control" type="time" name="horaCreacionInicio" required>
                                    <p class="text-muted">Obligatorio</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="fechaCreacionFin">Hasta:</label>
                                    <select class="form-control" name="fechaCreacionFin" required>
                                        @for ($i = 1; $i <= 7; $i++)
                                            <option value="{{ $i }}">{{ obtenerNombreDia($i) }}</option>
                                        @endfor
                                    </select>
                                    <p class="text-muted">Obligatorio</p>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="horaCreacionFin">A las:</label>
                                    <input class="form-control" type="time" name="horaCreacionFin" required>
                                    <p class="text-muted">Obligatorio</p>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="col-md-5 border p-4">
                            <legend class="text-sm font-black">Rango para la Validación de Pedidos</legend>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="fechaValidacionInicio">Desde:</label>
                                    <select class="form-control" name="fechaValidacionInicio" required>
                                        @for ($i = 1; $i <= 7; $i++)
                                            <option value="{{ $i }}">{{ obtenerNombreDia($i) }}</option>
                                        @endfor
                                    </select>
                                    <p class="text-muted">Obligatorio</p>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="horaValidacionInicio">A las:</label>
                                    <input class="form-control" type="time" name="horaValidacionInicio" required>
                                    <p class="text-muted">Obligatorio</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="fechaValidacionFin">Hasta:</label>
                                    <select class="form-control" name="fechaValidacionFin" required>
                                        @for ($i = 1; $i <= 7; $i++)
                                            <option value="{{ $i }}">{{ obtenerNombreDia($i) }}</option>
                                        @endfor
                                    </select>
                                    <p class="text-muted">Obligatorio</p>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="horaValidacionFin">A las:</label>
                                    <input class="form-control" type="time" name="horaValidacionFin" required>
                                    <p class="text-muted">Obligatorio</p>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="row my-2">
                        <button class="mx-auto btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@php
    // Función para obtener el nombre del día a partir de un número
    function obtenerNombreDia($numeroDia) {
        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];
        return $dias[$numeroDia] ?? 'Desconocido';
    }

    // Función para formatear la hora en formato HH:MM
    function formatearHora($hora) {
        return date('H:i', strtotime($hora));
    }
@endphp
