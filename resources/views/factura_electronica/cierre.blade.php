@extends('layouts.app')

@section('title', 'Factura Electronica | Mline SIGER')

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
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Control de Factura Electronica</h3>
        <div class="card-body">
            <div class="container mt-2">
                <header class="d-flex flex-row justify-content-end my-2">
                    <form method="POST" action="{{ route("factura_electronica_export", $cierre)  }}" class="mr-3">
                        @csrf

                        <button class="btn btn-info">Exportar a Excel</button>
                    </form>
                    @if ((Auth::user()->userable instanceof \App\CompassRole))
                    <a class="btn btn-outline-success" href="{{ route("factura_electronica_create", $cierre) }}">Crear FE</a>
                    @endif
                </header>
                <table class="table table-sm" id="datatable">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Folio</th>
                            <th>Monto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facturas as $factura)
                        <tr>
                            <td>{{ $factura->fecha  }}</td>
                            <td>{{ $factura->folio  }}</td>
                            <td>$ {{ number_format($factura->monto, 0)  }}</td>
                            <td>
                                <div class="d-inline-flex justify-content-around">
                                    <a href="{{ asset($factura->documento) }}" target="_BLANK">Ver PDF</a>
                                    @if ((Auth::user()->userable instanceof \App\CompassRole))
                                    <a href="{{ route("factura_electronica_edit", $factura) }}" class="btn btn-warning mx-2">Editar</a>
                                    <form method="POST" action="{{ route('factura_electronica_delete', $factura)  }}">
                                        @csrf
                                        @method("DELETE")

                                        <button class="btn btn-danger deleteBtn" type="submit">Eliminar</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section("js")
<script type="text/javascript">
    $(".deleteBtn").click((event) => {
        if (!confirm("Confirme si desea eliminar este documento")) {
            event.preventDefault();
        };
    });
</script>
@endsection