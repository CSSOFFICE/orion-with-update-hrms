<?php
    use Illuminate\Support\Facades\DB;

    $pro = DB::table('product')->get();
?>

<!--EACH LINE ITEM X-->
<input type="hidden" name="rev_no" value="<?php echo e($bill->rev_no); ?>">
<input type="hidden" name="bill_status" value="<?php echo e($bill->bill_status); ?>">

<tr class="billing-line-item" id="lineitem_<?php echo e($lineitem->lineitem_id ?? ''); ?>" type="plain">
    <!--action-->

    <!-- <td></td> -->
    <td class="x-quantity sr_no">
        <button type="button" title="<?php echo e(cleanLang(__('lang.delete'))); ?>"
            class="data-toggle-tooltip babagd btn btn-outline-danger btn-circle btn-sm js-billing-line-item-delete">
            <i class="sl-icon-trash"></i>
        </button>
    </td>


    <td class="x-quantity sr_no"> </td>
    <td class="form-group">

        <select name="item[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" id=""
            class="form-control product_bame_select form-control-sm item js_item_item js_line_validation_item">
            <option value="<?php echo e($lineitem->item ?? ''); ?>" data-id=""><?php echo e($lineitem->item ?? 'Select Product'); ?></option>
            <?php $__currentLoopData = $pro; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?= $p->product_name ?>" data-id="<?= $p->product_id ?>"><?= $p->product_name ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

    </td>

    <!--description-->
    <td class="form-group x-description bill_col_description">
        <textarea class="form-control babag  form-control-sm js_item_description js_line_validation_item product_description" rows="3"
            name="js_item_description[<?php echo e($lineitem->lineitem_id ?? ''); ?>]"><?php echo e($lineitem->lineitem_description ?? ''); ?></textarea>
    </td>
    <!--quantity-->
    <td class="form-group x-quantity bill_col_quantity">
        <input class="form-control babag form-control-sm js_item_quantity calculation-element js_line_validation_item"
            type="number" step="1" name="js_item_quantity[<?php echo e($lineitem->lineitem_id ?? ''); ?>]"
            value="<?php echo e($lineitem->lineitem_quantity ?? ''); ?>">
    </td>
    <!--unit-->
    <td class="form-group x-unit bill_col_unit">
        <input class="form-control babag form-control-sm js_item_unit js_line_validation_item" type="text"
            name="js_item_unit[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="<?php echo e($lineitem->lineitem_unit ?? ''); ?>">
    </td>
    <!--rate-->
    <td class="form-group x-price bill_col_price">
        <input
            class="form-control babag form-control-sm js_item_rate calculation-element decimal-field js_line_validation_item"
            type="number" step="1" name="js_item_rate[<?php echo e($lineitem->lineitem_id ?? ''); ?>]"
            value="<?php echo e($lineitem->lineitem_rate ?? ''); ?>">
    </td>
    <!--tax-->
    <td
        class="bill_col_tax form-group x-tax <?php echo e(runtimeVisibility('invoice-column-inline-tax', $bill->bill_tax_type)); ?> ">
        <select name="js_linetax_rate[<?php echo e($lineitem->lineitem_id ?? ''); ?>]"
            class="form-control form-control-sm select2-x js_linetax_rate">
            <option value="10">VAT(10%)</option>
            <option value="15">Sales tax (15%)</option>
            <option value="20">Income Tax (20%)</option>
        </select>
        <input type="number" class="js_linetax_total" name="js_linetax_rate[<?php echo e($lineitem->lineitem_id ?? ''); ?>]"
            value="0">
    </td>
    <!--total-->
    <td class="form-group x-total" id="bill_col_total">
        <input class="form-control form-control-sm js_item_total decimal-field" type="number" step="0.01"
            name="js_item_total[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="<?php echo e($lineitem->lineitem_total ?? ''); ?>"
            disabled>
    </td>

    <!--linked items-->
    <input type="hidden" class="js_item_linked_type"
        data-duplicate-check="<?php echo e($lineitem->lineitemresource_linked_type ?? ''); ?>|<?php echo e($lineitem->lineitemresource_linked_id ?? ''); ?>"
        name="js_item_linked_type[<?php echo e($lineitem->lineitem_id ?? ''); ?>]"
        value="<?php echo e($lineitem->lineitemresource_linked_type ?? ''); ?>">
    <input type="hidden" class="js_item_linked_id" name="js_item_linked_id[<?php echo e($lineitem->lineitem_id ?? ''); ?>]"
        value="<?php echo e($lineitem->lineitemresource_linked_id ?? ''); ?>">
    <input type="hidden" class="js_item_type" name="js_item_type[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="product">

    <!--line item type-->
    <input type="hidden" class="js_item_type" name="js_item_type[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="product">

</tr>
<!--/#EACH LINE ITEM-->
<?php /**PATH C:\xampp\htdocs\orion-ci-laravel\application\resources\views/pages/bill/components/elements/line-product.blade.php ENDPATH**/ ?>