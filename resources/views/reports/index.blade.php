@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports')
@section('page-subtitle', 'Appointment and activity summary')

@section('page-actions')
    <div class="btn-group">
        <a href="{{ route('reports.export-csv') }}" class="btn btn-success m-2 rounded">
            <i class="bi bi-filetype-csv me-1"></i>Export CSV
        </a>
        <a href="{{ route('reports.export-pdf') }}" class="btn btn-danger m-2 rounded">
            <i class="bi bi-filetype-pdf me-1"></i>Export PDF
        </a>
    </div>
@endsection

@section('content')

    <div class="row g-3 mb-4">
        {{-- Summary Cards --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small opacity-75">Completed (All Time)</p>
                            <h3 class="fw-bold mb-0">{{ $completedCount }}</h3>
                        </div>
                        <i class="bi bi-check-circle fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small opacity-75">Completed (This Month)</p>
                            <h3 class="fw-bold mb-0">{{ $completedThisMonth }}</h3>
                        </div>
                        <i class="bi bi-calendar-month fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small opacity-75">Cancelled</p>
                            <h3 class="fw-bold mb-0">{{ $cancelledCount }}</h3>
                        </div>
                        <i class="bi bi-x-circle fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small opacity-75">Today's Appointments</p>
                            <h3 class="fw-bold mb-0">{{ $dailyAppointments->count() }}</h3>
                        </div>
                        <i class="bi bi-calendar-day fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        {{-- Daily Appointments List --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-day me-2 text-primary"></i>Today's Appointments
                </div>
                <div class="card-body p-0">
                    @forelse($dailyAppointments as $appt)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <div>
                                <p class="fw-semibold mb-0">{{ $appt->client->full_name }}</p>
                                <small class="text-muted">
                                    {{ $appt->service_type }} at {{ $appt->appointment_time }}
                                </small>
                            </div>
                            @statusBadge($appt->status)
                        </div>
                    @empty
                        <p class="text-muted p-3 mb-0">No appointments today.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Weekly Summary --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-week me-2 text-primary"></i>Weekly Summary (Last 7 Days)
                </div>
                <div class="card-body">
                    @foreach ($weeklyAppointments as $day)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $day['date'] }}</span>
                            <span class="badge bg-secondary">{{ $day['count'] }} appointments</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Staff Activity --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-person-badge me-2 text-success"></i>Staff Activity
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Staff Name</th>
                                <th>Appointments Handled</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffActivity as $staff)
                                <tr>
                                    <td>{{ $staff->name }}</td>
                                    <td>{{ $staff->assigned_appointments_count }} appointments</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">No staff data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Client Visit Summary --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-people me-2 text-warning"></i>Top Clients (by visits)
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Client Name</th>
                                <th>Total Visits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clientVisits as $client)
                                <tr>
                                    <td>{{ $client->full_name }}</td>
                                    <td>{{ $client->appointments_count }} visits</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">No client data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection
