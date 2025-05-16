
<?php $__currentLoopData = $milestones_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<tr id="milestone_<?php echo e($milestone->milestonecategory_id); ?>">
    <td class="milestones_col_name">
        


        
        <?php echo e($milestone->milestonecategory_title); ?>

        <!--sorting data-->
        <?php if(config('visibility.milestone_actions')): ?>
        <input type="hidden" name="sort-milestones[<?php echo e($milestone->milestonecategory_id); ?>]"
            value="<?php echo e($milestone->milestonecategory_id); ?>">
        <?php endif; ?>
    </td>
    <td class="milestones_col_tasks">
        <?php echo e(get_task_status($milestone->bill_projectid,$milestone->milestonecategory_id,'ALL')); ?>

    </td>
    <td class="milestones_col_tasks_pending">

        <?php echo e(get_task_status($milestone->bill_projectid,$milestone->milestonecategory_id,'PENDING')); ?>

    </td>
    <td class="milestones_col_tasks_completed">

        <?php echo e(get_task_status($milestone->bill_projectid,$milestone->milestonecategory_id,'COMPLETED')); ?>

    </td>
    <?php if(config('visibility.milestone_actions')): ?>
    <td class="milestones_col_action actions_column d-none">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <?php if($milestone->milestonecategory_position): ?>
            <!---delete milestone with confirm checkbox-->
            <span id="milestone_form_<?php echo e($milestone->milestonecategory_id); ?>">
                <button type="button" title="<?php echo e(cleanLang(__('lang.delete'))); ?>"
                    class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                    id="foobar" data-confirm-title="<?php echo e(cleanLang(__('lang.delete_milestone'))); ?>"
                    data-confirm-text="
                            <input type='checkbox' id='confirm_action_<?php echo e($milestone->milestonecategory_id); ?>'
                                   class='filled-in chk-col-light-blue confirm_action_checkbox'
                                   data-field-id='delete_milestone_tasks_<?php echo e($milestone->milestonecategory_id); ?>'>
                            <label for='confirm_action_<?php echo e($milestone->milestonecategory_id); ?>'><?php echo e(cleanLang(__('lang.delete_all_tasks'))); ?></label>" data-ajax-type="DELETE" data-type="form"
                    data-form-id="milestone_form_<?php echo e($milestone->milestonecategory_id); ?>"
                    data-url="<?php echo e(url('/')); ?>/milestones/<?php echo e($milestone->milestonecategory_id); ?>?project_id=<?php echo e($milestone->milestonecategory_position); ?>">
                    <i class="sl-icon-trash"></i>
                </button>
                <input type="hidden" class="confirm_hidden_fields" name="delete_milestone_tasks"
                    id="delete_milestone_tasks_<?php echo e($milestone->milestonecategory_id); ?>">
            </span>
            <!---/#delete milestone with confirm checkbox-->
            <button type="button" title="<?php echo e(cleanLang(__('lang.edit'))); ?>"
                class="d-none data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="<?php echo e(urlResource('/milestones/'.$milestone->milestonecategory_id.'/edit')); ?>"
                data-loading-target="commonModalBody" data-modal-title="<?php echo e(cleanLang(__('lang.edit_milestone'))); ?>"
                data-action-url="<?php echo e(urlResource('/milestones/'.$milestone->milestonecategory_id.'?ref=list')); ?>"
                data-action-method="PUT" data-action-ajax-class=""
                data-action-ajax-loading-target="milestones-td-container">
                <i class="sl-icon-note"></i>
            </button>
            <?php else: ?>
            <!--optionally show disabled button?-->
            <span class="btn btn-outline-default btn-circle btn-sm disabled <?php echo e(runtimePlaceholdeActionsButtons()); ?>"
                data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.actions_not_available'))); ?>"><i class="sl-icon-trash"></i></span>
            <span class="btn btn-outline-default btn-circle btn-sm disabled <?php echo e(runtimePlaceholdeActionsButtons()); ?>"
                data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.actions_not_available'))); ?>"><i class="sl-icon-note"></i></span>
            <?php endif; ?>
        </span>
        <!--action button-->
    </td>
    <?php endif; ?>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/milestones/components/table/ajax.blade.php ENDPATH**/ ?>