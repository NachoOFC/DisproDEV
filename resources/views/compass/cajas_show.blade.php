@extends('layouts.app')

@section('title', 'Armar Cajas')

@section('home-route', route('compass.home'))

@section('nav-menu')
@include('compass.menu')
@endsection

@section('main')
<div class="container">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}" />
<a class="btn btn-secondary" style="color: #fff;"  href="{{ route('compass.pedidos.cajasIndex')}}"><i class='fas fa-arrow-alt-circle-left'></i></a>

  <div class="card">
    <h3 class="card-header font-bold text-xl">Armar Cajas</h3>
    <div class="card-body">
      <form action="{{ route('compass.pedidos.armarCaja', $requerimiento) }}" method="POST" class="container mt-2">
        @csrf

        <div class="container">
  <div class="row">
    <div class="col">
    <div class="row">
                  <div class="col text-right">Empresa:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->empresa->razon_social }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Giro:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->empresa->giro }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">RUT Empresa:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->empresa->rut }}</div>
                </div>
    </div>
    <div class="col">
    <div class="row">
                  <div class="col text-right">Centro:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->nombre }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Comuna:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->comuna }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Ciudad:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->ciudad }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Direccion:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->direccion }}</div>
                </div>
    </div>
    <div class="col">
    <div class="row">
                  <div class="col text-right">Nombre del Pedido:</div>
                  <div class="col font-bold">{{ $requerimiento->nombre }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Fecha de Creacion:</div>
                  <div class="col font-bold">{{ $requerimiento->created_at }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Bodeguero Responsable:</div>
                  <div class="col font-bold">
                    <select class="form-control form-control-sm w-50" name="bodeguero">
                      @foreach ($bodegueros as $bodeguero)
                      <option value="{{ $bodeguero->id }}">{{ $bodeguero->nombre }}</option>
                      @endforeach
                    </select>
                  </div>
    </div>
  </div>
</div>

        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-body">
                <!-- <div class="row">
                  <div class="col text-right">Empresa:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->empresa->razon_social }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Giro:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->empresa->giro }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">RUT Empresa:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->empresa->rut }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Centro:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->nombre }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Comuna:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->comuna }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Ciudad:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->ciudad }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Direccion:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->direccion }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Nombre del Pedido:</div>
                  <div class="col font-bold">{{ $requerimiento->nombre }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Fecha de Creacion:</div>
                  <div class="col font-bold">{{ $requerimiento->created_at }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Bodeguero Responsable:</div>
                  <div class="col font-bold">
                    <select class="form-control form-control-sm w-50" name="bodeguero">
                      @foreach ($bodegueros as $bodeguero)
                      <option value="{{ $bodeguero->id }}">{{ $bodeguero->nombre }}</option>
                      @endforeach
                    </select>
                  </div>
                </div> -->
                <div class="row mt-4">
                  <div class="col-md-3">
                    <agregar-producto-caja :productos='@json($productos)' action="{{ route('requerimiento.productos.agregar', $requerimiento) }}"></agregar-producto-caja>
                  </div>
                  <div class="col-md-4 offset-md-1">
                    <button type="submit" name="save" value="1" class="btn btn-info">Guardar</button>
                    <button type="submit" name="save" value="0" onclick="return confirmacion()" class="btn btn-success">Armar</button>
                    <button type="submit" name="delete" value="1" class="btn btn-danger">Eliminar Seleccionados</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
          <div class="tablee">
        <table id="datatable-requerimiento" class="table table-bordered">
          <thead>
            <tr>
              <th scope="col" class="letras">Selec</th>
              <th scope="col" class="letras">SKU</th>
              <th scope="col" class="letras">Familia</th>
              <th scope="col" class="letras">Detalle</th>
              <th scope="col" class="letras">Cantidad Solicitada</th>
              <th scope="col"class="letras">Cantidad a despachar</th>
              <th scope="col"class="letras">Fecha de Vencimiento</th>
              <th scope="col"class="letras">Observaciones</th>
              <th scope="col"class="letras">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($requerimiento->productos as $producto)
           
            <tr>
              <td><input type="checkbox" name="remove[]" value="{{$producto->id}}" /></td>
              <input type="hidden" value="{{$producto}}" name="productos[]" />
              <td class="letras">{{$producto->sku}}</td>
              <td class="letras">{{$producto->familia}}</td>
              <td class="letras">{{$producto->detalle}}</td>
              <td class="letras">{{$producto->pivot->cantidad}}</td>

              <td>
              @if ( $producto->pivot->real != 0.00 )
                <input class="form-control form-control-sm item1 border border-primary" name="real[]" id="real" value="{{$producto->pivot->real ?? $producto->pivot->cantidad}}" type="text">
                @else
                <input class="form-control form-control-sm item1 " name="real[]" id="real" value="{{$producto->pivot->real ?? $producto->pivot->cantidad}}" type="text">
                @endif
              </td>
              
                <td><input type="date" class="form-control form-control-sm" name="vencimientos[]" min="{{ \Carbon\Carbon::now()->addDays(10) }}" value="{{$producto->pivot->fecha_vencimiento}}"></td>
              <td><input class="form-control form-control-sm" type="text" name="observaciones[]" value="{{$producto->pivot->observacion}}"></td>
              <td>
                <div class="btn-group">
                  <a class="btn btn-primary"style="height: 30px !important; " href="{{ route('cajas.cambiar', [$requerimiento, $producto]) }}">
                    <i class="fas fa-undo" style="color: #fff;" ></i>
                  </a>
                  <a class="btn btn-danger"style="height: 30px !important; " href="{{ route('cajas.borrar', [$requerimiento, $producto]) }}">
                   <i style="color: #fff;" >&times</i>
                  </a>
                </div>

              </td>
            </tr>
            
            @endforeach
          </tbody>
        </table>
        </div>
      </form>
    </div>
  </div>
  @endsection

  @section('js')
<script>
  var Fn = {
  // Valida el rut con su cadena completa "XXXXXXXX-X"
  validaRut : function (rutCompleto) {
    rutCompleto = rutCompleto.replace("‐","-");
    if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( rutCompleto ))
      return false;
    var tmp   = rutCompleto.split('-');
    var digv  = tmp[1]; 
    var rut   = tmp[0];
    if ( digv == 'K' ) digv = 'k' ;
    
    return (Fn.dv(rut) == digv );
  },
  dv : function(T){
    var M=0,S=1;
    for(;T;T=Math.floor(T/10))
      S=(S+T%10*(9-M++%6))%11;
    return S?S-1:'k';
  }
}
// document.addEventListener("DOMContentLoaded", function() {
//   //document.getElementById("formulario").addEventListener('submit', validarFormulario); 

// var dato= document.getElementsByClassName("item1");

//  Array.from(dato).map(item2 => {
//   if(item2.value =! '0.00'){
//   //console.log(dato)

//   return item2.className = 'form-control form-control-sm item1 border border-primary'
//   //$(item2).find("#real").val(item2.value)
// }else{
//   return item2.className = 'form-control form-control-sm item1'
// }
 
  
// });


// });

function validarFormulario(evento) {
  
  var usuario = document.getElementById('rut_chofer').value;
  if (Fn.validaRut( $("#rut_chofer").val() ) &&  Fn.validaRut( $("#rut_empresa").val() )){

    alert('La clave es válida');
  } else {
    alert('La clave no es válida');
    event.preventDefault()
          event.stopPropagation()
          alert('La clave no es válida');
          window.history.back();
    
  }
  // var clave = document.getElementById('clave').value;
  // if (clave.length < 6) {
  //   alert('La clave no es válida');
  //   return;
  // }
  this.submit();
}


$("#btnvalida").click(function(){
  if (Fn.validaRut( $("#txt_rut").val() )){
    $("#msgerror").html("El rut ingresado es válido ");
  } else {
    $("#msgerror").html("El Rut no es válido ");
  }
});

function confirmacion(){
  var respuesta = confirm("Confirma el Armado de Caja?");
  if(respuesta== true){
    return true;
  }else{
    return false;
  }
}

  </script>
@stop