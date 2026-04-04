@extends('layouts.app')

@section('title', 'Service Records')
@section('page-title', 'Service Records')
@section('page-subtitle', 'History of completed appointment services')

@section('content')

    <div class="row g-3">

        {{-- Add Record Form --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-plus-circle me-2 text-success"></i>Add Service Record
                </div>
                <div class="card-body">
                    <form action="{{ route('service-records.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Appointment <span class="text-danger">*</span></label>
                            <select name="appointment_id" class="form-select @error('appointment_id') is-invalid @enderror">
                                <option value="">-- Select Appointment --</option>
                                @foreach (\App\Models\Appointment::with('client')->byStatus('completed')->doesntHave('serviceRecord')->latest()->get() as $appt)
                                    <option value="{{ $appt->id }}">
                                        #{{ $appt->id }} — {{ $appt->client->full_name }}
                                        ({{ $appt->appointment_date->format('M d') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('appointment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Service Date <span class="text-danger">*</span></label>
                            <input type="date" name="service_date"
                                class="form-control @error('service_date') is-invalid @enderror"
                                value="{{ old('service_date', today()->toDateString()) }}">
                            @error('service_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4"
                                placeholder="Service description...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remarks</label>
                            <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="2"
                                placeholder="Optional remarks...">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-save me-1"></i>Save Record
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Records List --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clipboard2-pulse me-2 text-success"></i>All Service Records
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Staff</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                                <tr>
                                    <td>{{ $record->service_date->format('M d, Y') }}</td>
                                    <td>{{ $record->client->full_name }}</td>
                                    <td>{{ $record->staff->name }}</td>
                                    <td>{{ Str::limit($record->description, 80) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        No service records yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">
                    {{ $records->links() }}
                </div>
            </div>
        </div>

    </div>

@endsection
