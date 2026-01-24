<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="GymPOS">
    <meta name="msapplication-TileColor" content="#2563eb">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- PWA Links -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/images/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/images/icon-512x512.png">

    <title>@yield('title', 'Gym & POS System')</title>

    @php
        $gymSettings = App\Models\GymSetting::getSettings();
    @endphp
    @if($gymSettings->gym_favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $gymSettings->gym_favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-light: #3b82f6;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --light-bg: #f8fafc;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--light-bg) 0%, #f1f5f9 100%);
            overflow-x: hidden;
        }

        /* Parallax Background */
        .main-content::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 120%;
            height: 120%;
            background: radial-gradient(circle at 20% 80%, rgba(37, 99, 235, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
            z-index: -1;
            animation: parallaxFloat 20s ease-in-out infinite;
        }

        @keyframes parallaxFloat {
            0%, 100% { transform: translateX(-10px) translateY(-10px) rotate(0deg); }
            50% { transform: translateX(10px) translateY(10px) rotate(1deg); }
        }

        /* Card Animations */
        .card {
            border: 1px solid var(--gray-200);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 0.75rem;
            background: var(--white);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideInUp 0.6s ease-out;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Staggered Animation */
        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }

        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .sidebar {
            min-height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--gray-200);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .sidebar .nav-link {
            color: var(--gray-700);
            padding: 0.75rem 1rem;
            margin: 0.125rem 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.1), transparent);
            transition: left 0.5s;
        }

        .sidebar .nav-link:hover::before {
            left: 100%;
        }

        .sidebar .nav-link:hover {
            color: var(--primary-color);
            background-color: var(--gray-50);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            color: var(--primary-color);
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            transform: translateX(5px);
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .main-content {
            background-color: var(--light-bg);
            min-height: 100vh;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--gray-800) !important;
            font-size: 1.25rem;
        }

        .card {
            border: 1px solid var(--gray-200);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            background: var(--white);
        }

        .card-header {
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            font-weight: 600;
            color: var(--gray-800);
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-light);
            border-color: var(--primary-light);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }

        .table {
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            background: var(--white);
        }

        .table th {
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
            border-color: var(--gray-200);
            font-weight: 600;
            color: var(--gray-700);
            padding: 1rem 0.75rem;
            position: relative;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .table th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
        }

        .table tbody tr {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-bottom: 1px solid var(--gray-100);
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.05) 0%, rgba(16, 185, 129, 0.05) 100%);
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table tbody tr:nth-child(even) {
            background-color: rgba(248, 250, 252, 0.5);
        }

        .table tbody tr:nth-child(even):hover {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, rgba(16, 185, 129, 0.08) 100%);
        }

        .table td {
            padding: 0.875rem 0.75rem;
            vertical-align: middle;
            border-color: var(--gray-100);
        }

        /* Animated Table Loading */
        .table-loading {
            position: relative;
        }

        .table-loading::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: tableShimmer 1.5s infinite;
        }

        @keyframes tableShimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Table Row Entrance Animation */
        .table tbody tr {
            animation: tableRowSlide 0.5s ease-out;
        }

        @keyframes tableRowSlide {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Staggered Row Animation */
        .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.15s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.2s; }
        .table tbody tr:nth-child(4) { animation-delay: 0.25s; }
        .table tbody tr:nth-child(5) { animation-delay: 0.3s; }

        /* Table Responsive Enhancement */
        .table-responsive {
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Mobile Table Styles */
        @media (max-width: 575.98px) {
            .table-responsive {
                border: none;
                box-shadow: none;
            }

            .table-mobile {
                border: none;
            }

            .table-mobile thead {
                display: none;
            }

            .table-mobile tbody tr {
                display: block;
                border: 1px solid var(--gray-200);
                border-radius: 0.5rem;
                margin-bottom: 1rem;
                padding: 1rem;
                background: var(--white);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .table-mobile tbody tr:hover {
                transform: none;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            }

            .table-mobile tbody td {
                display: block;
                text-align: left !important;
                border: none;
                padding: 0.5rem 0;
                position: relative;
                padding-left: 40%;
            }

            .table-mobile tbody td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 35%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 600;
                color: var(--gray-700);
            }

            .table-mobile tbody td:last-child {
                border-bottom: none;
            }

            /* Borderless table mobile styles */
            .table-borderless.table-mobile tbody tr {
                border: none;
                box-shadow: none;
                margin-bottom: 0.5rem;
                padding: 0.5rem;
                background: transparent;
            }

            .table-borderless.table-mobile tbody td {
                padding: 0.25rem 0;
                padding-left: 35%;
            }

            .table-borderless.table-mobile tbody td:before {
                width: 30%;
                font-size: 0.875rem;
            }
        }

        /* Tablet Table Styles */
        @media (min-width: 576px) and (max-width: 991.98px) {
            .table {
                font-size: 0.875rem;
            }

            .table th,
            .table td {
                padding: 0.75rem 0.5rem;
            }

            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Desktop Table Styles */
        @media (min-width: 992px) {
            .table th,
            .table td {
                padding: 1rem 0.75rem;
            }
        }

        /* Card Mobile Responsive */
        @media (max-width: 575.98px) {
            .card {
                margin-bottom: 1rem;
            }

            .card-header {
                padding: 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            .btn-group {
                display: flex;
                flex-direction: column;
                width: 100%;
            }

            .btn-group .btn {
                margin-bottom: 0.25rem;
                border-radius: 0.25rem !important;
            }
        }

        .form-control, .form-select {
            border: 1px solid var(--gray-300);
            border-radius: 0.5rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bg-success {
            background-color: var(--success-color) !important;
        }

        .bg-danger {
            background-color: var(--danger-color) !important;
        }

        .bg-info {
            background-color: var(--info-color) !important;
        }

        .bg-warning {
            background-color: var(--warning-color) !important;
        }

        /* Table Animations */
        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: var(--gray-50);
            transform: scale(1.01);
        }

        /* Badge Animations */
        .badge {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .badge:hover {
            transform: scale(1.1);
        }

        .badge:hover::before {
            left: 100%;
        }

        /* Status Badge Colors */
        .badge.bg-success {
            background: linear-gradient(135deg, var(--success-color), #059669) !important;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, var(--danger-color), #dc2626) !important;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, var(--warning-color), #d97706) !important;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, var(--info-color), #0891b2) !important;
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, var(--primary-color), #1d4ed8) !important;
        }

        .badge.bg-secondary {
            background: linear-gradient(135deg, var(--secondary-color), #475569) !important;
        }

        /* Pagination Styles - Specific to Laravel pagination */
        nav[role="navigation"][aria-label="Pagination Navigation"] {
            margin: 1rem 0;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] svg {
            width: 1rem !important;
            height: 1rem !important;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] img {
            display: none !important;
        }

        /* Show only desktop pagination */
        nav[role="navigation"][aria-label="Pagination Navigation"] .sm\:hidden {
            display: none !important;
        }

        nav[role="navigation"][aria-label="Pagination Navigation"] .hidden.sm\:flex-1 {
            display: flex !important;
        }

        .submenu {
            display: none;
            padding-left: 1rem;
        }

        .submenu.show {
            display: block;
        }

        .nav-link.has-submenu {
            position: relative;
        }

        .nav-link.has-submenu::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1rem;
            transition: transform 0.2s ease;
        }

        .nav-link.has-submenu.expanded::after {
            transform: rotate(180deg);
        }

        .badge-counter {
            background-color: var(--danger-color);
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            margin-left: 0.5rem;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 280px;
                height: 100vh;
                z-index: 1050;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100%;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Mobile Navigation -->
    <nav class="navbar navbar-expand-md navbar-light bg-white d-md-none border-bottom">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            @php
                $gymSettings = App\Models\GymSetting::getSettings();
            @endphp
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-dumbbell"></i> {{ $gymSettings->gym_name }}
            </span>
        </div>
    </nav>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar" id="sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4 d-none d-md-block">
                        <img src="data:image/png;base64,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"
                             style="max-width: 120px; width: 100%; height: auto; object-fit: contain;">
                    </div>

                    <!-- Mobile Header -->
                    <div class="d-md-none p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-dark fw-bold">
                                <i class="fas fa-dumbbell text-primary"></i> {{ $gymSettings->gym_name }}
                            </h5>
                            <button class="btn btn-sm" id="sidebarClose">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <ul class="nav flex-column">
                        @if(auth()->check() && auth()->user()->hasPermission('dashboard'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                               href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('my_members'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pt-members.*') ? 'active' : '' }}"
                               href="{{ route('pt-members.index') }}">
                                <i class="fas fa-users me-2"></i>
                                Member Saya
                            </a>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('members'))
                        <li class="nav-item">
                            <a class="nav-link has-submenu {{ request()->routeIs('members.*') || request()->routeIs('pt-members.*') ? 'active expanded' : '' }}"
                               href="#" onclick="toggleSubmenu(event, 'member-submenu')">
                                <i class="fas fa-users me-2"></i>
                                Member
                            </a>
                            <ul class="nav flex-column submenu {{ request()->routeIs('members.*') || request()->routeIs('pt-members.*') ? 'show' : '' }}" id="member-submenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('members.index') ? 'active' : '' }}"
                                       href="{{ route('members.index') }}">
                                        <i class="fas fa-list me-2"></i>
                                        Daftar Member
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('pt-members.*') ? 'active' : '' }}"
                                       href="{{ route('pt-members.index') }}">
                                        <i class="fas fa-dumbbell me-2"></i>
                                        Member PT
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('members.expired') ? 'active' : '' }}"
                                       href="{{ route('members.expired') }}">
                                        <i class="fas fa-user-clock me-2"></i>
                                        Member Expired
                                        @php
                                            $expiredCount = App\Models\Member::where('status', 'expired')->count();
                                        @endphp
                                        @if($expiredCount > 0)
                                            <span class="badge-counter">{{ $expiredCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('daily-users.*') ? 'active' : '' }}"
                                    href="{{ route('daily-users.index') }}">
                                        <i class="fas fa-calendar-day me-2"></i>
                                        Pengunjung Harian
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('products'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                               href="{{ route('products.index') }}">
                                <i class="fas fa-box me-2"></i>
                                Produk
                            </a>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('pos'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}"
                               href="{{ route('pos.index') }}">
                                <i class="fas fa-cash-register me-2"></i>
                                POS
                            </a>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('pos'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
                               href="{{ route('transactions.index') }}">
                                <i class="fas fa-receipt me-2"></i>
                                Transaksi
                            </a>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('stocks'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('stocks.*') ? 'active' : '' }}"
                               href="{{ route('stocks.index') }}">
                                <i class="fas fa-warehouse me-2"></i>
                                Stok
                                @if(auth()->user()->role === 'staff')
                                    <small class="text-muted">(View Only)</small>
                                @endif
                            </a>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('reports'))
                        <li class="nav-item">
                            <a class="nav-link has-submenu {{ request()->routeIs('reports.*') ? 'active expanded' : '' }}"
                               href="#" onclick="toggleSubmenu(event, 'reports-submenu')">
                                <i class="fas fa-chart-bar me-2"></i>
                                Laporan
                            </a>
                            <ul class="nav flex-column submenu {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="reports-submenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('reports.sales') ? 'active' : '' }}"
                                       href="{{ route('reports.sales') }}">
                                        <i class="fas fa-chart-line me-2"></i>
                                        Laporan Penjualan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('reports.pt-daily') ? 'active' : '' }}"
                                       href="{{ route('reports.pt-daily') }}">
                                        <i class="fas fa-dumbbell me-2"></i>
                                        PT Harian
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('reports.members') ? 'active' : '' }}"
                                       href="{{ route('reports.members') }}">
                                        <i class="fas fa-users me-2"></i>
                                        Laporan Member
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('reports.stocks') ? 'active' : '' }}"
                                       href="{{ route('reports.stocks') }}">
                                        <i class="fas fa-boxes me-2"></i>
                                        Laporan Stok
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('accounting'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('accounting.*') ? 'active' : '' }}"
                               href="{{ route('accounting.index') }}">
                                <i class="fas fa-calculator me-2"></i>
                                Pembukuan
                            </a>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('branches'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}"
                               href="{{ route('branches.index') }}">
                                <i class="fas fa-building me-2"></i>
                                Cabang
                            </a>
                        </li>
                        @endif
                        @if(auth()->check() && auth()->user()->hasPermission('settings'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('personal-trainers.*') ? 'active' : '' }}"
                               href="{{ route('personal-trainers.index') }}">
                                <i class="fas fa-dumbbell me-2"></i>
                                Personal Trainer
                            </a>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('settings'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('packets.*') ? 'active' : '' }}"
                               href="{{ route('packets.index') }}">
                                <i class="fas fa-box me-2"></i>
                                Master Paket
                            </a>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('users'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                               href="{{ route('users.index') }}">
                                <i class="fas fa-users-cog me-2"></i>
                                Kelola User
                            </a>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->hasPermission('settings'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                               href="{{ route('settings.index') }}">
                                <i class="fas fa-cog me-2"></i>
                                Pengaturan
                            </a>
                        </li>
                        @endif


                    </ul>

                    <hr class="text-muted">

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('page-actions')
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- PWA Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function(err) {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }

        // PWA Install Prompt
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Show install button or banner
            const installBanner = document.createElement('div');
            installBanner.innerHTML = `
                <div class="alert alert-info alert-dismissible fade show position-fixed"
                     style="top: 10px; right: 10px; z-index: 9999; max-width: 300px;" role="alert">
                    <i class="fas fa-mobile-alt me-2"></i>
                    <strong>Install App</strong><br>
                    <small>Install aplikasi ini di perangkat Anda untuk akses yang lebih cepat!</small>
                    <button type="button" class="btn btn-sm btn-primary mt-2 w-100" onclick="installPWA()">
                        <i class="fas fa-download me-1"></i> Install
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.body.appendChild(installBanner);
        });

        function installPWA() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    }
                    deferredPrompt = null;
                });
            }
        }
    </script>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            // CSRF Token Auto Refresh
            function refreshCSRFToken() {
                fetch('/csrf-token', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                    // Update all CSRF input fields
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.csrf_token;
                    });
                })
                .catch(error => console.log('CSRF refresh failed:', error));
            }

            // Refresh CSRF token every 30 minutes
            setInterval(refreshCSRFToken, 30 * 60 * 1000);

            // Handle 419 errors globally
            window.addEventListener('unhandledrejection', function(event) {
                if (event.reason && event.reason.status === 419) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Sesi Berakhir',
                        text: 'Sesi Anda telah berakhir. Halaman akan dimuat ulang.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });

            // Handle form submissions with CSRF protection
            document.addEventListener('submit', function(event) {
                const form = event.target;
                if (form.tagName === 'FORM') {
                    const tokenInput = form.querySelector('input[name="_token"]');
                    if (tokenInput) {
                        const currentToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        tokenInput.value = currentToken;
                    }
                }
            });

            // Setup AJAX defaults
            if (window.jQuery) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error: function(xhr) {
                        if (xhr.status === 419) {
                            Swal.fire({
                                title: 'Sesi Berakhir',
                                text: 'Sesi Anda telah berakhir. Halaman akan dimuat ulang.',
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    }
                });
            }

            // Add entrance animation to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Parallax scroll effect
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const parallax = document.querySelector('.main-content::before');
                if (parallax) {
                    const speed = scrolled * 0.5;
                    parallax.style.transform = `translateY(${speed}px)`;
                }
            });

            function showSidebar() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function hideSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', showSidebar);
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', hideSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', hideSidebar);
            }

            // Close sidebar when clicking on nav links in mobile
            const navLinks = sidebar.querySelectorAll('.nav-link:not(.has-submenu)');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        hideSidebar();
                    }
                });
            });
        });

        // Toggle submenu function
        function toggleSubmenu(event, submenuId) {
            event.preventDefault();
            const submenu = document.getElementById(submenuId);
            const link = event.currentTarget;

            if (submenu.classList.contains('show')) {
                submenu.classList.remove('show');
                link.classList.remove('expanded');
            } else {
                // Close other submenus
                document.querySelectorAll('.submenu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
                document.querySelectorAll('.nav-link.has-submenu.expanded').forEach(menuLink => {
                    menuLink.classList.remove('expanded');
                });

                // Open current submenu
                submenu.classList.add('show');
                link.classList.add('expanded');
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
