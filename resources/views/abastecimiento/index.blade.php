@extends('layouts.app')

@section('title', 'Lista de Puntos de Abastecimiento | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado()}}: Lista de Puntos de Abastecimiento</h3>
            <div class="card-body">
                <div class="container mt-2">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Comuna</th>
                                    <th scope="col">Ciudad</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($abastecimientos as $abastecimiento)
                                    <tr>
                                        <th scope="row">{{ $loop->index }}</th>
                                        <td>{{ $abastecimiento->nombre }}</td>
                                        <td>{{ $abastecimiento->comuna }}</td>
                                        <td>{{ $abastecimiento->ciudad }}</td>
                                        <td>
                                            <a class="btn btn-primary" href="{{route('abastecimientos.edit', $abastecimiento)}}"><i class="fas fa-edit"></i></a>
                                            <delete-btn-component action="{{ route('abastecimientos.destroy', $abastecimiento) }}"></delete-btn-component>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
