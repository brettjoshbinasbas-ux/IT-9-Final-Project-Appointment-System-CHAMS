<?php $__env->startSection('title', 'Reports'); ?>
<?php $__env->startSection('page-title', 'Reports'); ?>
<?php $__env->startSection('page-subtitle', 'Appointment and activity summary'); ?>

<?php $__env->startSection('page-actions'); ?>
    <div class="btn-group">
        <a href="<?php echo e(route('reports.export-csv')); ?>" class="btn btn-success m-2 rounded">
            <i class="bi bi-filetype-csv me-1"></i>Export CSV
        </a>
        <a href="<?php echo e(route('reports.export-pdf')); ?>" class="btn btn-danger m-2 rounded">
            <i class="bi bi-filetype-pdf me-1"></i>Export PDF
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row g-3 mb-4">
        
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small opacity-75">Completed (All Time)</p>
                            <h3 class="fw-bold mb-0"><?php echo e($completedCount); ?></h3>
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
                            <h3 class="fw-bold mb-0"><?php echo e($completedThisMonth); ?></h3>
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
                            <h3 class="fw-bold mb-0"><?php echo e($cancelledCount); ?></h3>
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
                            <h3 class="fw-bold mb-0"><?php echo e($dailyAppointments->count()); ?></h3>
                        </div>
                        <i class="bi bi-calendar-day fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">

        
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-day me-2 text-primary"></i>Today's Appointments
                </div>
                <div class="card-body p-0">
                    <?php $__empty_1 = true; $__currentLoopData = $dailyAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <div>
                                <p class="fw-semibold mb-0"><?php echo e($appt->client->full_name); ?></p>
                                <small class="text-muted">
                                    <?php echo e($appt->service_type); ?> at <?php echo e($appt->appointment_time); ?>

                                </small>
                            </div>
                            <?php
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
            ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted p-3 mb-0">No appointments today.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-week me-2 text-primary"></i>Weekly Summary (Last 7 Days)
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $weeklyAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><?php echo e($day['date']); ?></span>
                            <span class="badge bg-secondary"><?php echo e($day['count']); ?> appointments</span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
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
                            <?php $__empty_1 = true; $__currentLoopData = $staffActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($staff->name); ?></td>
                                    <td><?php echo e($staff->assigned_appointments_count); ?> appointments</span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">No staff data available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
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
                            <?php $__empty_1 = true; $__currentLoopData = $clientVisits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($client->full_name); ?></td>
                                    <td><?php echo e($client->appointments_count); ?> visits</span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">No client data available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\laravel patient-appointment-tracker-final\IT-9-Final-Project-Appointment-System-CHAMS\resources\views/reports/index.blade.php ENDPATH**/ ?>