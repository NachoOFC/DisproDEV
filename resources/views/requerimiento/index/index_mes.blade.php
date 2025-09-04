@extends('layouts.app')

@section('title', 'Lista de Ordenes | Mline SIGER')

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
            <h3 class="card-header font-bold text-xl">Lista de Orden de Pedidos</h3>
            <div class="card-body">
                <h5 class="card-title h4 text-center border-bottom">{{$centro->nombre}}</h5>
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Control</th>
                            @for ($i = 1; $i < 13; $i++)
                                <th scope="col">
                                    {{ ucfirst(\Carbon\Carbon::parse( date("Y-") . $i . date("-d") )->locale('es')->monthName) }}
                                </th>
                            @endfor
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">N° de Pedidos</th>
                            @for ($i = 0; $i < 13; $i++)
                                <td>
                                    {{ $counts[$i] }}
                                </td>
                            @endfor
                        </tr>
                        <tr>
                            <th scope="row">N° de Productos</th>
                            @for ($i = 0; $i < 13; $i++)
                                <td>
                                    {{ $products[$i] }}
                                </td>
                            @endfor
                        </tr>
                        <tr>
                            <th scope="row">Costo Total ($)</th>
                            @for ($i = 0; $i < 13; $i++)
                                <td>
                                    {{ number_format($totals[$i], 0) }}
                                </td>
                            @endfor
                        </tr>
                        <tr>
                            <th scope="row">Esperando validacion</th>
                            @php
                                $total = 0;
                            @endphp
                            @for ($i = 1; $i < 13; $i++)
                                @if ($estados->has(str_pad($i, 2, "0", STR_PAD_LEFT)) && $estados[str_pad($i, 2, "0", STR_PAD_LEFT)]->has('ESPERANDO VALIDACION'))
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '0']) }}">
                                            {{ $total += count($estados[str_pad($i, 2, "0", STR_PAD_LEFT)]['ESPERANDO VALIDACION']) }}
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '0']) }}">
                                            0
                                        </a>
                                    </td>
                                @endif
                            @endfor
                            <td> {{ $total }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Validado</th>
                            @php
                                $total = 0;
                            @endphp
                            @for ($i = 1; $i < 13; $i++)
                                @if ($estados->has(str_pad($i, 2, "0", STR_PAD_LEFT)) && $estados[str_pad($i, 2, "0", STR_PAD_LEFT)]->has('VALIDADO'))
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '1']) }}">
                                            {{ $total += count($estados[str_pad($i, 2, "0", STR_PAD_LEFT)]['VALIDADO']) }}
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '1']) }}">
                                            0
                                        </a>
                                    </td>
                                @endif
                            @endfor
                            <td> {{ $total }}</td>
                        </tr>
                        <tr>
                            <th scope="row">En Procesamiento</th>
                            @for ($i = 1; $i < 13; $i++)
                                @if ($estados->has(str_pad($i, 2, "0", STR_PAD_LEFT)) && $estados[str_pad($i, 2, "0", STR_PAD_LEFT)]->has('EN PROCESAMIENTO'))
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '2']) }}">
                                            {{ $total += count($estados[str_pad($i, 2, "0", STR_PAD_LEFT)]['EN PROCESAMIENTO']) }}
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '2']) }}">
                                            0
                                        </a>
                                    </td>
                                @endif
                            @endfor
                            <td>{{ $total }}</td>
                        </tr>
                        <tr>
                            <th scope="row">En Bodega</th>
                            @php
                                $total = 0;
                            @endphp
                            @for ($i = 1; $i < 13; $i++)
                                @if ($estados->has(str_pad($i, 2, "0", STR_PAD_LEFT)) && $estados[str_pad($i, 2, "0", STR_PAD_LEFT)]->has('EN BODEGA'))
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '3']) }}">
                                            {{ $total += count($estados[str_pad($i, 2, "0", STR_PAD_LEFT)]['EN BODEGA']) }}
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '3']) }}">
                                            0
                                        </a>
                                    </td>
                                @endif
                            @endfor
                            <td>{{ $total }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Despachado</th>
                            @php
                                $total = 0;
                            @endphp
                            @for ($i = 1; $i < 13; $i++)
                                @if ($estados->has(str_pad($i, 2, "0", STR_PAD_LEFT)) && $estados[str_pad($i, 2, "0", STR_PAD_LEFT)]->has('DESPACHADO'))
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '4']) }}">
                                            {{ $total += count($estados[str_pad($i, 2, "0", STR_PAD_LEFT)]['DESPACHADO']) }}
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '4']) }}">
                                            0
                                        </a>
                                    </td>
                                @endif
                            @endfor
                            <td>{{ $total }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Entregado</th>
                            @php
                                $total = 0;
                            @endphp
                            @for ($i = 1; $i < 13; $i++)
                                @if ($estados->has(str_pad($i, 2, "0", STR_PAD_LEFT)) && $estados[str_pad($i, 2, "0", STR_PAD_LEFT)]->has('ENTREGADO'))
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '5']) }}">
                                            {{ $total += count($estados[str_pad($i, 2, "0", STR_PAD_LEFT)]['ENTREGADO']) }}
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '5']) }}">
                                            0
                                        </a>
                                    </td>
                                @endif
                            @endfor
                            <td>{{ $total }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Rechazado</th>
                            @php
                                $total = 0;
                            @endphp
                            @for ($i = 1; $i < 13; $i++)
                                @if ($estados->has(str_pad($i, 2, "0", STR_PAD_LEFT)) && $estados[str_pad($i, 2, "0", STR_PAD_LEFT)]->has('RECHAZADO'))
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '6']) }}">
                                            {{ $total += count($estados[str_pad($i, 2, "0", STR_PAD_LEFT)]['RECHAZADO']) }}
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '6']) }}">
                                            0
                                        </a>
                                    </td>
                                @endif
                            @endfor
                            <td>{{ $total }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
