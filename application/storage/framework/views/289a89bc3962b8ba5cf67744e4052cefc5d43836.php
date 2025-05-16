<style>
    #plainModalContainer {
        width: 100%; /* Set your desired width */
        max-width: 700px; /* Optionally set a max width */
    }
</style>

<div class="row">
<div class="col-12">
    <div class="table-responsive receipt">
        <table class="table table-bordered">
            <tbody>
                <!--po-->
                <tr>
                    <td>Purchase Order Number</td>
                    <td><?php echo e($expense->porder_id); ?></td>
                </tr>

                <!--date-->
                <tr>
                    <td><?php echo e(cleanLang(__('lang.date'))); ?></td>
                    <td><?php echo e(runtimeDate($expense->expense_date)); ?></td>
                </tr>

                <!--client-->
                

                <!--project-->
                

                <!--user-->
                

                <!--description-->
                

                <!--Attchment-->
                

                <!--Attchment-->
                

                <!--date-->
                <!--description-->
                

                <tr>
                    <td id="fx-expenses-td-amount"><?php echo e(cleanLang(__('lang.amount'))); ?></td>
                    <td id="fx-expenses-td-money"><?php echo e(runtimeMoneyFormat($expense->total_amount)); ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Invoice No</td>
                    <td>Total Amount</td>
                    <td>Invoice Date</td>
                    <td>DO Number</td>
                    <td>Attachment</td>
                    <td>Status</td>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $xin_payable_total; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->invoice_no); ?></td>
                        <td><?php echo e($item->after_gst_po_gt); ?></td>
                        <td style="width:200px"><?php echo e(runtimeDate($expense->expense_date)); ?></td>
                        <td style="width:200px"><?php echo e($item->do_no); ?></td>
                        <td>

                         <?php if(!empty($item->exp_attachment)): ?>
                            <a href="<?php echo e(url('hrms/uploads/payment/'.$item->exp_attachment)); ?>" target="_blank">Click here to view</a>
                        <?php else: ?>
                            ---
                        <?php endif; ?>
                        </td>
                        <td><?php echo e(!empty($item->status) ? $item->status : 'Not Paid'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
</div>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/expenses/components/modals/expense.blade.php ENDPATH**/ ?>