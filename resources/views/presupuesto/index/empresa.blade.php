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
                    <form action="{{ route('presupuesto.indexEmpresa') }}" method="GET" class="form-row align-items-center">
                        <!-- Mes -->
                        <div class="form-group col-md-3">
                            <label for="month">Mes:</label>
                            <select class="form-control form-control-sm" name="month" id="month">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Año -->
                        <div class="form-group col-md-2">
                            <label for="year">Año:</label>
                            <select class="form-control form-control-sm" name="year" id="year">
                                @for ($i = date("Y") - 5; $i < date("Y") + 10; $i++)
                                    <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Centro -->
                        <div class="form-group col-md-3">
                            <label for="centro_id">Centro:</label>
                            <select class="form-control" name="centro_id" id="centro_id">
                                <option value="">Todos</option>
                                @foreach($centros as $centro)
                                    <option value="{{ $centro->id }}" {{ request('centro_id') == $centro->id ? 'selected' : '' }}>
                                        {{ $centro->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Acumulado -->
                        <div class="form-group col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="acumulado" id="acumulado" value="1" {{ request('acumulado') ? 'checked' : '' }}>
                                <label class="form-check-label" for="acumulado">Acumulado</label>
                            </div>
                        </div>

                        <!-- Bot���n de env���o -->
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>

            @isset($requerimientos)
            <div class="container mt-2">
                <div class="table-responsive">
                    <table id="datatable-presupuesto" class="table table-bordered table-sm">
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
                            <tr>
                                <td>{{ $date }}</td>
                                <td>Presupuesto</td>
                                <td>Carga Inicial</td>
                                <td class="text-center">{{ __($date->year.$date->month) }}</td>
                                <td class="text-right">{{ number_format($inicial, 0) }}</td>
                                <td></td>
                                <td class="text-right">{{ number_format($inicial, 0)}}</td>
                            </tr>
                            @php
                            $saldo = ($inicial / 100);
                            @endphp
                            @foreach ($requerimientos as $requerimiento)
                            @foreach ($requerimiento as $pedido)
                            <tr>
                                <td>{{ $pedido->created_at }}</td>
                                <td>
                                    <a href="{{ route('presupuesto.indexCentro', ['centroId' => $pedido->centro()->get()->first()->id]) }}">
                                        {{ $pedido->nombre }}
                                    </a>
                                </td>
                                <td>Orden de Pedido</td>
                                <td class="text-center">
                                    <modal-btn-component :button="false" title="Orden de Pedido" :message='[
                                                    { data: @json($pedido->centro), type: "Object", keys: ["nombre"]},
                                                    { data: @json($pedido->productos), type: "Array", keys: ["sku",
                                                    "detalle", "precio",
                                                    "pivot", "total"], pivot: "cantidad"},
                                                    { data: @json(["total" => "$" . number_format($pedido->getTotal()) ]), type: "Object", keys: ["total"]}
                                                    ]'>{{ $pedido->id }}</modal-btn-component>
                                </td>
                                <td></td>
                                <td class="text-right">{{ number_format($pedido->getTotal(), 0) }}</td>
                                <td class="text-right">{{ number_format(($saldo -= $pedido->getTotal()), 0) }}</td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endisset
        </div>
    </div>
</div>
@endsection