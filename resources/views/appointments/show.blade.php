@extends('layouts.app')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')
@section('page-subtitle', $appointment->client->full_name . ' — ' . $appointment->service_type)

@section('page-actions')
    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning me-2">
        <i class="bi bi-pencil me-1"></i>Edit
    </a>
    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
@endsection

@section('content')

    <div class="row g-3">

        {{-- Appointment Info --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-event me-2 text-primary"></i>Appointment Info
                </div>
                <div class="card-body">
                    <p><strong>Client:</strong>
                        <a href="{{ route('clients.show', $appointment->client) }}">
                            {{ $appointment->client->full_name }}
                        </a>
                    </p>
                    <p><strong>Staff:</strong> {{ $appointment->staff->name }}</p>
                    <p><strong>Service:</strong> {{ $appointment->service_type }}</p>
                    <p><strong>Date:</strong> {{ $appointment->appointment_date->format('F d, Y') }}</p>
                    <p><strong>Time:</strong> {{ $appointment->appointment_time }}</p>
                    <p><strong>Status:</strong> @statusBadge($appointment->status)</p>
                    @if ($appointment->notes)
                        <p><strong>Notes:</strong> {{ $appointment->notes }}</p>
                    @endif
                    <p class="text-muted small">
                        Created by {{ $appointment->creator->name }}
                        on {{ $appointment->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Quick Status Update — Only show if not completed --}}
        @if ($appointment->status !== 'completed')
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-arrow-repeat me-2 text-info"></i>Update Status
                    </div>
                    <div class="card-body">
                        <form action="{{ route('appointments.update-status', $appointment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select mb-2">
                                @foreach (\App\Models\Appointment::STATUSES as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ $appointment->status === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-info text-white w-100">
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            {{-- Show a message that status is locked --}}
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-lock-fill me-2 text-secondary"></i>Status Locked
                    </div>
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                        <p class="mt-2 mb-0">This appointment is completed.<br>Status cannot be changed.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Service Record --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clipboard2 me-2 text-success"></i>Service Record
                </div>
                <div class="card-body">
                    @if ($appointment->serviceRecord)
                        <p class="text-success small">
                            <i class="bi bi-check-circle-fill me-1"></i>Record exists
                        </p>
                        <p class="small">{{ Str::limit($appointment->serviceRecord->description, 100) }}</p>
                    @else
                        <p class="text-muted small">No service record yet.</p>
                        @if ($appointment->status === 'completed')
                            <a href="{{ route('service-records.index') }}" class="btn btn-sm btn-success w-100">
                                Add Record
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            {{-- Auto-submit the status form when dropdown changes --}}
            document.querySelector('[name="status"]').addEventListener('change', function() {
                this.closest('form').submit();
            });
        </script>
    @endpush

@endsection
