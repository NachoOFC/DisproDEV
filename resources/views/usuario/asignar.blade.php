@extends('layouts.app')

@section('title', 'Lista de Usuarios | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
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
                            <th scope="col">Holding</th>
                            <th scope="col">Empresa</th>
                            <th scope="col">Centro</th>
                            <th scope="col">Compass</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->index }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><a class="btn btn-primary" href="{{ route('usuario.asignar', ['userId' => $user, 'tipo' => 'h']) }}">Asignar a Holding</a></td>
                                <td><a class="btn btn-primary" href="{{ route('usuario.asignar', ['userId' => $user, 'tipo' => 'e']) }}">Asignar a Empresa</a></td>
                                <td><a class="btn btn-primary" href="{{ route('usuario.asignar', ['userId' => $user, 'tipo' => 'c']) }}">Asignar a Centro</a></td>
                                <td><a class="btn btn-primary" href="{{ route('usuario.asignar', ['userId' => $user, 'tipo' => 'r']) }}">Asignar a Compass</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@if (\Session::has('msg'))
    @php
        $msg = \Session::get('msg');
    @endphp
    @section('js')
        <script charset="utf-8">
            (Swal.fire({
                title: '{{$msg['meta']['title']}}',
                html: '{!! $msg['meta']['message'] !!}',
                icon: 'success'
            }))();

        </script>
    @endsection
@endif
