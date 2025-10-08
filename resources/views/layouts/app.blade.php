<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
            <link rel="dns-prefetch" href="//fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">

    <title>Alogis</title>

    <!-- Fonts -->
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

    <!-- Style -->
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet">
    
            <!-- Hotfix styles: tipografía y sidebar pulido (temporal en layout, migrar a SASS) -->
            <style>
                :root{
                    --brand-500: #0ea5a4; /* solo referencia, no usar en sidebar */
                    --muted-600: #55606a; /* item padre */
                    --muted-800: #1f2937; /* subitem */
                    --icon-600: #4b5563;
                    --accent-100: rgba(14,165,164,0.06);
                    --radius-sm: 6px;
                }

                html,body{
                    font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
                    color: #0f172a;
                    font-size: 14px;
                    line-height: 1.45;
                    -webkit-font-smoothing:antialiased;
                    -moz-osx-font-smoothing:grayscale;
                    /* Prevenir flash durante recarga */
                    overflow-x: hidden;
                }

                /* Prevenir flash de contenido sin estilo (FOUC) */
                .sidebar, #sidenav {
                    visibility: visible !important;
                    opacity: 1 !important;
                    transform: none !important;
                }

                h1{font-size:1.6rem;font-weight:700;margin-bottom:.4rem}
                h2{font-size:1.25rem;font-weight:600;margin-bottom:.35rem}
                h3{font-size:1.05rem;font-weight:600;margin-bottom:.3rem}
                small{font-size:.825rem;color:#6b7280}

                /* Sidebar refinements */
                nav.bg-light.sidebar, .bg-light.sidebar, .d-md-block.bg-light.sidebar{
                    background: #374151 !important;
                    font-size: .95rem;
                }

                /* Fix: asegurar que el sidebar y contenido funcionen correctamente */
                @media (min-width: 768px) {
                    .col-md-2 { 
                        flex: 0 0 16.666667%;
                        max-width: 16.666667%;
                    }
                    .col-md-10 { 
                        flex: 0 0 83.333333%;
                        max-width: 83.333333%;
                    }
                    nav.bg-light.sidebar, .bg-light.sidebar { 
                        box-sizing: border-box;
                    }
                }

                /* Fix para pantallas grandes: limitar ancho de sidebar */
                @media (min-width: 1200px) {
                    .col-md-2 { 
                        flex: 0 0 250px;
                        max-width: 250px;
                        width: 250px;
                    }
                    .col-md-10 { 
                        flex: 1;
                        max-width: calc(100% - 250px);
                        margin-left: 250px;
                    }
                    #sidenav {
                        position: fixed;
                        left: 0;
                        top: 0;
                        height: 100vh;
                        z-index: 999;
                        padding-top: 70px; /* Altura del header */
                    }
                    main.col-md-10 {
                        margin-left: 250px;
                        width: calc(100% - 250px);
                    }
                }

                /* Fix para pantallas extra grandes: mantener sidebar controlado */
                @media (min-width: 1400px) {
                    .col-md-2 { 
                        flex: 0 0 280px;
                        max-width: 280px;
                        width: 280px;
                    }
                    .col-md-10 { 
                        flex: 1;
                        max-width: calc(100% - 280px);
                        margin-left: 280px;
                    }
                    #sidenav {
                        width: 280px;
                        padding-top: 70px;
                    }
                    main.col-md-10 {
                        margin-left: 280px;
                        width: calc(100% - 280px);
                    }
                }

                .sidebar .nav-link{
                    color: #ffffff !important;
                    padding: 10px 12px;
                    border-radius: var(--radius-sm);
                    transition: background .18s ease, color .12s ease, transform .12s ease;
                }

                .sidebar .nav-link:hover{
                    background: rgba(255, 255, 255, 0.1) !important;
                    color: #ffffff !important;
                    transform: translateX(2px);
                }

                /* indicador vertical para el item activo */
                .sidebar .nav-link.active{
                    color: #ffffff !important;
                    background: linear-gradient(90deg, rgba(14,165,164,0.2), transparent) !important;
                    box-shadow: 0 1px 0 rgba(255,255,255,0.1) inset;
                }

                .sidebar .nav-link.active::before{
                    content: '';
                    display:inline-block;
                    width:4px;height:28px;border-radius:4px;margin-right:10px;background:var(--brand-500);vertical-align:middle;
                }

                .sidebar .dropdown-item{
                    color: #ffffff !important;
                    padding-left: 28px !important;
                    font-weight:500 !important;
                }

                /* icons */
                .sidebar i, .sidebar .mdi, .sidebar svg{ color: #ffffff !important; }

                /* FORZAR TODOS LOS TEXTOS DEL SIDEBAR A BLANCO - AGRESIVO */
                .sidebar *, 
                .sidebar a, 
                .sidebar span, 
                .sidebar div, 
                .sidebar li, 
                .sidebar p,
                .sidebar .nav-item,
                .sidebar .nav-item a,
                .sidebar .nav-item span,
                .sidebar .dropdown-item,
                .sidebar .dropdown-menu a,
                .sidebar .accordion-toggle,
                .sidebar .accordion-item,
                .sidebar .text-dark,
                .sidebar .text-muted,
                .sidebar .text-secondary,
                .sidebar .text-primary,
                nav.bg-light.sidebar *,
                nav.bg-light.sidebar a,
                nav.bg-light.sidebar span,
                nav.bg-light.sidebar div,
                nav.bg-light.sidebar li,
                .bg-light.sidebar *,
                .bg-light.sidebar a,
                .bg-light.sidebar span,
                .bg-light.sidebar div,
                .bg-light.sidebar li {
                    color: #ffffff !important;
                }

                /* Sobrescribir clases Bootstrap específicas */
                .sidebar .text-dark,
                .sidebar .text-muted,
                .sidebar .text-secondary,
                .sidebar .text-primary,
                .sidebar .text-info,
                .sidebar .text-warning,
                .sidebar .text-success,
                .sidebar .text-danger {
                    color: #ffffff !important;
                }

                /* Small utility adjustments */
                .card{ border-radius: 10px; }
                .btn{ border-radius: 8px; }
            </style>

</head>

<body class="bg-grey-lightest font-sans leading-normal tracking-normal">
    <div id="app">
        <v-app>
            <header class="navbar navbar-dark flex-md-nowrap px-4 py-1 shadow" style="position: relative; z-index: 1000; background-color: #374151;">
                @auth
                <button class="btn btn-outline-dark" type="button" data-toggle="collapse" data-target="#sidenav" aria-expanded="true" aria-controls="sidenav" id="sidenavBtn" hidden>
                    <i class="fas fa-bars"></i>
                </button>
                @endif
                <a class="navbar-brand" href="@yield('home-route')">
                    <div class="d-flex flex-row align-items-baseline justify-content-center h3">
                        <img src="{{ asset('img/LogoAlogis.jpeg')}}" class="w-40 h-20 img-fluid" alt="" />
                        <span class="h1 text-primary font-bold italic ml-2 mb-1 p-2">Alogis</span>
                    </div>
                </a>

                @auth
                <ul class="nav ml-auto text-light">

                    <li class="nav-item">
                        <a href="{{ route('notifications') }}" class="nav-link">
                            <i class="fas fa-bell"></i>
                            <span class="badge badge-danger">{{ count(Auth::user()->unreadNotifications) }}</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="user-dropdown" href="" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                            Hola, {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="user-dropdown">
                            <form action="{{ route('logout') }}" method="POST" accept-charset="utf-8">
                                @csrf

                                <button class="dropdown-item" type="submit">Salir</button>
                            </form>
                        </div>
                    </li>
                </ul>
                @endif
            </header>

            <div class="container-fluid" style="padding: 0;">
                <div class="row" style="margin: 0;">
                    <div id="sidenav" class="col-md-2 collapse show" style="padding: 0;">
                        <nav class="d-none d-md-block bg-light sidebar" style="position: sticky; top: 0; height: 100vh; overflow-y: auto; margin-top: 0 !important;">
                            <div class="nav flex-column" style="margin-top: 0 !important;">
                                @hasSection('nav-menu')
                                @yield('nav-menu')
                                @endif
                            </div>
                        </nav>
                    </div>

                    <main class="col-md-10" style="min-height: 100vh; padding: 1rem;">
                        @hasSection('main')
                        @yield('main')
                        @endif
                        @hasSection('content')
                        @yield('content')
                        @endif
                    </main>
                </div>
            </div>

    </div>

    <!-- Footer -->
    <footer class="row border-top bg-light mt-5 p-5 align-items-center justify-content-center">
        <a href="https://www.mline.cl/web/" target="_blank" class="d-flex flex-row align-items-center">
            <img alt="Logo de Mline SPA" src="{{ asset('img/logo-mline.png') }}" class="w-32 img-fluid" />
            <i class="fas fa-copyright"></i>
            Todos los derechos reservados. {{date("Y")}}
        </a>
    </footer>
    <!-- /Footer -->

    <!-- Scripts -->
    <script src="{{ asset(mix('js/app.js')) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" charset="utf-8"></script>
    @yield('js')

    @if (\Session::has('msg'))
    @php
    $msg = \Session::get('msg');
    @endphp
    <script charset="utf-8">
        (Swal.fire({
            title: '{{ isset($msg["meta"]["title"]) ? $msg["meta"]["title"] : "Accion Realizada" }}',
            html: '{!! isset($msg["meta"]["msg"]) ? $msg["meta"]["msg"] : "Todo listo" !!}'
        }))();
    </script>
    @endif
    </v-app>
</body>

</html>