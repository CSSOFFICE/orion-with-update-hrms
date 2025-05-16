<!--main table view-->
<?php echo $__env->make('pages.tickets.components.table.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!--filter-->
<?php if(auth()->user()->is_team): ?>
<?php echo $__env->make('pages.tickets.components.misc.filter-tickets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<!--filter--><?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/tickets/components/table/wrapper.blade.php ENDPATH**/ ?>