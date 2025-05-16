<!--EACH LINE ITEM X-->
<tr class="billing-line-item" id="lineitem_<?php echo e($lineitem->lineitem_id ?? ''); ?>" type="time">
    <!--action-->
    <td class="td-action list-table-action x-action bill_col_action">
        <button type="button" title="<?php echo e(cleanLang(__('lang.delete'))); ?>" class="data-toggle-tooltip btn btn-outline-danger btn-circle btn-sm js-billing-line-item-delete">
            <i class="sl-icon-trash"></i>
        </button>
    </td>

<td class="x-quantity sr_no"> </td>
<td class="form-group x-description bill_col_description">
    <input class="form-control form-control-sm item js_line_validation_item"
        name="item[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="<?php echo e($lineitem->item ?? ''); ?>">
</td>

    <!--description-->
    <td class="form-group x-description bill_col_description">
        <textarea class="form-control form-control-sm js_item_description js_line_validation_item" rows="3"
            name="js_item_description[<?php echo e($lineitem->lineitem_id ?? ''); ?>]"><?php echo e($lineitem->lineitem_description ?? ''); ?></textarea>
    </td>
    <!--quantity-->
    <td class="form-group x-quantity bill_col_quantity">
        <!--hrs-->
        <div class="input-group input-group-sm m-b-4">
            <span class="input-group-addon" id="fx-line-item-hrs"><?php echo e(cleanLang(__('lang.hrs'))); ?></span>
            <input type="number" class="form-control js_item_hours calculation-element js_line_validation_item" name="js_item_hours[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="<?php echo e($lineitem->lineitem_time_hours ?? ''); ?>">
        </div>
        <!--mins-->
        <div class="input-group input-group-sm">
            <span class="input-group-addon" id="fx-line-item-min"><?php echo e(cleanLang(__('lang.mins'))); ?></span>
            <input type="number" class="form-control js_item_minutes calculation-element js_line_validation_item" name="js_item_minutes[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="<?php echo e($lineitem->lineitem_time_minutes ?? ''); ?>">
        </div>
    </td>
    <!--units (hrs)-->
    <td class="form-group x-unit bill_col_unit">
        <input class="form-control form-control-sm js_item_unit js_line_validation_item" type="text"
            name="js_item_unit[<?php echo e($lineitem->lineitem_id ?? ''); ?>]"  value="<?php echo e($lineitem->lineitem_unit ?? ''); ?>">
    </td>
    <!--rate-->
    <td class="form-group x-price bill_col_price">
        <input
            class="form-control form-control-sm js_item_rate calculation-element decimal-field js_line_validation_item"
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
        <input type="number" class="js_linetax_total" name="js_linetax_rate[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="0">
    </td>
    <!--total-->
    <td class="form-group x-total" id="bill_col_total">
        <input class="form-control form-control-sm js_item_total decimal-field" type="number" step="0.01"
            name="js_item_total[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="<?php echo e($lineitem->lineitem_total ?? ''); ?>" disabled>
    </td>

    <!--line item type-->
    <input type="hidden" class="js_item_type" name="js_item_type[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="time">
    <input type="hidden" class="js_item_linked_type" name="js_item_linked_type[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="timer">
    <input type="hidden" class="js_item_linked_id" name="js_item_linked_id[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="<?php echo e($lineitem->lineitemresource_linked_id ?? ''); ?>">
    <input type="hidden" class="js_item_timers_list" name="js_item_timers_list[<?php echo e($lineitem->lineitem_id ?? ''); ?>]" value="<?php echo e($lineitem->lineitem_time_timers_list ?? ''); ?>">

</tr>
<!--/#EACH LINE ITEM-->
<?php /**PATH C:\xampp\htdocs\orion-ci-laravel\application\resources\views/pages/bill/components/elements/line-time.blade.php ENDPATH**/ ?>