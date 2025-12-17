<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('avion.ico') }}" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>@yield('title', 'Isla Transfers')</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
            <div class="container-fluid">
                <a class="text-light navbar-brand fw-bold" href="{{ url('/') }}">Isla Transfers</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">

                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="text-light nav-link active" href="{{ url('/') }}">Inicio</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">

                        @php
                        // 1. Intentamos obtener el usuario de cualquiera de los dos guards
                        $user = null;
                        $guardName = null;

                        if (Auth::guard('web')->check()) {
                        $user = Auth::guard('web')->user();
                        $guardName = 'web';
                        } elseif (Auth::guard('hotel')->check()) {
                        $user = Auth::guard('hotel')->user();
                        $guardName = 'hotel';
                        }
                        @endphp

                        @if($user)

                        <?php
                        // Preparamos el nombre para mostrar
                        // Si es Hotel usa 'usuario', si es Viajero usa 'nombre'
                        $displayName = $user->nombre ?? $user->usuario;
                        $firstLetter = strtoupper(substr($displayName, 0, 1));
                        ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar me-2">
                                    {{ $firstLetter }}
                                </div>
                                <span class="text-light d-none d-lg-inline">
                                    Hola, {{ $displayName }}
                                </span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                @if($guardName === 'web' && $user->email === 'admin@islatransfers.com')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Panel Admin</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                @endif

                                @if($guardName === 'web')
                                <li><a class="dropdown-item" href="{{ route('usuario.perfil') }}">Mi Perfil</a></li>
                                @endif

                                @if($guardName === 'hotel')
                                <li><a class="dropdown-item" href="{{ route('hotel.panel') }}">Panel Corporativo</a></li>
                                <li><a class="dropdown-item" href="{{ route('hotel.reservas.create') }}">Nueva Reserva</a></li>
                                @endif

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <form action="{{ $guardName === 'hotel' ? route('hotel.logout') : route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>

                        @else

                        <li class="nav-item">
                            <a class="text-light nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="text-light nav-link" href="{{ route('register') }}">Registro</a>
                        </li>

                        @endif


                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container-fluid p-0">
        @yield('content')
    </main>

<footer class="footer mt-auto py-5 bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Isla Transfers (Proyecto)</h5>
                    <p class="small text-secondary mb-2">Tu servicio de confianza para traslados en la isla.</p>
                    <p class="small text-secondary">
                        Avenida Ficticia, 123<br>
                        07001, Palma de Mallorca (Ficticia)<br>
                        <strong class="text-light">Teléfono:</strong> +34 971 00 00 00<br>
                        <strong class="text-light">Email:</strong> info@isla-transfers-ficticia.com
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ url('/') }}" class="text-secondary text-decoration-none small">Inicio</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none small">Reservar Ahora</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none small">Sobre Nosotros</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none small">Contacto</a>
                        </li>
                         <li class="mb-2">
                             <a href="{{ route('hotel.login') }}" class="text-secondary text-decoration-none small">Acceso Hoteles</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Legal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none small">Política de Privacidad</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none small">Términos y Condiciones</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none small">Política de Cookies</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Síguenos</h5>
                    <p class="small text-secondary">Nuestras redes (ficticias):</p>
                    </div>
            </div>

            <hr class="my-4 border-secondary">

            <div class="text-center text-secondary small">
                &copy; 2025 Isla Transfers (FP.448 - Proyecto Académico). Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
