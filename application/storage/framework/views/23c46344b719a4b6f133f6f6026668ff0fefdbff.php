<?php $__currentLoopData = $grn_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!--each row-->

<tr id="note_<?php echo e($note->grn_id); ?>">
    <?php if(config('visibility.notes_col_checkboxes')): ?>
    <td class="notes_col_checkbox checkitem" id="notes_col_checkbox_<?php echo e($note->grn_id); ?>">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-notes-<?php echo e($note->grn_id); ?>" name="ids[<?php echo e($note->grn_id); ?>]" class="listcheckbox listcheckbox-notes filled-in chk-col-light-blue" data-actions-container-class="notes-checkbox-actions-container">
            <label for="listcheckbox-notes-<?php echo e($note->grn_id); ?>"></label>
        </span>
    </td>
    <?php endif; ?>

    <td class="notes_col_title">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="<?php echo e(url('/')); ?>/notes/<?php echo e($note->grn_id); ?>" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            <?php echo e($note->product_name); ?>

        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="<?php echo e(url('/')); ?>/notes/<?php echo e($note->grn_id); ?>" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            <?php echo e($note->qty_need); ?>

        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="<?php echo e(url('/')); ?>/notes/<?php echo e($note->grn_id); ?>" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            <?php echo e($note->qty_rec); ?>

        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="<?php echo e(url('/')); ?>/notes/<?php echo e($note->grn_id); ?>" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            <?php echo e($note->qty_rem); ?>

        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request">
            <?php echo e($note->prd_rec_date); ?>

        </a>
    </td>




</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/projectinventory/components/table/ajax.blade.php ENDPATH**/ ?>