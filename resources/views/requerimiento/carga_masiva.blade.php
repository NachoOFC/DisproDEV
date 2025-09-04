
@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <div class="card-header font-bold text-xl"> Importar Requerimiento</div>
            <div class="card-body">
                <form action="{{ route('requerimientos.importar')}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-2" for="centro">Centro:</label>
                        <span class="col-sm-6">
                            <filter-select-component :options='@json($centros)' label="nombre" value="id" name="centro"></filter-select-component>
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2" for="archivo">Archivo:</label>
                        <span class="col-sm-6">
                            <input type="file" class="form-control" name="archivo">
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <button class="btn bg-orange-500 hover:bg-orange-700 text-white">Importar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
