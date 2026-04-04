@extends('layouts.app')

@section('title', 'New Appointment')
@section('page-title', 'New Appointment')
@section('page-subtitle', 'Schedule a new appointment')

@section('page-actions')
    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
@endsection

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-plus me-2 text-primary"></i>Appointment Details
                </div>
                <div class="card-body">
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
                                <select name="client_id" class="form-select @error('client_id') is-invalid @enderror">
                                    <option value="">-- Select Client --</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->full_name }} — {{ $client->phone }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Assigned Staff <span
                                        class="text-danger">*</span></label>
                                <select name="staff_id" class="form-select @error('staff_id') is-invalid @enderror">
                                    <option value="">-- Select Staff --</option>
                                    @foreach ($staff as $member)
                                        <option value="{{ $member->id }}"
                                            {{ old('staff_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('staff_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Service Type <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="service_type"
                                    class="form-control @error('service_type') is-invalid @enderror"
                                    value="{{ old('service_type') }}" placeholder="e.g. General Consultation">
                                @error('service_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                                <input type="date" name="appointment_date"
                                    class="form-control @error('appointment_date') is-invalid @enderror"
                                    value="{{ old('appointment_date') }}" min="{{ today()->toDateString() }}">
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Time <span class="text-danger">*</span></label>
                                <input type="time" name="appointment_time"
                                    class="form-control @error('appointment_time') is-invalid @enderror"
                                    value="{{ old('appointment_time') }}">
                                @error('appointment_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="">-- Select Status --</option>
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('status', 'scheduled') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"
                                    placeholder="Optional notes...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-calendar-check me-1"></i>Schedule Appointment
                            </button>
                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
