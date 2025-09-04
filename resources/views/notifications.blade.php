@extends('layouts.app')

@section('title', 'Notificaciones')

@section('home-route', $home)

@section('nav-menu')
    @include($menu)
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <div class="card-header font-bold text-xl">Notificaciones</div>
            <div class="card-body">
                <div class="row">
                    <div class="container">
                        @component('partials.notifications', ['notifications' => $notifications])
                        @endcomponent
                    </div>
                </div>
                <div class="card">
                    <div class="card-header font-bold">Notificaciones Antiguas</div>
                    <div class="card-body">
                        <form action="{{ route('SearchNotification')}}" method="POST">
                            @csrf

                            <div class="form-row">
                                <label class="col-md col-form-label" for="año">Seleccione el año:</label>
                                <div class="col-md">
                                    <select class="form-control" name="year" id="year">
                                        @for ($i = date("Y") - 20; $i < date("Y") + 20; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <label class="col-md col-form-label" for="">Seleccione el mes:</label>
                                <div class="col-md">
                                    <select class="form-control" name="mes">
                                        @for ($i = 1; $i < 13; $i++)
                                            <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md"><button class="btn btn-primary">Buscar</button></div>
                            </div>
                        </form>
                        @if (isset($oldNotifications))
                            @component('partials.notifications', ['notifications' => $oldNotifications])
                            @endcomponent
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
