        <!--HEADER-->
        
        <div class="row">
            <div class="col-lg-12">
            <div class="invoice-logo"><img width="200" src="<?php echo e(url('public/logo/logo.png')); ?>" alt="Invoice logo"  class="img-fluid">
            <img src="<?php echo e(url('public/logo/new_header.jpeg')); ?>" alt="Sample Image" width="auto" height="200"></div>
            </div>
        </div>

        <div class="d-none">
            <span class="pull-left">
                <h3><b><?php echo e(cleanLang(__('lang.estimate'))); ?></b>
                </h3>
                <span>
                    <!-- <?php echo e(print_r($bill)); ?> -->
                    <h5><?php echo e($bill->quotation_no); ?></h5>
                </span>
            </span>
            <!--status-->
            <span class="pull-right">
                <!--draft-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('draft', $bill->bill_status)); ?>"
                    id="estimate-status-draft">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('draft', 'text')); ?> muted"><?php echo e(cleanLang(__('lang.draft'))); ?></h1>
                </span>
                <!--new-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('new', $bill->bill_status)); ?>"
                    id="estimate-status-new">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('new', 'text')); ?>"><?php echo e(cleanLang(__('lang.new'))); ?></h1>
                </span>
                <!--accepted-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('accepted', $bill->bill_status)); ?>"
                    id="estimate-status-accpeted">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('accepted', 'text')); ?>"><?php echo e(cleanLang(__('lang.accepted'))); ?></h1>
                </span>
                <!--declined-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('declined', $bill->bill_status)); ?>"
                    id="estimate-status-declined">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('declined', 'text')); ?>"><?php echo e(cleanLang(__('lang.declined'))); ?></h1>
                </span>
                <!--revised-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('revised', $bill->bill_status)); ?>"
                    id="estimate-status-revised">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('revised', 'text')); ?>"><?php echo e(cleanLang(__('lang.revised'))); ?></h1>
                </span>
                <!--expired-->
                <span class="js-estimate-statuses <?php echo e(runtimeEstimateStatus('expired', $bill->bill_status)); ?>"
                    id="estimate-status-expired">
                    <h1 class="text-uppercase <?php echo e(runtimeEstimateStatusColors('expired', 'text')); ?>"><?php echo e(cleanLang(__('lang.expired'))); ?></h1>
                </span>
            </span>
        </div>
<?php /**PATH C:\xampp\htdocs\orion-ci-laravel\application\resources\views/pages/bill/components/elements/estimate/header-web.blade.php ENDPATH**/ ?>