@extends('layouts.app')

@section('title', 'Crear Usuario | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Nuevo Usuario de Centros de Cultivo</h3>
            <div class="card-body">

                <form action="{{ route('user.store')}}" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre:') }}</label>
                    
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" required  autofocus>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-mail:') }}</label>
                    
                        <div class="col-md-6">
                            <input type="email" class="form-control" name="email" required  autofocus>
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
                            <select name="centro" class="form-control">
                                @foreach ($centros as $centro)
                                    <option value="{{ $centro->id }}">{{ $centro->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
