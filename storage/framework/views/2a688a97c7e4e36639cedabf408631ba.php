<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle', 'Overview of today\'s activity'); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 small opacity-75">Total Clients</p>
                            <h3 class="fw-bold mb-0"><?php echo e($totalClients); ?></h3>
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
                            <h3 class="fw-bold mb-0"><?php echo e($todayCount); ?></h3>
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
                            <h3 class="fw-bold mb-0"><?php echo e($completedCount); ?></h3>
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
                            <h3 class="fw-bold mb-0"><?php echo e($scheduledCount); ?></h3>
                        </div>
                        <i class="bi bi-calendar-plus fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-pie-chart me-2 text-primary"></i>Appointments by Status
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-graph-up me-2 text-success"></i>Weekly Trend
                </div>
                <div class="card-body">
                    <canvas id="trendChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Status Pie Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($statusLabels); ?>,
                    datasets: [{
                        data: <?php echo json_encode($statusCounts); ?>,
                        backgroundColor: ['#5f9ea0', '#8b5a8f', '#6b8f5e', '#b54a5c', '#c9a53b']
                    }]
                }
            });

            // Weekly Trend Line Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($weekLabels); ?>,
                    datasets: [{
                        label: 'Appointments',
                        data: <?php echo json_encode($weekCounts); ?>,
                        borderColor: '#8b5a8f',
                        tension: 0.3,
                        fill: true
                    }]
                }
            });
        </script>
    <?php $__env->stopPush(); ?>

    
    <div class="row g-3">

        
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-week me-2 text-primary"></i>Upcoming Appointments
                </div>
                <div class="card-body p-0">
                    <?php $__empty_1 = true; $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <div>
                                <p class="fw-semibold mb-0"><?php echo e($appt->client->full_name); ?></p>
                                <small class="text-muted">
                                    <?php echo e($appt->service_type); ?> —
                                    <?php echo e($appt->appointment_date->format('M d, Y')); ?>

                                    at <?php echo e($appt->appointment_time); ?>

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
                        <p class="text-muted p-3 mb-0">No upcoming appointments.</p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white">
                    <a href="<?php echo e(route('appointments.index')); ?>" class="text-primary small">
                        View all appointments →
                    </a>
                </div>
            </div>
        </div>

        
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clipboard2-check me-2 text-success"></i>Recent Service Records
                </div>
                <div class="card-body p-0">
                    <?php $__empty_1 = true; $__currentLoopData = $recentRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="px-3 py-2 border-bottom">
                            <p class="fw-semibold mb-0"><?php echo e($record->client->full_name); ?></p>
                            <small class="text-muted"><?php echo e(Str::limit($record->description, 60)); ?></small>
                            <br>
                            <small class="text-secondary"><?php echo e($record->service_date->format('M d, Y')); ?></small>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted p-3 mb-0">No service records yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\laravel patient-appointment-tracker-final\IT-9-Final-Project-Appointment-System-CHAMS\resources\views/dashboard.blade.php ENDPATH**/ ?>