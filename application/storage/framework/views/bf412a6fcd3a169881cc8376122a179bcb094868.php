
<?php $__currentLoopData = $stock_move_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if($note->total_quantity>0): ?>
    <!--each row-->
    <tr id="note_<?php echo e($key); ?>">
        <?php if(config('visibility.notes_col_checkboxes')): ?>
            <td class="notes_col_checkbox checkitem" id="notes_col_checkbox_<?php echo e($key); ?>">
                <!--list checkbox-->
                <span class="list-checkboxes display-inline-block w-px-20">
                    <input type="checkbox" id="listcheckbox-notes-<?php echo e($key); ?>" name="ids[<?php echo e($key); ?>]"
                        class="listcheckbox listcheckbox-notes filled-in chk-col-light-blue"
                        data-actions-container-class="notes-checkbox-actions-container">
                    <label for="listcheckbox-notes-<?php echo e($key); ?>"></label>
                </span>
            </td>
        <?php endif; ?>

        <td class="notes_col_title">
            <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal"
                data-url="<?php echo e(url('/')); ?>/notes/<?php echo e($key); ?>" data-target="#plainModal"
                data-loading-target="plainModalBody" data-modal-title=" ">
                <?php echo e($note->product_name); ?>

            </a>
        </td>
        <td class="notes_col_tags">
            <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal"
                data-url="<?php echo e(url('/')); ?>/notes/<?php echo e($key); ?>" data-target="#plainModal"
                data-loading-target="plainModalBody" data-modal-title=" ">
                <?php echo e($note->total_quantity); ?>

            </a>
        </td>
        <td class="projects_col_action actions_column">
            <!--action button-->
            <?php if(config('visibility.action_buttons_edit')): ?>
            <span class="list-table-action dropdown font-size-inherit">
                <!--[inventory-return]-->
                <button type="button" title="Inventory Return"
                    class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                    data-toggle="modal" data-target="#commonModal"
                    data-url="<?php echo e(urlResource('/projectinventory/inventory-return/?project_id='.$project_id.'&product_id='.$note->product_id.'&total_qty='.$note->quantity.'&old_ware='.$note->warehouse_id)); ?>"
                    data-loading-target="commonModalBody" data-modal-title="Inventory Return"
                    data-action-url="<?php echo e(urlResource('projectinventory/inventory-return-submit')); ?>" data-action-method="POST">
                    <i class="sl-icon-note"></i>
                </button>
            </span>
            <?php endif; ?>
            <!--action button-->
        </td>
    </tr>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<!--each row-->
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/projectinventory/components/table/ajax.blade.php ENDPATH**/ ?>