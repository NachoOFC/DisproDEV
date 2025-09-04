@extends('layouts.app')

@section('title', 'Crear Bodeguero | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Nuevo Bodeguero</h3>
            <div class="card-body">
                <form action="{{route('bodegueros.store')}}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-2" for="nombre">Nombre:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="nombre">
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="rut">RUT:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="rut">
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
