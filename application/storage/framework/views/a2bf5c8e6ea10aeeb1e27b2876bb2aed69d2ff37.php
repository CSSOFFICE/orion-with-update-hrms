<!--main table view-->
<?php echo $__env->make('pages.clients.components.table.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!--filter-->
<?php if(auth()->user()->is_team): ?>
<?php echo $__env->make('pages.clients.components.misc.filter-clients', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<!--filter--><?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/clients/components/table/wrapper.blade.php ENDPATH**/ ?>