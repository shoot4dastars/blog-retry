<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title')
            @yield('title')
        @else
            {{ $title ?? 'BuddhaBlogs' }}
        @endif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --success: #10b981;
            --info: #06b6d4;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark-bg: #0f172a;
            --card-bg: #ffffff;
            --text-muted: #64748b;
        }

        body {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        }

        .navbar-modern {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 0.8rem 1rem;
        }

        .navbar-brand {
            font-weight: 700;
            background: linear-gradient(135deg, #a5f3fc 0%, #c084fc 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent !important;
        }

        .btn-outline-light {
            border-color: rgba(255,255,255,0.3);
            transition: all 0.2s ease;
        }
        .btn-outline-light:hover {
            background-color: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.5);
        }

        .offcanvas-modern {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border-right: none;
        }
        .offcanvas-modern .offcanvas-header {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .nav-link-modern {
            padding: 0.7rem 1rem;
            margin: 0.2rem 0;
            border-radius: 0.75rem;
            transition: all 0.2s;
            font-weight: 500;
        }
        .nav-link-modern:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: var(--card-bg);
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 30px -12px rgba(0,0,0,0.15);
        }

        .btn {
            border-radius: 2rem;
            padding: 0.4rem 1.2rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-sm {
            border-radius: 1.5rem;
            padding: 0.25rem 0.9rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            transform: scale(1.02);
        }
        .btn-success {
            background: linear-gradient(135deg, var(--success), #059669);
            border: none;
        }
        .btn-warning {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: none;
            color: #1e293b;
        }
        .btn-danger {
            background: linear-gradient(135deg, #f43f5e, var(--danger));
            border: none;
        }
        .btn-outline-secondary {
            border-color: #cbd5e1;
            color: #475569;
        }
        .btn-outline-secondary:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        .badge {
            padding: 0.35em 0.8em;
            font-weight: 500;
            border-radius: 2rem;
        }

        .form-control, .form-select {
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            padding: 0.6rem 1rem;
            transition: 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
        }

        .pagination {
            gap: 0.3rem;
        }
        .page-link {
            border-radius: 2rem;
            border: none;
            color: var(--primary);
            background: white;
            margin: 0 2px;
        }
        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .alert {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-modern sticky-top">
    <div class="container-fluid px-3">

        <div class="d-flex align-items-center">
            @auth
                <button class="btn btn-outline-light me-2 rounded-pill" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                    ☰
                </button>
            @endauth

            <a class="navbar-brand fw-bold fs-4" href="{{ route('posts.index') }}">
                BuddhaBlogs
            </a>
        </div>

        <div class="ms-auto d-flex align-items-center gap-2">
            @guest
                <a href="{{ route('login.form') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    Login
                </a>
                <a href="{{ route('register.form') }}" class="btn btn-info btn-sm rounded-pill px-3" style="background:#06b6d4; border:none;">
                    Register
                </a>
            @endguest

            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger btn-sm rounded-pill px-3">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

@auth
    <div class="offcanvas offcanvas-start offcanvas-modern" tabindex="-1" id="sidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-white">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column h-100">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('posts.my') }}" class="nav-link text-white nav-link-modern">
                        My Posts
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('posts.drafts') }}" class="nav-link text-white nav-link-modern">
                        My Drafts
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('posts.pending') }}" class="nav-link text-white nav-link-modern">
                        Pending
                    </a>
                </li>

                @can('manage-users')
                    <li class="nav-item">
                        <a href="{{ route('admin.users') }}" class="nav-link text-white nav-link-modern">
                            Manage Users
                        </a>
                    </li>
                @endcan

                @can('manage-role-permissions')
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.permissions.index') }}" class="nav-link text-white nav-link-modern">
                            Role Permissions
                        </a>
                    </li>
                @endcan

                @can('manage-user-permissions')
                    <li class="nav-item">
                        <a href="{{ route('admin.users.permissions.index') }}" class="nav-link text-white nav-link-modern">
                            User Permissions
                        </a>
                    </li>
                @endcan
            </ul>

            <div class="mt-auto">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link text-white nav-link-modern">
                            Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endauth

<div class="container my-4 py-2">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Optional Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>
</html>
