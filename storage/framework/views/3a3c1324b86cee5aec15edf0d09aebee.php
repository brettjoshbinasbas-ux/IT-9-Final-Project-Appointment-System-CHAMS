<?php $__env->startSection('title', 'Deactivated Users'); ?>
<?php $__env->startSection('page-title', 'Deactivated Users'); ?>
<?php $__env->startSection('page-subtitle', 'Restore or permanently delete deactivated accounts'); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Active Users
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Deactivated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($user->id); ?></td>
                            <td class="fw-semibold"><?php echo e($user->name); ?></td>
                            <td><?php echo e($user->email); ?></td>
                            <td>
                                <span
                                    class="badge <?php echo e($user->isAdmin() ? 'bg-danger' : ($user->isStaff() ? 'bg-primary' : 'bg-info')); ?>">
                                    <?php echo e(ucfirst($user->role)); ?>

                                </span>
                            </td>
                            <td><?php echo e($user->deleted_at->format('M d, Y H:i')); ?></td>
                            <td>
                                <form action="<?php echo e(route('users.restore', $user->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-arrow-repeat"></i> Restore
                                    </button>
                                </form>
                                <form action="<?php echo e(route('users.force-delete', $user->id)); ?>" method="POST" class="d-inline"
                                    onsubmit="return confirm('Permanently delete <?php echo e($user->name); ?>? This cannot be undone if they have records.')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Permanent Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No deactivated users found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\laravel patient-appointment-tracker-final\IT-9-Final-Project-Appointment-System-CHAMS\resources\views/users/trashed.blade.php ENDPATH**/ ?>