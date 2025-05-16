<!--ALL THIRD PART JAVASCRIPTS-->
<script src="/orion-ci-laravel/public/vendor/js/vendor.footer.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--nextloop.core.js-->
<script src="/orion-ci-laravel/public/js/core/ajax.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--MAIN JS - AT END-->
<script src="/orion-ci-laravel/public/js/core/boot.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--EVENTS-->
<script src="/orion-ci-laravel/public/js/core/events.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--CORE-->
<script src="/orion-ci-laravel/public/js/core/app.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--BILLING-->
<script src="/orion-ci-laravel/public/js/core/billing.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--GMAPS-->
<script src="/orion-ci-laravel/public/js/core/gmap.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--project page charts-->
<?php if(@config('visibility.projects_d3_vendor')): ?>
    <script src="/orion-ci-laravel/public/vendor/js/d3/d3.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>
    <script src="/orion-ci-laravel/public/vendor/js/c3-master/c3.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>
<?php endif; ?>

<!--stripe payments js-->
<?php if(@config('visibility.stripe_js')): ?>
    <script src="https:/orion-ci-laravel/js.stripe.com/v3/"></script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\orion-ci-laravel\application\resources\views/layout/footerjs.blade.php ENDPATH**/ ?>