@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5 class="text-primary text-center border-bottom">Ultimos Cambios</h5>
                    <ul class="list-group">
                      <li class="list-group-item">Modulo de Clientes <a href="{{route('cliente.home')}}">Inicio Clientes</a></li>
                      <li class="list-group-item">Modulo de Compass <a href="{{route('compass.home')}}">Inicio Compass</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
