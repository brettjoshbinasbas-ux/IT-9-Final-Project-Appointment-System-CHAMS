@extends('layouts.app')

@section('title', 'Appointments')
@section('page-title', 'Appointments')
@section('page-subtitle', 'View and manage all appointments')

@section('page-actions')
    @if (!auth()->user()->isStaff())
        <a href="{{ route('appointments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>New Appointment
        </a>
    @endif
@endsection

@section('content')

    {{-- Filters --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('appointments.index') }}" class="d-flex gap-2 flex-wrap">
                <select name="status" class="form-select" style="width: auto;">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" {{ $status === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="date" class="form-control" style="width: auto;" value="{{ $date }}">

                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Clear</a>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Date & Time</th>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Staff</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appt)
                        <tr>
                            <td>
                                <span class="fw-semibold">
                                    {{ $appt->appointment_date->format('M d, Y') }}
                                </span><br>
                                <small class="text-muted">{{ $appt->appointment_time }}</small>
                            </td>
                            <td>
                                @if ($appt->client)
                                    {{ $appt->client->full_name }}
                                @else
                                    <span class="text-muted">Client Deleted</span>
                                @endif
                            </td>
                            <td>{{ $appt->service_type }}</td>
                            <td>{{ $appt->staff->name }}</td>
                            <td>@statusBadge($appt->status)</td>
                            <td>
                                <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if (!auth()->user()->isStaff())
                                    <a href="{{ route('appointments.edit', $appt) }}"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No appointments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $appointments->withQueryString()->links() }}
        </div>
    </div>

@endsection
