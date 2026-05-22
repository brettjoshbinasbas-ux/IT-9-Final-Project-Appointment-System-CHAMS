<?php $__env->startSection('title', 'Clients'); ?>
<?php $__env->startSection('page-title', 'Clients'); ?>
<?php $__env->startSection('page-subtitle', 'Manage registered clients'); ?>

<?php $__env->startSection('page-actions'); ?>
    <?php if(!auth()->user()->isStaff()): ?>
        <a href="<?php echo e(route('clients.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Add Client
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('clients.index')); ?>" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone..."
                    value="<?php echo e($search); ?>">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i>
                </button>
                <?php if($search): ?>
                    <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-outline-secondary">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
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
                            <td>
                                <a href="<?php echo e(route('clients.show', $client)); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if(!auth()->user()->isStaff()): ?>
                                    <a href="<?php echo e(route('clients.edit', $client)); ?>" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if(auth()->check() && auth()->user()->isAdmin()): ?>
                                    <form action="<?php echo e(route('clients.destroy', $client)); ?>" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete <?php echo e($client->full_name); ?>?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No clients found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <?php echo e($clients->withQueryString()->links()); ?>

        </div>

    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\laravel patient-appointment-tracker-final\IT-9-Final-Project-Appointment-System-CHAMS\resources\views/clients/index.blade.php ENDPATH**/ ?>