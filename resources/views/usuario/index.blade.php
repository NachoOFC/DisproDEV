@extends('layouts.app')

@section('title', 'Lista de Usuarios | Mline SIGER')

@if (Auth::user()->userable instanceof \App\CompassRole)
    @section('home-route', route('compass.home'))
@else
    @section('home-route', route('cliente.home'))
@endif

@section('nav-menu')
@if (Auth::user()->userable instanceof \App\CompassRole)
    @include('compass.menu')
@else
    @include('cliente.menu')
@endif
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Lista de Usuarios</h3>
            <div class="card-body">
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Tipo de Usuario</th>
                            <th scope="col">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->index }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->userable_id !== null)
                                    @if (substr($user->userable_type, 4) == 'CompassRole')
                                      Admin
                                    @else
                                      {{substr($user->userable_type, 4)}}
                                    @endif
                                       
                                    @else
                                       Sin Asignar
                                    @endif
                                    
                                    
                                   
                                <td>
                                    <a class="btn btn-primary" href="{{route('usuarios.edit', $user)}}"><i class="fas fa-edit"></i></a>
                                    <delete-btn-component action="{{ route('usuarios.destroy', $user) }}"></delete-btn-component>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
