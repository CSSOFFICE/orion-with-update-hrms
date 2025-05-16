<?php
$firtt_back = 'firtt_back';
$atz = 65;
$amount = 0;
$budget_amount = 0;
$petty_case_amount = 0;
$purchase_order_amount = 0;
$subcontractor_amount = 0;
$total_qtn = 0;

?>

<style>
    .clients_col_company span {
        display: inline-block;
        max-width: auto;
        /* Adjust as needed */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        word-wrap: break-word;
    }
</style>

<?php $__currentLoopData = $templete_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $T => $TM): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
$cat_id_exist = DB::table('tasks')->where('task_cat_id', $T)->exists();

$i = 0;
?>
<?php if($cat_id_exist): ?>
<tr>
    <td style="background-color: coral;">SECT <?php echo e(chr($atz)); ?> </td>
    <td style="background-color: coral;"><?php echo e($TM); ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="background-color: coral;"></td>
    <td></td>
    <td></td>
    
    <td></td>
    <td></td>
    <td style="background-color: greenyellow;"></td>
    <td></td>
</tr>
<?php endif; ?>
<?php $__currentLoopData = $grn_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if($client->task_cat_id==$T): ?>
<?php if($client->type==''): ?>
<?php
$budget_amount += get_data_budget_data($client->task_cat_id, $client->task_id);
$total_qtn += $client->task_qtn;

?>
<!-- Each row -->
<tr id="client_<?php echo e($client->task_id); ?>">
    <td class="clients_col_id" id="clients_col_id_<?php echo e($client->task_id); ?>"><?php echo e($i + 1); ?> </td>
    <td class="clients_col_company" id="clients_col_id_<?php echo e($client->task_id); ?>">
        <span
            style="width:250px;word-wrap:break-word;white-space:normal;"><?php echo e($client->task_title ?? '---'); ?></span>
    </td>
    <td class="clients_col_company" id="clients_col_id_<?php echo e($client->task_id); ?>"></td>
    <td class="clients_col_company" id="clients_col_id_<?php echo e($client->task_id); ?>">
        <?php echo e(str_limit($client->task_unit ?? '', 35)); ?>

    </td>
    <td class="clients_col_company" id="clients_col_id_<?php echo e($client->task_id); ?>">
        <?php echo e(str_limit($client->task_qtn ?? '', 35)); ?>

    </td>
    <td class="clients_col_company " style="background-color: coral;"
        id="clients_col_id_<?php echo e($client->task_id); ?>"><input type="number"
            data-t_id="<?php echo e($client->task_id); ?>" data-c_id="<?php echo e($client->task_cat_id); ?>"
            class="form-control budget"
            value="<?php echo e(get_data_budget_data($client->task_cat_id, $client->task_id)); ?>"></td>

    <td class="clients_col_company" id="clients_col_id_<?php echo e($client->task_id); ?>"></td>

    <td class="clients_col_company contract" id="clients_col_id_<?php echo e($client->task_id); ?>">
        <?php echo e(str_limit($client->purchase_order_total_format ?? 00, 35)); ?>

    </td>


    <td class="clients_col_company subcontractor_amount" id="clients_col_id_<?php echo e($client->task_id); ?>">
        <?php echo e(subcontractor_amount($client->task_cat_id, $client->task_id, $client->task_projectid)); ?>

    </td>

    <td class="clients_col_company pettycase" id="clients_col_id_<?php echo e($client->task_id); ?>">
        <?php echo e(get_petty_case_invoice($client->task_cat_id, $client->task_id, $client->task_projectid)); ?>

    </td>

    <?php
    $budget_data = get_data_budget_data($client->task_cat_id, $client->task_id);
    $purchase_order_total = $client->purchase_order_total;
    $subcontractor_amt = subcontractor_amount(
    $client->task_cat_id,
    $client->task_id,
    $client->task_projectid,
    );
    $petty_invoice = get_petty_case_invoice(
    $client->task_cat_id,
    $client->task_id,
    $client->task_projectid,
    );

    $surplus_deficit = $budget_data - $purchase_order_total - $subcontractor_amt - $petty_invoice;

    ?>
    <td class="clients_col_company itemized surplus_deficit" style="background-color: greenyellow;"
        id="clients_col_id_<?php echo e($client->task_id); ?>" data-surplus_deficit="<?php echo e($surplus_deficit); ?>">

        <?php echo e(number_format($surplus_deficit, 2)); ?>

    </td>
    <td class="clients_col_company" id="clients_col_id_<?php echo e($client->task_id); ?>">
        <?php echo e($client->supplier_name); ?>

    </td>

    <?php if(config('visibility.action_column')): ?>
    <td class="clients_col_action actions_column" id="clients_col_action_<?php echo e($client->task_id); ?>">
        <span class="list-table-action dropdown font-size-inherit">
            <?php if(config('visibility.action_buttons_delete')): ?>
            <button type="button" title="<?php echo e(cleanLang(__('lang.delete'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-danger btn-circle btn-sm confirm-action-danger"
                data-confirm-title="<?php echo e(cleanLang(__('lang.delete_client'))); ?>"
                data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
                data-ajax-type="DELETE" data-url="<?php echo e(url('/clients/' . $client->task_id)); ?>">
                <i class="sl-icon-trash"></i>
            </button>
            <?php endif; ?>
            <?php if(config('visibility.action_buttons_edit')): ?>
            <button type="button" title="<?php echo e(cleanLang(__('lang.edit'))); ?>"
                class="data-toggle-action-tooltip btn btn-outline-success btn-circle btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                data-toggle="modal" data-target="#commonModal"
                data-url="<?php echo e(urlResource('/clients/' . $client->task_id . '/edit')); ?>"
                data-loading-target="commonModalBody"
                data-modal-title="<?php echo e(cleanLang(__('lang.edit_client'))); ?>"
                data-action-url="<?php echo e(urlResource('/clients/' . $client->task_id . '?ref=list')); ?>"
                data-action-method="PUT" data-action-ajax-loading-target="clients-td-container">
                <i class="sl-icon-note"></i>
            </button>
            <?php endif; ?>
            <a href="<?php echo e(url('/clients/' . $client->task_id) ?? ''); ?>"
                class="btn btn-outline-info btn-circle btn-sm">
                <i class="ti-new-window"></i>
            </a>
        </span>
    </td>
    <?php endif; ?>
</tr>

<?php
$amount += $client->task_total;
$purchase_order_amount += $client->purchase_order_total;
$subcontractor_amount += subcontractor_amount(
$client->task_cat_id,
$client->task_id,
$client->task_projectid,
);
$petty_case_amount += get_petty_case_invoice(
$client->task_cat_id,
$client->task_id,
$client->task_projectid,
);
$firtt_back = '';
$i += 1;
?>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php
$atz += 1;

?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<tr>
    <td></td>
    <td></td>
    <td>Total</td>
    <td></td>
    <td><?php echo e($total_qtn); ?></td>
    <td id="budget"><?php echo e($budget_amount); ?></td>
    <td>0</td>
    
    <td><?php echo e(number_format($purchase_order_amount, 2)); ?></td>
    <td><?php echo e(number_format($subcontractor_amount, 2)); ?></td>
    <td><?php echo e(number_format($petty_case_amount, 2)); ?></td>
    <td id="total_surplus_deficit">
        <?php
        $total_surplus_deficit =
        $budget_amount - $purchase_order_amount - $subcontractor_amount - $petty_case_amount;
        ?>
        <?php echo e(number_format($total_surplus_deficit, 2)); ?>

    </td>
    <td></td>
</tr>
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/budget/components/table/ajax.blade.php ENDPATH**/ ?>