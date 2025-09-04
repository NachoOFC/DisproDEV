@extends('layouts.app')

@section('title', 'Seleccionar Centro | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Seleccionar Centro</h3>
            <div class="card-body">
                <form action="{{ route('presupuesto.editar') }}" method="GET">
                    @csrf

                    <div class="form-group">
                        <label for="centro">Centro:</label>
                        <select class="form-control" id="centro" name="centro" required>
                            <option value="">Seleccione un centro</option>
                            @foreach ($centros as $centro)
                                <option value="{{ $centro->id }}">{{ $centro->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="year">Año:</label>
                        <select class="form-control" id="year" name="year" required>
                            @for ($i = date('Y') - 5; $i <= date('Y') + 5; $i++)
                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection