@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

    @section('nav-menu')
        @include('compass.menu')
    @endsection

    @section('main')
        <div class="container">
            <div class="card">
                <div class="card-header font-bold text-xl">Servicio al Cliente</div>
                <div class="card-body">
                    <form
                        method="POST"
                        action="{{ route("servicio-cliente.submit") }}"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <div class="form-group form-row">
                            <div class="col-md-2">
                                <label for="minuta">Minuta:</label>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" type="file" name="minuta" />
                                @isset($status)
                                    @if($status["minuta"])
                                        <div class="alert alert-success">
                                            Cargado exitosamente
                                        </div>
                                    @else
                                        <div class="alert alet-danger">
                                            No se pudo cargar, intente nuevamente en unos minutos.
                                        </div>
                                    @endif
                                @endisset
                            </div>
                        </div>

                        <div class="form-group form-row">
                            <div class="col-md-2">
                                <label for="saludable">Consejos Vida Saludable:</label>
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" type="file" name="saludable" />
                                @isset($status)
                                    @if($status["saludable"])
                                        <div class="alert alert-success">
                                            Cargado exitosamente
                                        </div>
                                    @else
                                        <div class="alert alet-danger">
                                            No se pudo cargar, intente nuevamente en unos minutos.
                                        </div>
                                    @endif
                                @endisset
                            </div>
                        </div>

                        <button class="btn bg-orange-500 hover:bg-orange-700 text-white">Cargar</button>
                    </form>
                </div>
            </div>
        </div>
    @endsection
