@extends('layouts.app')

@section('title', 'Editar Holding | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Editar Holding</h3>
            <div class="card-body">
                <form action="{{route('holdings.update', $holding)}}" method="POST" accept-charset="utf-8">
                    @csrf
                    @method('PUT')

                    <div class="form-group row">
                        <label class="col-sm-2" for="nombre">Nombre:</label>
                        <div class="col-sm-6">
                            <input type="text" value="{{$holding->nombre}}" name="nombre" class="form-control"/>
                        </div>
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
