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
                        @php
                            $gymSettings = App\Models\GymSetting::getSettings();
                        @endphp
                        <h4 class="text-dark fw-bold">
                            <i class="fas fa-dumbbell text-primary"></i> {{ $gymSettings->gym_name }}
                        </h4>
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
                            <a class="nav-link has-submenu {{ request()->routeIs('members.*') ? 'active expanded' : '' }}" 
                               href="#" onclick="toggleSubmenu(event, 'member-submenu')">
                                <i class="fas fa-users me-2"></i>
                                Member
                            </a>
                            <ul class="nav flex-column submenu {{ request()->routeIs('members.*') ? 'show' : '' }}" id="member-submenu">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('members.index') ? 'active' : '' }}" 
                                       href="{{ route('members.index') }}">
                                        <i class="fas fa-list me-2"></i>
                                        Daftar Member
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
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" 
                               href="{{ route('reports.sales') }}">
                                <i class="fas fa-chart-bar me-2"></i>
                                Laporan
                            </a>
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