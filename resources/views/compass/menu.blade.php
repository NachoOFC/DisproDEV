<!-- Inline accordion styles/scripts: fallback inmediato mientras no se vuelvan a compilar los assets -->
<style>
/* High-specificity small patch to ensure the sidebar keeps a white, card-like aesthetic
   Ajustes de tipografía: aumentar peso y tamaño de texto para items y subitems */
.bg-light.sidebar nav, nav.bg-light.sidebar, .d-md-block.bg-light.sidebar {} /* placeholder to increase specificity */
.bg-light.sidebar nav .accordion-toggle, nav.bg-light.sidebar .accordion-toggle, .d-md-block.bg-light.sidebar .accordion-toggle { position: relative !important; }
.bg-light.sidebar nav .accordion-toggle .fa-chevron-down, nav.bg-light.sidebar .accordion-toggle .fa-chevron-down, .d-md-block.bg-light.sidebar .accordion-toggle .fa-chevron-down { position: absolute !important; right: 12px !important; top: 50% !important; transform: translateY(-50%) !important; }
.bg-light.sidebar nav .accordion-toggle, nav.bg-light.sidebar .accordion-toggle, .d-md-block.bg-light.sidebar .accordion-toggle { padding-right: 40px !important; display: flex !important; align-items: center !important; justify-content: flex-start !important; }
.bg-light.sidebar nav .accordion-toggle .chev, nav.bg-light.sidebar .accordion-toggle .chev, .d-md-block.bg-light.sidebar .accordion-toggle .chev { display: inline-block !important; visibility: visible !important; opacity: 1 !important; min-width: 12px !important; width: 12px !important; height: 12px !important; font-size: 12px !important; flex-shrink: 0 !important; }
.@font-face{font-family: 'InterLocal'; src: local('Inter'), local('Segoe UI'), local('Helvetica Neue');}
.bg-light.sidebar, nav.bg-light.sidebar, .d-md-block.bg-light.sidebar, .sidebar { font-family: 'Inter', 'InterLocal', 'Segoe UI', system-ui, -apple-system, 'Helvetica Neue', Arial !important; background-color: #1a1a1a !important; }

/* Card-like container for each nav item */
.sidebar .nav-item { margin: 10px 10px; border-radius: 10px; overflow: visible; }

.sidebar .accordion-item { border-radius: 8px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3); background: #2d3748; border: 0; }

/* Estética: tipografía más grande y peso mayor */
.sidebar .accordion-toggle, .sidebar a.nav-link { display: flex; align-items: center; justify-content: flex-start; padding: 16px 18px; padding-right: 40px; cursor: pointer; font-weight: 700; font-size: 1.16rem; color: #ffffff !important; text-decoration: none !important; position: relative; line-height: 1.2; }
.sidebar .accordion-toggle i, .sidebar .nav-link i, .sidebar .dropdown-item i { width: 22px; text-align: center; color: #ffffff; margin-right: 14px; font-size: 1.05rem; }
.sidebar .accordion-toggle .fa-chevron-down{ transition: transform .18s ease; color: #ffffff !important; font-size: 1.02rem; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); }

/* placeholder span.chev (hidden) kept for compatibility */
.sidebar .accordion-toggle .chev{ display: none; }

/* Forzar chevron al final: envolver iconos izquierdo y mover chevron con utilidades */
.sidebar .accordion-toggle .left-icon { margin-right: 12px; }
.sidebar .accordion-toggle .chev { 
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #ffffff !important; 
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    min-width: 12px !important;
    width: 12px !important;
    height: 12px !important;
    font-size: 12px !important;
    flex-shrink: 0 !important;
}

/* Chevrons dinámicos generados por JavaScript - FORZAR VISIBILIDAD EN TODOS LOS ZOOM */
.sidebar .fa-chevron-down,
.sidebar .fas.fa-chevron-down,
nav.bg-light.sidebar .fa-chevron-down,
nav.bg-light.sidebar .fas.fa-chevron-down,
.bg-light.sidebar .fa-chevron-down,
.bg-light.sidebar .fas.fa-chevron-down {
    color: #ffffff !important;
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    min-width: 12px !important;
    width: 12px !important;
    height: 12px !important;
    font-size: 12px !important;
    flex-shrink: 0 !important;
}

/* Media queries para asegurar visibilidad en todos los niveles de zoom */
@media (min-width: 1px) {
    .sidebar .fa-chevron-down,
    .sidebar .fas.fa-chevron-down,
    nav.bg-light.sidebar .fa-chevron-down,
    nav.bg-light.sidebar .fas.fa-chevron-down,
    .bg-light.sidebar .fa-chevron-down,
    .bg-light.sidebar .fas.fa-chevron-down {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
}

@media (min-width: 768px) {
    .sidebar .fa-chevron-down,
    .sidebar .fas.fa-chevron-down {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
}

@media (min-width: 992px) {
    .sidebar .fa-chevron-down,
    .sidebar .fas.fa-chevron-down {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
}

@media (min-width: 1200px) {
    .sidebar .fa-chevron-down,
    .sidebar .fas.fa-chevron-down {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
}

/* Subitems appear inside the card with a softer background to separate visually */
.sidebar .accordion-panel { display: block !important; position: static !important; width: auto !important; max-height: 0; overflow: hidden; transition: max-height .28s ease, padding .18s ease; padding: 0 14px; }
.sidebar .accordion-item.open > .accordion-panel { max-height: 1000px; padding: .5rem 14px .8rem; }
.sidebar .dropdown-menu.accordion-panel { background: transparent; border: none; box-shadow: none; padding-left: 0; position: static !important; transform: none !important; }
.sidebar .dropdown-menu.accordion-panel a.dropdown-item { display: block; padding: 10px 14px; color: #ffffff !important; font-weight: 600 !important; border-radius: 6px; margin: 6px 0; font-size: 1.00rem; }

/* Hover / active states: mantener tema oscuro, resaltar con fondo más claro */
.bg-light.sidebar a.nav-link:hover, .bg-light.sidebar a.dropdown-item:hover, .sidebar a.nav-link:hover, .sidebar a.dropdown-item:hover{ background-color: rgba(255, 255, 255, 0.1) !important; box-shadow: 0 6px 20px rgba(0,0,0,0.2); color: #ffffff !important; text-decoration: none !important; }
.bg-light.sidebar a.nav-link.active, .bg-light.sidebar a.dropdown-item.active, .sidebar a.nav-link.active, .sidebar a.dropdown-item.active{ background-color: rgba(14,165,164,0.2) !important; box-shadow: 0 8px 28px rgba(0,0,0,0.3); color: #ffffff !important; }

/* Subitems slightly smaller but clearer to read */
.sidebar .dropdown-item{ font-size: 1.00rem; }

/* Divider subtle */
.sidebar .dropdown-divider{ border-top: 1px solid #f1f5f9; margin: 8px 0; }

/* Ajustes para iconos de material/v-icon cuando existan */
.sidebar v-icon{ color: #55606a; margin-right: 8px; vertical-align: middle; }

/* Mejor contraste para textos y accesibilidad */
.sidebar, .sidebar a{ color-scheme: light; }

/* Forzar colores para estados activos / abiertos que el CSS compilado marca en azul */
.bg-light.sidebar a.nav-link.active, nav.bg-light.sidebar a.nav-link.active, .bg-light.sidebar .nav-link.active {
    color: #ffffff !important;
}
.bg-light.sidebar .accordion-item.open > .accordion-toggle, .bg-light.sidebar .accordion-toggle.active, .bg-light.sidebar .accordion-toggle[aria-expanded="true"] {
    color: #ffffff !important;
}

/* FORZAR TODOS LOS TEXTOS A BLANCO - SUPER AGRESIVO PARA COMPASS */
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
.sidebar .accordion-toggle,
.sidebar .accordion-toggle *,
.sidebar .accordion-item,
.sidebar .accordion-item *,
.sidebar .accordion-panel,
.sidebar .accordion-panel *,
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
.bg-light.sidebar i {
    color: #ffffff !important;
}

/* Sobrescribir cualquier clase de Bootstrap o custom */
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
.bg-light.sidebar .text-secondary {
    color: #ffffff !important;
}
</style>

<li class="nav-item">
    <a class="nav-link" href="{{ route('compass.home')}}"><i class="fas fa-home mr-2"></i>Inicio</a>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownRequerimientos" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-clipboard-list left-icon"></i>
        Ordenes de Pedido
        <span class="chev"></span>
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
        <i class="fas fa-route left-icon"></i>
        Puntos de Abastecimientos
        <span class="chev"></span>
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('abastecimientos.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('abastecimientos.create')}}">Nuevo</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownCentrosB" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-address-card left-icon"></i>
        Bodegueros
        <span class="chev"></span>
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('bodegueros.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('bodegueros.create')}}">Nuevo</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownHoldings" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-building left-icon"></i>
        Holdings
        <span class="chev"></span>
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('holdings.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('holdings.create')}}">Nuevo</a>
        <a class="dropdown-item" href="{{route('usuarios.index', 'h')}}">Usuarios</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownEmpresas" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-industry left-icon"></i>
        Empresas
        <span class="chev"></span>
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
        <i class="fas fa-warehouse left-icon"></i>
        Centros de Cultivos
        <span class="chev"></span>
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('centros.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('centros.create')}}">Nuevo</a>
        <a class="dropdown-item" href="{{route('usuarios.index', 'c')}}">Usuarios</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownUsuariosMain" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-users left-icon"></i>
        Usuarios
        <span class="chev"></span>
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
        <i class="fas fa-list left-icon"></i>
        Reportes
        <span class="chev"></span>
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
        <i class="fas fa-clipboard-check left-icon"></i>
        Estados de Pago
        <span class="chev"></span>
    </a>
    <div class="accordion-panel">
        <a class="dropdown-item" href="{{route('estado_pago_general')}}">Estados de Pago</a>
        <a class="dropdown-item" href="{{route('estado_pago_resumen')}}">Resumen Estado de Pago</a>
        <a class="dropdown-item" href="{{route('estado_pago_cierre')}}">Cierre Estado de Pago</a>
    </div>
</li>
<li class="nav-item accordion-item">
    <a class="nav-link accordion-toggle" href="#" role="button" id="dropdownCierre" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-clipboard-check left-icon"></i>
        Control OC/NC/FE
        <span class="chev"></span>
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
            // force position via inline styles to avoid being overwritten by compiled CSS
            icon.style.setProperty('position','absolute');
            icon.style.setProperty('right','12px');
            icon.style.setProperty('top','50%');
            icon.style.setProperty('transform','translateY(-50%)');
            // FORZAR COLOR BLANCO PARA CHEVRON Y VISIBILIDAD EN TODOS LOS ZOOM
            icon.style.setProperty('color','#ffffff','important');
            icon.style.setProperty('display','inline-block','important');
            icon.style.setProperty('visibility','visible','important');
            icon.style.setProperty('opacity','1','important');
            icon.style.setProperty('min-width','12px','important');
            icon.style.setProperty('width','12px','important');
            icon.style.setProperty('height','12px','important');
            icon.style.setProperty('font-size','12px','important');
            t.appendChild(icon);
            // ensure the toggle uses relative positioning for absolute chevron positioning
            try{ 
                t.style.setProperty('position','relative','important'); 
                t.style.setProperty('padding-right','40px','important');
            }catch(e){}
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

<!-- Forzar color BLANCO por JS para sidebar -->
<script>
    (function(){
        try{
            var containers = document.querySelectorAll('nav.bg-light.sidebar, .bg-light.sidebar, .d-md-block.bg-light.sidebar');
            containers.forEach(function(c){
                c.querySelectorAll('a').forEach(function(a){
                        // FORZAR TODOS LOS ENLACES A BLANCO
                        a.style.setProperty('color','#ffffff','important');
                        if (a.classList.contains('dropdown-item')){
                            a.style.setProperty('font-weight','600','important');
                        } else {
                            a.style.setProperty('font-weight','700','important');
                        }
                        a.style.setProperty('text-decoration','none','important');
                            // ensure toggles have space for chevron and relative positioning (match CSS)
                            a.style.setProperty('padding-right','56px','important');
                            a.style.setProperty('position','relative','important');
                    });
                // FORZAR TODOS LOS ICONOS A BLANCO
                c.querySelectorAll('i, v-icon').forEach(function(ic){
                    ic.style.setProperty('color','#ffffff','important');
                });
                // FORZAR TODOS LOS ELEMENTOS A BLANCO
                c.querySelectorAll('*').forEach(function(el){
                    if(el.tagName !== 'SCRIPT' && el.tagName !== 'STYLE'){
                        el.style.setProperty('color','#ffffff','important');
                    }
                });
            });
        }catch(e){console && console.error && console.error(e);}    
    })();
</script>