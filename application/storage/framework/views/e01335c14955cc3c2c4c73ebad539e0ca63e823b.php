
<div class="invoice-item-actions">

    <!--add blank line-->
    

    <!--add time line-->
    



    


    <!--add product item-->
    


    <!--[invoices] add expense-->
    <?php if($bill->bill_type == 'invoice'): ?>
        <button type="button"
            class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon actions-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#expensesModal"
            data-modal-title="<?php echo e(cleanLang(__('lang.change_category'))); ?>" data-reset-loading-target="true"
            data-url="<?php echo e(url('/expenses?action=search&itemresource_type=invoice&expense_billable=billable&expense_billing_status=not_invoiced&dom_reset=skip&filter_expense_projectid=' . $bill->bill_projectid)); ?>"
            data-loading-target="expenses-table-wrapper"><i class="mdi mdi-cash-usd text-themecontrast"></i>
            <span><?php echo e(cleanLang(__('lang.expense'))); ?></span></button>

        <!--[invoices] add time sheet-->
        <button type="button"
            class="btn btn-secondary btn-rounded btn-sm btn-rounded-icon actions-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#timebillingModal"
            data-modal-title="<?php echo e(cleanLang(__('lang.change_category'))); ?>" data-reset-loading-target="true"
            data-url="<?php echo e(url('/invoices/timebilling/' . $bill->bill_projectid . '?grouping=tasks')); ?>"
            data-loading-target="timebilling-table-wrapper"><i class="mdi mdi-calendar-clock text-themecontrast"></i>
            <span><?php echo e(cleanLang(__('lang.hours_worked'))); ?></span></button>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\orion-ci-laravel\application\resources\views/pages/bill/components/misc/add-line-buttons.blade.php ENDPATH**/ ?>