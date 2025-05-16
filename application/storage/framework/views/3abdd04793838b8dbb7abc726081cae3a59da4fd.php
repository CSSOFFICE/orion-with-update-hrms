<?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<!--each row-->
<tr id="task_<?php echo e($task->task_id); ?>" class="task-<?php echo e($task->task_status); ?>">
    <td class="tasks_col_title td-edge">
        <!--for polling timers-->
        <input type="hidden" name="tasks[<?php echo e($task->task_id); ?>]" value="<?php echo e($task->assigned_to_me); ?>">
        <!--checkbox-->
        <span
            class="task_border td-edge-border <?php echo e(runtimeTaskStatusColors($task->task_status, 'background')); ?>"></span>
        <?php if(config('visibility.tasks_checkbox')): ?>
        <span class="list-checkboxes m-l-0">
            <input type="checkbox" id="toggle_task_status_<?php echo e($task->task_id); ?>" name="toggle_task_status"
                class="toggle_task_status filled-in chk-col-light-blue js-ajax-ux-request-default"
                data-url="<?php echo e(urlResource('/tasks/' . $task->task_id . '/toggle-status')); ?>"
                data-ajax-type="post" data-type="form" data-form-id="task_<?php echo e($task->task_id); ?>"
                data-notifications="disabled" data-container="task_<?php echo e($task->task_id); ?>"
                data-progress-bar="hidden" <?php echo e(runtimePrechecked($task->task_status)); ?>>
            <label for="toggle_task_status_<?php echo e($task->task_id); ?>"><a class=""
                    href="<?php echo e(url('/estimates/' . $task->view_id)); ?>"
                    data-loading-target="main-top-nav-bar"><span class="x-strike-through"
                        id="table_task_title_<?php echo e($task->task_id); ?>">
                        <?php echo e(str_limit($task->quo_number ?? '---', 40)); ?></span></a>
            </label>
        </span>
        <?php endif; ?>
        <?php if(config('visibility.tasks_nocheckbox')): ?>
        <a class="show-modal-button reset-card-modal-form js-ajax-ux-request p-l-5" href="javascript:void(0)"
            data-toggle="modal" data-target="#cardModal"
            data-url="<?php echo e(urlResource('/tasks/' . $task->task_id)); ?>"
            data-loading-target="main-top-nav-bar"><span class="x-strike-through"
                id="table_task_title_<?php echo e($task->task_id); ?>"><?php echo e(str_limit($task->task_title ?? '---', 45)); ?></span>12</a>
        <?php endif; ?>
    </td>
    <td class="tasks_col_created"><?php echo e($task->task_date_start); ?></td>
    <td class="tasks_col_deadline">
        <?php
        $user = \App\Models\User::find($task->task_creatorid);
        echo $user->first_name ?? '';
        ?>
    </td>
    <td class="tasks_col_deadline"><?php echo e(runtimeDate($task->task_date_due)); ?></td>

    <?php if(config('visibility.tasks_col_mytime')): ?>
    <td class="tasks_col_my_time">
        <?php if($task->assigned_to_me): ?>
        <span class="x-timer-time timers <?php echo e(runtimeTimerRunningStatus($task->timer_current_status)); ?>"
            id="task_timer_table_<?php echo e($task->task_id); ?>"><?php echo clean(runtimeSecondsHumanReadable($task->my_time, false)); ?></span>
        <?php if($task->task_status != 'completed'): ?>
        <!--start a timer-->
        <span
            class="x-timer-button js-timer-button js-ajax-request timer-start-button hidden <?php echo e(runtimeTimerVisibility($task->timer_current_status, 'stopped')); ?>"
            id="timer_button_start_table_<?php echo e($task->task_id); ?>" data-task-id="<?php echo e($task->task_id); ?>"
            data-location="table"
            data-url="<?php echo e(url('/')); ?>/tasks/timer/<?php echo e($task->task_id); ?>/start?source=list"
            data-form-id="tasks-list-table" data-type="form" data-progress-bar='hidden'
            data-ajax-type="POST">
            <span><i class="mdi mdi-play-circle"></i></span>
        </span>
        <!--stop a timer-->
        <span
            class="x-timer-button js-timer-button js-ajax-request timer-stop-button hidden <?php echo e(runtimeTimerVisibility($task->timer_current_status, 'running')); ?>"
            id="timer_button_stop_table_<?php echo e($task->task_id); ?>" data-task-id="<?php echo e($task->task_id); ?>"
            data-location="table"
            data-url="<?php echo e(url('/')); ?>/tasks/timer/<?php echo e($task->task_id); ?>/stop?source=list"
            data-form-id="tasks-list-table" data-type="form" data-progress-bar='hidden'
            data-ajax-type="POST">
            <span><i class="mdi mdi-stop-circle"></i></span>
        </span>
        <!--timer updating-->
        <input type="hidden" name="timers[<?php echo e($task->task_id); ?>]" value="">
        <?php endif; ?>
        <?php else: ?>
        <span><?php echo e($task->AMO ?? ''); ?></span>
        <?php endif; ?>
    </td>
    <?php endif; ?>

    <?php if(config('visibility.tasks_col_tags')): ?>
    <td class="tasks_col_tags">
        <!--tag-->
        <?php if(count($task->tags) > 0): ?>
        <?php $__currentLoopData = $task->tags->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span class="label label-outline-default"><?php echo e(str_limit($tag->tag_title, 15)); ?></span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <span>---</span>
        <?php endif; ?>
        <!--/#tag-->

        <!--more tags (greater than tags->take(x) number above -->
        <?php if(count($task->tags) > 1): ?>
        <?php $tags = $task->tags; ?>
        <?php echo $__env->make('misc.more-tags', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <!--more tags-->
    </td>
    <?php endif; ?>
    <?php if($task->task_status == 'accepted'): ?>
    <td class="tasks_col_status">
        <span
            class="label <?php echo e(runtimeEstimateStatusColors($task->task_status, 'label')); ?>"><?php echo e('Approval'); ?></span>
    </td>
    <?php else: ?>
    <td class="tasks_col_status">
        <span
            class="label <?php echo e(runtimeEstimateStatusColors($task->task_status, 'label')); ?>"><?php echo e($task->task_status == 'Approval_from_Management' ? 'Wating For Customer Approval' : runtimeLang($task->task_status)); ?></span>
    </td>
    <?php endif; ?>
    <td class="tasks_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--[delete]-->
            <?php if(config('visibility.action_buttons_delete')): ?>
            <button type="button" title="<?php echo e(cleanLang(__('lang.delete'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="<?php echo e(cleanLang(__('lang.delete_item'))); ?>"
                data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>" data-ajax-type="DELETE"
                data-url="<?php echo e(url('/')); ?>/estimates/<?php echo e($task->view_id); ?>">
                <i class="sl-icon-trash"></i>
            </button>
            <?php endif; ?>

            <!--view-->
            

            <a href="<?php echo e(url('/estimates/' . $task->view_id)); ?>" title="<?php echo e(cleanLang(__('lang.view'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>
        </span>
        
        <span class="list-table-action dropdown  font-size-inherit">
            <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false" title="<?php echo e(cleanLang(__('lang.more'))); ?>"
                title="<?php echo e(cleanLang(__('lang.more'))); ?>"
                class="data-toggle-tooltip data-toggle-tooltip btn btn-outline-default-light btn-circle btn-sm">
                <i class="ti-more"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="listTableAction">

                <!--actions button - email client -->
                <a class="dropdown-item confirm-action-info d-none" href="javascript:void(0)"
                    data-confirm-title="<?php echo e(cleanLang(__('lang.email_to_client'))); ?>"
                    data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
                    data-url="<?php echo e(url('/estimates')); ?>/<?php echo e($task->bill_estimateid); ?>/resend?ref=list">
                    <?php echo e(cleanLang(__('lang.email_to_client'))); ?></a>
                <!--actions button - change category-->
                <a class="dropdown-item actions-modal-button  js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title="<?php echo e(cleanLang(__('lang.change_status'))); ?>"
                    data-url="<?php echo e(urlResource('/estimates/' . $task->task_id . '/change-status')); ?>"
                    data-action-url="<?php echo e(urlResource('/estimates/' . $task->task_id . '/change-status')); ?>"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    <?php echo e(cleanLang(__('lang.change_status'))); ?></a>
                <!--actions button - change category-->
                <a class="dropdown-item actions-modal-button d-none js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title=" <?php echo e(cleanLang(__('lang.change_status'))); ?>"
                    data-url="<?php echo e(url('/estimates/change-category')); ?>"
                    data-action-url="<?php echo e(urlResource('/estimates/change-category?id=' . $task->task_id)); ?>"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    <?php echo e(cleanLang(__('lang.change_status'))); ?></a>
                <a class="dropdown-item confirm-action-info hidden" href="javascript:void(0)"
                    data-confirm-title="<?php echo e(cleanLang(__('lang.email_to_client'))); ?>"
                    data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
                    data-url="<?php echo e(url('/estimates')); ?>/<?php echo e($task->bill_estimateid); ?>/convert-to-invoice">
                    <?php echo e(cleanLang(__('lang.convert_to_invoice'))); ?></a>

                <?php if($task->is_project_creates=="No"): ?>
                <a class="dropdown-item confirm-action-info" href="javascript:void(0)"
                    data-confirm-title="Convert To Project"
                    data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
                    data-url="<?php echo e(url('/estimates')); ?>/<?php echo e($task->task_id); ?>/convert">
                    Convert To Project</a>
                <?php endif; ?>

            </div>
        </span>
        
    </td>



    <!--more button (team)-->
    <?php if(auth()->user()->is_team && $task->permission_super_user): ?>
    <span class="list-table-action dropdown  font-size-inherit">
        <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false" title="<?php echo e(cleanLang(__('lang.more'))); ?>"
            class="data-toggle-action-tooltip btn btn-outline-default-light btn-circle btn-sm">
            <i class="ti-more"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="listTableAction">
            <a class="dropdown-item confirm-action-danger"
                data-confirm-title="<?php echo e(cleanLang(__('lang.stop_all_timers'))); ?>"
                data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>" data-ajax-type="PUT"
                data-url="<?php echo e(url('/')); ?>/tasks/timer/<?php echo e($task->task_id); ?>/stopall?source=list">
                <?php echo e(cleanLang(__('lang.stop_all_timers'))); ?>

            </a>
        </div>
    </span>
    <?php endif; ?>
    <!--more button-->
    <!--action button-->
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/quos/components/table/ajax.blade.php ENDPATH**/ ?>