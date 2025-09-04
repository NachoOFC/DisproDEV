@extends('layouts.app')

@section('title', 'Nota de Credito | Mline SIGER')

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
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Control de Notas de Credito</h3>
        <div class="card-body">
            <div class="container mt-2">
                <form class="flex flex-row items-end" method="POST" action="{{ route("nota_credito_filtrar")  }}">
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
                        <button type="submit" class="btn btn-primary">Mostrar estados de pagos en este periodo</button>
                    </div>
                </form>
                @isset($cierres)
                <table class="table table-sm" id="datatable">
                    <thead>
                        <tr>
                            <th>Folio EDP</th>
                            <th>Periodo</th>
                            <th>Monto</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cierres as $cierre)
                        <tr>
                            <td>{{ date_format($cierre->created_at, "d/m/Y") }}-{{ $cierre->id  }}</td>
                            <td>{{ $cierre->desde  }} -> {{ $cierre->hasta  }}</td>
                            <td>$ {{ number_format($cierre->monto, 0)  }}</td>
                            <td>
                                <a href="{{ route('nota_credito_cierre', $cierre)  }}">Seleccionar</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endisset
            </div>
        </div>
    </div>
</div>
@endsection