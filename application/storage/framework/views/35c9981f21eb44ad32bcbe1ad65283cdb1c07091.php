<?php if(isset($page['page']) && $page['page'] == 'estimates'): ?>
<?php echo $__env->make('pages.variation.components.actions.checkbox-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<!--main table view-->
<?php echo $__env->make('pages.variation.components.table.table', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!--filter-->
<?php if(auth()->user()->is_team): ?>
<?php echo $__env->make('pages.variation.components.misc.filter-estimates', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<!--filter-->
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/variation/components/table/wrapper.blade.php ENDPATH**/ ?>