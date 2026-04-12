

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

    <div class="row g-3">

        
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
                    <p><strong>Date:</strong> <?php echo e($appointment->appointment_date->format('F d, Y')); ?></p>
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

        
        <?php if($appointment->status !== 'completed'): ?>
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
                                        <?php echo e($appointment->status === $value ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button type="submit" class="btn btn-info text-white w-100">
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            
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
                    <?php else: ?>
                        <p class="text-muted small">No service record yet.</p>
                        <?php if($appointment->status === 'completed'): ?>
                            <a href="<?php echo e(route('service-records.index')); ?>" class="btn btn-sm btn-success w-100">
                                Add Record
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            
            document.querySelector('[name="status"]').addEventListener('change', function() {
                this.closest('form').submit();
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\laravel patient-appointment-tracker-copy\CHAMS\resources\views/appointments/show.blade.php ENDPATH**/ ?>