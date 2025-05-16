<?php $__currentLoopData = $estimates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estimate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<tr id="estimate_<?php echo e($estimate->bill_estimateid); ?>">
    <?php if(config('visibility.estimates_col_checkboxes')): ?>
    <td class="estimates_col_checkbox checkitem" id="estimates_col_checkbox_<?php echo e($estimate->bill_estimateid); ?>">
        <!--list checkbox-->
        <span class="list-checkboxes display-inline-block w-px-20">
            <input type="checkbox" id="listcheckbox-estimates-<?php echo e($estimate->bill_estimateid); ?>"
                name="ids[<?php echo e($estimate->bill_estimateid); ?>]"
                class="listcheckbox listcheckbox-estimates filled-in chk-col-light-blue"
                data-actions-container-class="estimates-checkbox-actions-container">
            <label for="listcheckbox-estimates-<?php echo e($estimate->bill_estimateid); ?>"></label>
        </span>
    </td>
    <?php endif; ?>
    <td class="estimates_col_id" id="estimates_col_id_<?php echo e($estimate->bill_estimateid); ?>">
        <a href="<?php echo e(url('/estimates/'.$estimate->bill_estimateid)); ?>"><?php echo e($estimate->est_quotation_no); ?></a>
    </td>
    <td class="estimates_col_date" id="estimates_col_date_<?php echo e($estimate->bill_estimateid); ?>">
        <?php echo e(runtimeDate($estimate->bill_date)); ?>

    </td>
    <!-- <?php if(config('visibility.estimates_col_client')): ?>
    <td class="estimates_col_company" id="estimates_col_company_<?php echo e($estimate->bill_estimateid); ?>">
        <a href="/clients/<?php echo e($estimate->bill_clientid); ?>">
            <?php echo e(str_limit($estimate->f_name ?? '---', 30)); ?></a>
    </td>
    <?php endif; ?> -->
    <?php if(config('visibility.estimates_col_created_by')): ?>
    <td class="estimates_col_created_by" id="estimates_col_created_by_<?php echo e($estimate->bill_estimateid); ?>">
        <img src="<?php echo e(getUsersAvatar($estimate->avatar_directory, $estimate->avatar_filename)); ?>" alt="user"
            class="img-circle avatar-xsmall">
        <?php echo e($estimate->f_name ?? runtimeUnkownUser()); ?>

    </td>
    <?php endif; ?>

    <td class="estimates_col_expires" id="estimates_col_expires_<?php echo e($estimate->bill_estimateid); ?>">
        <?php echo e(runtimeDate($estimate->bill_expiry_date)); ?></td>
    <?php if(config('visibility.estimates_col_tags')): ?>
    <td class="estimates_col_tags" id="estimates_col_tags_<?php echo e($estimate->bill_estimateid); ?>">
        <!--tag-->
        <?php if(count($estimate->tags) > 0): ?>
        <span class="label label-outline-default"><?php echo e(str_limit($estimate->tags->first()->tag_title, 15)); ?></span>
        <?php else: ?>
        <span>---</span>
        <?php endif; ?>
        <!--/#tag-->

        <!--more tags-->
        <?php if(count($estimate->tags) > 1): ?>
        <?php $tags = $estimate->tags; ?>
        <?php echo $__env->make('misc.more-tags', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        <!--more tags-->
    </td>
    <?php endif; ?>
    <td class="estimates_col_amount" id="estimates_col_amount_<?php echo e($estimate->bill_estimateid); ?>">
        <?php echo e(runtimeMoneyFormat($estimate->bill_final_amount)); ?>

    </td>
    <td class="estimates_col_status" id="estimates_col_status_<?php echo e($estimate->bill_estimateid); ?>">
        <span class="label <?php echo e(runtimeEstimateStatusColors($estimate->bill_status, 'label')); ?>">

            <?php if($estimate->bill_status == "new"): ?>
            Management Approved
            <?php elseif($estimate->bill_status =="Approval_from_Management"): ?>
             Wating For Customer Approval
            <?php else: ?>
            <?php echo e(runtimeLang($estimate->bill_status)); ?>

            <?php endif; ?>
     </span>
    </td>
    <td class="estimates_col_action actions_column" id="estimates_col_action_<?php echo e($estimate->bill_estimateid); ?>">
        <!--action button-->
        <span class="list-table-action dropdown font-size-inherit">
            <!--delete-->
            <?php if(config('visibility.action_buttons_delete')): ?>
            <button type="button" title="<?php echo e(cleanLang(__('lang.delete'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="<?php echo e(cleanLang(__('lang.delete_item'))); ?>"
                data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>" data-ajax-type="DELETE"
                data-url="<?php echo e(url('/')); ?>/estimates/<?php echo e($estimate->bill_estimateid); ?>">
                <i class="sl-icon-trash"></i>
            </button>
            <?php endif; ?>
            <!--edit-->
            <?php if(config('visibility.action_buttons_edit')): ?>
            <a href="<?php echo e(url('/estimates/'.$estimate->bill_estimateid.'/edit-estimate')); ?>" title="<?php echo e(cleanLang(__('lang.edit'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="sl-icon-note"></i>
            </a>
            <?php endif; ?>
            <a href="<?php echo e(url('/estimates/'.$estimate->bill_estimateid)); ?>" title="<?php echo e(cleanLang(__('lang.view'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>
        </span>
        <!--action button-->

        <!--more button (team)-->
        <?php if(config('visibility.action_buttons_edit') == 'show'): ?>
        <span class="list-table-action dropdown  font-size-inherit">
            <button type="button" id="listTableAction" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                title="<?php echo e(cleanLang(__('lang.more'))); ?>" title="<?php echo e(cleanLang(__('lang.more'))); ?>"
                class="data-toggle-tooltip data-toggle-tooltip btn btn-outline-default-light btn-circle btn-sm">
                <i class="ti-more"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="listTableAction">
                <!--actions button - email client -->
                <a class="dropdown-item confirm-action-info" href="javascript:void(0)"
                    data-confirm-title="<?php echo e(cleanLang(__('lang.email_to_client'))); ?>"
                    data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
                    data-url="<?php echo e(url('/estimates')); ?>/<?php echo e($estimate->bill_estimateid); ?>/resend?ref=list">
                    <?php echo e(cleanLang(__('lang.email_to_client'))); ?></a>
                <!--actions button - change category-->
                <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title="<?php echo e(cleanLang(__('lang.change_status'))); ?>"
                    data-url="<?php echo e(urlResource('/estimates/'.$estimate->bill_estimateid.'/change-status')); ?>"
                    data-action-url="<?php echo e(urlResource('/estimates/'.$estimate->bill_estimateid.'/change-status')); ?>"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    <?php echo e(cleanLang(__('lang.change_status'))); ?></a>
                <!--actions button - change category-->
                <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title="<?php echo e(cleanLang(__('lang.change_category'))); ?>"
                    data-url="<?php echo e(url('/estimates/change-category')); ?>"
                    data-action-url="<?php echo e(urlResource('/estimates/change-category?id='.$estimate->bill_estimateid)); ?>"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    <?php echo e(cleanLang(__('lang.change_category'))); ?></a>
                <a class="dropdown-item confirm-action-info hidden" href="javascript:void(0)"
                    data-confirm-title="<?php echo e(cleanLang(__('lang.email_to_client'))); ?>"
                    data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
                    data-url="<?php echo e(url('/estimates')); ?>/<?php echo e($estimate->bill_estimateid); ?>/convert-to-invoice">
                    <?php echo e(cleanLang(__('lang.convert_to_invoice'))); ?></a>
            </div>
        </span>
        <?php endif; ?>
        <!--more button-->

    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<!--each row-->
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/estimates/components/table/ajax.blade.php ENDPATH**/ ?>