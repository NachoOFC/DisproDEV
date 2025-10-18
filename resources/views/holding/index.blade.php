@extends('layouts.app')

@section('title', 'Lista de Holdings | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Lista de Holdings</h3>
            <div class="card-body">
                <div class="container mt-2">
                <div class="table-responsive">
                    <table id="datatable" class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($holdings as $holding)
                                <tr>
                                    <th scope="row">{{ $loop->index }}</th>
                                    <td>{{ $holding->nombre }}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{route('holdings.edit', $holding)}}" title="Editar holding" aria-label="Editar holding {{ $holding->name }}"><i class="fas fa-edit" aria-hidden="true"></i><span class="sr-only">Editar</span></a>
                                        <delete-btn-component action="{{ route('holdings.destroy', $holding) }}" title="Eliminar holding" aria-label="Eliminar holding {{ $holding->name }}"></delete-btn-component>
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
