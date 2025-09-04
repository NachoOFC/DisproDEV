@extends('layouts.app')

@section('title', 'Estado Observacion | Mline SIGER')

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
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Cuadro de estado de observaciones a recepciones</h3>
        <div class="card-body">
            <div class="container mt-2">
                <form class="flex flex-row items-end" method="POST" action="{{ route("generate_estado_general")  }}">
                    @csrf
                    @if(isset($empresas) && count($empresas) > 0)
                    <div class="flex flex-col">
                        <label for="">Empresa:</label>
                        <select class="form-control" name="empresa_id" required>
                            <option>Seleccione una empresa</option>
                            @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}">{{ $empresa->razon_social  }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="flex flex-col mx-2">
                        <label for="">Desde:</label>
                        <input type="date" name="inicio" class="form-control" required />
                    </div>

                    <div class="flex flex-col">
                        <label for="">Hasta:</label>
                        <input type="date" name="fin" class="form-control" required />
                    </div>
                    <div class="flex flex-col mx-2">
                        <button type="submit" class="btn btn-primary">Ver Guias</button>
                    </div>

                </form>
            </div>
            @if(isset($aceptadas) || isset($rechazadas) || isset($observadas))
            <div class="container mt-2">
                <v-expansion-panels>
                    @isset($aceptadas)
                    <v-expansion-panel>
                        <v-expansion-panel-header>Aceptadas</v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID OP</th>
                                            <th scope="col">ID GUIA</th>
                                            <th scope="col">CENTRO</th>
                                            <th scope="col">FECHA</th>
                                            <th scope="col">MONTO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($aceptadas as $guia)
                                        <tr>
                                            <td>{{ $guia->requerimiento_id  }}</td>
                                            <td>{{ $guia->folio  }}</td>
                                            <td>{{ $guia->nombre_centro  }}</td>
                                            <td>{{ $guia->fecha  }}</td>
                                            <td>{{ number_format($guia->neto, 0)  }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    @endisset
                    @isset($rechazadas)
                    <v-expansion-panel>
                        <v-expansion-panel-header>Rechazadas</v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID OP</th>
                                            <th scope="col">ID GUIA</th>
                                            <th scope="col">CENTRO</th>
                                            <th scope="col">FECHA</th>
                                            <th scope="col">MONTO</th>
                                            <th scope="col">PRODUCTOS <br /> FALTANTES (PARCIAL)</th>
                                            <th scope="col">PRODUCTOS <br /> FALTANTES (TOTAL)</th>
                                            <th scope="col">TOTAL RECHAZADAS</th>
                                            <th scope="col">SIN LIQUIDAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($aceptadas as $guia)
                                        @if(($guia->getObservacionesCountById(2) + $guia->getObservacionesCountById(3)) > 0)
                                        <tr>
                                            <td>{{ $guia->requerimiento_id  }}</td>
                                            <td>{{ $guia->folio  }}</td>
                                            <td>{{ $guia->nombre_centro  }}</td>
                                            <td>{{ $guia->fecha  }}</td>
                                            <td class="text-center">{{ number_format($guia->neto, 0)  }}</td>
                                            <td class="text-center">
                                                <a href="{{ route("estado_pago_concepto", ["guiaDespacho" => $guia, "concepto" => "3"])  }}" target="_blank">
                                                    {{ $guia->getObservacionesCountById(3)  }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route("estado_pago_concepto", ["guiaDespacho" => $guia, "concepto" => "2"])  }}" target="_blank">
                                                    {{ $guia->getObservacionesCountById(2)  }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route("estado_pago_concepto", ["guiaDespacho" => $guia, "concepto" => "2,3"])  }}" target="_blank">
                                                    {{ $guia->getObservacionesCountById(2) +  $guia->getObservacionesCountById(3) }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                {{ $guia->getNoLiquidadosRechazados()  }}
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    @endisset
                    @isset($observadas)
                    <v-expansion-panel>
                        <v-expansion-panel-header>Recibidas con Observaciones</v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID OP</th>
                                            <th scope="col">ID GUIA</th>
                                            <th scope="col">CENTRO</th>
                                            <th scope="col">FECHA</th>
                                            <th scope="col">MONTO</th>
                                            <th scope="col">PRODUCTOS EN MAL ESTADO</th>
                                            <th scope="col">PRODUCTOS VENCIDOS</th>
                                            <th scope="col">PRODUCTOS POR VENCER</th>
                                            <th scope="col">ENVASES DETERIORADOS</th>
                                            <th scope="col">TOTAL OBSERVADAS</th>
                                            <th scope="col">SIN LIQUIDAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($aceptadas as $guia)
                                        @if(($guia->getObservacionesCountById(4) + $guia->getObservacionesCountById(5) + $guia->getObservacionesCountById(6) + $guia->getObservacionesCountById(7)) > 0)
                                        <tr>
                                            <td>{{ $guia->requerimiento_id  }}</td>
                                            <td>{{ $guia->folio  }}</td>
                                            <td>{{ $guia->nombre_centro  }}</td>
                                            <td>{{ $guia->fecha  }}</td>
                                            <td class="text-center">{{ number_format($guia->neto, 0)  }}</td>
                                            <td class="text-center">
                                                <a href="{{ route("estado_pago_concepto", ["guiaDespacho" => $guia, "concepto" => "4"])  }}" target="_blank">
                                                    {{ $guia->getObservacionesCountById(4)  }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route("estado_pago_concepto", ["guiaDespacho" => $guia, "concepto" => "5"])  }}" target="_blank">
                                                    {{ $guia->getObservacionesCountById(5)  }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route("estado_pago_concepto", ["guiaDespacho" => $guia, "concepto" => "6"])  }}" target="_blank">
                                                    {{ $guia->getObservacionesCountById(6)  }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route("estado_pago_concepto", ["guiaDespacho" => $guia, "concepto" => "7"])  }}" target="_blank">
                                                    {{ $guia->getObservacionesCountById(7)  }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route("estado_pago_concepto", ["guiaDespacho" => $guia, "concepto" => "4,5,6,7"])  }}" target="_blank">
                                                    {{ $guia->getObservacionesCountById(4) + $guia->getObservacionesCountById(5) + $guia->getObservacionesCountById(6) + $guia->getObservacionesCountById(7)  }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                {{ $guia->getNoLiquidadosObservados()  }}
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    @endisset
                </v-expansion-panels>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection