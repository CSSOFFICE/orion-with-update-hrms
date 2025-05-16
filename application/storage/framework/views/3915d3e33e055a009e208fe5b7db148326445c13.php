    <!----------Assigned----------->
    <?php if(config('visibility.tasks_card_assigned')): ?>
    <div class="x-section">
        <div class="x-title">
            <h6><?php echo e(cleanLang(__('lang.assigned_users'))); ?></h6>
        </div>
        <span id="task-assigned-container" class="">
            <?php echo $__env->make('pages.task.components.assigned', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </span>
        <!--user-->
        <?php if($task->permission_assign_users): ?>
        <span class="x-assigned-user x-assign-new js-card-settings-button-static card-task-assigned" tabindex="0"
            data-popover-content="card-task-team" data-title="<?php echo e(cleanLang(__('lang.assign_users'))); ?>"><i
                class="mdi mdi-plus"></i></span>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <!--spacer-->
    <div class="p-b-40"></div>
    <?php endif; ?>


    <!--show timer-->
    <div id="task-timer-container">
        <?php echo $__env->make('pages.task.components.timer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>


    <!----------settings----------->
    <div class="x-section">
        <div class="x-title">
            <h6><?php echo e(cleanLang(__('lang.settings'))); ?></h6>
        </div>
        <!--start date-->
        <div class="x-element" id="task-start-date"><i class="mdi mdi-calendar-plus"></i>
            <span><?php echo e(cleanLang(__('lang.start_date'))); ?>:</span>
            <?php if($task->permission_edit_task): ?>
            <span class="x-highlight x-editable card-pickadate"
                data-url="<?php echo e(urlResource('/tasks/'.$task->task_id.'/update-start-date/')); ?>" data-type="form"
                data-progress-bar='hidden' data-form-id="task-start-date" data-hidden-field="task_date_start"
                data-container="task-start-date-container" data-ajax-type="post"
                id="task-start-date-container"><?php echo e(runtimeDate($task->task_date_start)); ?></span></span>
            <input type="hidden" name="task_date_start" id="task_date_start">
            <?php else: ?>
            <span class="x-highlight"><?php echo e(runtimeDate($task->task_date_start)); ?></span>
            <?php endif; ?>
        </div>
        <!--due date-->
        <div class="x-element" id="task-due-date"><i class="mdi mdi-calendar-clock"></i>
            <span><?php echo e(cleanLang(__('lang.due_date'))); ?>:</span>
            <?php if($task->permission_edit_task): ?>
            <span class="x-highlight x-editable card-pickadate"
                data-url="<?php echo e(urlResource('/tasks/'.$task->task_id.'/update-due-date/')); ?>" data-type="form"
                data-progress-bar='hidden' data-form-id="task-due-date" data-hidden-field="task_date_due"
                data-container="task-due-date-container" data-ajax-type="post"
                id="task-due-date-container"><?php echo e(runtimeDate($task->task_date_due)); ?></span></span>
            <input type="hidden" name="task_date_due" id="task_date_due">
            <?php else: ?>
            <span class="x-highlight"><?php echo e(runtimeDate($task->task_date_due)); ?></span>
            <?php endif; ?>
        </div>
        <!--status-->
        <div class="x-element" id="card-task-status"><i class="mdi mdi-flag"></i> <span><?php echo e(cleanLang(__('lang.status'))); ?>: </span>
            <?php if($task->permission_edit_task): ?>
            <span class="x-highlight x-editable js-card-settings-button-static" id="card-task-status-text" tabindex="0"
                data-popover-content="card-task-statuses" data-offset="0 25%"
                data-title="<?php echo e(cleanLang(__('lang.status'))); ?>"><?php echo e(runtimeLang($task->task_status)); ?></strong></span>
            <?php else: ?>
            <span class="x-highlight"><?php echo e(runtimeLang($task->task_status)); ?></span>
            <?php endif; ?>
        </div>

        <!--priority-->
        <div class="x-element" id="card-task-priority"><i class="mdi mdi-verified"></i> <span><?php echo e(cleanLang(__('lang.priority'))); ?>:
            </span>
            <?php if($task->permission_edit_task): ?>
            <span class="x-highlight x-editable js-card-settings-button-static" id="card-task-priority-text"
                tabindex="0" data-popover-content="card-task-priorities"
                data-title="<?php echo e(cleanLang(__('lang.priority'))); ?>"><?php echo e(runtimeLang($task->task_priority)); ?></strong></span>
            <input type="hidden" name="task_priority" id="task_priority">
            <?php else: ?>
            <span class="x-highlight"><?php echo e(runtimeLang($task->task_priority)); ?></span>
            <?php endif; ?>
        </div>

        <!--client visibility-->
        <?php if(auth()->user()->type =='team'): ?>
        <div class="x-element" id="card-task-client-visibility"><i class="mdi mdi-eye"></i>
            <span><?php echo e(cleanLang(__('lang.client'))); ?>:</span>
            <span class="x-highlight x-editable js-card-settings-button-static" id="card-task-client-visibility-text"
                tabindex="0" data-popover-content="card-task-visibility"
                data-title="<?php echo e(cleanLang(__('lang.client_visibility'))); ?>"><?php echo e(runtimeDBlang($task->task_client_visibility, 'task_client_visibility')); ?></strong></span>
            <input type="hidden" name="task_client_visibility" id="task_client_visibility">
        </div>
        <?php endif; ?>
    </div>

    <!----------actions----------->
    <div class="x-section">
        <div class="x-title">
            <h6><?php echo e(cleanLang(__('lang.actions'))); ?></h6>
        </div>

        <!--track if we have any actions-->
        <?php $count_action = 0 ; ?>

        <!--change milestone-->
        <?php if($task->permission_edit_task && auth()->user()->type =='team'): ?>
        <div class="x-element x-action js-card-settings-button-static" id="card-task-milestone" tabindex="0"
            data-popover-content="card-task-milestones" data-title="<?php echo e(cleanLang(__('lang.milestone'))); ?>"><i
                class="mdi mdi-redo-variant"></i>
            <span class="x-highlight"><?php echo e(cleanLang(__('lang.change_milestone'))); ?></strong></span>
        </div>
        <?php $count_action ++ ; ?>
        <?php endif; ?>

        <!--stop all timer-->
        <?php if($task->permission_super_user): ?>
        <div class="x-element x-action confirm-action-danger" data-confirm-title="<?php echo e(cleanLang(__('lang.stop_all_timers'))); ?>"
            data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
            data-url="<?php echo e(url('/')); ?>/tasks/timer/<?php echo e($task->task_id); ?>/stopall?source=card"><i
                class="mdi mdi-timer-off"></i>
            <span class="x-highlight" id="task-start-date"><?php echo e(cleanLang(__('lang.stop_all_timers'))); ?></span></span></div>
        <?php $count_action ++ ; ?>
        <?php endif; ?>

        <!--delete-->
        <?php if($task->permission_delete_task): ?>
        <div class="x-element x-action confirm-action-danger" data-confirm-title="<?php echo e(cleanLang(__('lang.delete_item'))); ?>"
            data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>" data-ajax-type="DELETE"
            data-url="<?php echo e(urlResource('/')); ?>/tasks/<?php echo e($task->task_id); ?>"><i class="mdi mdi-delete"></i> <span
                class="x-highlight" id="task-start-date"><?php echo e(cleanLang(__('lang.delete'))); ?></span></span></div>
        <?php $count_action ++ ; ?>
        <?php endif; ?>

        <!--no action available-->
        <?php if($count_action == 0): ?>
        <div class="x-element">
            <?php echo e(cleanLang(__('lang.no_actions_available'))); ?>

        </div>
        <?php endif; ?>

    </div>


    <!----------meta infor----------->
    <div class="x-section">
        <div class="x-title">
            <h6><?php echo e(cleanLang(__('lang.information'))); ?></h6>
        </div>
        <div class="x-element x-action">
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.task_id'))); ?></td>
                        <td><strong>#<?php echo e($task->task_id); ?></strong></td>
                    </tr>
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.created_by'))); ?></td>
                        <td><strong><?php echo e($task->first_name); ?> <?php echo e($task->last_name); ?></strong></td>
                    </tr>
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.date_created'))); ?></td>
                        <td><strong><?php echo e(runtimeDate($task->task_created)); ?></strong></td>
                    </tr>
                    <?php if(auth()->user()->is_team): ?>
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.total_time'))); ?></td>
                        <td><strong><span id="task_timer_all_card_<?php echo e($task->task_id); ?>"><?php echo clean(runtimeSecondsHumanReadable($task->sum_all_time, false)); ?></span></strong>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.time_invoiced'))); ?></td>
                        <td><strong><span id="task_timer_all_card_<?php echo e($task->task_id); ?>"><?php echo clean(runtimeSecondsHumanReadable($task->sum_invoiced_time, false)); ?></span></strong>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.project'))); ?></td>
                        <td><strong><a href="<?php echo e(urlResource('/projects/'.$task->task_projectid)); ?>"
                                    target="_blank">#<?php echo e($task->project_id); ?></a></strong>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-----------------------------popover dropdown elements------------------------------------>

    <!--task statuses - popover -->
    <?php if($task->permission_edit_task): ?>
    <div class="hidden" id="card-task-statuses">
        <ul class="list">
            <?php $__currentLoopData = config('settings.task_statuses'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="card-tasks-update-status-link" data-button-text="card-task-status-text"
                data-progress-bar='hidden' data-url="<?php echo e(urlResource('/tasks/'.$task->task_id.'/update-status')); ?>"
                data-type="form" data-value="<?php echo e($key); ?>" data-form-id="--set-dynamically--" data-ajax-type="post">
                <?php echo e(runtimeLang($key)); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <input type="hidden" name="task_status" id="task_status">
        <input type="hidden" name="current_task_status_text" id="current_task_status_text">
    </div>
    <?php endif; ?>


    <!--task priority - popover-->
    <?php if($task->permission_edit_task): ?>
    <div class="hidden" id="card-task-priorities">
        <ul class="list">
            <?php $__currentLoopData = config('settings.task_priority'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="card-tasks-update-priority-link" data-button-text="card-task-priority-text"
                data-progress-bar='hidden' data-url="<?php echo e(urlResource('/tasks/'.$task->task_id.'/update-priority')); ?>"
                data-type="form" data-value="<?php echo e($key); ?>" data-form-id="--set-dynamically--" data-ajax-type="post">
                <?php echo e(runtimeLang($key)); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <input type="hidden" name="task_priority" id="task_priority">
        <input type="hidden" name="current_task_priority_text" id="current_task_priority_text">
    </div>
    <?php endif; ?>

    <!--client visibility - popover-->
    <?php if($task->permission_edit_task): ?>
    <div class="hidden" id="card-task-visibility">
        <ul class="list">
            <li class="card-tasks-update-visibility-link" data-button-text="card-task-client-visibility-text"
                data-progress-bar='hidden' data-url="<?php echo e(urlResource('/tasks/'.$task->task_id.'/update-visibility')); ?>"
                data-type="form" data-value="no" data-text="<?php echo e(cleanLang(__('lang.hidden'))); ?>"
                data-form-id="card-task-client-visibility" data-ajax-type="post">
                <?php echo e(cleanLang(__('lang.hidden'))); ?>

            </li>
            <li class="card-tasks-update-visibility-link" data-button-text="card-task-client-visibility-text"
                data-progress-bar='hidden' data-url="<?php echo e(urlResource('/tasks/'.$task->task_id.'/update-visibility')); ?>"
                data-type="form" data-value="yes" data-text="<?php echo e(cleanLang(__('lang.visible'))); ?>"
                data-form-id="card-task-client-visibility" data-ajax-type="post">
                <?php echo e(cleanLang(__('lang.visible'))); ?>

            </li>
        </ul>
        <input type="hidden" name="task_client_visibility" id="task_client_visibility">
        <input type="hidden" name="current_task_client_visibility_text" id="current_task_client_visibility_text">
    </div>
    <?php endif; ?>

    <!--milestone - popover -->
    <?php if($task->permission_edit_task): ?>
    <div class="hidden" id="card-task-milestones">
        <div class="form-group m-t-10">
            <select class="custom-select col-12 form-control form-control-sm" id="task_milestoneid"
                name="task_milestoneid">
                <?php if(isset($milestones)): ?>
                <?php $__currentLoopData = $milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($milestone->milestone_id); ?>">
                    <?php echo e(runtimeLang($milestone->milestone_title, 'task_milestone')); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group text-right">
            <button type="button" class="btn btn-danger btn-sm" id="card-tasks-update-milestone-button"
                data-progress-bar='hidden' data-url="<?php echo e(urlResource('/tasks/'.$task->task_id.'/update-milestone')); ?>"
                data-type="form" data-ajax-type="post" data-form-id="popover-body">
                <?php echo e(cleanLang(__('lang.update'))); ?>

            </button>
        </div>
    </div>
    <?php endif; ?>


    <!--assign user-->
    <div class="hidden" id="card-task-team">
        <div class="alert alert-info">Only users assigned to the project are shown in this list</div>
        <?php $__currentLoopData = $project_assigned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="form-check m-b-15">
            <label class="custom-control custom-checkbox">
                <input type="checkbox" name="assigned[<?php echo e($staff->id); ?>]" class="custom-control-input">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description"><img
                        src="<?php echo e(getUsersAvatar($staff->avatar_directory, $staff->avatar_filename)); ?>"
                        class="img-circle avatar-xsmall"> <?php echo e($staff->first_name); ?> <?php echo e($staff->last_name); ?></span>
            </label>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="form-group text-right">
            <button type="button" class="btn btn-danger btn-sm" id="card-tasks-update-assigned"
                data-progress-bar='hidden' data-url="<?php echo e(urlResource('/tasks/'.$task->task_id.'/update-assigned')); ?>"
                data-type="form" data-ajax-type="post" data-form-id="popover-body">
                <?php echo e(cleanLang(__('lang.update'))); ?>

            </button>
        </div>
    </div>
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/task/rightpanel.blade.php ENDPATH**/ ?>