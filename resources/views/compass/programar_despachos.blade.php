@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
@include('compass.menu')

@endsection

@section('main')
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}" />
<div class="container">
  <div class="card">
    <div class="card-header font-bold text-xl">
          Programar Despachos</div>
    <div class="card-body">
      @if ($requerimientos->count() > 0)

      <!-- <header>
  <h1>Valida RUT</h1>
  <p>Algoritmo de validaciónd del rut Chileno</p>
</header> -->

<a class="btn btn-secondary" style="color: #fff;"  href="{{ route('compass.home')}}">Pagina anterior</a>

<form class="container" action="{{ route('compass.pedidos.programarDespachos') }}" method="POST">
        @csrf
        <div class="row">
          
          <div class="col-md-6">
            <div class="form-group form-row">
              <label class="col-md col-form-label text-right" for="nombre">Nombre Transportista:</label>
              <span class="col-md">
                <input class="form-control
                                       @error('nombre_chofer') is-invalid @enderror" name="nombre_chofer" type="text">
                @error("nombre_chofer")
                <div class="alert alert-danger">
                  {{ $message }}
                </div>
                @enderror
              </span>
            </div>
            <div class="form-group form-row">
              <label class="col-md col-form-label text-right" for="rut">RUT Transportista:</label>
              <span class="col-md">
                <input class="form-control" name="rut_chofer" pattern="\d{7,8}-[0-9kK]{1}" type="text" id="rut_chofer">
                @error("rut_chofer")
                <div class="alert alert-danger">
                  {{ $message }}
                </div>
                @enderror
                <small class="text-muted">Sin puntos. Ej: 46656975-4</small>
              </span>
            </div>
            <div class="form-group form-row">
              <label class="col-md col-form-label text-right" for="contacto">Contacto Transportista:</label>
              <span class="col-md">
                <input class="form-control
                                       @error('contacto')
                                       is-invalid
                                       @enderror" name="contacto" type="text">
                @error("contacto")
                <div class="alert alert-danger">
                  {{ $message }}
                </div>
                @enderror
              </span>
            </div>
            <div class="form-group form-row">
              <label class="col-md col-form-label text-right" for="contacto">Rut Empresa:</label>
              <span class="col-md">
                <input class="form-control
                                     @error('rut_empresa')
                                     is-invalid
                                     @enderror" pattern="\d{7,8}-[0-9kK]{1}" name="rut_empresa" type="text" id="rut_empresa">
                <small class="text-muted">Sin puntos. Ej: 46656975-4</small>
                @error("rut_empresa")
                <div class="alert alert-danger">
                  {{ $message }}
                </div>
                @enderror
              </span>
            </div>
           
            
            
           
            <div class="form-group form-row">
              <div class="col-md-4 mx-auto" id="registrar"><button class="btn btn-ve" type="submit">Programar Despacho</button></div>
            </div>
          </div>
          <div class="col">
            <div class="form-group form-row">
              <label class="col-md col-form-label text-right" for="contacto">Patente:</label>
              <span class="col-md">
                <input class="form-control
                                     @error('patente')
                                     is-invalid
                                     @enderror" name="patente" type="text">
                @error("patente")
                <div class="alert alert-danger">
                  {{ $message }}
                </div>
                @enderror
              </span>
            </div>
            <div class="form-group form-row">
              <label class="col-md col-form-label text-right" for="fecha">Fecha de Despacho:</label>
              <span class="col-md">
                <input class="form-control
                                       @error('fecha_programada')
                                       is-invalid
                                       @enderror" name="fecha_programada" type="date">
                @error("fecha_programada")
                <div class="alert alert-danger">
                  {{ $message }}
                </div>
                @enderror
              </span>
            </div>
            <div class="form-group form-row">
              <label class="col-md col-form-label text-right" for="destino">Punto de Abastecimiento</label>
              <span class="col-md">
                <select class="form-control
                                         @error('abastecimiento_id')
                                         is-invalid
                                         @enderror" name="abastecimiento_id">
                  @foreach ($abastecimientos as $abastecimiento)
                  <option value="{{ $abastecimiento->id }}">{{ $abastecimiento->nombre }}</option>
                  @endforeach
                </select>
                @error("abastecimiento_id")
                <div class="alert alert-danger">
                  {{ $message }}
                </div>
                @enderror
              </span>
            </div>
            </div>

          
        </div>
        <div class="row">
          <div class="col container table-sm">
            @error("requerimientos")
            <div class="alert alert-danger">
              {{ $message }}
            </div>
            @enderror
            <table class="table table-sm table-striped table-bordered" id="datatable">
              <thead>
                <tr>
                  <th scope="col" >Seleccionar</th>
                  <th scope="col">#</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Centro</th>
                  <th scope="col">Empresa</th>
                  <th scope="col">Fecha de Solicitud</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($requerimientos as $requerimiento)
                <tr>
                  <td>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="{{ $requerimiento->id }}" name="requerimientos[]">
                      <label class="form-check-label">
                        Incluir
                      </label>
                    </div>
                  </td>
                  <th scope="row">{{ $requerimiento->id }}</th>
                  <td>{{ $requerimiento->nombre }}</td>
                  <td>{{ $requerimiento->centro->nombre }}</td>
                  <td>{{ $requerimiento->centro->empresa->razon_social }}</td>
                  <td>{{ $requerimiento->created_at }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </form>
      @else
      <div class="alert alert-dark">Sin <a class="alert-link" href="{{ route('compass.pedidos.cajasIndex')}}">Cajas</a> disponibles para despachar</div>
      @endif
    </div>
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
$(document).ready(function(){
  document.getElementById("rut_chofer").addEventListener('keyup', validarFormulario); 
  document.getElementById("rut_empresa").addEventListener('keyup', validarFormulario1); 
});

function validarFormulario(evento) {
  
  //var usuario = document.getElementById('rut_chofer').value;
  document.getElementById("registrar").innerHTML = ''
  let condicion =document.getElementById("registrar")
  let elemento = document.getElementById("rut_chofer");

  if (Fn.validaRut( $("#rut_chofer").val() ) ){
    //document.getElementById("rut1").innerHTML = ''
    //elemento.classList.remove('is-invalid');
     condicion.innerHTML +=`
     <button class="btn btn-ve" type="submit"  >Programar Despacho</button>
      `
    alert('El rut es válido');
  } else {
   // alert('La clave no es válida');
   
    //elemento.className += " is-invalid";
    let elemento1 = document.getElementById("rut1");
    // elemento1.innerHTML +=` <div class="alert alert-danger">
    //               Rut invalido
    //             </div>`
    
     condicion.innerHTML +=`
     <button class="btn btn-ve" type="submit" disabled >Programar Despacho</button>
     `
    
         // alert('La clave no es válida');
          //window.history.back();
    
  }

  





  // var clave = document.getElementById('clave').value;
  // if (clave.length < 6) {
  //   alert('La clave no es válida');
  //   return;
  // }
  //this.submit();
}

function validarFormulario1(evento) {
  
  //var usuario = document.getElementById('rut_chofer').value;
  document.getElementById("registrar").innerHTML = ''
  let condicion1 =document.getElementById("registrar")
  let elemento = document.getElementById("rut_empresa");

  if (Fn.validaRut( $("#rut_empresa").val() ) ){
    //document.getElementById("rut1").innerHTML = ''
    //elemento.classList.remove('is-invalid');
     condicion1.innerHTML +=`
      <div class="col-md-4 mx-auto"><button class="btn btn-ve" type="submit"  >Programar Despacho</button></div>
      `
    alert('el rut es válido');
  } else {
   // alert('La clave no es válida');
   
    //elemento.className += " is-invalid";
    let elemento1 = document.getElementById("rut1");
    // elemento1.innerHTML +=` <div class="alert alert-danger">
    //               Rut invalido
    //             </div>`
    
     condicion1.innerHTML +=`
     <div class="col-md-4 mx-auto"><button class="btn btn-ve" type="submit" disabled >Programar Despacho</button></div>
     `
    
         // alert('La clave no es válida');
          //window.history.back();
    
  }

  





  // var clave = document.getElementById('clave').value;
  // if (clave.length < 6) {
  //   alert('La clave no es válida');
  //   return;
  // }
  //this.submit();
}






  </script>
@stop


