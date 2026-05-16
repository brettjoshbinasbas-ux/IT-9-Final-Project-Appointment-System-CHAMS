@extends('layouts.app')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')
@section('page-subtitle', $appointment->client->full_name . ' — ' . $appointment->service_type)

@section('page-actions')
    @if (!auth()->user()->isStaff())
        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    @endif
    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
@endsection

@section('content')

    <style>
        /* Shopee-style fulfillment timeline */
        .fulfillment-timeline {
            padding: 20px 0;
        }

        .timeline-step {
            position: relative;
            text-align: center;
            flex: 1;
        }

        .timeline-icon {
            width: 50px;
            height: 50px;
            background: #f0e6f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            transition: all 0.3s;
            position: relative;
            z-index: 2;
        }

        .timeline-step.completed .timeline-icon {
            background: linear-gradient(135deg, #6b8f5e, #4a6b3e);
            color: white;
            box-shadow: 0 4px 10px rgba(107, 143, 94, 0.3);
        }

        .timeline-step.active .timeline-icon {
            background: linear-gradient(135deg, #8b5a8f, #6b3e70);
            color: white;
            box-shadow: 0 4px 10px rgba(139, 90, 143, 0.4);
            transform: scale(1.05);
        }

        .timeline-step.pending .timeline-icon {
            background: #e0d4e3;
            color: #7a6a7e;
        }

        .timeline-step.cancelled .timeline-icon {
            background: #b54a5c;
            color: white;
        }

        .timeline-label {
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .timeline-step.completed .timeline-label {
            color: #6b8f5e;
        }

        .timeline-step.active .timeline-label {
            color: #8b5a8f;
        }

        .timeline-step.pending .timeline-label {
            color: #7a6a7e;
        }

        .timeline-date {
            font-size: 10px;
            color: #999;
            margin-top: 4px;
        }

        /* Connecting line */
        .timeline-connector {
            position: absolute;
            top: 25px;
            left: 50%;
            width: calc(100% - 50px);
            height: 3px;
            background: #e0d4e3;
            z-index: 1;
        }

        .timeline-connector.completed {
            background: linear-gradient(90deg, #6b8f5e, #8b5a8f);
        }

        .timeline-step:last-child .timeline-connector {
            display: none;
        }

        /* Status badge enhancements */
        .status-badge-large {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
        }

        /* Fulfillment status card */
        .fulfillment-card {
            background: linear-gradient(135deg, #faf5fc 0%, #f0e6f2 100%);
            border-radius: 16px;
            padding: 20px;
        }

        .due-countdown {
            font-size: 14px;
            color: #b54a5c;
        }

        .due-countdown.urgent {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }
    </style>

    <div class="row g-3">

        {{-- Fulfillment Status Card (Shopee-style) --}}
        <div class="col-12">
            <div class="fulfillment-card shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-0">
                            <i class="bi bi-truck me-2"></i>Fulfillment Status
                        </h5>
                        <small class="text-muted">Appointment tracking</small>
                    </div>
                    <div>
                        @php
                            $fulfillment = $appointment->fulfillment_status;
                            $badgeClass = match (true) {
                                str_contains($fulfillment, 'Completed') => 'bg-success',
                                str_contains($fulfillment, 'Due Today') => 'bg-warning text-dark',
                                str_contains($fulfillment, 'Overdue') => 'bg-danger',
                                str_contains($fulfillment, 'Cancelled') => 'bg-secondary',
                                default => 'bg-info',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} status-badge-large">
                            <i
                                class="bi {{ str_contains($fulfillment, 'Completed')
                                    ? 'bi-check-circle-fill'
                                    : (str_contains($fulfillment, 'Due Today')
                                        ? 'bi-hourglass-split'
                                        : (str_contains($fulfillment, 'Overdue')
                                            ? 'bi-exclamation-triangle-fill'
                                            : (str_contains($fulfillment, 'Cancelled')
                                                ? 'bi-x-circle-fill'
                                                : 'bi-clock-history'))) }}"></i>
                            {{ $fulfillment }}
                        </span>
                    </div>
                </div>

                {{-- Shopee-style Timeline --}}
                <div class="fulfillment-timeline d-flex justify-content-between position-relative">
                    @php
                        $steps = [
                            'scheduled' => ['icon' => 'bi-calendar-plus', 'label' => 'Scheduled'],
                            'confirmed' => ['icon' => 'bi-check2-circle', 'label' => 'Confirmed'],
                            'completed' => ['icon' => 'bi-truck', 'label' => 'Fulfilled'],
                        ];

                        $currentStep = $appointment->status;
                        $stepKeys = array_keys($steps);
                        $currentIndex = array_search($currentStep, $stepKeys);

                        // Handle cancelled/no_show as separate states
                        $isCancelled = $currentStep === 'cancelled';
                        $isNoShow = $currentStep === 'no_show';
                    @endphp

                    @if ($isCancelled)
                        {{-- Cancelled Timeline --}}
                        <div class="timeline-step cancelled text-center" style="flex: 1;">
                            <div class="timeline-icon">
                                <i class="bi bi-x-lg fs-4"></i>
                            </div>
                            <div class="timeline-label">Cancelled</div>
                            <div class="timeline-date">{{ $appointment->updated_at->format('M d, H:i') }}</div>
                        </div>
                    @elseif($isNoShow)
                        <div class="timeline-step cancelled text-center" style="flex: 1;">
                            <div class="timeline-icon">
                                <i class="bi bi-person-x fs-4"></i>
                            </div>
                            <div class="timeline-label">No Show</div>
                            <div class="timeline-date">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                        </div>
                    @else
                        @foreach ($steps as $stepKey => $step)
                            @php
                                $stepIndex = array_search($stepKey, $stepKeys);
                                $isCompleted = $stepIndex <= $currentIndex;
                                $isActive = $stepKey === $currentStep;
                                $statusClass = $isCompleted ? 'completed' : ($isActive ? 'active' : 'pending');
                            @endphp
                            <div class="timeline-step {{ $statusClass }} text-center" style="flex: 1;">
                                <div class="timeline-icon">
                                    <i class="bi {{ $step['icon'] }} fs-4"></i>
                                </div>
                                <div class="timeline-label">{{ $step['label'] }}</div>
                                @if ($isCompleted)
                                    <div class="timeline-date">
                                        @if ($stepKey === 'scheduled')
                                            {{ $appointment->created_at->format('M d, H:i') }}
                                        @elseif($stepKey === 'confirmed' && $appointment->histories->where('new_status', 'confirmed')->first())
                                            {{ $appointment->histories->where('new_status', 'confirmed')->first()->created_at->format('M d, H:i') }}
                                        @elseif($stepKey === 'completed' && $appointment->histories->where('new_status', 'completed')->first())
                                            {{ $appointment->histories->where('new_status', 'completed')->first()->created_at->format('M d, H:i') }}
                                        @endif
                                    </div>
                                @endif
                                @if (!$loop->last)
                                    <div class="timeline-connector {{ $stepIndex < $currentIndex ? 'completed' : '' }}">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Due Date Countdown (if not completed/cancelled) --}}
                @if (!in_array($appointment->status, ['completed', 'cancelled', 'no_show']))
                    @php
                        $dueDate = $appointment->appointment_date;
                        $daysLeft = now()->diffInDays($dueDate, false);
                        $isUrgent = $daysLeft <= 1 && $daysLeft >= 0;
                    @endphp
                    <div class="mt-3 pt-2 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar-check"></i> Appointment Date
                            </small>
                            <div class="due-countdown {{ $isUrgent ? 'urgent fw-bold text-danger' : '' }}">
                                @if ($dueDate->isToday())
                                    <i class="bi bi-hourglass-split me-1"></i>Due TODAY!
                                @elseif($dueDate->isPast())
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>OVERDUE
                                @else
                                    <i class="bi bi-clock me-1"></i>{{ $daysLeft }} day(s) left
                                @endif
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            @php
                                $totalDays = max(30, $daysLeft + 5);
                                $progressPercent = $dueDate->isPast()
                                    ? 100
                                    : max(0, 100 - ($daysLeft / $totalDays) * 100);
                            @endphp
                            <div class="progress-bar {{ $isUrgent ? 'bg-warning' : ($dueDate->isPast() ? 'bg-danger' : 'bg-success') }}"
                                role="progressbar" style="width: {{ min(100, $progressPercent) }}%"
                                aria-valuenow="{{ min(100, $progressPercent) }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

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
                    <p><strong>Date:</strong>
                        <span class="{{ $appointment->appointment_date->isToday() ? 'text-warning fw-bold' : '' }}">
                            {{ $appointment->appointment_date->format('F d, Y') }}
                            @if ($appointment->appointment_date->isToday())
                                <i class="bi bi-star-fill text-warning"></i>
                            @endif
                        </span>
                    </p>
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
        @if ($appointment->status !== 'completed' && $appointment->status !== 'cancelled' && $appointment->status !== 'no_show')
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
                                        {{ $appointment->status === $value ? 'selected' : '' }}
                                        {{ in_array($value, ['cancelled', 'no_show']) ? 'style=color:#b54a5c' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-info text-white w-100">
                                <i class="bi bi-save me-1"></i>Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @elseif($appointment->status === 'cancelled')
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-x-circle-fill me-2 text-danger"></i>Cancelled
                    </div>
                    <div class="card-body text-center">
                        <i class="bi bi-x-circle-fill text-danger fs-1"></i>
                        <p class="mt-2 mb-0">This appointment has been cancelled.</p>
                    </div>
                </div>
            </div>
        @elseif($appointment->status === 'no_show')
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-person-x me-2 text-secondary"></i>No Show
                    </div>
                    <div class="card-body text-center">
                        <i class="bi bi-person-x text-secondary fs-1"></i>
                        <p class="mt-2 mb-0">Client did not show up.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-check-circle-fill me-2 text-success"></i>Completed
                    </div>
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                        <p class="mt-2 mb-0">Appointment fulfilled!<br>Status locked.</p>
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
                        <a href="{{ route('service-records.index') }}" class="btn btn-sm btn-outline-success w-100">
                            <i class="bi bi-eye me-1"></i>View Details
                        </a>
                    @else
                        <p class="text-muted small">No service record yet.</p>
                        @if ($appointment->status === 'completed')
                            <a href="{{ route('service-records.index') }}" class="btn btn-sm btn-success w-100">
                                <i class="bi bi-plus-circle me-1"></i>Add Record
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- Status History Timeline --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clock-history me-2 text-info"></i>Status History
                </div>
                <div class="card-body">
                    @forelse($appointment->histories as $history)
                        <div class="d-flex mb-3">
                            <div class="me-3 text-center">
                                <i class="bi bi-arrow-repeat text-primary"></i>
                            </div>
                            <div>
                                <p class="mb-0">
                                    <strong>
                                        {{ $history->old_status ? ucfirst($history->old_status) : 'Created' }}
                                        →
                                        {{ ucfirst($history->new_status) }}
                                    </strong>
                                </p>
                                <small class="text-muted">
                                    By {{ $history->changer->name }} •
                                    {{ $history->created_at->format('M d, Y H:i') }}
                                </small>
                                @if ($history->notes)
                                    <p class="small text-muted mb-0">{{ $history->notes }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No status changes recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Auto-submit the status form when dropdown changes
        const statusSelect = document.querySelector('[name="status"]');
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }
    </script>
@endpush
