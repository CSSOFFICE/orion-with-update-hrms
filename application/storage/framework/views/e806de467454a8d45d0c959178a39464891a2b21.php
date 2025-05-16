<!--bulk actions-->
<?php echo $__env->make('pages.addressb.components.actions.checkbox-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!--main table view-->
<?php echo $__env->make('pages.addressb.components.table.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!--filter-->
<?php if(auth()->user()->is_team): ?>
<?php echo $__env->make('pages.addressb.components.misc.filter-contacts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<!--filter-->
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/addressb/components/table/wrapper.blade.php ENDPATH**/ ?>