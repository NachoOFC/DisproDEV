@extends('layouts.app')

@section('title', 'Cuenta Corriente | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Cuenta Corriente</h3>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <form action="{{ url()->current() }}" class="form-group form-row">
                            <label class="col-md-2 col-form-label text-md-right">Mes:</label>
                            <div class="col-md-4">
                                <select class="form-control form-control-sm" name="mes">
                                    @for ($i = 0; $i < 12; $i++)
                                        <option value="{{$i}}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <label class="col-md-2 col-form-label text-md-right">Año:</label>
                            <div class="col-md-3">
                                <select class="form-control form-control-sm" name="year">
                                    @for ($i = date("Y"); $i < date("Y") + 6; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-1"><button class="btn bg-blue-500 hover:bg-blue-700 text-white">Filtrar</button></div>
                        </form>
                    </div>
                </div>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link" href="{{ route("presupuesto.indexCentro", ['acumulado' => true])}}">Acumulado</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route("presupuesto.indexCentro", ['soloMes' => true])}}">Solo Mes</a></li>
                </ul>
                <div class="container mt-2">
                    <div class="table-responsive">
                        <table id="datatable-presupuesto" class="table table-sm table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Fecha</th>
                                    <th scope="col" class="text-center">Concepto</th>
                                    <th scope="col" class="text-center">Tipo</th>
                                    <th scope="col" class="text-center">Id</th>
                                    <th scope="col" class="text-center">Entrada ($)</th>
                                    <th scope="col" class="text-center">Salida ($)</th>
                                    <th scope="col" class="text-center">Saldo ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    if(array_key_exists($date->month, $inicial)) {
                                        $saldo = ($inicial[$date->month]->monto);
                                    } else {
                                        $saldo = $inicial->monto ?? 0;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $date }}</td>
                                    <td>Presupuesto</td>
                                    <td class="text-center">Carga Inicial</td>
                                    <td class="text-center">{{ __($date->year.$date->month) }}</td>
                                    <td class="text-right">{{ number_format($saldo, 2) }}</td>
                                    <td></td>
                                    <td class="text-right">{{ number_format($saldo, 2)}}</td>
                                </tr>
                                @foreach ($requerimientos as $pedido)
                                        <tr>
                                            <td>{{$pedido->created_at}}</td>
                                            <td>
                                                <a href="{{route('pedidos.show', $pedido)}}">
                                                    {{ __($pedido->nombre) }}
                                                </a>
                                            </td>
                                            <td class="text-center">Orden de Pedido</td>
                                            <td class="text-center">
                                                <modal-btn-component
                                                    :button="false"
                                                    title="Orden de Pedido"
                                                    :message='[
                                                    { data: @json($pedido->productos), type: "Array", keys: ["sku",
                                                    "detalle", "precio",
                                                    "pivot", "total"], pivot: "cantidad"},
                                                    { data: @json(["total" => "$" . number_format($pedido->getTotal()) ]), type: "Object", keys: ["total"]}
                                                    ]'>{{$pedido->id}}</modal-btn-component>
                                            </td>
                                            <td></td>
                                            <td class="text-right">{{ number_format($pedido->getTotal(), 2) }}</td>
                                            <td class="text-right">{{ number_format(($saldo -= $pedido->getTotal()), 2) }}</td>
                                        </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
