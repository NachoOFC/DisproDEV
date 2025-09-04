@extends('layouts.app')

@section('title', 'Lista de Ordenes | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\CompassRole))
@section('home-route', route('compass.home'))
@section('nav-menu')
@include('compass.menu')
@endsection
@else
@section('home-route', route('cliente.home'))
@section('nav-menu')
@include('cliente.menu')
@endsection
@endif


@section('main')
<div class="container">

  <div class="card">
    <h3 class="card-header font-bold text-xl">Datos de Orden de Pedido</h3>
    <div class="card-body">
      <div class="row">
        <div class="card col-md mx-2">
          <div class="card-body">
            <h4 class="card-title text-xl font-bold border-bottom">Datos Empresa</h4>
            <b>Razon Social: </b>{{ $empresa->razon_social }} <br />
            <b>RUT Empresa: </b>{{ $empresa->rut }} <br />
            <b>Giro: </b>{{ $empresa->giro }} <br />
            <b>Direccion: </b>{{ $empresa->direccion }} <br />
          </div>
        </div>
        <div class="card col-md mx-2">
          <div class="card-body">
            <h4 class="card-title text-xl font-bold border-bottom">Datos Centro</h4>
            <b>Nombre: </b>{{ $centro->nombre }} <br>
            <b>Direccion: </b>{{ $centro->direccion }} <br>
            <b>Comuna: </b>{{ $centro->comuna }} <br>
            <b>Ciudad: </b>{{ $centro->ciudad }}
          </div>
        </div>
      </div>
      <div class="row mt-2">
        <div class="card col-md mx-2">
          <div class="card-body">
            <h4 class="card-title text-xl font-bold border-bottom">Datos Orden de Pedido</h4>
            <b>Nombre: </b>{{ $requerimiento->nombre }} <br />
            <b>Folio: </b>{{ $requerimiento->folio ?? $requerimiento->id }} <br />
            <b>Estado: </b>{{ $requerimiento->estado }} <br />
            <b>Total: </b>$ {{ number_format($requerimiento->getTotal(), 0) }} <br />
            <b>Bodeguero: </b>{{ $requerimiento->bodeguero->nombre ?? "S/I" }} <br />
            <b>Fecha de Creacion: </b>{{ $requerimiento->created_at }} <br />
            <b>Ultima Actualizacion: </b>{{ $requerimiento->updated_at }} <br />
          </div>
        </div>
        <div class="card col-md mx-2">
          <div class="card-body">
            <h4 class="card-title text-xl font-bold
                         border-bottom">Datos de Transporte</h4>
            <b>Nombre Transportista: </b>
            {{ $requerimiento->transporte->nombre ?? 'Sin Despachar' }} <br />
            <b>RUT Transportista: </b>
            {{ $requerimiento->transporte->rut ??
                 'Sin Despachar'}} <br />
            <b>Contacto Transportista: </b>
            {{ $requerimiento->transporte->contacto
                 ?? 'Sin Despachar' }} <br />

            @if (Auth::user()->userable instanceof \App\CompassRole && $requerimiento->guiasDespacho->count() > 0)
            <v-expansion-panels>
              <v-expansion-panel>
                <v-expansion-panel-header>Ver Guias de Despacho</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <guia-despacho-show :guias='@json($guias)'></guia-despacho-show>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
            @endif
          </div>
        </div>
      </div>
      <div class="row mt-2">
        <div class="container table-responsive">
          <table id="datatable" class="table table-sm">
            <thead>
              <tr>
                <th scope="col">SKU</th>
                <th scope="col">Detalle</th>
                <th scope="col">Precio Unitario ($)</th>
                <th scope="col">Fecha de Vencimiento</th>
                <th scope="col">Cantidad Solicitada</th>
                <th scope="col">Cantidad Despachada</th>
                <th scope="col">Subtotal ($)</th>
                <th scope="col">Observaciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($productos as $producto)
              <tr>
                <td>{{ $producto->sku }}</td>
                <td>{{ $producto->detalle }}</td>
                <td>{{ number_format($producto->pivot->precio) }}</td>
                <td>{{ $producto->pivot->fecha_vencimiento ?? 'N/A'}}</td>
                <td>{{ $producto->pivot->cantidad ?? 0 }}</td>
                <td>{{ $producto->pivot->real ?? 'Sin Despachar' }}</td>
                <td>{{ number_format($producto->pivot->precio * ($producto->pivot->real ?? $producto->pivot->cantidad)) }}</td>
                <td>{{ $producto->pivot->observacion ?? 'Sin Observaciones' }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection