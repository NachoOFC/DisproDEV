@extends('layouts.app')

@section('title', 'Editar Punto de Abastecimiento | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Editar Punto de Abastecimiento</h3>
            <div class="card-body">
                <form action="{{route('abastecimientos.update', $abastecimiento)}}" method="POST" accept-charset="utf-8">
                    @csrf
                    @method('PUT')

                    <div class="form-group row">
                        <label class="col-sm-2" for="nombre">Nombre:</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="nombre" value="{{$abastecimiento->nombre ?? ''}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="comuna">Comuna:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="comuna">
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="ciudad">Ciudad:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="ciudad">
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
