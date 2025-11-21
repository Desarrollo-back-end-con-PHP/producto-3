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

                        @auth
                        @php
                        // Obtenemos el nombre del usuario logueado
                        $user = Auth::user();
                        $userName = $user->nombre ?? $user->usuario; // Compatible con Viajero y Hotel
                        $firstLetter = strtoupper(substr($userName, 0, 1));
                        @endphp

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar me-2">
                                    {{ $firstLetter }}
                                </div>
                                <span class="text-light d-none d-lg-inline">
                                    Hola, {{ $userName }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">

                                @if(Auth::guard('web')->check() && Auth::user()->email === 'admin@islatransfers.com')
                                <li><a class="dropdown-item" href="{{ route('admin.hoteles.index') }}">Panel Admin</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                @endif

                                @if(Auth::guard('web')->check())
                                <li><a class="dropdown-item" href="{{ route('usuario.perfil') }}">Mi Perfil</a></li>
                                @endif

                                @if(Auth::guard('hotel')->check())
                                <li><a class="dropdown-item" href="{{ route('hotel.panel') }}">Panel Corporativo</a></li>
                                @endif

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <form action="{{ Auth::guard('hotel')->check() ? route('hotel.logout') : route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endauth

                        @guest
                        <li class="nav-item">
                            <a class="text-light nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="text-light nav-link" href="{{ route('register') }}">Registro</a>
                        </li>
                        @endguest

                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container-fluid p-0"> @yield('content')
    </main>

    <footer class="footer mt-auto py-4 bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5><i class="fas fa-plane"></i> Isla Transfers</h5>
                    <p class="text-light small">Tu servicio de confianza.</p>
                    <p class="small text-muted">
                        <a href="{{ route('hotel.login') }}" class="text-decoration-none text-muted">Acceso Hoteles (Partners)</a>
                    </p>
                </div>
            </div>
            <hr>
            <div class="text-center text-light small">
                &copy; {{ date('Y') }} Isla Transfers.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>