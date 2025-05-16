<?php $i=0;?>
<?php $__currentLoopData = $expenses_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $i++;?>
<!--each row-->
<tr id="expense_<?php echo e($expense->purchase_order_id); ?>">
    <?php if(config('visibility.expenses_col_checkboxes')): ?>
    <td class="expenses_col_checkbox checkitem" id="expenses_col_checkbox_<?php echo e($expense->payable_id); ?>">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-expenses-<?php echo e($expense->payable_id); ?>"
                name="ids[<?php echo e($expense->payable_id); ?>]"
                class="listcheckbox listcheckbox-expenses filled-in chk-col-light-blue expenses-checkbox"
                data-actions-container-class="expenses-checkbox-actions-container"
                data-expense-id="<?php echo e($expense->payable_id); ?>" data-unit="<?php echo e(cleanLang(__('lang.item'))); ?>" data-quantity="1"
                data-description="<?php echo e($expense->expense_description??''); ?>" data-rate="<?php echo e($expense->expense_amount??''); ?>">
            <label for="listcheckbox-expenses-<?php echo e($expense->payable_id); ?>"></label>
        </span>
    </td>
    <?php endif; ?>
    <?php if(config('visibility.expenses_col_date')): ?>
    <td class="expenses_col_date">
        <?php echo $i;?>

    </td>
    <?php endif; ?>
    <?php if(config('visibility.expenses_col_description')): ?>
    <td class="expenses_col_description">

        <span  title="<?php echo e($expense->expense_description??''); ?>"><?php echo e($expense->invoice_no ?? '---'); ?></span>
    </td>
    <?php endif; ?>
    <!--column visibility-->
    <?php if(config('visibility.expenses_col_project')): ?>
    <td class="expenses_col_project">
        <a href="/projects/<?php echo e($expense->project_id ??''); ?>"><?php echo e(str_limit($expense->purchase_order_no ?? '---', 12)); ?></a>
    </td>
    <?php endif; ?>
    <!--column visibility-->
    <?php if(config('visibility.expenses_col_client')): ?>
    <td class="expenses_col_client">
        <img src="<?php echo e(getUsersAvatar($expense->avatar_directory??'', $expense->avatar_filename??'')); ?>" alt="user"
            class="img-circle avatar-xsmall">

            <?php echo e($expense->supplier_name); ?>


    </td>
    <?php endif; ?>

    <!--column visibility-->
    <td class="expenses_col_amount">
        <?php echo e(runtimeMoneyFormat($expense->potal)); ?>

    </td>

    

    <?php if(config('visibility.expenses_col_status')): ?>
    <td class="expenses_col_client">

        <?php echo e($expense->status); ?>

    </td>
    <?php endif; ?>

    <td>
       <?php echo e(runtimeDate($expense->expense_created??'')); ?>


    </td>

    <?php if(config('visibility.expenses_col_action')): ?>
    <td class="expenses_col_action actions_column" id="expenses_col_action_<?php echo e($expense->payable_id); ?>">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            <?php if(config('visibility.action_buttons_delete')): ?>
                

                <button type="button" title="<?php echo e(cleanLang(__('lang.delete'))); ?>"
                    class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                    data-confirm-title="<?php echo e(cleanLang(__('lang.delete_item'))); ?>" data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
                    data-ajax-type="GET" data-url="<?php echo e(url('/')); ?>/expenses/expenses-delete/<?php echo e($expense->purchase_order_id); ?>">
                    <i class="sl-icon-trash"></i>
                </button>
            <?php endif; ?>
            <!--edit-->

             <?php if(config('visibility.action_buttons_edit')): ?>

            <button type="button" title="<?php echo e(cleanLang(__('lang.edit'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="<?php echo e(urlResource('/expenses/'.$expense->payable_id.'/edit')); ?>"
                data-loading-target="commonModalBody" data-modal-title="<?php echo e(cleanLang(__('lang.edit_invoice'))); ?>"
                data-action-url="<?php echo e(urlResource('/expenses/'.$expense->payable_id.'?ref=list')); ?>"
                data-action-method="PUT" data-action-ajax-class=""
                data-action-ajax-loading-target="expenses-td-container">
                <i class="sl-icon-note"></i>
            </button>

            <?php endif; ?>

            <button type="button" title="<?php echo e(cleanLang(__('lang.view'))); ?>"
                class="data-toggle-tooltip show-modal-button btn btn-outline-info btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#plainModal" data-loading-target="plainModalBody"
                data-modal-title="<?php echo e(cleanLang(__('lang.expense_records'))); ?>" data-url="<?php echo e(url('/expenses/'.$expense->payable_id)); ?>">
                <i class="ti-new-window"></i>
            </button>

            <!--more button (team)-->

            <?php if(config('visibility.action_buttons_edit') == 'show'): ?>
            
            <?php endif; ?>

            <!--more button-->
        </span>
        <!--action button-->

    </td>
    <?php endif; ?>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/expenses/components/table/ajax.blade.php ENDPATH**/ ?>