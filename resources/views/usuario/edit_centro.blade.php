@extends('layouts.app')

@section('title', 'Editar Usuario | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Editar Usuario {{ $user->name }}</h3>

                    <div class="card-body">
                        <form method="POST" action="{{ route('user.updateCentro', $user) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre:') }}</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" required  autofocus value="{{ $user->name }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-mail:') }}</label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" required  autofocus value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña:') }}</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password" required  autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="centro" class="col-md-4 col-form-label text-md-right">{{ __('Seleccione el Centro de Cultivo:') }}</label>

                                <div class="col-md-6">
                                    <select name="centro" class="form-control" value="{{ $user->userable->id }}">
                                        @foreach ($centros as $centro)
                                            <option
                                                @if ($user->userable->id === $centro->id)
                                                    {{ __("selected")}}
                                                @endif
                                                value="{{ $centro->id }}">{{ $centro->nombre }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
