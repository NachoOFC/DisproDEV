    @extends('layouts.app')

    @section('title', 'Compass SIGER')

    @section('home-route', route('compass.home'))

      @section('nav-menu')
        @include('compass.menu')
      @endsection
    @section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <v-card class="">
                <v-card-title>Crear Proveedor</v-card-title>
                <form class="px-3" method="POST" action="{{ route('proveedores.store') }}">
                    @csrf

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="razon_social">Razon Social:</label>

                            <input type="text" name="razon_social" class="form-control @error('razon_social') is-invalid @enderror">
                            @error('razon_social')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="giro">Giro:</label>

                            <input type="text" name="giro" class="form-control @error('giro') is-invalid @enderror">
                            @error('giro')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="rut">Rut:</label>

                            <input type="text" name="rut" class="form-control @error('rut') is-invalid @enderror">
                            @error('rut')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="comuna">Comuna:</label>

                            <input type="text" name="comuna" class="form-control @error('comuna') is-invalid @enderror">
                            @error('comuna')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="direccion">Direccion:</label>

                            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror">
                            @error('direccion')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="telefono">Telefono:</label>

                            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror">
                            @error('telefono')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="correo">Correo:</label>

                            <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror">
                            @error('correo')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row mb-5">
                        <div class="col-md-4">
                            <button type="submit" name="continue" value="0" class="btn btn-primary">Guardar y Salir</button>
                        </div>
                        <div class="col-md-4 offset-md-4">
                            <button type="submit" name="continue" value="1" class="btn btn-primary">Guardar y Continuar</button>
                        </div>
                    </div>
                </form>
            </v-card>
        </div>
        @endsection
