<!--bulk actions-->
<?php echo $__env->make('pages.expenses.components.actions.checkbox-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!--main table view-->
<?php echo $__env->make('pages.expenses.components.table.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!--filter-->
<?php if(auth()->user()->is_team): ?>
<?php echo $__env->make('pages.expenses.components.misc.filter-expenses', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<!--filter-->
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/expenses/components/table/wrapper.blade.php ENDPATH**/ ?>