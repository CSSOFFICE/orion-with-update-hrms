<!--CRUMBS CONTAINER (LEFT)-->
<div class="col-md-12 <?php echo e(runtimeCrumbsColumnSize($page['crumbs_col_size'] ?? '')); ?> align-self-center <?php echo e($page['crumbs_special_class'] ?? ''); ?>" id="breadcrumbs">
    <h3 class="text-themecolor"><?php echo e($page['heading']); ?></h3>
    <!--crumbs-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo e(cleanLang(__('lang.app'))); ?></li>
        <?php if(isset($page['crumbs'])): ?>
        <?php $__currentLoopData = $page['crumbs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="breadcrumb-item <?php if($loop->last): ?> active active-bread-crumb <?php endif; ?>"><?php echo e($title ?? ''); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </ol>
    <!--crumbs-->
    <?php if($title=="Customer"): ?>

    <div class="d-flex ">
        <form id="header-search_by_month">
            <div class="form-check">
                <input class="form-check-input client_status" type="radio" id="exampleRadio1" class="btn icon-btn btn-light float-right border" data-form-id="header-search_by_month" name="client_status" data-ajax-type="post" data-type="form" data-url="<?php echo e($page['dynamic_search_url'] ?? ''); ?>" value="1" <?php echo e($cust_type == 1 ? 'checked' : ''); ?>>
                <label class="form-check-label" for="exampleRadio1">
                    Individual
                </label>
            </div>
        </form>
        <form id="header-search_by_month_b">
            <div class="d-flex">
                <div class="form-check">
                    <input class="form-check-input client_status_b" type="radio" id="exampleRadio2" class="btn icon-btn btn-light float-right border" data-form-id="header-search_by_month_b" name="client_status" data-ajax-type="post" data-type="form" data-url="<?php echo e($page['dynamic_search_url'] ?? ''); ?>" value="2" <?php echo e($cust_type == 2 ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="exampleRadio2">
                        Company
                    </label>
                </div>


            </div>

        </form>
    </div>

    <?php endif; ?>
</div>

<!--include various checkbox actions-->

<?php if(isset($page['page']) && $page['page'] == 'files'): ?>
<?php echo $__env->make('pages.files.components.actions.checkbox-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<?php if(isset($page['page']) && $page['page'] == 'notes'): ?>
<?php echo $__env->make('pages.notes.components.actions.checkbox-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/misc/heading-crumbs.blade.php ENDPATH**/ ?>