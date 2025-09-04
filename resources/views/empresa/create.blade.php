@extends('layouts.app')

@section('title', 'Crear Empresa | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\Empresa))
    @section('home-route', route('cliente.home'))
@elseif (Auth::user()->userable instanceof \App\CompassRole)
    @section('home-route', route('compass.home'))
@endif

@section('nav-menu')
@if ((Auth::user()->userable instanceof \App\Empresa))
    @include('cliente.menu')
@elseif (Auth::user()->userable instanceof \App\CompassRole)
    @include('compass.menu')
@endif
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Nueva Empresa</h3>
            <div class="card-body">
                <form action="{{route('empresas.store')}}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-2" for="razon_social">Razon Social:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="razon_social">
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
                        <label class="col-sm-2" for="direccion">Direccion:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="direccion">
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2" for="giro">Giro:</label>
                        <span class="col-sm-6">
                            <input class="form-control" required type="text" name="giro">
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="holding">Holding Dueño:</label>
                        <span class="col-sm-6">
                            <select name="holding" class="form-control">
                                    <option value="">Sin Holding</option>
                                @foreach ($holdings as $holding)
                                    <option value="{{$holding->id}}">{{$holding->nombre}}</option>
                                @endforeach
                            </select>
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

@section('js')
@endsection
