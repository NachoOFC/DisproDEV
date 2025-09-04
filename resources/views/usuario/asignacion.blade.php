@extends('layouts.app')

@section('title', 'Asignar Usuarios | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Asignar Usuarios</h3>
            <div class="card-body">
                <form action="{{ route('usuario.asignacion') }}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="form-group form-row">
                        <label class="col-md-4 col-form-label text-md-right" for="name">Nombre:</label>
                        <div class="col-md-6">
                            <input type="text" readonly class="form-control-plaintext" value="{{$user->name}}">
                        </div>
                    </div>

                    <div class="form-group form-row">
                        <label class="col-md-4 col-form-label text-md-right" for="name">E-mail:</label>
                        <div class="col-md-6">
                            <input type="text" readonly class="form-control-plaintext" value="{{$user->email}}">
                        </div>
                    </div>

                    <div class="form-group form-row">
                        <label class="col-md-4 col-form-label text-md-right" for="asignacion">Asignación:</label>
                        <div class="col-md-6">
                            <select class="form-control" name="asignacion">
                                @foreach ($asignacion as $obj)
                                    <option value="{{$obj->id}}">{{$obj->nombre ?? $obj->razon_social ?? $obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-row">
                        <label class="col-md-4 col-form-label text-md-right" for="centro">Centros:</label>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            <!--<input type="checkbox" id="select-all">-->
                                        </th>
                                        <th>Nombre del Centro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($centro as $obj)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="centro[]" value="{{ $obj->id }}">
                                            </td>
                                            <td>{{ $obj->nombre ?? $obj->razon_social ?? $obj->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <input type="hidden" value="{{$user->id}}" name="userId"/>
                    <input type="hidden" value="{{get_class($asignacion[0])}}" name="asignacionType"/>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Asignar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="centro[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endsection
