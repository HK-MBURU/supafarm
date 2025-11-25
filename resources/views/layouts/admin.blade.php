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
            --secondary: #E7B216;
            --accent: #358BA2;
            --dark: #2D3748;
            --light: #F7FAFC;
            --gray: #718096;
            --border: #E2E8F0;
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
        }

        /* Layout */
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background: var(--dark);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #4A5568;
            background: var(--primary);
        }

        .sidebar-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .nav-section {
            padding: 15px 0;
            border-bottom: 1px solid #4A5568;
        }

        .nav-section h3 {
            padding: 0 20px 10px;
            font-size: 0.875rem;
            text-transform: uppercase;
            color: var(--secondary);
            font-weight: 500;
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
            transition: none;
            border-left: 3px solid transparent;
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
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 260px;
        }

        /* Header */
        .admin-header {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 0 30px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
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
        }

        /* Content Area */
        .admin-content {
            padding: 30px;
        }

        /* Cards */
        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 0;
            margin-bottom: 20px;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            background: var(--light);
        }

        .card-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
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
            transition: none;
            border-radius: 0;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
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
            font-size: 0.875rem;
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
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            background: white;
            font-size: 1rem;
            border-radius: 0;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border);
            padding: 20px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.875rem;
            text-transform: uppercase;
            font-weight: 500;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            border-radius: 0;
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

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .admin-main {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h1>SupaFarm Admin</h1>
            </div>

            <nav>
                <div class="nav-section">
                    <h3>Dashboard</h3>
                    <ul class="nav-links">
                        <li><a href="{{ route('admin.supafarm') }}"
                                class="{{ request()->routeIs('admin.supafarm') ? 'active' : '' }}">
                                📊 Overview
                            </a></li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h3>Products</h3>
                    <ul class="nav-links">
                        <li><a href="{{ route('admin.categories.index') }}"
                                class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                📁 Categories
                            </a></li>
                        <li><a href="{{ route('admin.products.index') }}"
                                class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                📦 All Products
                            </a></li>

                    </ul>
                </div>

                <div class="nav-section">
                    <h3>Orders</h3>
                    <ul class="nav-links">
                        <li><a href="{{ route('admin.orders.index') }}"
                                class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                                🛒 All Orders
                            </a></li>

                    </ul>
                </div>

                <div class="nav-section">
                    <h3>Content</h3>
                    <ul class="nav-links">
                        <li><a href="{{ route('admin.about.index') }}"
                                class="{{ request()->routeIs('admin.about*') ? 'active' : '' }}">
                                👥 About Page
                            </a></li>
                        <li><a href="{{ route('admin.contacts.index') }}"
                                class="{{ request()->routeIs('admin.contacts*') ? 'active' : '' }}">
                                📞 Contacts
                            </a></li>
                    </ul>
                </div>

                <div class="nav-section">
                    <h3>Account</h3>
                    <ul class="nav-links">
                        <li><a href="{{ route('admin.profile.edit') }}"
                                class="{{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                                👤 Profile
                            </a></li>
                        <li><a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                🚪 Logout
                            </a></li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <h2>@yield('page-title', 'Dashboard')</h2>
                </div>
                <div class="header-right">
                    <div class="user-menu">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span>{{ auth()->user()->name }}</span>
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
        // Simple JavaScript for basic interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add active class to current page links
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-links a');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
