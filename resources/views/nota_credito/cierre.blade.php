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
                <header class="d-flex flex-row justify-content-end my-2">
                    <form method="POST" action="{{ route("nota_credito_export", $cierre)  }}" class="mr-3">
                        @csrf

                        <button class="btn btn-info">Exportar a Excel</button>
                    </form>
                    @if ((Auth::user()->userable instanceof \App\CompassRole))
                    <a class="btn btn-outline-success" href="{{ route("nota_credito_create", $cierre) }}">Crear NC</a>
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
                        @foreach($notas as $nota)
                        <tr>
                            <td>{{ $nota->fecha  }}</td>
                            <td>{{ $nota->folio  }}</td>
                            <td>$ {{ number_format($nota->monto, 0)  }}</td>
                            <td>
                                <div class="d-inline-flex justify-content-around">
                                    <a href="{{ asset($nota->documento) }}" target="_BLANK">Ver PDF</a>
                                    @if ((Auth::user()->userable instanceof \App\CompassRole))
                                    <a href="{{ route("nota_credito_edit", $nota) }}" class="btn btn-warning mx-2">Editar</a>
                                    <form method="POST" action="{{ route('nota_credito_delete', $nota)  }}" class="deleteForm">
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