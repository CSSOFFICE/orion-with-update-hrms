<?php $__currentLoopData = $addressb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$addressb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<tr id="contact_<?php echo e($addressb->id); ?>">
    <?php if(config('visibility.address_col_checkboxes')): ?>
    <td class="address_col_checkbox checkitem " id="address_col_checkbox_<?php echo e($addressb->id); ?>">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-address-<?php echo e($addressb->id); ?>" name="ids[<?php echo e($addressb->id); ?>]"
                class="listcheckbox listcheckbox-address filled-in chk-col-light-blue"
                data-actions-container-class="address-checkbox-actions-container"

            <label for="listcheckbox-address-<?php echo e($addressb->id); ?>"></label>
        </span>
    </td>
    <?php endif; ?>
    <td class="address_col_first_name" id="address_col_first_name_<?php echo e($addressb->id); ?>">
       <span><?php echo e($k+1); ?></span>

    </td>
    <td class="address_col_first_name" id="address_col_first_name_<?php echo e($addressb->id); ?>">
       <span><?php echo e($addressb->street); ?></span>

    </td>

    <td class="address_col_company" id="address_col_company_<?php echo e($addressb->id); ?>">
        <a href="<?php echo e(url('/clients')); ?>/<?php echo e($addressb->clientid); ?>"><?php echo e($addressb->p_unit); ?></a>
    </td>
    <td class="address_col_company" id="address_col_company_<?php echo e($addressb->id); ?>">
        <a href="<?php echo e(url('/clients')); ?>/<?php echo e($addressb->clientid); ?>"><?php echo e($addressb->country); ?></a>
    </td>
        <td class="address_col_email" id="address_col_email_<?php echo e($addressb->id); ?>">
        <?php echo e($addressb->zipcode); ?>

    </td>
    


    <td class="address_col_action actions_column" id="address_col_action_<?php echo e($addressb->id); ?>">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            <button type="button" title="<?php echo e(cleanLang(__('lang.delete'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="<?php echo e(cleanLang(__('lang.delete_user'))); ?>" data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
                data-ajax-type="DELETE" data-url="<?php echo e(url('/')); ?>/addressb/<?php echo e($addressb->id); ?>">
                <i class="sl-icon-trash"></i>
            </button>

            <!--edit-->
            <?php if(config('visibility.action_buttons_edit')): ?>
            <button type="button" title="<?php echo e(cleanLang(__('lang.edit'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="<?php echo e(urlResource('/addressb/'.$addressb->id.'/edit')); ?>" data-loading-target="commonModalBody"
                data-modal-title="Edit Address"
                data-action-url="<?php echo e(urlResource('/addressb/'.$addressb->id.'?ref=list')); ?>" data-action-method="PUT"
                data-action-ajax-class="" data-action-ajax-loading-target="address-td-container">
                <i class="sl-icon-note"></i>
            </button>
            <?php endif; ?>
        </span>
        <!--action button-->
    </td>

</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/addressb/components/table/ajax.blade.php ENDPATH**/ ?>