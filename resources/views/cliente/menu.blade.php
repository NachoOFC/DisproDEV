<!-- Estilos locales para el menú de cliente: usar acento gris oscuro -->
<style>
nav.bg-light.sidebar, .bg-light.sidebar, .d-md-block.bg-light.sidebar {
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, 'Helvetica Neue', Arial !important;
    background-color: #1a1a1a !important;
}
nav.bg-light.sidebar a.nav-link, .bg-light.sidebar a.nav-link, .sidebar a.nav-link {
    color: #ffffff !important; /* padres: blanco */
    font-weight: 600 !important;
}
nav.bg-light.sidebar a.nav-link i, .bg-light.sidebar a.nav-link i, .sidebar a.nav-link i {
    color: #ffffff !important;
}
nav.bg-light.sidebar a.nav-link:hover, .bg-light.sidebar a.nav-link:hover, .sidebar a.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
}
nav.bg-light.sidebar .dropdown-menu a.dropdown-item, .bg-light.sidebar .dropdown-menu a.dropdown-item {
    color: #ffffff !important; /* subitems: blanco */
}
nav.bg-light.sidebar .dropdown-menu a.dropdown-item:hover, .bg-light.sidebar .dropdown-menu a.dropdown-item:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
}
/* Forzar colores para estados activos / abiertos para evitar que reglas compiladas los pongan en azul */
.bg-light.sidebar a.nav-link.active, nav.bg-light.sidebar a.nav-link.active, .bg-light.sidebar .nav-link.active {
    color: #ffffff !important;
    background-color: rgba(14,165,164,0.2) !important;
}
.bg-light.sidebar .accordion-item.open > .accordion-toggle, .bg-light.sidebar .accordion-toggle.active, .bg-light.sidebar .accordion-toggle[aria-expanded="true"] {
    color: #ffffff !important;
}

/* FORZAR TODOS LOS TEXTOS A BLANCO - SUPER AGRESIVO PARA CLIENTE */
.sidebar *,
.sidebar a,
.sidebar span,
.sidebar div,
.sidebar li,
.sidebar p,
.sidebar i,
.sidebar .fas,
.sidebar .far,
.sidebar .fab,
.sidebar .nav-item,
.sidebar .nav-item *,
.sidebar .nav-link,
.sidebar .nav-link *,
.sidebar .dropdown-item,
.sidebar .dropdown-item *,
.sidebar .dropdown-menu,
.sidebar .dropdown-menu *,
.sidebar .dropdown-toggle,
.sidebar .dropdown-toggle *,
nav.bg-light.sidebar,
nav.bg-light.sidebar *,
nav.bg-light.sidebar a,
nav.bg-light.sidebar span,
nav.bg-light.sidebar div,
nav.bg-light.sidebar li,
nav.bg-light.sidebar i,
.bg-light.sidebar,
.bg-light.sidebar *,
.bg-light.sidebar a,
.bg-light.sidebar span,
.bg-light.sidebar div,
.bg-light.sidebar li,
.bg-light.sidebar i,
.d-md-block.bg-light.sidebar,
.d-md-block.bg-light.sidebar * {
    color: #ffffff !important;
}

/* Sobrescribir cualquier clase de Bootstrap o custom para cliente */
.sidebar .text-dark,
.sidebar .text-muted,
.sidebar .text-secondary,
.sidebar .text-primary,
.sidebar .text-info,
.sidebar .text-warning,
.sidebar .text-success,
.sidebar .text-danger,
.sidebar .text-light,
.sidebar .text-white,
nav.bg-light.sidebar .text-dark,
nav.bg-light.sidebar .text-muted,
nav.bg-light.sidebar .text-secondary,
.bg-light.sidebar .text-dark,
.bg-light.sidebar .text-muted,
.bg-light.sidebar .text-secondary,
.d-md-block.bg-light.sidebar .text-dark,
.d-md-block.bg-light.sidebar .text-muted,
.d-md-block.bg-light.sidebar .text-secondary {
    color: #ffffff !important;
}
</style>

<li class="nav-item">
    <a class="nav-link" href="{{ route('cliente.home')}}">
        <i class="fas fa-home mr-2"></i>
        Inicio
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ asset('rrhh/minuta.pdf') }}">
        <i class="fas fa-clock mr-2"></i>
        Minuta del Mes
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ asset('rrhh/saludable.pdf') }}">
        <i class="fas fa-leaf mr-2"></i>
        Una Vida Saludable
    </a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownRequerimientos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-tasks fa-fw mr-2"></i>
        Ordenes de Pedido
    </a>
    <div class="dropdown-menu">
        @switch(get_class(Auth::user()->userable))
        @case('App\Centro')
        <a class="dropdown-item" href="{{route(
                            'pedidos.centro',
                            Auth::user()->userable->id
                            )}}">
            Lista
        </a>
        <a class="dropdown-item" href="{{ route('requerimientos.create')}}">
            Nuevo
        </a>
        <a class="dropdown-item" href="{{  route('libreria.index') }}">
            Libreria
        </a>
        @break
        @case('App\Empresa')
        <a class="dropdown-item" href="{{route('pedidos.indexCentro')}}">
            Lista
        </a>
        @if(Auth::user()->logistica)
        <a class="dropdown-item" href="{{ route('pedidos.listaLogistica')}}">
            Lista Logistica
        </a>
        @else
        <a class="dropdown-item" href="{{ route('pedidos.validar')}}">
            Validar
        </a>
        @endif
        @break
        @case('App\Holding')
        <a class="dropdown-item" href="{{route('pedidos.indexEmpresa')}}">
            Lista
        </a>
        @break
        @endswitch
    </div>
</li>
@if(!Auth::user()->logistica)
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-wallet mr-2"></i>
        Presupuesto
    </a>
    <div class="dropdown-menu">
        @if(Auth::user()->userable instanceof App\Holding)
        <a class="dropdown-item" href="{{route('presupuesto.create')}}">Cargar</a>
        <a class="dropdown-item" href="{{route('presupuesto.indexHolding')}}">Cuenta Corriente</a>
        @elseif(Auth::user()->userable instanceof App\Empresa)
        <a class="dropdown-item" href="{{route('presupuesto.create')}}">Cargar</a>
        <a class="dropdown-item" href="{{route('presupuesto.seleccionarCentro') }}">Editar</a>
        <a class="dropdown-item" href="{{route('presupuesto.indexEmpresa')}}">Cuenta Corriente</a>
        <a class="dropdown-item" href="{{route('presupuesto.cmi')}}">Consolidado</a>
        @else
        <a class="dropdown-item" href="{{route('presupuesto.indexCentro')}}">Cuenta Corriente</a>
        @endif
    </div>
</li>
@if(Auth::user()->userable instanceof App\Holding)
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-industry mr-2"></i>
        Empresas
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('empresas.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('empresas.create')}}">Nuevo</a>
    </div>
</li>
@endif

<!-- Forzar color por JS para evitar que reglas externas lo pongan en azul -->
<script>
    (function(){
        try{
            var containers = document.querySelectorAll('nav.bg-light.sidebar, .bg-light.sidebar, .d-md-block.bg-light.sidebar');
            containers.forEach(function(c){
                c.querySelectorAll('a').forEach(function(a){
                        // distinguir padres y subitems: si es dropdown-item -> subitem
                        if (a.classList.contains('dropdown-item')){
                            a.style.setProperty('color','#2b2f33','important');
                            a.style.setProperty('font-weight','500','important');
                        } else {
                            a.style.setProperty('color','#55606a','important');
                            a.style.setProperty('font-weight','600','important');
                        }
                        a.style.setProperty('text-decoration','none','important');
                    });
                c.querySelectorAll('i, v-icon').forEach(function(ic){
                    ic.style.setProperty('color','#4b5563','important');
                });
            });
        }catch(e){}
    })();
</script>
@if(Auth::user()->userable instanceof App\Empresa)
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-warehouse mr-2"></i>
        Centros
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('centros.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('centros.create')}}">Nuevo</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-users mr-2"></i>
        Usuarios de Centro
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('user.indexEmpresa')}}">Lista</a>
        <a class="dropdown-item" href="{{route('user.create')}}">Nuevo</a>
    </div>
</li>
@endif
@else
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-chart-bar mr-2"></i>
        Reportes
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('reportes.enviados')}}">
            Productos despachados
        </a>
        <a class="dropdown-item" href="{{route('estado_pago_general')}}">Estados de Pago</a>
        <a class="dropdown-item" href="{{route('estado_pago_resumen')}}">Resumen estados de pago</a>
        <a class="dropdown-item" href="{{route('estado_pago_cierre')}}">Cierre estados de pago</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownCierre" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-clipboard-check mr-2"></i>
        Control OC/NC/FE
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('orden_compra_index')}}">Orden de Compra</a>
        <a class="dropdown-item" href="{{route('nota_credito_index')}}">Nota de Credito</a>
        <a class="dropdown-item" href="{{route('factura_electronica_index')}}">Factura Electronica</a>
        <a class="dropdown-item" href="{{route('conciliacion_index')}}">Conciliación</a>
    </div>
</li>
@endif