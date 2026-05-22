<?php $__env->startSection('title', 'Deleted Clients'); ?>
<?php $__env->startSection('page-title', 'Deleted Clients'); ?>
<?php $__env->startSection('page-subtitle', 'Restore or permanently delete archived clients'); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Active Clients
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
                        <th>Phone</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($client->id); ?></td>
                            <td class="fw-semibold"><?php echo e($client->full_name); ?></td>
                            <td><?php echo e($client->email ?? '—'); ?></td>
                            <td><?php echo e($client->phone); ?></td>
                            <td><?php echo e($client->deleted_at->format('M d, Y H:i')); ?></td>
                            <td>
                                <form action="<?php echo e(route('clients.restore', $client->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-arrow-repeat"></i> Restore
                                    </button>
                                </form>
                                <form action="<?php echo e(route('clients.force-delete', $client->id)); ?>" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Permanently delete <?php echo e($client->full_name); ?>? This cannot be undone.')">
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
                                No deleted clients found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <?php echo e($clients->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\laravel patient-appointment-tracker-final\IT-9-Final-Project-Appointment-System-CHAMS\resources\views/clients/trashed.blade.php ENDPATH**/ ?>