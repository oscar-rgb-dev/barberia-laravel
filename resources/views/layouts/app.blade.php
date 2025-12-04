<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Loyalty Barber')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Estilos personalizados -->
    <style>
        .navbar-custom {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            color: #ffc107 !important;
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .nav-link {
            color: #fff !important;
            transition: all 0.3s;
            margin: 0 5px;
            border-radius: 5px;
        }
        
        .nav-link:hover {
            color: #ffc107 !important;
            background: rgba(255, 193, 7, 0.1);
        }
        
        .nav-link.active {
            color: #ffc107 !important;
            background: rgba(255, 193, 7, 0.15);
        }
        
        .dropdown-menu {
            background: #2d2d2d;
            border: none;
        }
        
        .dropdown-item {
            color: #fff;
        }
        
        .dropdown-item:hover {
            background: #ffc107;
            color: #000;
        }
        
        .hero-bg {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
        }
        
        .btn-gold {
            background: linear-gradient(45deg, #ffc107, #ffb300);
            color: #000;
            font-weight: bold;
            border: none;
        }
        
        .btn-gold:hover {
            background: linear-gradient(45deg, #ffb300, #ffa000);
            color: #000;
        }
        
        .service-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
        }
        
        .price-tag {
            background: #ffc107;
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
        
        footer {
            background: #1a1a1a;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navbar Superior -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-cut"></i> BARBERÍA
            </a>
            
            <!-- Botón hamburguesa para móviles -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Menú de Navegación -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <!-- Inicio -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" 
                           href="{{ route('home') }}">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>
                    
                    <!-- Servicios -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('servicios.*') ? 'active' : '' }}" 
                           href="{{ route('servicios.index') }}">
                            <i class="fas fa-concierge-bell"></i> Servicios
                        </a>
                    </li>
                    
                    <!-- Galería -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('productos.catalogo') ? 'active' : '' }}" 
                           href="{{ route('productos.catalogo') }}">
                            <i class="fas fa-shopping-bag"></i> Productos
                        </a>
                    </li>
                    
                
                    <!-- Citas -->
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('citas.index') }}">
                                <i class="fas fa-calendar me-1"></i> Mis Citas
                            </a>
                        </li>
                    @endauth

                    
                </ul>
                
                <!-- Menú del lado derecho -->
                <ul class="navbar-nav ms-auto">
                    @auth
                        <!-- Menú Administrativo para admins -->
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i> Administración
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.empleados.index') }}">
                                        <i class="fas fa-users"></i> Empleados
                                    </a>
                                </li>
                                {{-- Para administradores --}}
                                @admin
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.citas.index') }}">
                                            <i class="fas fa-calendar-alt me-1"></i> Gestión de Citas
                                        </a>
                                    </li>
                                @endadmin
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.servicios.index') }}">
                                        <i class="fas fa-concierge-bell"></i> Servicios
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.productos.index') }}">
                                        <i class="fas fa-box"></i> Productos
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.citas.index') }}">
                                        <i class="fas fa-calendar-alt"></i> Todas las Citas
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.departamentos.index') }}">
                                        <i class="fas fa-building"></i> Departamentos
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.jornadas.index') }}">
                                        <i class="fas fa-clock"></i> Jornadas
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        
                        <!-- Menú para Barberos -->
                        @if(auth()->user()->isBarbero())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-tie"></i> Barbero
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('barbero.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Mi Panel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('barbero.citas.index') }}">
                                        <i class="fas fa-calendar-check"></i> Mis Citas
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        
                        <!-- Menú de Usuario -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user"></i> Mi Perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cog"></i> Configuración
                                    </a>
                                </li>
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
                    @else
                        <!-- Menú para usuarios no logueados -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Registrarse
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-cut"></i> Loyalty Barber</h5>
                    <p class="mb-0">Estilo, tradición y calidad en cada corte.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; {{ date('Y') }} Loyalty Barber. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>