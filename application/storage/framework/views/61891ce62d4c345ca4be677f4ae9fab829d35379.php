<div class="col-12" id="bill-totals-wrapper">
    <!--total amounts-->
    <div class="pull-right mt-2 text-right">

        <table class="invoice-total-table">

            <!--invoice amount-->
            <tbody id="billing-table-section-subtotal" class="<?php echo e($bill->visibility_subtotal_row); ?>">
                <tr>
                    <td><?php echo e(cleanLang(__('lang.subtotal'))); ?></td>
                    <td id="billing-subtotal-figure">
                        <span><?php echo e(runtimeMoneyFormat($bill->bill_subtotal)); ?></span>
                    </td>
                </tr>
            </tbody>

            <!--discounted invoice-->
            <tbody id="billing-table-section-discounts" class="<?php echo e($bill->visibility_discount_row); ?>">
<input type="hidden" name="abc" id="baba_id" value="">

                <tr id="billing-sums-discount-container">
                    <?php if($bill->bill_discount_type == 'percentage'): ?>
                    <td><?php echo e(cleanLang(__('lang.discount'))); ?> <span class="x-small"
                            id="dom-billing-discount-type">(<?php echo e($bill->bill_discount_percentage); ?>%)</span>
                    </td>
                    <?php else: ?>
                    <td><?php echo e(cleanLang(__('lang.discount'))); ?> <span class="x-small" id="dom-billing-discount-type">(<?php echo e(cleanLang(__('lang.fixed'))); ?>)</span></td>
                    <?php endif; ?>
                    <td id="billing-sums-discount">
                        <?php echo e(runtimeMoneyFormat($bill->bill_discount_amount)); ?>

                    </td>
                </tr>
                <tr id="billing-sums-before-tax-container" class="<?php echo e($bill->visibility_before_tax_row); ?>">
                    <td>Total <span class="x-small">(<?php echo e(cleanLang(__('lang.before_tax'))); ?>)</span></td>
                    <td id="billing-sums-before-tax">
                        <span><?php echo e(runtimeMoneyFormat($bill->bill_amount_before_tax)); ?></span></td>
                </tr>
            </tbody>

           

            <!--invoice total-->
            <tbody id="invoice-table-section-total">
                <tr class="text-themecontrast d-none" id="billing-sums-total-container">
                    <td class="billing-sums-total"><?php echo e(cleanLang(__('lang.invoice_total'))); ?></td>
                    <td id="billing-sums-total">
                        <span><?php echo e(runtimeMoneyFormat($bill->bill_final_amount)); ?></span>
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/orion/application/resources/views/pages/bill/components/elements/totals-table.blade.php ENDPATH**/ ?>