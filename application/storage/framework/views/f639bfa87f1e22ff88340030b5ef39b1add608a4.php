<!--ALL THIRD PART JAVASCRIPTS-->
<script src="public/vendor/js/vendor.footer.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--nextloop.core.js-->
<script src="public/js/core/ajax.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--MAIN JS - AT END-->
<script src="public/js/core/boot.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--EVENTS-->
<script src="public/js/core/events.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--CORE-->
<script src="public/js/core/app.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--BILLING-->
<script src="public/js/core/billing.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--GMAPS-->
<script src="public/js/core/gmap.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--project page charts-->
<?php if(@config('visibility.projects_d3_vendor')): ?>
    <script src="public/vendor/js/d3/d3.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>
    <script src="public/vendor/js/c3-master/c3.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>
<?php endif; ?>

<!--stripe payments js-->
<?php if(@config('visibility.stripe_js')): ?>
    <script src="https:js.stripe.com/v3/"></script>
<?php endif; ?>
<?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/layout/footerjs.blade.php ENDPATH**/ ?>