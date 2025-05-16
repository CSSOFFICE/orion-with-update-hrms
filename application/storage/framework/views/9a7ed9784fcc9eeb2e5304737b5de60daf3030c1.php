<div class="card-title m-b-0" id="<?php echo e(runtimePermissions('task-edit-title', $task->permission_edit_task)); ?>">
    <?php echo e($task->task_title); ?>

</div>
<!--buttons: edit-->
<?php if($task->permission_edit_task): ?>
<div id="card-title-edit" class="card-title-edit hidden">
    <input type="text" class="form-control form-control-sm card-title-input" id="task_title" name="task_title">
    <!--button: subit & cancel-->
    <div id="card-title-submit" class="p-t-10 text-right">
        <button type="button" class="btn waves-effect waves-light btn-xs btn-default"
            id="card-title-button-cancel"><?php echo e(cleanLang(__('lang.cancel'))); ?></button>
        <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
            data-url="<?php echo e(urlResource('/tasks/'.$task->task_id.'/update-title')); ?>" data-progress-bar='hidden' data-type="form"
            data-form-id="card-title-edit" data-ajax-type="post" id="card-title-button-save"><?php echo e(cleanLang(__('lang.save'))); ?></button>
    </div>
</div>
<?php endif; ?>
<div class="m-b-15"><small><strong>Milestone: </strong></small><small id="card-task-milestone-title"><?php echo e(runtimeLang($task->milestone_title, 'task_milestone')); ?></small></div>
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/task/components/title.blade.php ENDPATH**/ ?>