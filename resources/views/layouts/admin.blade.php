<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('avion.ico') }}" type="image/x-icon">
    <title>Panel Admin - Isla Transfers</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
    </style>
</head>
<body>

    <div id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading text-white">
                <i class="fas fa-plane-departure me-2"></i> Isla Admin
            </div>
            <div class="list-group list-group-flush mt-3">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2 w-25"></i> Dashboard
                </a>
                
                <a href="{{ route('admin.viajeros.index') }}" class="list-group-item {{ request()->routeIs('admin.viajeros*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2 w-25"></i> Viajeros
                </a>

                <a href="{{ route('admin.hoteles.index') }}" class="list-group-item {{ request()->routeIs('admin.hoteles*') ? 'active' : '' }}">
                    <i class="fas fa-hotel me-2 w-25"></i> Hoteles
                </a>
                
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

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm px-4 py-3">
                <button class="btn btn-light" id="menu-toggle"><i class="fas fa-bars"></i></button>
                
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-2 small text-muted">Administrador</span>
                    <div class="avatar-circle bg-dark text-white">A</div>
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
</body>
</html>