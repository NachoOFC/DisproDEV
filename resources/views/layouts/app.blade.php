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
                }

                h1{font-size:1.6rem;font-weight:700;margin-bottom:.4rem}
                h2{font-size:1.25rem;font-weight:600;margin-bottom:.35rem}
                h3{font-size:1.05rem;font-weight:600;margin-bottom:.3rem}
                small{font-size:.825rem;color:#6b7280}

                /* Sidebar refinements */
                nav.bg-light.sidebar, .bg-light.sidebar, .d-md-block.bg-light.sidebar{
                    background: #ffffff;
                    font-size: .95rem;
                }

                .sidebar .nav-link{
                    color: var(--muted-600) !important;
                    padding: 10px 12px;
                    border-radius: var(--radius-sm);
                    transition: background .18s ease, color .12s ease, transform .12s ease;
                }

                .sidebar .nav-link:hover{
                    background: var(--accent-100) !important;
                    color: var(--muted-800) !important;
                    transform: translateX(2px);
                }

                /* indicador vertical para el item activo */
                .sidebar .nav-link.active{
                    color: var(--muted-800) !important;
                    background: linear-gradient(90deg, rgba(14,165,164,0.03), transparent) !important;
                    box-shadow: 0 1px 0 rgba(15,23,42,0.04) inset;
                }

                .sidebar .nav-link.active::before{
                    content: '';
                    display:inline-block;
                    width:4px;height:28px;border-radius:4px;margin-right:10px;background:var(--brand-500);vertical-align:middle;
                }

                .sidebar .dropdown-item{
                    color: var(--muted-800) !important;
                    padding-left: 28px !important;
                    font-weight:500 !important;
                }

                /* icons */
                .sidebar i, .sidebar .mdi, .sidebar svg{ color: var(--icon-600) !important; }

                /* Small utility adjustments */
                .card{ border-radius: 10px; }
                .btn{ border-radius: 8px; }
            </style>

</head>

<body class="bg-grey-lightest font-sans leading-normal tracking-normal">
    <div id="app">
        <v-app>
            <header class="navbar navbar-dark bg-gray-200 flex-md-nowrap px-4 py-1 shadow z-10 fixed top-0 left-0 right-0">
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

            <div class="container-fluid mt-40">
                <div class="row">
                    <div id="sidenav" class="col-md-2 collapse show ">
                        <nav class="d-none d-md-block bg-light sidebar pt-8 ">
                            <div class="nav flex-column mt-4">
                                @hasSection('nav-menu')
                                @yield('nav-menu')
                                @endif
                            </div>
                        </nav>
                    </div>

                    <main class="col pt-4 w-96">
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