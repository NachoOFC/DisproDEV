@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <div class="card-header font-bold text-xl">
                {{ Auth::user()->getNombreRelacionado() }}: Cargar Folios
            </div>
            <div class="card-body">
                <div class="form-row">
                    <form class="col" action="{{ route('folios.store') }}" method="POST" accept-charset="utf-8">
                        @csrf

                        <div class="form-group row">
                            <label class="col-md-2" for="desde">Desde:</label>
                            <div class="col-md-6">
                                <input type="text" name="desde" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2" for="hasta">Hasta:</label>
                            <div class="col-md-6">
                                <input type="text" name="hasta" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-10">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </form>
                    <div class="col">
                        <strong>Folios Disponibles:</strong>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Desde</th>
                                    <th scope="col">Hasta</th>
                                    <th scope="col">Ultimo Folio</th>
                                    <th scope="col">Disponibles</th>
                                    <th scope="col">Fecha de Creacion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if (isset($folio))
                                        <td>{{ $folio->desde }}</td>
                                        <td>{{ $hasta = $folio->hasta }}</td>
                                        <td>{{ $folio->ultimo }}</td>
                                        <td>{{ $hasta - $folio->ultimo }}</td>
                                        <td>{{ $folio->created_at }}</td>
                                    @else
                                        <div class="alert alert-dark">No hay folios cargados</div>
                                    @endif
                                </tr>
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
                html: '{!! $msg['meta']['msg'] !!}',
                icon: 'success'
            }))();

        </script>
    @endsection
@endif
