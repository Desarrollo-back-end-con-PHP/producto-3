<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Partners - Isla Transfers')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa; /* Gris muy suave para el fondo principal */
        }
        main {
            flex: 1;
        }
        .navbar-brand {
            font-weight: 800;
            /* Eliminamos el color azul fijo aquí para que navbar-dark lo maneje */
        }
        /* Ajuste opcional si quieres un azul más oscuro que el primary estándar */
        /* .bg-hotel { background-color: #0a58ca; } */
    </style>
</head>

<body>
    <header>
        {{-- CAMBIO PRINCIPAL: navbar-dark y bg-primary --}}
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="container">
                {{-- LOGO / MARCA --}}
                <a class="navbar-brand" href="{{ route('hotel.panel') }}">
                    {{-- El icono y texto ahora serán blancos automáticamente por navbar-dark --}}
                    <i class="fas fa-umbrella-beach me-2"></i>Isla Transfers <span class="text-warning text-uppercase small" style="font-size: 0.6em;">Partners</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    {{-- MENÚ IZQUIERDA (NAVEGACIÓN) --}}
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            {{-- CAMBIO: El enlace activo ahora es text-white en lugar de text-primary --}}
                            <a class="nav-link {{ request()->routeIs('hotel.panel') ? 'active fw-bold text-white' : '' }}" href="{{ route('hotel.panel') }}">
                                <i class="fas fa-chart-pie me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- CAMBIO: El enlace activo ahora es text-white --}}
                            <a class="nav-link {{ request()->routeIs('hotel.reservas.create') ? 'active fw-bold text-white' : '' }}" href="{{ route('hotel.reservas.create') }}">
                                <i class="fas fa-plus-circle me-1"></i> Nueva Reserva
                            </a>
                        </li>
                    </ul>

                    {{-- MENÚ DERECHA (USUARIO) --}}
                    <ul class="navbar-nav ms-auto">
                        @auth('hotel')
                            <li class="nav-item dropdown">
                                {{-- CAMBIO: text-dark pasa a text-white para el nombre del usuario --}}
                                <a class="nav-link dropdown-toggle text-white fw-bold d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    {{-- CAMBIO: El círculo del avatar ahora es bg-white y texto primary para contrastar --}}
                                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        {{ strtoupper(substr(Auth::guard('hotel')->user()->usuario, 0, 1)) }}
                                    </div>
                                    {{ Auth::guard('hotel')->user()->usuario }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                    <li><span class="dropdown-header text-muted">Cuenta Corporativa</span></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('hotel.logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('hotel.login') }}">Iniciar Sesión</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    {{-- CONTENIDO --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER (Se mantiene igual, oscuro) --}}
    <footer class="footer mt-auto py-5 bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3 text-white">Isla Transfers</h5>
                    <p class="small text-secondary mb-2">Tu servicio de confianza para traslados en la isla.</p>
                    <p class="small text-secondary">
                        Avenida Ficticia, 123<br>
                        07001, Palma de Mallorca<br>
                        <strong class="text-light">Tel:</strong> +34 971 00 00 00<br>
                        <strong class="text-light">Email:</strong> partners@islatransfers.com
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3 text-white">Panel Partner</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('hotel.panel') }}" class="text-secondary text-decoration-none small">Dashboard</a></li>
                        <li class="mb-2"><a href="{{ route('hotel.reservas.create') }}" class="text-secondary text-decoration-none small">Nueva Reserva</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3 text-white">Soporte</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none small">Contactar Administración</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none small">Incidencias</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3 text-white">Estado</h5>
                    <div class="d-flex align-items-center text-success small">
                        <i class="fas fa-circle me-2"></i> Sistema Operativo
                    </div>
                </div>
            </div>

            <hr class="my-4 border-secondary">

            <div class="text-center text-secondary small">
                &copy; {{ date('Y') }} Isla Transfers. Panel exclusivo para hoteles asociados.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>