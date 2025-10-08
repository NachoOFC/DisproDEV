@if ($type === 0)
<!-- Tipo Centro -->
<div class="table-responsive">
    <table id="datatable" class="table table-striped mb-0">
    <thead>
        <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Folios</th>
            <th scope="col">Estado</th>
            @if ((Auth::user()->userable instanceof \App\Centro))
            <th scope="col">Libreria</th>
            @endif
            <th scope="col">Creado</th>
            <th scope="col">Actualizado</th>
            <th scope="col">Accion</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requerimientos as $requerimiento)
        <tr>
            <td>
                <a href="{{ route('pedidos.show', $requerimiento) }}">
                    {{ $requerimiento->nombre }}
                </a>
            </td>
            <td>
                {{ $requerimiento->folio ? $requerimiento->folio->join(", ") : "N/A"  }}
            </td>
            <td>{{ $requerimiento->estado }}</td>
            @if ((Auth::user()->userable instanceof \App\Centro))
            <td>
                <agregar-libreria-component action="{{
                                        route('libreria.editar', $requerimiento)
                                        }}" :library='@json(Auth::user()
                                        ->hasRequerimiento($requerimiento))'></agregar-libreria-component>
            </td>
            @endif
            <td>{{ date_format($requerimiento->created_at, "d-m-Y") }}</td>
            <td>{{ date_format($requerimiento->updated_at, "d-m-Y") }}</td>
            <td>
                <div class="btn-group" role="group">
                    @if (Auth::user()->userable instanceof \App\Centro)
                    @if ( $requerimiento->estado === 'DESPACHADO')
                    <a class="btn btn-outline-success" href="{{ route(
                                                 'pedidos.recepcion',
                                                 $requerimiento) }}">
                        Recepcion de Pedido
                    </a>
                    @endif
                    @if ( $requerimiento->estado === 'RECIBIDO CON OBSERVACIONES')
                    <a class="btn btn-outline-info" href="{{ route(
                                                 'rechazos.show',
                                                 $requerimiento) }}">
                        Ver Observaciones
                    </a>
                    @endif
                    @endif
                    @if ( $requerimiento->estado === 'DESPACHADO')
                    <modal-btn-component title="Orden de Pedido" :message='[
                                           { data: @json([
                                               "nombre" => $requerimiento->transporte->nombre_chofer,
                                               "rut" => $requerimiento->transporte->rut_chofer,
                                               "contacto" => $requerimiento->transporte->contacto
                                           ])
                                           , type: "Object", keys: ["nombre",
                                           "rut", "contacto"]}
                                           ]'>
                        Ver Transporte
                    </modal-btn-component>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
    </table>
</div>
@elseif ($type === 1)
<!-- Tipo Empresa -->
<div class="table-responsive">
    <table id="datatable" class="table table-striped table-sm mb-0">
        <thead>
            <tr>
                <th scope="col" rowspan="2">Nombre</th>
                <th scope="col" rowspan="2">Accion</th>
                <th class="text-center" scope="row" colspan="{{ \App\Estado::all()->count() }}">Estados</th>
            </tr>
            <tr>
                @foreach(\App\Estado::all() as $estado)
                <th scope="col">{{ $estado->nombre }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if (Auth::user()->userable instanceof \App\Empresa)
                <!-- Si el usuario es de tipo Empresa -->
                @php
                    $empresa = Auth::user()->userable; // Obtener la empresa del usuario
                    $centrosAsignados = Auth::user()->centros; // Centros asignados al usuario
                    $centros = $centrosAsignados->count() > 0 ? $centrosAsignados : $empresa->centros; // Mostrar centros asignados o todos los centros de la empresa
                @endphp

                @foreach ($centros as $centro)
                    <tr>
                        <td>{{ $centro->nombre }}</td>
                        <td>
                            <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '0']) }}">
                                Ver Detalles
                            </a>
                        </td>
                        @foreach(\App\Estado::all() as $estado)
                        <td>
                            {{ count($centro->requerimientos()->where('estado', $estado->nombre)->get()) }}
                        </td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                <!-- Si no es de tipo Empresa, mantener la lógica actual -->
                @if (Auth::user()->centro)
                    <!-- Si el usuario tiene un centro asignado, mostrar solo los requerimientos de ese centro -->
                    @php
                        $centro = Auth::user()->centro;
                        $requerimientos = $centro->requerimientos()->where("created_at", ">=", now()->subMonths(3))->get();
                    @endphp
                    <tr>
                        <td>{{ $centro->nombre }}</td>
                        <td>
                            <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '0']) }}">
                                Ver Detalles
                            </a>
                        </td>
                        @foreach(\App\Estado::all() as $estado)
                        <td>
                            {{ count($requerimientos->where('estado', $estado->nombre)) }}
                        </td>
                        @endforeach
                    </tr>
                @else
                    <!-- Si no tiene un centro asignado, mostrar todos los centros -->
                    @foreach ($centros as $centro)
                    <tr>
                        <td>{{ $centro->nombre }}</td>
                        <td>
                            <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '0']) }}">
                                Ver Detalles
                            </a>
                        </td>
                        @foreach(\App\Estado::all() as $estado)
                        <td>
                            {{ count($centro->requerimientos()->where('estado', $estado->nombre)->get()) }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                @endif
            @endif
        </tbody>
    </table>
</div>
@elseif ($type === 2)
<!-- Tipo Holding -->
<div class="table-responsive">
    <table id="datatable" class="table table-striped table-sm mb-0">
        <thead class="" style='color: #B5B2B2; background-color:#383636'>
            <tr>
                <th scope="col" rowspan="2" class="border-solid border border-white " ><div class="text-center">Nombre</div></th>
                <th scope="col" rowspan="2" class="border-solid border border-white text-center">Accion</th>
                <th class="text-center border-solid border border-white text-center" scope="row"  colspan="{{ \App\Estado::all()->count() }}">Estados</th>
            </tr>
            <tr>
                @foreach(\App\Estado::all() as $estado)
                <th scope="col" class="border-solid border border-white text-center">{{ $estado->nombre }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($empresas as $empresa)
            <tr>
                <td>{{ $empresa->razon_social }}</td>
                <td>
                    <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 0])}}">
                        Ver Todos
                    </a>
                </td>
                @foreach(\App\Estado::all() as $estado)
                <td>
                    <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => $estado->id])}}">
                        {{ count($empresa->getRequerimientoByEstado($estado->nombre)) }}
                    </a>
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif