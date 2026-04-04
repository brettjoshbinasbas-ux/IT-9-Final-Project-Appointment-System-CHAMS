@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview of today\'s activity')

@section('content')

    {{-- ── Summary Cards ──────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small opacity-75">Total Clients</p>
                            <h3 class="fw-bold mb-0">{{ $totalClients }}</h3>
                        </div>
                        <i class="bi bi-people fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small opacity-75">Today's Appointments</p>
                            <h3 class="fw-bold mb-0">{{ $todayCount }}</h3>
                        </div>
                        <i class="bi bi-calendar-day fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small opacity-75">Completed</p>
                            <h3 class="fw-bold mb-0">{{ $completedCount }}</h3>
                        </div>
                        <i class="bi bi-check-circle fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small opacity-75">Scheduled</p>
                            <h3 class="fw-bold mb-0">{{ $scheduledCount }}</h3>
                        </div>
                        <i class="bi bi-calendar-plus fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Two Columns ─────────────────────────────────────────────── --}}
    <div class="row g-3">

        {{-- Upcoming Appointments --}}
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-week me-2 text-primary"></i>Upcoming Appointments
                </div>
                <div class="card-body p-0">
                    @forelse($upcomingAppointments as $appt)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <div>
                                <p class="fw-semibold mb-0">{{ $appt->client->full_name }}</p>
                                <small class="text-muted">
                                    {{ $appt->service_type }} —
                                    {{ $appt->appointment_date->format('M d, Y') }}
                                    at {{ $appt->appointment_time }}
                                </small>
                            </div>
                            @statusBadge($appt->status)
                        </div>
                    @empty
                        <p class="text-muted p-3 mb-0">No upcoming appointments.</p>
                    @endforelse
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('appointments.index') }}" class="text-primary small">
                        View all appointments →
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Service Records --}}
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clipboard2-check me-2 text-success"></i>Recent Service Records
                </div>
                <div class="card-body p-0">
                    @forelse($recentRecords as $record)
                        <div class="px-3 py-2 border-bottom">
                            <p class="fw-semibold mb-0">{{ $record->client->full_name }}</p>
                            <small class="text-muted">{{ Str::limit($record->description, 60) }}</small>
                            <br>
                            <small class="text-secondary">{{ $record->service_date->format('M d, Y') }}</small>
                        </div>
                    @empty
                        <p class="text-muted p-3 mb-0">No service records yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection
