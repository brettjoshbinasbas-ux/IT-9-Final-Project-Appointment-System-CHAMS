<?php $__env->startSection('title', 'Appointments'); ?>
<?php $__env->startSection('page-title', 'Appointments'); ?>
<?php $__env->startSection('page-subtitle', 'View and manage all appointments'); ?>

<?php $__env->startSection('page-actions'); ?>
    <?php if(!auth()->user()->isStaff()): ?>
        <a href="<?php echo e(route('appointments.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>New Appointment
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('appointments.index')); ?>" class="d-flex gap-2 flex-wrap">
                <select name="status" class="form-select" style="width: auto;">
                    <option value="">All Statuses</option>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php echo e($status === $value ? 'selected' : ''); ?>>
                            <?php echo e($label); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <input type="date" name="date" class="form-control" style="width: auto;" value="<?php echo e($date); ?>">

                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-outline-secondary">Clear</a>
            </form>
        </div>
    </div>

    
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
                    <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <span class="fw-semibold">
                                    <?php echo e($appt->appointment_date->format('M d, Y')); ?>

                                </span><br>
                                <small class="text-muted"><?php echo e($appt->appointment_time); ?></small>
                            </td>
                            <td>
                                <?php if($appt->client): ?>
                                    <?php echo e($appt->client->full_name); ?>

                                <?php else: ?>
                                    <span class="text-muted">Client Deleted</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($appt->service_type); ?></td>
                            <td><?php echo e($appt->staff->name); ?></td>
                            <td><?php
                $colors = [
                    'scheduled'  => 'primary',
                    'confirmed'  => 'info',
                    'completed'  => 'success',
                    'cancelled'  => 'danger',
                    'no_show'    => 'secondary',
                ];
                $label = \App\Models\Appointment::STATUSES[$appt->status] ?? ucfirst($appt->status);
                $color = $colors[$appt->status] ?? 'dark';
                echo '<span class="badge bg-' . $color . '">' . $label . '</span>';
            ?></td>
                            <td>
                                <a href="<?php echo e(route('appointments.show', $appt)); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if(!auth()->user()->isStaff()): ?>
                                    <a href="<?php echo e(route('appointments.edit', $appt)); ?>"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No appointments found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <?php echo e($appointments->withQueryString()->links()); ?>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\laravel patient-appointment-tracker\patient-appointment-tracker\laravel chams github\IT-9-Final-Project-Appointment-System-CHAMS\resources\views/appointments/index.blade.php ENDPATH**/ ?>