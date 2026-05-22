<?php $__env->startSection('title', 'Kanban Board'); ?>
<?php $__env->startSection('page-title', 'Kanban Board'); ?>
<?php $__env->startSection('page-subtitle', 'Drag and drop to update status'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        .kanban-column {
            min-height: 500px;
            transition: background-color 0.2s;
        }

        .kanban-column.drag-over {
            background-color: rgba(139, 90, 143, 0.15);
        }

        .kanban-card {
            cursor: grab;
            transition: all 0.2s;
            border-left: 4px solid;
            position: relative;
        }

        .kanban-card:active {
            cursor: grabbing;
        }

        .kanban-card.dragging {
            opacity: 0.4;
        }

        /* Status-based border colors */
        .kanban-card[data-status="scheduled"] {
            border-left-color: #5f9ea0;
        }

        .kanban-card[data-status="confirmed"] {
            border-left-color: #8b5a8f;
        }

        .kanban-card[data-status="completed"] {
            border-left-color: #6b8f5e;
        }

        .kanban-card[data-status="cancelled"] {
            border-left-color: #b54a5c;
        }

        .kanban-card[data-status="no_show"] {
            border-left-color: #7a6a7e;
        }

        /* Column header colors */
        .kanban-column-header[data-status="scheduled"] {
            background: linear-gradient(135deg, #5f9ea0, #4a7d7f);
            color: white;
        }

        .kanban-column-header[data-status="confirmed"] {
            background: linear-gradient(135deg, #8b5a8f, #6b3e70);
            color: white;
        }

        .kanban-column-header[data-status="completed"] {
            background: linear-gradient(135deg, #6b8f5e, #4a6b3e);
            color: white;
        }

        .kanban-column-header[data-status="cancelled"] {
            background: linear-gradient(135deg, #b54a5c, #8a3545);
            color: white;
        }

        .kanban-column-header[data-status="no_show"] {
            background: linear-gradient(135deg, #7a6a7e, #5a4a5e);
            color: white;
        }

        /* Column scrolling */
        .kanban-column {
            max-height: 65vh;
            overflow-y: auto;
        }

        .kanban-column::-webkit-scrollbar {
            width: 5px;
        }

        .kanban-column::-webkit-scrollbar-track {
            background: #e0d4e3;
            border-radius: 10px;
        }

        .kanban-column::-webkit-scrollbar-thumb {
            background: #8b5a8f;
            border-radius: 10px;
        }
    </style>

    <div style="margin-left: 10%; margin-top: 5%;">
        <div class="row g-3" id="kanban-board">
            <?php $__currentLoopData = App\Models\Appointment::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusValue => $statusLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-2">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header kanban-column-header fw-semibold" data-status="<?php echo e($statusValue); ?>">
                            <?php echo e($statusLabel); ?>

                            <span class="badge bg-light text-dark float-end">
                                <?php echo e($appointmentsByStatus[$statusValue] ?? 0); ?>

                            </span>
                        </div>
                        <div class="card-body kanban-column" data-status="<?php echo e($statusValue); ?>">
                            <?php $__currentLoopData = $appointments->where('status', $statusValue); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="card mb-2 kanban-card shadow-sm" data-id="<?php echo e($appt->id); ?>"
                                    data-status="<?php echo e($appt->status); ?>" draggable="true">
                                    <div class="card-body p-2">
                                        <p class="fw-semibold mb-0 small">
                                            <?php if($appt->client): ?>
                                                <?php echo e($appt->client->full_name); ?>

                                            <?php else: ?>
                                                <span class="text-muted">[Deleted Client #<?php echo e($appt->client_id); ?>]</span>
                                            <?php endif; ?>
                                        </p>
                                        <small class="text-muted"><?php echo e($appt->service_type); ?></small><br>
                                        <small class="text-secondary">
                                            <i class="bi bi-calendar3"></i> <?php echo e($appt->appointment_date->format('M d')); ?>

                                            <i class="bi bi-clock"></i> <?php echo e($appt->appointment_time); ?>

                                        </small>
                                        <div class="mt-1">
                                            <small class="text-muted">Staff: <?php echo e($appt->staff->name); ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let draggedCard = null;

            // Get all draggable cards
            const cards = document.querySelectorAll('.kanban-card');
            const columns = document.querySelectorAll('.kanban-column');

            // Make cards draggable
            cards.forEach(card => {
                card.addEventListener('dragstart', handleDragStart);
                card.addEventListener('dragend', handleDragEnd);
            });

            // Set up drop zones on columns
            columns.forEach(column => {
                column.addEventListener('dragover', handleDragOver);
                column.addEventListener('dragleave', handleDragLeave);
                column.addEventListener('drop', handleDrop);
            });

            function handleDragStart(e) {
                draggedCard = this;
                e.dataTransfer.setData('text/plain', this.dataset.id);
                e.dataTransfer.effectAllowed = 'move';
                this.classList.add('dragging');
            }

            function handleDragEnd(e) {
                this.classList.remove('dragging');
                draggedCard = null;
            }

            function handleDragOver(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                this.classList.add('drag-over');
            }

            function handleDragLeave(e) {
                this.classList.remove('drag-over');
            }

            async function handleDrop(e) {
                e.preventDefault();
                this.classList.remove('drag-over');

                const targetColumn = this.closest('.kanban-column');
                if (!targetColumn) return;

                const newStatus = targetColumn.dataset.status;
                const appointmentId = e.dataTransfer.getData('text/plain');

                if (!appointmentId || !draggedCard) return;

                const currentStatus = draggedCard.dataset.status;

                // Don't update if dropping in same column
                if (currentStatus === newStatus) return;

                // Show loading state
                const originalText = targetColumn.innerHTML;
                targetColumn.style.opacity = '0.6';

                try {
                    const response = await fetch(`/appointments/${appointmentId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                ?.content ||
                                document.querySelector('input[name="_token"]')?.value,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    });

                    if (response.ok) {
                        // Reload to refresh the board
                        window.location.reload();
                    } else {
                        const error = await response.json();
                        alert(error.message || 'Failed to update status');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while updating the status');
                } finally {
                    targetColumn.style.opacity = '';
                }
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\laravel patient-appointment-tracker-final\IT-9-Final-Project-Appointment-System-CHAMS\resources\views/appointments/kanban.blade.php ENDPATH**/ ?>