<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.tasks'))); ?></label>
    <div class="col-12">

        <?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
            <select type="text" class="form-control  form-control-sm" autocomplete="off" id="tasks" name="tasks"
                value="<?php echo e($subtask->task_title ?? ''); ?>">
                <option value="<?php echo e($subtask->unit_rate ?? ''); ?>">Select Task</option>
                <?php $__currentLoopData = $task; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value->task_id); ?>"
                        <?php echo e($value->task_id == $subtask->subtask_taskid ? 'selected' : ''); ?>><?php echo e($value->task_title); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        <?php else: ?>
            <select type="text" class="form-control  form-control-sm" autocomplete="off" id="tasks"
                name="tasks" value="<?php echo e($subtask->task_title ?? ''); ?>">
                <option value="<?php echo e($subtask->unit_rate ?? ''); ?>">Select Task</option>
                <?php $__currentLoopData = $task; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value->task_id); ?>"><?php echo e($value->task_title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

        <?php endif; ?>
    </div>
    <label
        class="col-12 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.task_description'))); ?></label>
    <div class="col-12">
        <textarea class="form-control  form-control-sm" autocomplete="off" id="subtask_description" name="subtask_description"><?php echo e($subtask->subtask_description ?? ''); ?> </textarea>
        <input type="hidden" name="subtask_projectid" value="<?php echo e(request('project_id')); ?>">
    </div>
    <label
        class="col-12 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.task_detail'))); ?></label>
    <div class="col-12">
        <textarea class="form-control  form-control-sm" autocomplete="off" id="subtask_detail" name="subtask_detail"><?php echo e($subtask->subtask_detail ?? ''); ?> </textarea>

    </div>
    <label class="col-12 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.unit_rate'))); ?></label>
    <div class="col-12">
        <input type="text" class="form-control  form-control-sm" autocomplete="off" id="unit_rate" name="unit_rate"
            value="<?php echo e($subtask->unit_rate ?? ''); ?>">

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/subtasks/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>