<!-- action buttons -->
<?php echo $__env->make('misc.list-pages-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- action buttons -->

<!--stats panel-->
<?php if(auth()->user()->is_team): ?>
<div id="payments-stats-wrapper" class="stats-wrapper card-embed-fix">
<?php if(@count($payments) > 0): ?> <?php echo $__env->make('misc.list-pages-stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <?php endif; ?>
</div>
<?php endif; ?>
<!--stats panel-->

<!--payments table-->
<div class="card-embed-fix">
<?php echo $__env->make('pages.payments.components.table.wrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<!--payments table--><?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/payments/tabswrapper.blade.php ENDPATH**/ ?>