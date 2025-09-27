<!-- Inline accordion styles/scripts: fallback inmediato mientras no se vuelvan a compilar los assets -->
<style>
.sidebar .accordion-item { border-radius: 4px; overflow: hidden; }
.sidebar .accordion-toggle { display: flex; align-items: center; justify-content: space-between; padding-right: 8px; cursor: pointer; }
.sidebar .accordion-toggle .fa-chevron-down{ transition: transform .2s ease; margin-left: 8px; }
.sidebar .accordion-panel { display: block !important; position: static !important; width: auto !important; max-height: 0; overflow: hidden; transition: max-height .28s ease, padding .2s ease; padding-left: 12px; padding-top: 0; padding-bottom: 0; }
.sidebar .accordion-item.open > .accordion-panel { max-height: 1000px; padding-top: .35rem; padding-bottom: .35rem; }
.sidebar .accordion-item.open > .accordion-toggle .fa-chevron-down{ transform: rotate(180deg); }
.sidebar .dropdown-menu.accordion-panel { background: transparent; border: none; box-shadow: none; padding-left: 0; position: static !important; transform: none !important; }
.sidebar .dropdown-menu.accordion-panel a.dropdown-item { padding-left: 18px; }
</style>

<li class="nav-item">
    <a class="nav-link" href="{{ route('compass.home')}}"><i class="fas fa-home mr-2"></i>Inicio</a>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownRequerimientos" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-clipboard-list mr-2"></i>
        Ordenes de Pedido
    </a>
    <div class="accordion-panel">
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
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownCentros" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-route mr-2"></i>
        Puntos de Abastecimientos
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('abastecimientos.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('abastecimientos.create')}}">Nuevo</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownCentrosB" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-address-card mr-2"></i>
        Bodegueros
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('bodegueros.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('bodegueros.create')}}">Nuevo</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownHoldings" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-building mr-2"></i>
        Holdings
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('holdings.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('holdings.create')}}">Nuevo</a>
        <a class="dropdown-item" href="{{route('usuarios.index', 'h')}}">Usuarios</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownEmpresas" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-industry mr-2"></i>
        Empresas
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('empresas.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('empresas.create')}}">Nuevo</a>
        <a class="dropdown-item" href="{{route('usuarios.index', 'e')}}">Usuarios</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{route('horarios.create')}}">Asignar Horarios</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownCentrosCultivo" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-warehouse mr-2"></i>
        Centros de Cultivos
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('centros.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('centros.create')}}">Nuevo</a>
        <a class="dropdown-item" href="{{route('usuarios.index', 'c')}}">Usuarios</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownUsuariosMain" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-users mr-2"></i>
        Usuarios
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('usuarios.index', 'r')}}">Usuarios</a>
        <a class="dropdown-item" href="{{route('usuarios.index')}}">Todos los Usuarios</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{route('register')}}">Nuevo Usuario</a>
        <a class="dropdown-item" href="{{route('usuarios.asignar')}}">Asignar Usuario</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownReportesMain" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-list mr-2"></i>
        Reportes
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('reportes.packs')}}">Generar Packs</a>
        <a class="dropdown-item" href="{{route('reportes.productos')}}">Rebaja de Productos</a>
        <a class="dropdown-item" href="{{route('reportes.guias.view')}}">Historial de Guias de Despacho</a>
        <a class="dropdown-item" href="{{route('reportes.carta.view')}}">Carta de Trabajo</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownEstados" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-clipboard-check mr-2"></i>
        Estados de Pago
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('estado_pago_general')}}">Estados de Pago</a>
        <a class="dropdown-item" href="{{route('estado_pago_resumen')}}">Resumen Estado de Pago</a>
        <a class="dropdown-item" href="{{route('estado_pago_cierre')}}">Cierre Estado de Pago</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownCierre" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-clipboard-check mr-2"></i>
        Control OC/NC/FE
    </a>
    <div class="accordion-panel">
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

<li class="nav-item dropdown accordion-item">
    <a class="nav-link dropdown-toggle accordion-toggle" href="#" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
        <v-icon>mdi-server</v-icon>
        Tablas Bidones
    </a>
    <div class="accordion-panel" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route("proveedores.index") }}">
            <v-icon>mdi-truck</v-icon> Proveedores
        </a>
        <a class="dropdown-item" href="{{ route("bidones.index") }}">
            <v-icon>mdi-widgets</v-icon> Bidones
        </a>
    </div>
</li>

<li class="nav-item dropdown accordion-item">
    <a class="nav-link dropdown-toggle accordion-toggle" href="#" id="inventarioDropdown" role="button" aria-haspopup="true" aria-expanded="false">
        <v-icon>mdi-inbox-multiple</v-icon> Inventario Bidones
    </a>
    <div class="accordion-panel" aria-labelledby="inventarioDropdown">
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

<li class="nav-item dropdown accordion-item">
    <a class="nav-link dropdown-toggle accordion-toggle" href="#" id="reporteDropdown" role="button" aria-haspopup="true" aria-expanded="false">
        <v-icon>mdi-chart-box</v-icon> Reportes Bidones
    </a>
    <div class="accordion-panel" aria-labelledby="reporteDropdown">
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

<!-- El CSS/JS del acordeón se movió a los assets (resources/sass/app.scss y resources/js/app.js) -->
<script>
(function(){
    function ensureChevron(t){
        if (!t.querySelector('.fa-chevron-down')){
            var icon = document.createElement('i');
            icon.className = 'fas fa-chevron-down';
            t.appendChild(icon);
        }
    }

    try{
        document.querySelectorAll('.accordion-toggle').forEach(function(t){ ensureChevron(t); });
    }catch(e){}

        // Inicializar: asegurar que no haya items abiertos por defecto (evita conflicto con Bootstrap o views cacheadas)
        try{
            // remover clase 'open' si existe
            document.querySelectorAll('.accordion-item.open').forEach(function(it){ it.classList.remove('open'); });
            // remover clase 'show' (bootstrap) de paneles
            document.querySelectorAll('.accordion-panel.show, .accordion-panel.dropdown-menu.show').forEach(function(p){ p.classList.remove('show'); });
            // forzar aria-expanded=false en toggles
            document.querySelectorAll('.accordion-toggle[aria-expanded="true"]').forEach(function(a){ a.setAttribute('aria-expanded','false'); });
            // forzar estilo colapsado
            document.querySelectorAll('.accordion-panel').forEach(function(p){ p.style.maxHeight = '0'; p.style.overflow = 'hidden'; });
        }catch(e){}

    document.addEventListener('click', function(e){
        var t = e.target.closest && e.target.closest('.accordion-toggle');
        if(!t) return;
        e.preventDefault();
        ensureChevron(t);

        var item = t.closest('.accordion-item');
        if(!item) return;

        var container = item.parentElement;
        if(container){
            var siblings = container.querySelectorAll(':scope > .accordion-item');
            siblings.forEach(function(s){ if(s !== item){ s.classList.remove('open');
                // collapse sibling panels
                var sp = s.querySelector('.accordion-panel'); if(sp) sp.style.maxHeight = '0';
            } });
        }    

        var panel = item.querySelector('.accordion-panel');
        var willOpen = !item.classList.contains('open');

        if(willOpen){
            // open
            item.classList.add('open');
            if(panel){
                panel.style.maxHeight = panel.scrollHeight + 'px';
            }
        } else {
            // close
            item.classList.remove('open');
            if(panel) panel.style.maxHeight = '0';
        }

        var a = item.querySelector('.accordion-toggle');
        if(a) a.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    }, false);
})();
</script>