<?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr id="project_<?php echo e($project->project_id); ?>">
    <?php if(config('visibility.projects_col_checkboxes')): ?>
    <td class="projects_col_checkbox checkitem" id="projects_col_checkbox_<?php echo e($project->project_id); ?>">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-projects-<?php echo e($project->project_id); ?>"
                name="ids[<?php echo e($project->project_id); ?>]"
                class="listcheckbox listcheckbox-projects filled-in chk-col-light-blue"
                data-actions-container-class="projects-checkbox-actions-container">
            <label for="listcheckbox-projects-<?php echo e($project->project_id); ?>"></label>
        </span>
    </td>
    <?php endif; ?>
    <?php
    $p=DB::table('purchase_order')->where('project_id',$project->project_id)->get()->first();


    ?>
    <td class="projects_col_id"><a
            href=<?php echo e(url('/projects/' . $project->project_id)); ?>><?php echo e($project->project_code??''); ?></label></a>
    </td>
    <td class="projects_col_project">
        <a href=<?php echo e(url('/projects/' . $project->project_id)); ?>><?php echo e(str_limit($project->project_title ?? '---', 20)); ?><a />
    </td>
    <?php if(config('visibility.projects_col_client')): ?>
    <td class="projects_col_client">
        <a
            href=<?php echo e(url('/clients/' . $project->project_clientid)); ?>><?php echo e(str_limit($project->f_name ?? $project->client_company_name??'' , 12)); ?></a>
    </td>
    <?php endif; ?>
    <td class="projects_col_start_date ">
        <?php echo e(runtimeDate($project->project_date_start)); ?>

    </td>
    <td class="projects_col_end_date"><?php echo e(runtimeDate($project->project_date_due)); ?></td>
    <?php if(config('visibility.projects_col_tags')): ?>
    <td class="projects_col_tags">
        <!--tag-->
        <?php if(count($project->tags) > 0): ?>
        <?php $__currentLoopData = $project->tags->take(1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <span class="label label-outline-default"><?php echo e(str_limit($tag->tag_title, 15)); ?></span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <span>---</span>
        <?php endif; ?>
        <!--/#tag-->

        <!--more tags (greater than tags->take(x) number above -->
        <?php if(count($project->tags) > 1): ?>
        <?php $tags = $project->tags; ?>
        <?php echo $__env->make('misc.more-tags', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <!--more tags-->
    </td>
    <?php endif; ?>
    <td class="projects_col_progress p-r-20">
        <div class="progress" data-toggle="tooltip" title="<?php echo e($project->project_progress); ?>%">
            <?php if($project->project_progress == 100): ?>
            <div class="progress-bar bg-success w-100 h-px-10 font-11 font-weight-500" data-toggle="tooltip"
                title="100%" role="progressbar"></div>
            <?php else: ?>
            <!--dynamic inline style-->
            <div class="progress-bar bg-info h-px-10 font-16 font-weight-500 w-<?php echo e(round($project->project_progress)); ?>"
                role="progressbar"></div>
            <?php endif; ?>
        </div>
    </td>
    <?php if(config('visibility.projects_col_team')): ?>
    <td class="projects_col_team">
        <!--assigned users-->
        <?php if(count($project->assigned) > 0): ?>
        <?php $__currentLoopData = $project->assigned->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <img src="<?php echo e($user->avatar); ?>" data-toggle="tooltip" title="<?php echo e($user->first_name); ?>"
            data-placement="top" alt="<?php echo e($user->first_name); ?>" class="img-circle avatar-xsmall">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <span>---</span>
        <?php endif; ?>
        <!--assigned users-->
        <!--more users-->
        <?php if(count($project->assigned) > 2): ?>
        <?php
        $more_users_title = __('lang.assigned_users');
        $users = $project->assigned;
        ?>
        <?php echo $__env->make('misc.more-users', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <!--more users-->
    </td>
    <?php endif; ?>
    <td class="projects_col_status">
        <span
            class="label <?php echo e(runtimeProjectStatusColors($project->project_status, 'label')); ?>"><?php echo e(runtimeLang($project->project_status)); ?></span>
    </td>
    <?php
    $d = DB::table('roles')
    ->where('role_id', Auth()->user()->role_id)
    ->first();

    ?>
    <?php if($d->role_name == 'Super Admin' || $d->role_name == 'Manager'): ?>
    <td class="projects_col_status">
        <span
            class="label <?php echo e(runtimeProjectStatusColors($project->project_status, 'label')); ?>"><?php echo e($project->project_sn); ?></span>
    </td>
    <?php endif; ?>
    <td class="projects_col_action actions_column">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <?php if(config('visibility.action_buttons_delete')): ?>
            <!--[delete]-->
            <?php if($project->permission_delete_project): ?>
            <button type="button" title="<?php echo e(cleanLang(__('lang.delete'))); ?>"
                class="data-toggle-action-tooltip delete_project btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="<?php echo e(cleanLang(__('lang.delete_item'))); ?>"
                data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>" data-ajax-type="DELETE"
                data-url="<?php echo e(url('/')); ?>/projects/<?php echo e($project->project_id); ?>" data-id="<?php echo e($project->project_id); ?>">
                <i class="sl-icon-trash"></i>
            </button>
            <?php else: ?>
            <!--optionally show disabled button?-->
            <span
                class="btn btn-outline-default btn-circle btn-sm disabled  <?php echo e(runtimePlaceholdeActionsButtons()); ?>"
                data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.actions_not_available'))); ?>"><i
                    class="sl-icon-trash"></i></span>
            <?php endif; ?>
            <?php endif; ?>
            <!--[edit]-->
            <?php if(config('visibility.action_buttons_edit')): ?>
            <?php if($project->permission_edit_project): ?>
            <button type="button" title="<?php echo e(cleanLang(__('lang.edit'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="<?php echo e(urlResource('/projects/' . $project->project_id . '/edit')); ?>"
                data-loading-target="commonModalBody"
                data-modal-title="<?php echo e(cleanLang(__('lang.edit_project'))); ?>"
                data-action-url="<?php echo e(urlResource('/projects/' . $project->project_id)); ?>"
                data-action-method="PUT" data-action-ajax-class=""
                data-action-ajax-loading-target="projects-td-container">
                <i class="sl-icon-note"></i>
            </button>
            <?php else: ?>
            <!--optionally show disabled button?-->
            <span
                class="btn btn-outline-default btn-circle btn-sm disabled  <?php echo e(runtimePlaceholdeActionsButtons()); ?>"
                data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.actions_not_available'))); ?>"><i
                    class="sl-icon-note"></i></span>
            <?php endif; ?>
            <?php endif; ?>
            <!--view-->
            <a href=<?php echo e(url('/projects/' . $project->project_id)); ?> title="<?php echo e(cleanLang(__('lang.view'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>
        </span>
        <!--action button-->
        <!--more button (team)-->
        <?php if(config('visibility.action_buttons_edit')): ?>
        <span class="list-table-action dropdown font-size-inherit">
            <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false" title="<?php echo e(cleanLang(__('lang.more'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-default-light btn-circle btn-sm">
                <i class="ti-more"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="listTableAction">
                <!--change category-->
                <?php if($project->permission_edit_project): ?>
                <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title="<?php echo e(cleanLang(__('lang.change_category'))); ?>"
                    data-url="<?php echo e(url('/projects/change-category')); ?>"
                    data-action-url="<?php echo e(urlResource('/projects/change-category?id=' . $project->project_id)); ?>"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    <?php echo e(cleanLang(__('lang.change_category'))); ?></a>
                <!--change status-->
                <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title="<?php echo e(cleanLang(__('lang.change_status'))); ?>"
                    data-url="<?php echo e(urlResource('/projects/' . $project->project_id . '/change-status')); ?>"
                    data-action-url="<?php echo e(urlResource('/projects/' . $project->project_id . '/change-status')); ?>"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    <?php echo e(cleanLang(__('lang.change_status'))); ?></a>
                <!--stop all timers-->
                <a class="dropdown-item confirm-action-danger"
                    data-confirm-title="<?php echo e(cleanLang(__('lang.stop_all_timers'))); ?>"
                    data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>" data-ajax-type="PUT"
                    data-url="<?php echo e(urlResource('/projects/' . $project->project_id . '/stop-all-timers')); ?>">
                    <?php echo e(cleanLang(__('lang.stop_all_timers'))); ?>

                </a>
                <?php else: ?>
                <span class="small">--- no options avaiable</span>
                <?php endif; ?>
            </div>
        </span>
        <?php endif; ?>
        <!--more button-->
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/orion/application/resources/views/pages/projects/components/table/ajax.blade.php ENDPATH**/ ?>