<!--ALL THIRD PART JAVASCRIPTS-->
<script src="/css/orion/public/vendor/js/vendor.footer.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--nextloop.core.js-->
<script src="/css/orion/public/js/core/ajax.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--MAIN JS - AT END-->
<script src="/css/orion/public/js/core/boot.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--EVENTS-->
<script src="/css/orion/public/js/core/events.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--CORE-->
<script src="/css/orion/public/js/core/app.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--BILLING-->
<script src="/css/orion/public/js/core/billing.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--GMAPS-->
<script src="/css/orion/public/js/core/gmap.js?v=<?php echo e(config('system.versioning')); ?>"></script>

<!--project page charts-->
<?php if(@config('visibility.projects_d3_vendor')): ?>
    <script src="/css/orion/public/vendor/js/d3/d3.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>
    <script src="/css/orion/public/vendor/js/c3-master/c3.min.js?v=<?php echo e(config('system.versioning')); ?>"></script>
<?php endif; ?>

<!--stripe payments js-->
<?php if(@config('visibility.stripe_js')): ?>
    <script src="https:js.stripe.com/v3/"></script>
<?php endif; ?>
<?php /**PATH E:\xampp\htdocs\css\orion\application\resources\views/layout/footerjs.blade.php ENDPATH**/ ?>