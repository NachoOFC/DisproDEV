@extends('layouts.app')

@section('title', 'Orden de Compra | Mline SIGER')

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
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Control de Ordenes de Compra</h3>
        <div class="card-body">
            <div class="container mt-2">
                <header class="d-flex flex-row justify-content-end my-2">
                    <form method="POST" action="{{ route("orden_compra_export", $cierre)  }}" class="mr-3">
                        @csrf

                        <button class="btn btn-info">Exportar a Excel</button>
                    </form>
                    @if (Auth::user()->userable instanceof \App\Empresa)
                    <a class="btn btn-outline-success" href="{{ route("orden_compra_create", $cierre) }}">Crear OC</a>
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
                        @foreach($ordenes as $orden)
                        <tr>
                            <td>{{ $orden->fecha  }}</td>
                            <td>{{ $orden->folio  }}</td>
                            <td>$ {{ number_format($orden->monto, 0)  }}</td>
                            <td>
                                <div class="d-inline-flex justify-content-around">
                                    <a href="{{ asset($orden->documento) }}" target="_BLANK">Ver PDF</a>

                                    @if (Auth::user()->userable instanceof \App\Empresa)
                                    <a href="{{ route("orden_compra_edit", $orden) }}" class="btn btn-warning mx-2">Editar</a>
                                    <form method="POST" action="{{ route('orden_compra_delete', $orden)  }}">
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