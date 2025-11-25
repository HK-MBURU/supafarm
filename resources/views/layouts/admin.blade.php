<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SupaFarm Admin')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Add Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles -->
    <style>
        :root {
            --primary: #BC450D;
            --primary-dark: #9a380a;
            --secondary: #E7B216;
            --accent: #358BA2;
            --dark: #1a202c;
            --dark-light: #2D3748;
            --light: #F7FAFC;
            --gray: #718096;
            --border: #E2E8F0;
            --sidebar-width: 260px;
            --sidebar-collapsed: 70px;
            --header-height: 70px;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Layout */
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--dark);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #4A5568;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: var(--header-height);
        }

        .sidebar-header h1 {
            font-size: 1.25rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
        }

        .nav-section {
            padding: 15px 0;
            border-bottom: 1px solid #4A5568;
        }

        .nav-section h3 {
            padding: 0 20px 10px;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--secondary);
            font-weight: 500;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
        }

        .nav-links {
            list-style: none;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #CBD5E0;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            white-space: nowrap;
            overflow: hidden;
        }

        .nav-links a:hover {
            background: #4A5568;
            color: white;
            border-left-color: var(--secondary);
        }

        .nav-links a.active {
            background: #4A5568;
            color: white;
            border-left-color: var(--primary);
        }

        .nav-links i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .nav-text {
            transition: opacity 0.3s ease;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
        }

        /* Header */
        .admin-header {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 0 30px;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: var(--shadow);
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--dark);
            cursor: pointer;
        }

        .header-left h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .user-menu:hover {
            background: var(--light);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 500;
            color: var(--dark);
        }

        .user-role {
            font-size: 0.875rem;
            color: var(--gray);
        }

        /* Content Area */
        .admin-content {
            padding: 30px;
            min-height: calc(100vh - var(--header-height));
        }

        /* Cards */
        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            background: var(--light);
            border-radius: 12px 12px 0 0 !important;
        }

        .card-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .table th {
            background: var(--light);
            font-weight: 600;
            color: var(--dark);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: 1px solid;
            background: white;
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-secondary {
            background: var(--secondary);
            border-color: var(--secondary);
            color: var(--dark);
        }

        .btn-accent {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.75rem;
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            background: white;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(53, 139, 162, 0.1);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border);
            padding: 24px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
            line-height: 1;
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.875rem;
            text-transform: uppercase;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: #C6F6D5;
            color: #22543D;
        }

        .badge-warning {
            background: #FEFCBF;
            color: #744210;
        }

        .badge-danger {
            background: #FED7D7;
            color: #822727;
        }

        .badge-info {
            background: #BEE3F8;
            color: #1A365D;
        }

        .badge-secondary {
            background: #E2E8F0;
            color: #2D3748;
        }

        /* Mobile Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.mobile-open {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .sidebar-overlay.mobile-open {
                display: block;
            }

            .mobile-toggle {
                display: block;
            }

            .sidebar-toggle {
                display: block;
            }

            .admin-header {
                padding: 0 20px;
            }

            .admin-content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .admin-content {
                padding: 16px;
            }

            .card-body {
                padding: 16px;
            }

            .header-right .user-info {
                display: none;
            }

            .table-responsive {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .admin-header {
                padding: 0 16px;
            }

            .header-left h2 {
                font-size: 1.25rem;
            }

            .admin-content {
                padding: 12px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 0.75rem;
            }
        }

        /* Sidebar collapsed state */
        .sidebar-collapsed .admin-sidebar {
            width: var(--sidebar-collapsed);
        }

        .sidebar-collapsed .admin-main {
            margin-left: var(--sidebar-collapsed);
        }

        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .nav-section h3 {
            opacity: 0;
            visibility: hidden;
        }

        .sidebar-collapsed .sidebar-header h1 {
            opacity: 0;
        }

        /* Scrollbar Styling */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: #2D3748;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: #4A5568;
            border-radius: 3px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }
    </style>
</head>

<body>
    <div class="admin-container" id="adminContainer">
        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <h1>SupaFarm Admin</h1>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav>
                <div class="nav-section">
                    <h3>Dashboard</h3>
                    <ul class="nav-links">
                        <li>
                            <a href="{{ route('admin.supafarm') }}" class="{{ request()->routeIs('admin.supafarm') ? 'active' : '' }}">
                                <i class="fas fa-chart-line"></i>
                                <span class="nav-text">Overview</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h3>Products</h3>
                    <ul class="nav-links">
                        <li>
                            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <i class="fas fa-folder"></i>
                                <span class="nav-text">Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                <i class="fas fa-cube"></i>
                                <span class="nav-text">All Products</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h3>Orders</h3>
                    <ul class="nav-links">
                        <li>
                            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="nav-text">All Orders</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h3>Content</h3>
                    <ul class="nav-links">
                        <li>
                            <a href="{{ route('admin.about.index') }}" class="{{ request()->routeIs('admin.about*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span class="nav-text">About Page</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.contacts.index') }}" class="{{ request()->routeIs('admin.contacts*') ? 'active' : '' }}">
                                <i class="fas fa-phone"></i>
                                <span class="nav-text">Contacts</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h3>Account</h3>
                    <ul class="nav-links">
                        <li>
                            <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                                <i class="fas fa-user"></i>
                                <span class="nav-text">Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="nav-text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="mobile-toggle" id="mobileToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2>@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="header-right">
                    <div class="user-menu">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ auth()->user()->name }}</span>
                            <span class="user-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const adminContainer = document.getElementById('adminContainer');
            const adminSidebar = document.getElementById('adminSidebar');
            const mobileToggle = document.getElementById('mobileToggle');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Mobile sidebar toggle
            function toggleMobileSidebar() {
                adminSidebar.classList.toggle('mobile-open');
                sidebarOverlay.classList.toggle('mobile-open');
                document.body.style.overflow = adminSidebar.classList.contains('mobile-open') ? 'hidden' : '';
            }

            // Desktop sidebar collapse
            function toggleSidebar() {
                adminContainer.classList.toggle('sidebar-collapsed');
                // Save state to localStorage
                const isCollapsed = adminContainer.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            }

            // Event listeners
            mobileToggle.addEventListener('click', toggleMobileSidebar);
            sidebarToggle.addEventListener('click', toggleMobileSidebar);
            sidebarOverlay.addEventListener('click', toggleMobileSidebar);

            // Close sidebar when clicking on a link (mobile)
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 1024) {
                        toggleMobileSidebar();
                    }
                });
            });

            // Load sidebar state from localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                adminContainer.classList.add('sidebar-collapsed');
            }

            // Add active class to current page links
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-links a');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Handle window resize
            function handleResize() {
                if (window.innerWidth > 1024) {
                    adminSidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('mobile-open');
                    document.body.style.overflow = '';
                }
            }

            window.addEventListener('resize', handleResize);

            // Add smooth transitions after page load
            setTimeout(() => {
                document.body.style.transition = 'all 0.3s ease';
            }, 100);
        });
    </script>

    @stack('scripts')
</body>

</html>
