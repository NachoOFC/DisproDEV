@extends('layouts.app')

@section('title', 'Validar Ordenes de Pedido')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            @php
                $user = Auth::user();
                $centros = $user->centros; // Obtener los centros asignados al usuario

                if ($centros->count() > 0) {
                    // Si tiene centros asignados, obtener los requerimientos de esos centros
                    $requerimientos = collect([]);
                    foreach ($centros as $centro) {
                        $requerimientos = $requerimientos->merge(
                            $centro->requerimientos()
                                ->where('estado', 'ESPERANDO VALIDACION')
                                ->get()
                        );
                    }
                } else {
                    // Si no tiene centros asignados, obtener todos los requerimientos de la empresa
                    $empresa = $user->userable; // Asumiendo que el usuario es de tipo Empresa
                    $requerimientos = $empresa->requerimientos()
                        ->where('estado', 'ESPERANDO VALIDACION')
                        ->get();
                }
            @endphp

            @if ($requerimientos->count() > 0)
                <div class="card-header">
                    <div class="d-flex flex-row">
                        <div class="btn-group" role="group" aria-label="Validar Pedidos">
                            <div class="mr-4">
                                <validar-pedidos-component action="{{ route('pedidos.aceptarTodos') }}" :todos="true">Aceptar Todos</validar-pedidos-component>
                            </div>
                            <div>
                                <validar-pedidos-component action="{{ route('pedidos.rechazarTodos') }}" :todos="true" :validacion="false">Rechazar Todos</validar-pedidos-component>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card-body">
                @if ($requerimientos->count() > 0)
                    <tabs>
                        @if ($centros->count() > 0)
                            <!-- Si tiene centros asignados, mostrar pestañas por centro -->
                            @foreach ($centros as $centro)
                                @php
                                    $requerimientosCentro = $centro->requerimientos()
                                        ->where('estado', 'ESPERANDO VALIDACION')
                                        ->get();
                                @endphp

                                @if ($requerimientosCentro->count() > 0)
                                    <tab title="{{ $centro->nombre }}">
                                        <table id="datatable" class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Estado</th>
                                                    <th scope="col">Ver</th>
                                                    <th scope="col">Editar</th>
                                                    <th scope="col">Aceptar</th>
                                                    <th scope="col">Rechazar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($requerimientosCentro as $requerimiento)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('pedidos.show', $requerimiento) }}">{{ $requerimiento->nombre }}</a>
                                                        </td>
                                                        <td>{{ $requerimiento->estado }}</td>
                                                        <td class="d-flex flex-row">
                                                            <modal-btn-component
                                                                title="Orden de Pedido"
                                                                :message='[
                                                                    { data: @json($requerimiento->productos), type: "Array", keys: ["sku", "detalle", "pivot"], pivot: "cantidad"},
                                                                    { data: @json(["total" => "$" . number_format($requerimiento->getTotal()) ]), type: "Object", keys: ["total"]}
                                                                ]'>
                                                                Ver Orden de Pedido
                                                            </modal-btn-component>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-info" href="{{ route('requerimientos.edit', $requerimiento) }}">Editar</a>
                                                        </td>
                                                        <td>
                                                            <validar-pedidos-component
                                                                action="{{ route('pedidos.aceptar') }}"
                                                                :todos="false"
                                                                :pedido='{{ $requerimiento->id }}'>
                                                                Aceptar
                                                            </validar-pedidos-component>
                                                        </td>
                                                        <td>
                                                            <validar-pedidos-component
                                                                action="{{ route('pedidos.rechazar') }}"
                                                                :todos="false"
                                                                :validacion="false"
                                                                :pedido='{{ $requerimiento->id }}'>
                                                                Rechazar
                                                            </validar-pedidos-component>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </tab>
                                @endif
                            @endforeach
                        @else
                            <!-- Si no tiene centros asignados, mostrar todos los requerimientos en una sola pestaña -->
                            <tab title="Todos los Requerimientos">
                                <table id="datatable" class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Ver</th>
                                            <th scope="col">Editar</th>
                                            <th scope="col">Aceptar</th>
                                            <th scope="col">Rechazar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requerimientos as $requerimiento)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('pedidos.show', $requerimiento) }}">{{ $requerimiento->nombre }}</a>
                                                </td>
                                                <td>{{ $requerimiento->estado }}</td>
                                                <td class="d-flex flex-row">
                                                    <modal-btn-component
                                                        title="Orden de Pedido"
                                                        :message='[
                                                            { data: @json($requerimiento->productos), type: "Array", keys: ["sku", "detalle", "pivot"], pivot: "cantidad"},
                                                            { data: @json(["total" => "$" . number_format($requerimiento->getTotal()) ]), type: "Object", keys: ["total"]}
                                                        ]'>
                                                        Ver Orden de Pedido
                                                    </modal-btn-component>
                                                </td>
                                                <td>
                                                    <a class="btn btn-info" href="{{ route('requerimientos.edit', $requerimiento) }}">Editar</a>
                                                </td>
                                                <td>
                                                    <validar-pedidos-component
                                                        action="{{ route('pedidos.aceptar') }}"
                                                        :todos="false"
                                                        :pedido='{{ $requerimiento->id }}'>
                                                        Aceptar
                                                    </validar-pedidos-component>
                                                </td>
                                                <td>
                                                    <validar-pedidos-component
                                                        action="{{ route('pedidos.rechazar') }}"
                                                        :todos="false"
                                                        :validacion="false"
                                                        :pedido='{{ $requerimiento->id }}'>
                                                        Rechazar
                                                    </validar-pedidos-component>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </tab>
                        @endif
                    </tabs>
                @else
                    <div class="alert alert-dark">
                        No hay órdenes de pedido pendientes de validación.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    @if (\Session::has('msg'))
        @php
            $msg = \Session::get('msg')
        @endphp
        <script charset="utf-8">
            (Swal.fire({
                icon: "success",
                title: "{{$msg['title']}}",
                text: "{{$msg['text']}}"
            }))()
        </script>
    @endif
@endsection