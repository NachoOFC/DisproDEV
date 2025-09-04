@extends('layouts.app')

@section('title', 'Lista de Bodegueros | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Lista de Bodegueros</h3>
            <div class="card-body">
                <div class="container mt-2">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">RUT</th>
                                    <th scope="col">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bodegueros as $bodeguero)
                                    <tr>
                                        <th scope="row">{{ $loop->index + 1 }}</th>
                                        <td>{{ $bodeguero->nombre }}</td>
                                        <td>{{ $bodeguero->rut }}</td>
                                        <td>
                                            <a class="btn btn-primary" href="{{route('bodegueros.edit', $bodeguero)}}"><i class="fas fa-edit"></i></a>
                                            <delete-btn-component action="{{ route('bodegueros.destroy', $bodeguero) }}"></delete-btn-component>
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
