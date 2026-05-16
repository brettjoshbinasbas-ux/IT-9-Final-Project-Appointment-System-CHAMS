<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', ($appSettings['app_name'] ?? 'CHAMS') . ' - Clinical Health Appointment System')</title>
    @vite(['resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --primary-color: {{ $appSettings['primary_color'] ?? '#8b5a8f' }};
            --primary-dark: {{ $appSettings['primary_color'] ?? '#8b5a8f' }};
            --sidebar-color: {{ $appSettings['sidebar_color'] ?? '#2a1a2e' }};
            --sidebar-dark: {{ $appSettings['sidebar_color'] ?? '#1a0f1d' }};
        }

        body {
            background-color: #f5f0f7;
            overflow: hidden;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Fixed sidebar wrapper - USING DYNAMIC COLOR */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(135deg, var(--sidebar-color) 0%, var(--sidebar-dark) 100%);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            z-index: 1000;
        }

        /* Scrollable nav area */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0 12px 20px 12px;
        }

        /* Custom scrollbar styling for sidebar */
        .sidebar-nav::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: #3a2a3e;
            border-radius: 10px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: color-mix(in srgb, var(--primary-color) 70%, white);
        }

        /* Main content wrapper */
        .main-content {
            margin-left: 280px;
            width: calc(100% - 280px);
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 24px;
            position: relative;
        }

        /* Custom scrollbar styling for main content */
        .main-content::-webkit-scrollbar {
            width: 8px;
        }

        .main-content::-webkit-scrollbar-track {
            background: #e0d4e3;
            border-radius: 10px;
        }

        .main-content::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        .main-content::-webkit-scrollbar-thumb:hover {
            background: color-mix(in srgb, var(--primary-color) 70%, black);
        }

        /* Sidebar links */
        .sidebar a {
            color: #c9b3d0;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: #ffffff;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .nav-link {
            border-radius: 6px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link i {
            width: 22px;
            font-size: 1.1rem;
        }

        .nav-group {
            margin-bottom: 20px;
        }

        .nav-group-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #7a6a7e;
            padding: 8px 16px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .badge-today {
            font-size: 0.7rem;
            margin-left: auto;
        }

        /* Logo section */
        .sidebar-logo {
            padding: 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 16px;
        }

        /* User section */
        .sidebar-user {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.2);
        }

        /* Button Styles - USING DYNAMIC COLOR */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: color-mix(in srgb, var(--primary-color) 80%, black);
            border-color: color-mix(in srgb, var(--primary-color) 80%, black);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-warning {
            background-color: #c9a53b;
            border-color: #c9a53b;
            color: #2a1a2e;
        }

        .btn-warning:hover {
            background-color: #b8942e;
            border-color: #b8942e;
        }

        .btn-info {
            background-color: #5f9ea0;
            border-color: #5f9ea0;
            color: white;
        }

        .btn-info:hover {
            background-color: #4a7d7f;
            border-color: #4a7d7f;
        }

        /* Status Badge Colors */
        .bg-primary {
            background-color: #5f9ea0 !important;
        }

        .bg-success {
            background-color: #6b8f5e !important;
        }

        .bg-danger {
            background-color: #b54a5c !important;
        }

        .bg-warning {
            background-color: #c9a53b !important;
            color: #2a1a2e !important;
        }

        .bg-info {
            background-color: var(--primary-color) !important;
        }

        .bg-secondary {
            background-color: #7a6a7e !important;
        }

        /* Card Headers */
        .card-header.bg-white {
            background-color: #faf5fc !important;
            border-bottom: 2px solid var(--primary-color);
        }

        /* Text Colors */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-success {
            color: #6b8f5e !important;
        }

        .text-danger {
            color: #b54a5c !important;
        }

        .text-warning {
            color: #c9a53b !important;
        }

        .text-info {
            color: #5f9ea0 !important;
        }

        /* Table Header */
        .table-dark {
            background-color: var(--sidebar-color);
        }

        .table-light {
            background-color: #faf5fc;
        }

        .table-hover tbody tr:hover {
            background-color: #f0e6f2;
        }

        /* Pagination */
        .pagination .page-link {
            color: var(--primary-color);
        }

        .pagination .active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
    </style>
</head>

<body>

    <div class="d-flex">

        {{-- ── Sidebar (fixed) ─────────────────────────────────────────── --}}
        <div class="sidebar">
            <!-- Logo Section - Fixed Top -->
            <div class="sidebar-logo">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/PAMS logo.png') }}" alt="Logo" style="width: 60px; height: auto;">
                    <div>
                        <h6 class="text-white fw-bold fs-5 mb-0">
                            <i class="bi bi-calendar-heart me-2"></i>{{ $appSettings['app_name'] ?? 'C.H.A.M.S.' }}
                        </h6>
                        <small
                            class="text-secondary">{{ $appSettings['company_name'] ?? 'Clinical Health Appointment Management System' }}</small>
                    </div>
                </div>
            </div>

            <!-- Scrollable Navigation Area -->
            <div class="sidebar-nav">
                <!-- MAIN NAVIGATION -->
                <div class="nav-group">
                    <div class="nav-group-title">
                        <i class="bi bi-grid"></i> MAIN
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- CLIENT MANAGEMENT -->
                <div class="nav-group">
                    <div class="nav-group-title">
                        <i class="bi bi-people"></i> CLIENT MANAGEMENT
                    </div>
                    <a href="{{ route('clients.index') }}"
                        class="nav-link {{ request()->routeIs('clients.*') && !request()->routeIs('clients.trashed') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i>
                        <span>All Clients</span>
                    </a>
                    @if (!auth()->user()->isStaff())
                        <a href="{{ route('clients.create') }}"
                            class="nav-link {{ request()->routeIs('clients.create') ? 'active' : '' }}">
                            <i class="bi bi-person-plus"></i>
                            <span>Add Client</span>
                        </a>
                    @endif
                </div>

                <!-- APPOINTMENT MANAGEMENT -->
                <div class="nav-group">
                    <div class="nav-group-title">
                        <i class="bi bi-calendar-week"></i> APPOINTMENTS
                    </div>
                    <a href="{{ route('appointments.index') }}"
                        class="nav-link {{ request()->routeIs('appointments.index') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        <span>All Appointments</span>
                        @if ($todayCount > 0)
                            <span class="badge bg-warning text-dark badge-today ms-auto">{{ $todayCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('appointments.kanban') }}"
                        class="nav-link {{ request()->routeIs('appointments.kanban') ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                        <span>Kanban Board</span>
                    </a>
                    @if (!auth()->user()->isStaff())
                        <a href="{{ route('appointments.create') }}"
                            class="nav-link {{ request()->routeIs('appointments.create') ? 'active' : '' }}">
                            <i class="bi bi-calendar-plus"></i>
                            <span>New Appointment</span>
                        </a>
                    @endif
                </div>

                <!-- SERVICE RECORDS -->
                <div class="nav-group">
                    <div class="nav-group-title">
                        <i class="bi bi-clipboard2-pulse"></i> SERVICE RECORDS
                    </div>
                    <a href="{{ route('service-records.index') }}"
                        class="nav-link {{ request()->routeIs('service-records.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2-check"></i>
                        <span>Service History</span>
                    </a>
                </div>

                <!-- REPORTS & ANALYTICS -->
                <div class="nav-group">
                    <div class="nav-group-title">
                        <i class="bi bi-graph-up"></i> REPORTS
                    </div>
                    <a href="{{ route('reports.index') }}"
                        class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i>
                        <span>Reports Dashboard</span>
                    </a>
                </div>

                <!-- ADMIN SECTION (visible only to admins) -->
                @if (auth()->user()->isAdmin())
                    <div class="nav-group">
                        <div class="nav-group-title">
                            <i class="bi bi-shield-lock"></i> ADMINISTRATION
                        </div>
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                            <i class="bi bi-person-gear"></i>
                            <span>User Management</span>
                        </a>
                        <a href="{{ route('users.trashed') }}"
                            class="nav-link {{ request()->routeIs('users.trashed') ? 'active' : '' }}">
                            <i class="bi bi-person-x"></i>
                            <span>Deactivated Users</span>
                        </a>
                        <a href="{{ route('clients.trashed') }}"
                            class="nav-link {{ request()->routeIs('clients.trashed') ? 'active' : '' }}">
                            <i class="bi bi-archive"></i>
                            <span>Archive / Deleted</span>
                        </a>
                        {{-- <a href="{{ route('admin.settings.index') }}"
                            class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="bi bi-gear-fill"></i>
                            <span>System Settings</span>
                        </a> --}}
                    </div>
                @endif
            </div>

            {{-- User info + logout at bottom (fixed) --}}
            <div class="sidebar-user">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-person-circle text-secondary fs-4"></i>
                    <div class="flex-grow-1">
                        <p class="text-white mb-0 small fw-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-secondary small mb-0">
                            <span class="badge bg-secondary">{{ ucfirst(auth()->user()->role) }}</span>
                        </p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Main Content (offset) ─────────────────────────────────────── --}}
        <div class="main-content">
            {{-- Page header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-0">@yield('page-title', 'Dashboard')</h4>
                    <small class="text-muted">@yield('page-subtitle', '')</small>
                </div>
                <div>@yield('page-actions')</div>
            </div>

            {{-- Flash messages --}}
            @include('partials.flash')

            {{-- Page content --}}
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
