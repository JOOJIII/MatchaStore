<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Matcha Store</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Admin Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2e7d32 0%, #1b5e20 100%);
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 1rem;
            margin: 0.2rem 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        .navbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .content-wrapper {
            background: #f8f9fc;
            min-height: calc(100vh - 56px);
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4">
                <h3 class="text-white">🍵 Matcha Store</h3>
                <small class="text-light">Admin Panel</small>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box mr-2"></i>Products
                </a>
                <!-- PERBAIKAN DI SINI: admin.orders bukan admin.orders.index -->
                <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart mr-2"></i>Orders
                </a>
                <!-- TAMBAHKAN ROUTE UNTUK USERS JIKA DIBUTUHKAN -->
                <!-- <a href="{{ route('admin.users.index') }}" class="nav-link">
                    <i class="fas fa-users mr-2"></i>Users
                </a> -->
                <a href="{{ route('admin.feedbacks') }}" class="nav-link {{ request()->routeIs('admin.feedbacks*') ? 'active' : '' }}">
                    <i class="fas fa-comments mr-2"></i>Feedbacks
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <button class="btn btn-outline-success" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" 
                               role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle mr-1"></i> {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('/profile') }}">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Content -->
            <div class="content-wrapper p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // Toggle sidebar
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
    
    @stack('scripts')
</body>
</html>