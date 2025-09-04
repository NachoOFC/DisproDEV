@extends('layouts.app')

@section('title', 'Ver Observaciones | Mline SIGER')

@section('home-route', route('cliente.home'))

    @section('nav-menu')
        @include('cliente.menu')
    @endsection

    @section('main')
        <div class="card">
            <h3 class="card-header font-bold text-xl">
                {{ \Auth::user()->userable->razon_social }}:
                Control de observaciones de guia de despacho
            </h3>
            <div class="card-body">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-12">
                        <v-simple-table dense fixed-header height="500">
                            <template v-slot:default>
                                <thead>
                                    <tr>
                                        <th># Requerimiento</th>
                                        <th>Requerimiento</th>
                                        <th>Folio Guia Despacho</th>
                                        <th>Fecha Guia Despacho</th>
                                        <th>Producto</th>
                                        <th>Solicitado</th>
                                        <th>Despachado</th>
                                        <th>Observacion Compass</th>
                                        <th>Motivo Rechazo</th>
                                        <th>Genera nota de credito</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rechazos as $rechazo)
                                        <tr>
                                            <td>{{ $rechazo->guiaDespacho->requerimiento->id  }}</td>
                                            <td>{{ $rechazo->guiaDespacho->requerimiento->nombre  }}</td>
                                            <td>{{ $rechazo->guiaDespacho->folio  }}</td>
                                            <td>{{ $rechazo->guiaDespacho->fecha  }}</td>
                                            <td>{{ $rechazo->producto->detalle  }}</td>
                                            <td>{{ $rechazo->productoGuia->pivot->cantidad  }}</td>
                                            <td>{{ $rechazo->productoGuia->pivot->real  }}</td>
                                            <td>{{ $rechazo->productoGuia->pivot->observacion  }}</td>
                                            <td>{{ $rechazo->motivo  }}</td>
                                            <td>
                                                <state-switcher-component
                                                    @if ($rechazo->estadoPago)
                                                    :currentState="{{ $rechazo->estadoPago  }}"
                                                    @endif
                                                    action="{{ route('rechazo.estado-pago.post', $rechazo)  }}"
                                                ></state-switcher-component>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </template>
                        </v-simple-table>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-2">
                        <form method="POST" action="{{ route("rechazo.guardar-estado") }}">
                            @csrf
                            <input type="hidden" name="rechazos" value="{{ $rechazos->pluck("id")->toJson() }}" />

                            <button class="btn btn-success">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endsection
