<?php $__currentLoopData = $board['tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a class="" href="<?php echo e(url('/estimates/' . $task->view_id)); ?>" data-loading-target="main-top-nav-bar"><span
            class="x-strike-through" id="table_task_title_<?php echo e($task->task_id); ?>">
           </span>
    <!--each card-->
    <div class="kanban-card "

        data-task-id="<?php echo e($task->task_id); ?>" data-loading-target="main-top-nav-bar" id="card_task_<?php echo e($task->task_id); ?>">
        <div class="x-title wordwrap" id="kanban_task_title_<?php echo e($task->task_id); ?>"><?php echo e($task->quo_number); ?>

            <span class="x-action-button" id="card-action-button-<?php echo e($task->task_id); ?>" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false"><i class="mdi mdi-dots-vertical"></i></span>
            <div class="dropdown-menu dropdown-menu-small dropdown-menu-right js-stop-propagation"
                aria-labelledby="card-action-button-<?php echo e($task->task_id); ?>">
                <?php $count_actions = 0 ; ?>
                <!--delete-->
                <?php if($task->permission_delete_task): ?>
                    <a class="dropdown-item confirm-action-danger  js-stop-propagation"
                        data-confirm-title="<?php echo e(cleanLang(__('lang.delete_item'))); ?>"
                        data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>" data-ajax-type="DELETE"
                        data-url="<?php echo e(url('/')); ?>/tasks/<?php echo e($task->task_id); ?>"><?php echo e(cleanLang(__('lang.delete'))); ?></a>
                    <?php $count_actions ++ ; ?>
                <?php endif; ?>
                <!--stop my timer-->
                <?php if($task->timer_current_status): ?>
                    <a class="dropdown-item confirm-action-danger js-stop-propagation"
                        data-confirm-title="<?php echo e(cleanLang(__('lang.stop_my_timer'))); ?>"
                        data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>" data-ajax-type="GET"
                        data-url="<?php echo e(url('/')); ?>/tasks/timer/<?php echo e($task->task_id); ?>?action=stop"><?php echo e(cleanLang(__('lang.stop_my_timer'))); ?></a>
                    <?php $count_actions ++ ; ?>
                <?php endif; ?>
                <!--stop all timers-->
                <?php if(auth()->user()->is_team && $task->permission_super_user): ?>
                    <a class="dropdown-item confirm-action-danger js-stop-propagation"
                        data-confirm-title="<?php echo e(cleanLang(__('lang.stop_all_timers'))); ?>"
                        data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>" data-ajax-type="GET"
                        data-url="<?php echo e(url('/')); ?>/tasks/timer/<?php echo e($task->task_id); ?>/stopall?source=list"><?php echo e(cleanLang(__('lang.stop_all_timers'))); ?></a>
                    <?php $count_actions ++ ; ?>
                <?php endif; ?>
                <!--no actions-->
                <?php if($count_actions == 0): ?>
                    <a class="dropdown-item js-stop-propagation"
                        href="javascript:void(0);"><?php echo e(cleanLang(__('lang.no_actions_available'))); ?></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="x-meta">
            <!--priority-->
            <?php if(config('system.settings_tasks_kanban_priority') == 'show'): ?>
                <label
                    class="label <?php echo e(runtimeTaskPriorityColors($task->task_priority, 'label')); ?> p-t-3 p-b-3 p-l-8 p-r-8"
                    data-toggle="tooltip"
                    title="<?php echo e(cleanLang(__('lang.priority'))); ?>"><?php echo e(runtimeLang($task->task_title)); ?></label>
            <?php endif; ?>
            <!--date created-->
            <?php if(config('system.settings_tasks_kanban_date_created') == 'show'): ?>
                <span><strong><?php echo e(cleanLang(__('lang.created'))); ?>:</strong>
                    <?php echo e(runtimeDate($task->task_created)); ?></span>
            <?php endif; ?>
            <!--start date-->
            <?php if(config('system.settings_tasks_kanban_date_start') == 'show'): ?>
                <span><strong><?php echo e(cleanLang(__('lang.start_date'))); ?>:</strong>:
                    <?php echo e(runtimeDate($task->task_date_start)); ?></span>
            <?php endif; ?>
            <!--due date-->
            <?php if(config('system.settings_tasks_kanban_date_due') == 'show'): ?>
                <span><strong><?php echo e(cleanLang(__('lang.due'))); ?>:</strong>:
                    <?php echo e(runtimeDate($task->task_date_due)); ?></span>
            <?php endif; ?>
        </div>
        <div class="x-footer row">
            <div class="col-6 x-icons">

                <!--client visibility-->
                <?php if(config('system.settings_tasks_kanban_client_visibility') == 'show' && auth()->user()->is_team): ?>
                    <?php if($task->task_client_visibility == 'no'): ?>
                        <span class="x-icon" data-toggle="tooltip"
                            title="<?php echo e(cleanLang(__('lang.client'))); ?> - <?php echo e(cleanLang(__('lang.hidden'))); ?>"
                            data-placement="top"><i class="mdi mdi-eye-outline-off"></i></span>
                    <?php endif; ?>
                <?php endif; ?>

                <!--attachments-->
                <?php if($task->has_attachments): ?>
                    <span class="x-icon"><i class="mdi mdi-attachment"></i>
                        <?php if($task->count_unread_attachments > 0): ?>
                            <span class="x-notification" id="card_notification_attachment_<?php echo e($task->task_id); ?>"></span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
                <!--comments-->
                <?php if($task->has_comments): ?>
                    <span class="x-icon"><i class="mdi mdi-comment-text-outline"></i>
                        <?php if($task->count_unread_comments > 0): ?>
                            <span class="x-notification" id="card_notification_comment_<?php echo e($task->task_id); ?>"></span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>

                <!--checklists-->
                <?php if($task->has_checklist): ?>
                    <span class="x-icon"><i class="mdi mdi-checkbox-marked-outline"></i></span>
                <?php endif; ?>

                <!--timer running-->
                <span class="x-icon text-danger <?php echo e(runtimeCardMyRunningTimer($task->timer_current_status)); ?>"
                    id="card-task-timer-<?php echo e($task->task_id); ?>"><i class="mdi mdi-timer"></i></span>

            </div>
            <div class="col-6 x-assigned">
                <?php $__currentLoopData = $task->assigned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img src="<?php echo e(getUsersAvatar($user->avatar_directory, $user->avatar_filename)); ?>"
                        data-toggle="tooltip" title="" data-placement="top" alt="<?php echo e($user->first_name); ?>"
                        class="img-circle avatar-xsmall" data-original-title="<?php echo e($user->first_name); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div></a>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/quos/components/kanban/card.blade.php ENDPATH**/ ?>