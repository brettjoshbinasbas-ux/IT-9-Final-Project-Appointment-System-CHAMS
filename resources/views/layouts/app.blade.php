<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Patient Appointment Tracker')</title>
    @vite(['resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    @stack('styles')

    <style>
        body {
            background-color: #f5f0f7;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #2a1a2e 0%, #1a0f1d 100%);
        }

        .sidebar a {
            color: #c9b3d0;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: #ffffff;
            background: linear-gradient(90deg, #8b5a8f 0%, #6b3e70 100%);
        }

        .nav-link {
            border-radius: 6px;
            padding: 10px 16px;
            display: block;
        }

        .badge-today {
            font-size: 0.7rem;
        }

        /* Custom Button Styles */
        .btn-primary {
            background-color: #8b5a8f;
            border-color: #8b5a8f;
        }

        .btn-primary:hover {
            background-color: #6b3e70;
            border-color: #6b3e70;
        }

        .btn-outline-primary {
            color: #8b5a8f;
            border-color: #8b5a8f;
        }

        .btn-outline-primary:hover {
            background-color: #8b5a8f;
            border-color: #8b5a8f;
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
            background-color: #8b5a8f !important;
        }

        .bg-secondary {
            background-color: #7a6a7e !important;
        }

        /* Card Headers */
        .card-header.bg-white {
            background-color: #faf5fc !important;
            border-bottom: 2px solid #d9b8df;
        }

        /* Text Colors */
        .text-primary {
            color: #8b5a8f !important;
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
            background-color: #2a1a2e;
        }

        .table-light {
            background-color: #faf5fc;
        }

        .table-hover tbody tr:hover {
            background-color: #f0e6f2;
        }

        /* Pagination */
        .pagination .page-link {
            color: #8b5a8f;
        }

        .pagination .active .page-link {
            background-color: #8b5a8f;
            border-color: #8b5a8f;
            color: white;
        }
    </style>
</head>

<body>

    <div class="d-flex">

        {{-- ── Sidebar ─────────────────────────────────────────── --}}
        <div class="sidebar col-2 p-3">
            <div class="mb-4 d-flex align-items-center gap-2">
                <img src="{{ asset('images/PAMS logo.png') }}" alt="Logo" style="width: 70px; height: auto;">
                <div>
                    <h6 class="text-white fw-bold fs-5 mb-0">
                        <i class="bi bi-calendar-heart me-2"></i>C.H.A.M.S.
                    </h6>
                    <small class="text-secondary">Clinical Health Appointment<br>Management System</small>
                </div>
            </div>

            <nav class="d-flex flex-column gap-1">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>

                <a href="{{ route('clients.index') }}"
                    class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i>Clients
                </a>

                <a href="{{ route('appointments.index') }}"
                    class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check me-2"></i>Appointments
                    @if ($todayCount > 0)
                        <span class="badge bg-warning text-dark badge-today ms-1">{{ $todayCount }}</span>
                    @endif
                </a>

                <a href="{{ route('service-records.index') }}"
                    class="nav-link {{ request()->routeIs('service-records.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-pulse me-2"></i>Service Records
                </a>
                <a href="{{ route('reports.index') }}"
                    class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up me-2"></i>Reports
                </a>

                {{-- ─── ADMIN ONLY: USERS LINK ─────────────────────────── --}}
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('users.index') }}"
                        class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear me-2"></i>Users
                    </a>
                    <a href="{{ route('clients.trashed') }}"
                        class="nav-link {{ request()->routeIs('clients.trashed') ? 'active' : '' }}">
                        <i class="bi bi-archive me-2"></i>Archive
                    </a>
                @endif
            </nav>

            {{-- User info + logout at bottom --}}
            <div class="mt-auto pt-4 border-top border-secondary">
                <p class="text-secondary small mb-1">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ auth()->user()->name }}
                </p>
                <p class="text-secondary small mb-2">
                    <span class="badge bg-secondary">{{ ucfirst(auth()->user()->role) }}</span>
                </p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Main Content ─────────────────────────────────────── --}}
        <div class="col-10 p-4">

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
