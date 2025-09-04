@extends('layouts.app')

@section('title', 'Editar Presupuesto | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Editar Presupuesto - {{ $centro->nombre }} ({{ $year }})</h3>
            <div class="card-body">
                <form action="{{ route('presupuesto.actualizar', [$centro->id, $year]) }}" method="POST">
                    @csrf

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($montosPorMes as $mes => $monto)
                                <tr>
                                    <td>{{ DateTime::createFromFormat('!m', $mes)->format('F') }}</td>
                                    <td>
                                        <input type="number" name="montos[{{ $mes }}]" value="{{ $monto }}" class="form-control" min="0" step="0.01" required>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection