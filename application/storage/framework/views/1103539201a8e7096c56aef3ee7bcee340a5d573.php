<!-- action buttons -->
<?php echo $__env->make('misc.list-pages-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- action buttons -->

<!--stats panel-->
<div id="projects-stats-wrapper" class="stats-wrapper card-embed-fix">
<?php if(@count($projects) > 0 && auth()->user()->is_team): ?> <?php echo $__env->make('misc.list-pages-stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <?php endif; ?>
</div>
<!--stats panel-->

<!--projects table-->
<div class="card-embed-fix">
<?php echo $__env->make('pages.projects.components.table.wrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<!--projects table--><?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/projects/tabswrapper.blade.php ENDPATH**/ ?>