<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('avion.ico') }}" type="image/x-icon">
    {{-- Título dinámico --}}
    <title>@yield('title', 'Panel Admin - Isla Transfers')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Yield para estilos específicos --}}
    @yield('styles')

    <style>
        body { background-color: #f8f9fa; }
        #wrapper { display: flex; width: 100%; align-items: stretch; min-height: 100vh; }
        #sidebar-wrapper { min-width: 250px; max-width: 250px; background-color: #343a40; color: #fff; transition: all 0.3s; }
        #sidebar-wrapper .sidebar-heading { padding: 1.5rem; font-size: 1.25rem; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.1); }
        #sidebar-wrapper .list-group-item { padding: 1rem 1.5rem; background-color: #343a40; color: rgba(255,255,255,0.7); border: none; }
        #sidebar-wrapper .list-group-item:hover { background-color: #212529; color: #fff; text-decoration: none; }
        #sidebar-wrapper .list-group-item.active { background-color: #0d6efd; color: #fff; }
        #page-content-wrapper { width: 100%; overflow-x: hidden; }
        .avatar-circle { width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        
        /* Ajuste para móviles */
        #wrapper.toggled #sidebar-wrapper { margin-left: -250px; }
    </style>
</head>
<body>

    <div id="wrapper">
        {{-- BARRA LATERAL --}}
        <div id="sidebar-wrapper">
            <div class="sidebar-heading text-white">
                <i class="fas fa-plane-departure me-2"></i> Isla Admin
            </div>
            <div class="list-group list-group-flush mt-3">
                
                {{-- 1. DASHBOARD (INFORMACIÓN) --}}
                <a href="{{ route('admin.dashboard') }}" class="list-group-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2 w-25"></i> Información
                </a>
                
                {{-- 2. CALENDARIO --}}
                <a href="{{ route('admin.calendar') }}" class="list-group-item {{ request()->routeIs('admin.calendar*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt me-2 w-25"></i> Calendario
                </a>
                
                {{-- 3. VIAJEROS --}}
                <a href="{{ route('admin.viajeros.index') }}" class="list-group-item {{ request()->routeIs('admin.viajeros*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2 w-25"></i> Viajeros
                </a>

                {{-- 4. HOTELES --}}
                <a href="{{ route('admin.hoteles.index') }}" class="list-group-item {{ request()->routeIs('admin.hoteles*') ? 'active' : '' }}">
                    <i class="fas fa-hotel me-2 w-25"></i> Hoteles
                </a>
                
                {{-- 5. RESERVAS --}}
                <a href="{{ route('admin.reservas.index') }}" class="list-group-item {{ request()->routeIs('admin.reservas*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check me-2 w-25"></i> Reservas
                </a>

                <div class="mt-auto p-3">
                    <a href="{{ url('/') }}" class="btn btn-outline-light w-100 btn-sm mb-2">
                        <i class="fas fa-globe me-2"></i> Ir a la Web
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 btn-sm">Cerrar Sesión</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- CONTENIDO DE PÁGINA --}}
        <div id="page-content-wrapper">
            {{-- BARRA SUPERIOR (NAVBAR) --}}
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary border-bottom shadow-sm px-4 py-3">
                
                {{-- Botón menú (Hamburguesa) --}}
                <button class="btn btn-link text-white" id="menu-toggle"><i class="fas fa-bars"></i></button>
                
                {{-- Lado Derecho: Menú de Usuario Desplegable --}}
                <div class="ms-auto">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-white fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                
                                {{-- LOGICA PHP PARA OBTENER EL NOMBRE --}}
                                @php
                                    $user = auth()->user(); // Obtenemos el usuario logueado
                                    // Tu lógica original:
                                    $displayName = $user->nombre ?? $user->usuario ?? 'Administrador'; 
                                    $firstLetter = strtoupper(substr($displayName, 0, 1));
                                @endphp

                                {{-- Texto: Hola, [Nombre Dinámico] --}}
                                <span class="me-2">Hola, {{ $displayName }}</span>
                                
                                {{-- Avatar: Círculo blanco con letra dinámica --}}
                                <div class="avatar-circle bg-white text-primary fw-bold">
                                    {{ $firstLetter }}
                                </div>
                            </a>

                            {{-- Menú Desplegable --}}
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                                
                                {{-- Opción 1: Ir al Perfil --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('usuario.perfil') }}">
                                        <i class="fas fa-user-cog me-2 text-secondary"></i> Modificar Perfil
                                    </a>
                                </li>
                                
                                <li><hr class="dropdown-divider"></li>

                                {{-- Opción 2: Cerrar Sesión --}}
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="container-fluid px-4 py-4">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById("menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("wrapper").classList.toggle("toggled");
            
            var sidebar = document.getElementById("sidebar-wrapper");
            if (sidebar.style.marginLeft === "-250px") {
                sidebar.style.marginLeft = "0";
            } else {
                sidebar.style.marginLeft = "-250px";
            }
        });
    </script>

    @yield('scripts')
</body>
</html>