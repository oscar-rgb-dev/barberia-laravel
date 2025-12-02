<!-- resources/views/layouts/barbero.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel del Barbero') - Barbería</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Estilos personalizados (idénticos al Admin) -->
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
                        Barbería
                    </h4>
                    <small class="text-muted">Panel del Barbero</small>
                </div>

                <!-- Menú de Navegación (versión Barberos) -->
                <ul class="nav flex-column">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barbero.dashboard') ? 'active' : '' }}"
                           href="{{ route('barbero.dashboard') }}">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barbero.citas.*') ? 'active' : '' }}"
                           href="{{ route('barbero.citas.index') }}">
                            <i class="fas fa-calendar-alt"></i>
                            Mis Citas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barbero.citas.horarios-disponibles') ? 'active' : '' }}"
                           href="{{ route('barbero.citas.horarios-disponibles') }}">
                            <i class="fas fa-clock"></i>
                            Horarios
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users"></i>
                            Mis Clientes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-bar"></i>
                            Reportes
                        </a>
                    </li>

                    <li class="nav-item mt-4">
                        <a class="nav-link text-warning" href="{{ route('home') }}">
                            <i class="fas fa-arrow-left"></i>
                            Volver al sitio
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content">

            <!-- Navbar superior -->
            <nav class="navbar navbar-expand-lg navbar-admin">
                <div class="container-fluid">

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbar">
                        <ul class="navbar-nav ms-auto">

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i>
                                    {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-user"></i> Mi Perfil
                                        </a>
                                    </li>

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt"></i>
                                                Cerrar Sesión
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
