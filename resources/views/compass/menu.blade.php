<li class="nav-item">
    <a class="nav-link" href="{{ route('compass.home')}}"><i class="fas fa-home mr-2"></i>Inicio</a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownRequerimientos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-clipboard-list mr-2"></i>
        Ordenes de Pedido
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('pedidos.indexEmpresa')}}">Lista</a>
        @if (Auth::user()->userable->name === 'Compras')
        <a class="dropdown-item" href="{{ route('compass.pedidos.verificar')}}">Verificar</a>
        @endif
        @if (Auth::user()->userable->name === 'Despacho')
        <a class="dropdown-item" href="{{route('reportes.carta.view')}}">Carta de Trabajo</a>
        <a class="dropdown-item" href="{{ route('compass.pedidos.cajasIndex')}}">Armar Cajas</a>
        <a class="dropdown-item" href="{{ route('compass.pedidos.programarDespachos')}}">Programar Despachos</a>
        <a class="dropdown-item" href="{{ route('compass.pedidos.despachar')}}">Despachar</a>
        @endif
    </div>
</li>
@if (Auth::user()->userable->name === 'Compras')
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownCentros" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-route mr-2"></i>
        Puntos de Abastecimientos
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('abastecimientos.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('abastecimientos.create')}}">Nuevo</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownCentros" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-address-card mr-2"></i>
        Bodegueros
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('bodegueros.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('bodegueros.create')}}">Nuevo</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownHoldings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-building mr-2"></i>
        Holdings
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('holdings.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('holdings.create')}}">Nuevo</a>
        <a class="dropdown-item" href="{{route('usuarios.index', 'h')}}">Usuarios</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownEmpresas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-industry mr-2"></i>
        Empresas
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('empresas.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('empresas.create')}}">Nuevo</a>
        <a class="dropdown-item" href="{{route('usuarios.index', 'e')}}">Usuarios</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{route('horarios.create')}}">Asignar Horarios</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownCentros" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-warehouse mr-2"></i>
        Centros de Cultivos
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('centros.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('centros.create')}}">Nuevo</a>
        <a class="dropdown-item" href="{{route('usuarios.index', 'c')}}">Usuarios</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownUsuarios" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-users mr-2"></i>
        Usuarios
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('usuarios.index', 'r')}}">Usuarios</a>
        <a class="dropdown-item" href="{{route('usuarios.index')}}">Todos los Usuarios</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{route('register')}}">Nuevo Usuario</a>
        <a class="dropdown-item" href="{{route('usuarios.asignar')}}">Asignar Usuario</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownReportes" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-list mr-2"></i>
        Reportes
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('reportes.packs')}}">Generar Packs</a>
        <a class="dropdown-item" href="{{route('reportes.productos')}}">Rebaja de Productos</a>
        <a class="dropdown-item" href="{{route('reportes.guias.view')}}">Historial de Guias de Despacho</a>
        <a class="dropdown-item" href="{{route('reportes.carta.view')}}">Carta de Trabajo</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownEstados" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-clipboard-check mr-2"></i>
        Estados de Pago
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('estado_pago_general')}}">Estados de Pago</a>
        <a class="dropdown-item" href="{{route('estado_pago_resumen')}}">Resumen Estado de Pago</a>
        <a class="dropdown-item" href="{{route('estado_pago_cierre')}}">Cierre Estado de Pago</a>
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
<li class="nav-item">
    <a class="nav-link" href="{{ route('cargarFolios') }}">
        <i class="fas fa-sign-in-alt mr-2"></i>
        Cargar Folios
    </a>
</li>
@endif
<li class="nav-item">
    <a class="nav-link" href="{{ route('requerimiento.cargaMasiva') }}">
        <i class="fas fa-sign-in-alt mr-2"></i>
        Importar Requerimientos
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('servicio-cliente.view') }}">
        <i class="fas fa-comments mr-2"></i>
        Servicio al cliente
    </a>
</li>


<div class="dropdown-divider"></div>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <v-icon>mdi-server</v-icon>
        Tablas Bidones
    </a>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route("proveedores.index") }}">
            <v-icon>mdi-truck</v-icon> Proveedores
        </a>
        <a class="dropdown-item" href="{{ route("bidones.index") }}">
            <v-icon>mdi-widgets</v-icon> Bidones
        </a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="inventarioDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <v-icon>mdi-inbox-multiple</v-icon> Inventario Bidones
    </a>
    <div class="dropdown-menu" aria-labelledby="inventarioDropdown">
        <a class="dropdown-item" href="{{ route("entradas.index") }}">
            <v-icon>mdi-inbox-arrow-down</v-icon> Entrada
        </a>
        <a class="dropdown-item" href="{{ route("carga-iniciales.index") }}">
            <v-icon>mdi-inbox-arrow-down-outline</v-icon> Carga Inicial
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route("salidas.index") }}">
            <v-icon>mdi-inbox-arrow-up-outline</v-icon> Salida
        </a>
        <a class="dropdown-item" href="{{ route("nota-creditos.index") }}">
            <v-icon>mdi-inbox-arrow-up</v-icon> Nota de Creditos
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route("ajustes.index") }}">
            <v-icon>mdi-inbox-outline</v-icon> Ajustes
        </a>
    </div>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="reporteDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <v-icon>mdi-chart-box</v-icon> Reportes Bidones
    </a>
    <div class="dropdown-menu" aria-labelledby="reporteDropdown">
        <a class="dropdown-item" href="{{ route("kardex-show") }}">
            <v-icon>mdi-chart-box</v-icon> Kardex
        </a>
        <a class="dropdown-item" href="{{ route("inventario") }}">
            <v-icon>mdi-archive</v-icon> Inventario
        </a>
        <a class="dropdown-item" href="{{ route("entrada") }}">
            <v-icon>mdi-archive-arrow-down</v-icon> Entradas
        </a>
        <a class="dropdown-item" href="{{ route("salida") }}">
            <v-icon>mdi-archive-arrow-up</v-icon> Salidas
        </a>
    </div>
</li>