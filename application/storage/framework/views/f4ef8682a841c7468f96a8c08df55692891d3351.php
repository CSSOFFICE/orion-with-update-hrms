<div class="form-group row">
    <label for="example-month-input" class="col-12 col-form-label text-left"> <?php echo e(cleanLang(__('lang.change_status'))); ?></label>
    <div class="col-12">
        <select class="select2-basic form-control form-control-sm" id="category" name="category">
            <?php $__currentLoopData = config('settings.quo_statuses'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($key); ?>"><?php echo e(runtimeLang($key)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/misc/change-category.blade.php ENDPATH**/ ?>