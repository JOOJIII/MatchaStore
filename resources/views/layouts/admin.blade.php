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
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            overflow-x: hidden;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        #wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, #2e7d32 0%, #1b5e20 100%);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-heading {
            padding: 1.5rem 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-heading h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .sidebar .list-group-flush {
            padding: 1rem 0.5rem;
        }
        
        .sidebar .nav-link {
            color: #fff;
            padding: 0.875rem 1rem;
            margin: 0.25rem 0;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
            text-decoration: none;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        /* Page Content Wrapper */
        #page-content-wrapper {
            flex: 1;
            width: calc(100% - 250px);
            margin-left: 250px;
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Navbar Styles */
        .navbar {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .navbar .btn-outline-success {
            border-color: #2e7d32;
            color: #2e7d32;
        }
        
        .navbar .btn-outline-success:hover {
            background: #2e7d32;
            color: white;
        }
        
        /* Content Wrapper */
        .content-wrapper {
            flex: 1;
            background: #f8f9fc;
            padding: 2rem;
            width: 100%;
        }
        
        /* Toggled State */
        #wrapper.toggled .sidebar {
            margin-left: -250px;
        }
        
        #wrapper.toggled #page-content-wrapper {
            margin-left: 0;
            width: 100%;
        }
        
        /* Stats Cards */
        .stat-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 10px;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 4px solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }
        
        .border-left-danger {
            border-left: 4px solid #e74a3b !important;
        }
        
        .border-left-secondary {
            border-left: 4px solid #858796 !important;
        }
        
        .border-left-dark {
            border-left: 4px solid #5a5c69 !important;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .card-header {
            background: #fff;
            border-bottom: 1px solid #e3e6f0;
            border-radius: 10px 10px 0 0 !important;
        }
        
        /* Table Styles */
        .table {
            color: #5a5c69;
        }
        
        .table thead th {
            border-bottom: 2px solid #e3e6f0;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        /* Badge Styles */
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 600;
            border-radius: 5px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            #page-content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            #wrapper.toggled .sidebar {
                margin-left: 0;
                box-shadow: 5px 0 20px rgba(0,0,0,0.2);
            }
            
            #wrapper.toggled #page-content-wrapper {
                margin-left: 0;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .sidebar {
                width: 220px;
            }
            
            .sidebar-heading h3 {
                font-size: 1.25rem;
            }
            
            .sidebar .nav-link {
                padding: 0.75rem 0.875rem;
                font-size: 0.9rem;
            }
        }
        
        /* Scrollbar Styles */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <h3 class="text-white">🍵 Matcha Store</h3>
                <small class="text-light">Admin Panel</small>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="{{ route('admin.feedbacks') }}" class="nav-link {{ request()->routeIs('admin.feedbacks*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Feedbacks</span>
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
                                <i class="fas fa-user-circle mr-1"></i> 
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('/') }}">
                                    <i class="fas fa-home mr-2"></i>Visit Store
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
            <div class="content-wrapper">
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
        
        // Close sidebar on mobile when clicking outside
        $(document).ready(function() {
            if ($(window).width() <= 768) {
                $(document).click(function(e) {
                    var container = $(".sidebar");
                    var toggleBtn = $("#menu-toggle");
                    
                    if (!container.is(e.target) && container.has(e.target).length === 0 
                        && !toggleBtn.is(e.target) && toggleBtn.has(e.target).length === 0) {
                        if ($("#wrapper").hasClass("toggled")) {
                            $("#wrapper").removeClass("toggled");
                        }
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>