@extends('layouts.app')

@section('content')
<div class="container-fluid h-screen" style="background-image: url({{ asset('img/campo.jpeg')  }});">
    <div class="row justify-content-end">
        <div class="col-md-4">
            <div class="card max-w-sm rounded overflow-hidden shadow-lg mt-24">
                <div class="px-6 py-4">
                    <h5 class="text-center border-bottom font-bold text-xl mb-4">Login</h5>
                    <form method="POST" action="{{ route('login') }}" class="px-8 pt-6 pb-8 mb-4">
                        @csrf

                        @if ($errors->any())
                        <div class="alert alert-danger"><b>Error iniciando sesion</b>: E-mail o clave incorrecto</div>
                        @endif
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                                Email:
                            </label>
                            <input class="shadow-sm appearance-none border invalid:border-red-500 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="email" type="email" placeholder="Ejemplo: john.doe@example.com" required autocomplete="email" />
                        </div>

                        <div class="mb-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                                Clave:
                            </label>
                            <input class="shadow-sm appearance-none border invalid:border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="password" type="password" required autocomplete="password" placeholder="************" />
                        </div>

                        <div class="mb-3 ml-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="block text-gray-700 text-sm font-bold ml-2" for="remember">
                                {{ __('Recuerdame') }}
                            </label>
                        </div>
                        <div class="flex items-center justify-between">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Ingresar
                            </button>
                            @if (Route::has('password.request'))
                            <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('password.request') }}">
                                ¿Olvidaste tu clave?
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection