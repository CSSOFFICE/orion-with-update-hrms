<?php $__currentLoopData = $grn_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<tr id="note_<?php echo e($note->purchase_requistion_id); ?>">
    <?php if(config('visibility.notes_col_checkboxes')): ?>
    <td class="notes_col_checkbox checkitem" id="notes_col_checkbox_<?php echo e($note->purchase_requistion_id); ?>">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-notes-<?php echo e($note->grn_id); ?>" name="ids[<?php echo e($note->purchase_requistion_id); ?>]" class="listcheckbox listcheckbox-notes filled-in chk-col-light-blue" data-actions-container-class="notes-checkbox-actions-container">
            <label for="listcheckbox-notes-<?php echo e($note->purchase_requistion_id); ?>"></label>
        </span>
    </td>
    <?php endif; ?>

    <td class="notes_col_title">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="<?php echo e(url('/')); ?>/prq/<?php echo e($note->purchase_requistion_id); ?>" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            <?php echo e($note->porder_id); ?>

        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="<?php echo e(url('/')); ?>/notes/<?php echo e($note->purchase_requistion_id); ?>" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            
            <?php echo e($note->created_datetime); ?>

        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="<?php echo e(url('/')); ?>/notes/<?php echo e($note->purchase_requistion_id); ?>" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            <?php echo e($note->created_datetime); ?>

        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request" data-toggle="modal" data-url="<?php echo e(url('/')); ?>/notes/<?php echo e($note->purchase_requistion_id); ?>" data-target="#plainModal" data-loading-target="plainModalBody" data-modal-title=" ">
            <?php echo e($note->status); ?>

        </a>
    </td>
    <!-- <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request">
            <?php echo e($note->purchase); ?>

        </a>
    </td>
    <td class="notes_col_tags">
        <a href="javascript:void(0)" class="show-modal-button js-ajax-ux-request">
            <?php echo e($note->site_address); ?>

        </a>
    </td> -->
    <td class="notes_col_action  actions_column <?php echo e($page[ 'visibility_col_action'] ?? ''); ?> ">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">

            <button type="button" title="<?php echo e(cleanLang(__('lang.delete'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="<?php echo e(cleanLang(__('lang.delete_note'))); ?>" data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
                data-ajax-type="DELETE" data-url="<?php echo e(url( '/')); ?>/prq/<?php echo e($note->purchase_requistion_id); ?> ">
                <i class="sl-icon-trash"></i>
            </button>
            <button type="button" title="<?php echo e(cleanLang(__('lang.edit'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="<?php echo e(urlResource('/prq/'.$note->purchase_requistion_id.'/edit')); ?>" data-loading-target="commonModalBody"
                data-modal-title="<?php echo e(cleanLang(__('lang.edit_note'))); ?>"
                data-action-url="<?php echo e(urlResource('/prq/'.$note->purchase_requistion_id.'?ref=list')); ?>" data-action-method="PUT"
                data-action-ajax-class="" data-action-ajax-loading-target="notes-td-container">
                <i class="sl-icon-note"></i>
            </button>



            <a href="javascript:void(0)" title="<?php echo e(cleanLang(__('lang.view'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm show-modal-button js-ajax-ux-request"
                data-toggle="modal" data-url="<?php echo e(url( '/')); ?>/prq/<?php echo e($note->purchase_requistion_id); ?> " data-target="#plainModal"
                data-loading-target="plainModalBody" data-modal-title="">
                <i class="ti-new-window"></i>
            </a>
        </span>
        <span>
            <?php if($note->engineer_status===111): ?>
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" id="Project_Manager_id" data-id="<?php echo e($note->purchase_requistion_id); ?>" data-type="pending_engineer">Engineer Approval</button>
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" data-id="<?php echo e($note->purchase_requistion_id); ?>" data-type="pending_rejected">Reject</button>


            <?php elseif($note->project_status===199): ?>
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" id="" data-id="<?php echo e($note->purchase_requistion_id); ?>" data-type="pending_project">Pending Project Manager Approval</button>
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" data-id="<?php echo e($note->purchase_requistion_id); ?>" data-type="pending_rejected">Reject</button>

            <?php elseif($note->managemant_status===992): ?>
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" id="" data-id="<?php echo e($note->purchase_requistion_id); ?>" data-type="pending_approved">Approve</button> /
            <button type="button" class="btn btn-light text-light bg-dark shadow Project_Manager_id" data-id="<?php echo e($note->purchase_requistion_id); ?>" data-type="pending_rejected">Reject</button>


            <?php endif; ?>
            <!--action button-->
        </span>

    </td>



</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<script>
    $("body").on("click", '.Project_Manager_id', function() {
        const csrfToken = "<?php echo e(csrf_token()); ?>";
        let id = $(this).data('id');
        let type = $(this).data('type');
        $.ajax({
            url: "<?php echo e(route('prq.change_status')); ?>",
            type: "post",
            data: {
                id: id,
                type: type,
                _token: csrfToken
            },
            success: function(re) {
                window.location.reload(1);
            }
        })

    })
</script>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/prq/components/table/ajax.blade.php ENDPATH**/ ?>