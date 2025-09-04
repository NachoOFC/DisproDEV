    @extends('layouts.app')

    @section('title', 'Compass SIGER')

    @section('home-route', route('compass.home'))

      @section('nav-menu')
        @include('compass.menu')
      @endsection
    @section('content')
    <div class="col-md-8 mx-auto">
        <v-card>
            <v-card-title>Editar Proveedor</v-card-title>
            <form class="px-3" method="POST" action="{{ route('proveedores.update', $proveedor) }}">
                @csrf
                @method("PUT")

                <div class="form-group ">
                    <label for="razon_social">Razon Social:</label>

                    <input type="text" name="razon_social" class="form-control @error('razon_social') is-invalid @enderror" value="{{ $proveedor->razon_social }}">
                    @error('razon_social')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group ">
                    <label for="giro">Giro:</label>

                    <input type="text" name="giro" class="form-control @error('giro') is-invalid @enderror" value="{{ $proveedor->giro }}">
                    @error('giro')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group ">
                    <label for="rut">Rut:</label>

                    <input type="text" name="rut" class="form-control @error('rut') is-invalid @enderror" value="{{ $proveedor->rut }}">
                    @error('rut')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group ">
                    <label for="comuna">Comuna:</label>

                    <input type="text" name="comuna" class="form-control @error('comuna') is-invalid @enderror" value="{{ $proveedor->comuna }}">
                    @error('comuna')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group ">
                    <label for="direccion">Direccion:</label>

                    <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ $proveedor->direccion }}">
                    @error('direccion')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group ">
                    <label for="telefono">Telefono:</label>

                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ $proveedor->telefono }}">
                    @error('telefono')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group ">
                    <label for="correo">Correo:</label>

                    <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ $proveedor->correo }}">
                    @error('correo')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-row mb-5">
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </v-card>
    </div>
    @endsection
