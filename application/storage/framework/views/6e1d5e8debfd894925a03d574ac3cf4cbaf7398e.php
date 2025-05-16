<div class="card count-<?php echo e(@count($subtasks)); ?>" id="subtasks-view-wrapper">

    <div class="card-body">
        <div class="table-responsive list-table-wrapper">

            <?php if(@count($subtasks) > 0): ?>
            <table id="tasks-list-table" class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10"
                data-url="<?php echo e(url('/')); ?>/subtasks/timer-poll/" data-type="form" data-ajax-type="post"
                data-form-id="tasks-list-table">
                <thead>
                    <tr>

                    <th class="tasks_col_action"><a href="javascript:void(0)"><?php echo e(cleanLang(__('lang.tasks'))); ?></a></th>

                        <th class="tasks_col_action"><a href="javascript:void(0)"><?php echo e(cleanLang(__('lang.task_description'))); ?></a></th>
                        <th class="tasks_col_action"><a href="javascript:void(0)"><?php echo e(cleanLang(__('lang.task_detail'))); ?></a></th>
                        <th class="tasks_col_action hidden"><a href="javascript:void(0)"><?php echo e(cleanLang(__('lang.unit'))); ?></a></th>
                        <th class="tasks_col_action"><a href="javascript:void(0)"><?php echo e(cleanLang(__('lang.unit_rate'))); ?></a></th>

                        <th class="tasks_col_action"><a href="javascript:void(0)"><?php echo e(cleanLang(__('lang.action'))); ?></a></th>
                    </tr>
                </thead>
                <tbody id="tasks-td-container">
                    <!--ajax content here-->
                     <?php echo $__env->make('pages.subtasks.components.table.ajax', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <!--ajax content here-->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="20">
                            <!--load more button-->
                             <?php echo $__env->make('misc.load-more-button', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <!--load more button-->
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php endif; ?>
            <?php if(@count($subtasks) == 0): ?>
            <!--nothing found-->
            <?php echo $__env->make('notifications.no-results-found', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <!--nothing found-->
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/subtasks/components/table/table.blade.php ENDPATH**/ ?>