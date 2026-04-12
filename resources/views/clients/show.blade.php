@extends('layouts.app')

@section('title', $client->full_name)
@section('page-title', $client->full_name)
@section('page-subtitle', 'Client Profile')

@section('page-actions')
    @if (!auth()->user()->isStaff())
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    @endif
    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
@endsection

@section('content')

    <div class="row g-3">

        {{-- Client Info Card --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-person-circle me-2 text-primary"></i>Contact Details
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $client->full_name }}</p>
                    <p><strong>Email:</strong> {{ $client->email ?? '—' }}</p>
                    <p><strong>Phone:</strong> {{ $client->phone }}</p>
                    <p><strong>Address:</strong> {{ $client->address ?? '—' }}</p>
                    @if ($client->notes)
                        <p><strong>Notes:</strong> {{ $client->notes }}</p>
                    @endif
                    <p class="text-muted small">
                        Registered: {{ $client->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Appointments History --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-check me-2 text-primary"></i>
                    Appointment History ({{ $client->appointments->count() }})
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Service</th>
                                <th>Staff</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($client->appointments as $appt)
                                <tr>
                                    <td>{{ $appt->appointment_date->format('M d, Y') }}</td>
                                    <td>{{ $appt->service_type }}</td>
                                    <td>{{ $appt->staff->name }}</td>
                                    <td>@statusBadge($appt->status)</td>
                                    <td>
                                        <a href="{{ route('appointments.show', $appt) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        No appointments yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection
