<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Administrativo - Barbería')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 193, 7, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: #ffc107;
            background: rgba(255, 193, 7, 0.15);
            border-left: 4px solid #ffc107;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        .navbar-admin {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .admin-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        
        .admin-card:hover {
            transform: translateY(-2px);
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stats-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stats-card.success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .stats-card.info {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar d-md-block">
                <div class="position-sticky pt-3">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <h4 class="text-warning">
                            <i class="fas fa-cut"></i>
                            Barbería Admin
                        </h4>
                        <small class="text-muted">Panel de Control</small>
                    </div>

                    <!-- Menú de Navegación -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <!-- Gestión de Servicios -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('servicios.*') ? 'active' : '' }}" 
                               href="{{ route('servicios.index') }}">
                                <i class="fas fa-concierge-bell"></i>
                                Servicios
                            </a>
                        </li>
                        
                        <!-- Gestión de Empleados -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('empleados.*') ? 'active' : '' }}" 
                               href="{{ route('empleados.index') }}">
                                <i class="fas fa-users"></i>
                                Empleados
                            </a>
                        </li>
                        
                        <!-- Gestión de Citas -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('citas.*') ? 'active' : '' }}" 
                               href="{{ route('citas.index') }}">
                                <i class="fas fa-calendar-alt"></i>
                                Citas
                            </a>
                        </li>
                        
                        <!-- Configuración -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('departamentos.*') || request()->routeIs('jornadas.*') ? 'active' : '' }}" 
                               href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i>
                                Configuración
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('departamentos.*') ? 'active' : '' }}" 
                                       href="{{ route('departamentos.index') }}">
                                        <i class="fas fa-building"></i> Departamentos
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('jornadas.*') ? 'active' : '' }}" 
                                       href="{{ route('jornadas.index') }}">
                                        <i class="fas fa-clock"></i> Jornadas
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="nav-item mt-4">
                            <a class="nav-link text-warning" href="{{ route('home') }}">
                                <i class="fas fa-arrow-left"></i>
                                Volver al Sitio
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Navbar Superior -->
                <nav class="navbar navbar-expand-lg navbar-admin">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <div class="collapse navbar-collapse" id="adminNavbar">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                        <i class="fas fa-user-circle"></i>
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">
                                            <i class="fas fa-user"></i> Mi Perfil
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Contenido Principal -->
                <main class="p-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personalizados -->
    <script>
        // Activar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    
    @stack('scripts')
</body>
</html>