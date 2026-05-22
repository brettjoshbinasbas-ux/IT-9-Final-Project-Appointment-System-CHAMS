<?php $__env->startSection('title', 'Appointment Details'); ?>
<?php $__env->startSection('page-title', 'Appointment Details'); ?>
<?php $__env->startSection('page-subtitle', $appointment->client->full_name . ' — ' . $appointment->service_type); ?>

<?php $__env->startSection('page-actions'); ?>
    <?php if(!auth()->user()->isStaff()): ?>
        <a href="<?php echo e(route('appointments.edit', $appointment)); ?>" class="btn btn-warning me-2">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
    <?php endif; ?>
    <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

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
                        <?php
                            $fulfillment = $appointment->fulfillment_status;
                            $badgeClass = match (true) {
                                str_contains($fulfillment, 'Completed') => 'bg-success',
                                str_contains($fulfillment, 'Due Today') => 'bg-warning text-dark',
                                str_contains($fulfillment, 'Overdue') => 'bg-danger',
                                str_contains($fulfillment, 'Cancelled') => 'bg-secondary',
                                default => 'bg-info',
                            };
                        ?>
                        <span class="badge <?php echo e($badgeClass); ?> status-badge-large">
                            <i
                                class="bi <?php echo e(str_contains($fulfillment, 'Completed')
                                    ? 'bi-check-circle-fill'
                                    : (str_contains($fulfillment, 'Due Today')
                                        ? 'bi-hourglass-split'
                                        : (str_contains($fulfillment, 'Overdue')
                                            ? 'bi-exclamation-triangle-fill'
                                            : (str_contains($fulfillment, 'Cancelled')
                                                ? 'bi-x-circle-fill'
                                                : 'bi-clock-history')))); ?>"></i>
                            <?php echo e($fulfillment); ?>

                        </span>
                    </div>
                </div>

                
                <div class="fulfillment-timeline d-flex justify-content-between position-relative">
                    <?php
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
                    ?>

                    <?php if($isCancelled): ?>
                        
                        <div class="timeline-step cancelled text-center" style="flex: 1;">
                            <div class="timeline-icon">
                                <i class="bi bi-x-lg fs-4"></i>
                            </div>
                            <div class="timeline-label">Cancelled</div>
                            <div class="timeline-date"><?php echo e($appointment->updated_at->format('M d, H:i')); ?></div>
                        </div>
                    <?php elseif($isNoShow): ?>
                        <div class="timeline-step cancelled text-center" style="flex: 1;">
                            <div class="timeline-icon">
                                <i class="bi bi-person-x fs-4"></i>
                            </div>
                            <div class="timeline-label">No Show</div>
                            <div class="timeline-date"><?php echo e($appointment->appointment_date->format('M d, Y')); ?></div>
                        </div>
                    <?php else: ?>
                        <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stepKey => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $stepIndex = array_search($stepKey, $stepKeys);
                                $isCompleted = $stepIndex <= $currentIndex;
                                $isActive = $stepKey === $currentStep;
                                $statusClass = $isCompleted ? 'completed' : ($isActive ? 'active' : 'pending');
                            ?>
                            <div class="timeline-step <?php echo e($statusClass); ?> text-center" style="flex: 1;">
                                <div class="timeline-icon">
                                    <i class="bi <?php echo e($step['icon']); ?> fs-4"></i>
                                </div>
                                <div class="timeline-label"><?php echo e($step['label']); ?></div>
                                <?php if($isCompleted): ?>
                                    <div class="timeline-date">
                                        <?php if($stepKey === 'scheduled'): ?>
                                            <?php echo e($appointment->created_at->format('M d, H:i')); ?>

                                        <?php elseif($stepKey === 'confirmed' && $appointment->histories->where('new_status', 'confirmed')->first()): ?>
                                            <?php echo e($appointment->histories->where('new_status', 'confirmed')->first()->created_at->format('M d, H:i')); ?>

                                        <?php elseif($stepKey === 'completed' && $appointment->histories->where('new_status', 'completed')->first()): ?>
                                            <?php echo e($appointment->histories->where('new_status', 'completed')->first()->created_at->format('M d, H:i')); ?>

                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if(!$loop->last): ?>
                                    <div class="timeline-connector <?php echo e($stepIndex < $currentIndex ? 'completed' : ''); ?>">
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>

                
                <?php if(!in_array($appointment->status, ['completed', 'cancelled', 'no_show'])): ?>
                    <?php
                        $dueDate = $appointment->appointment_date;
                        $daysLeft = now()->diffInDays($dueDate, false);
                        $isUrgent = $daysLeft <= 1 && $daysLeft >= 0;
                    ?>
                    <div class="mt-3 pt-2 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar-check"></i> Appointment Date
                            </small>
                            <div class="due-countdown <?php echo e($isUrgent ? 'urgent fw-bold text-danger' : ''); ?>">
                                <?php if($dueDate->isToday()): ?>
                                    <i class="bi bi-hourglass-split me-1"></i>Due TODAY!
                                <?php elseif($dueDate->isPast()): ?>
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>OVERDUE
                                <?php else: ?>
                                    <i class="bi bi-clock me-1"></i><?php echo e($daysLeft); ?> day(s) left
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 6px;">
                            <?php
                                $totalDays = max(30, $daysLeft + 5);
                                $progressPercent = $dueDate->isPast()
                                    ? 100
                                    : max(0, 100 - ($daysLeft / $totalDays) * 100);
                            ?>
                            <div class="progress-bar <?php echo e($isUrgent ? 'bg-warning' : ($dueDate->isPast() ? 'bg-danger' : 'bg-success')); ?>"
                                role="progressbar" style="width: <?php echo e(min(100, $progressPercent)); ?>%"
                                aria-valuenow="<?php echo e(min(100, $progressPercent)); ?>" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-event me-2 text-primary"></i>Appointment Info
                </div>
                <div class="card-body">
                    <p><strong>Client:</strong>
                        <a href="<?php echo e(route('clients.show', $appointment->client)); ?>">
                            <?php echo e($appointment->client->full_name); ?>

                        </a>
                    </p>
                    <p><strong>Staff:</strong> <?php echo e($appointment->staff->name); ?></p>
                    <p><strong>Service:</strong> <?php echo e($appointment->service_type); ?></p>
                    <p><strong>Date:</strong>
                        <span class="<?php echo e($appointment->appointment_date->isToday() ? 'text-warning fw-bold' : ''); ?>">
                            <?php echo e($appointment->appointment_date->format('F d, Y')); ?>

                            <?php if($appointment->appointment_date->isToday()): ?>
                                <i class="bi bi-star-fill text-warning"></i>
                            <?php endif; ?>
                        </span>
                    </p>
                    <p><strong>Time:</strong> <?php echo e($appointment->appointment_time); ?></p>
                    <p><strong>Status:</strong> <?php
                $colors = [
                    'scheduled'  => 'primary',
                    'confirmed'  => 'info',
                    'completed'  => 'success',
                    'cancelled'  => 'danger',
                    'no_show'    => 'secondary',
                ];
                $label = \App\Models\Appointment::STATUSES[$appointment->status] ?? ucfirst($appointment->status);
                $color = $colors[$appointment->status] ?? 'dark';
                echo '<span class="badge bg-' . $color . '">' . $label . '</span>';
            ?></p>
                    <?php if($appointment->notes): ?>
                        <p><strong>Notes:</strong> <?php echo e($appointment->notes); ?></p>
                    <?php endif; ?>
                    <p class="text-muted small">
                        Created by <?php echo e($appointment->creator->name); ?>

                        on <?php echo e($appointment->created_at->format('M d, Y')); ?>

                    </p>
                </div>
            </div>
        </div>

        
        <?php if($appointment->status !== 'completed' && $appointment->status !== 'cancelled' && $appointment->status !== 'no_show'): ?>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-arrow-repeat me-2 text-info"></i>Update Status
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('appointments.update-status', $appointment)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <select name="status" class="form-select mb-2">
                                <?php $__currentLoopData = \App\Models\Appointment::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>"
                                        <?php echo e($appointment->status === $value ? 'selected' : ''); ?>

                                        <?php echo e(in_array($value, ['cancelled', 'no_show']) ? 'style=color:#b54a5c' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button type="submit" class="btn btn-info text-white w-100">
                                <i class="bi bi-save me-1"></i>Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php elseif($appointment->status === 'cancelled'): ?>
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
        <?php elseif($appointment->status === 'no_show'): ?>
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
        <?php else: ?>
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
        <?php endif; ?>

        
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clipboard2 me-2 text-success"></i>Service Record
                </div>
                <div class="card-body">
                    <?php if($appointment->serviceRecord): ?>
                        <p class="text-success small">
                            <i class="bi bi-check-circle-fill me-1"></i>Record exists
                        </p>
                        <p class="small"><?php echo e(Str::limit($appointment->serviceRecord->description, 100)); ?></p>
                        <a href="<?php echo e(route('service-records.index')); ?>" class="btn btn-sm btn-outline-success w-100">
                            <i class="bi bi-eye me-1"></i>View Details
                        </a>
                    <?php else: ?>
                        <p class="text-muted small">No service record yet.</p>
                        <?php if($appointment->status === 'completed'): ?>
                            <a href="<?php echo e(route('service-records.index')); ?>" class="btn btn-sm btn-success w-100">
                                <i class="bi bi-plus-circle me-1"></i>Add Record
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clock-history me-2 text-info"></i>Status History
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $appointment->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex mb-3">
                            <div class="me-3 text-center">
                                <i class="bi bi-arrow-repeat text-primary"></i>
                            </div>
                            <div>
                                <p class="mb-0">
                                    <strong>
                                        <?php echo e($history->old_status ? ucfirst($history->old_status) : 'Created'); ?>

                                        →
                                        <?php echo e(ucfirst($history->new_status)); ?>

                                    </strong>
                                </p>
                                <small class="text-muted">
                                    By <?php echo e($history->changer->name); ?> •
                                    <?php echo e($history->created_at->format('M d, Y H:i')); ?>

                                </small>
                                <?php if($history->notes): ?>
                                    <p class="small text-muted mb-0"><?php echo e($history->notes); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted">No status changes recorded yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Auto-submit the status form when dropdown changes
        const statusSelect = document.querySelector('[name="status"]');
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\laravel patient-appointment-tracker-final\IT-9-Final-Project-Appointment-System-CHAMS\resources\views/appointments/show.blade.php ENDPATH**/ ?>