<div class="row">
    <div class="col-12">
        <div class="table-responsive receipt">
            <table class="table table-bordered">
                <tbody>
                    <!--date-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.date'))); ?></td>
                        <td><?php echo e(runtimeDate($expense->expense_date)); ?></td>
                    </tr>
                    <!--client-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.client'))); ?></td>
                        <td><?php echo e($expense->client_company_name); ?></td>
                    </tr>
                    <!--project-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.project'))); ?></td>
                        <td><?php echo e($expense->project_title); ?></td>
                    </tr>
                    <!--user-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.recorded_by'))); ?></td>
                        <td><?php echo e($expense->first_name); ?> <?php echo e($expense->last_name); ?></td>
                    </tr>
                    <!--description-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.description'))); ?></td>
                        <td><?php echo e($expense->expense_description); ?></td>
                    </tr>
                    <!--Attchment-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.attachement'))); ?></td>
                        <td>
                            <?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <ul class="p-l-0">
                                <li  id="fx-expenses-files-attached">
                                    <a href="expenses/attachments/download/<?php echo e($attachment->attachment_uniqiueid); ?>" download>
                                        <?php echo e($attachment->attachment_filename); ?> <i class="ti-download"></i>
                                    </a>
                                </li>
                            </ul>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    </tr>
                    <!--date-->
                    <!--description-->
                    <tr>
                        <td><?php echo e(cleanLang(__('lang.financial'))); ?></td>
                        <td>
                            <span
                                class="label <?php echo e(runtimeExpenseStatusColors($expense->expense_billable, 'label')); ?>"><?php echo e(runtimeLang($expense->expense_billable)); ?></span> <span
                                class="label <?php echo e(runtimeExpenseStatusColors($expense->expense_billing_status, 'label')); ?>"><?php echo e(runtimeLang($expense->expense_billing_status)); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td id="fx-expenses-td-amount"><?php echo e(cleanLang(__('lang.amount'))); ?></td>
                        <td id="fx-expenses-td-money"><?php echo e(runtimeMoneyFormat($expense->expense_amount)); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div><?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/expenses/components/modals/expense.blade.php ENDPATH**/ ?>