<!-- action buttons -->
<?php echo $__env->make('misc.list-pages-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- action buttons -->

<!--stats panel-->
<?php if(auth()->user()->is_team): ?>
<div id="contacts-stats-wrapper" class="stats-wrapper card-embed-fix">
<?php if(@count($address) > 0): ?> <?php echo $__env->make('misc.list-pages-stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <?php endif; ?>
</div>
<?php endif; ?>
<!--stats panel-->

<!--contacts table-->
<div class="card-embed-fix">
<?php echo $__env->make('pages.address.components.table.wrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<!--contacts table-->
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/address/tabswrapper.blade.php ENDPATH**/ ?>