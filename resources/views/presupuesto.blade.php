@extends('layouts.app')

@section('title', 'Administrar Presupuesto | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
  @include('cliente.menu')
@endsection

@section('main')
  <div class="card">
    <header class="card-header">
      <h4 class="card-title h1">Asignación de Presupuesto</h4>  
    </header>
    <form class="card-body" action="{{ route('presupuesto.store') }}" method="POST">
      @csrf
      <div class="row">
        <div class="form-group col-md-2">
          <h5 class="card-title h5">Escoger Año</h5>
          <select class="form-control">
            <option>Elegir..</option>
            <option>2019</option>
            <option>2020</option>
            <option>2021</option>
            <option>2022</option>
            <option>2023</option>
          </select>
        </div>
      </div>  
      <div class="form-group col-md-12">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Mes</th>
              @foreach ($centros as $centro)
                <th>{{$centro->nombre}}</th>
              @endforeach
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
              <tr>
                <td>Enero</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
              <tr>
                <td>Febrero</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
              <tr>
                <td>Marzo</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
              <tr>
                <td>Abril</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
              <tr>
                <td>Mayo</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
              <tr>
                <td>Junio</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
              <tr>
                <td>Julio</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
              <tr>
                <td>Agosto</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
              <tr>
                <td>Septiembre</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
              <tr>
                <td>Octubre</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
              <tr>
                <td>Noviembre</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
              <tr>
                <td>Diciembre</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr>
          </tbody>
          <tfoot>
              <tr>
                <td>Total</td>
                @foreach ($centros as $centro)
                <td><input class="form-control col-md-10" type="text" name="" /></td>
                @endforeach
                <td><input class="form-control col-md-10" type="text" name="" /></td>
              </tr> 
          </tfoot>  
        </table>
      </div>  
    </form>
  </div>

@endsection
