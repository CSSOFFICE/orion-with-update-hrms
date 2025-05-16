
<?php $__env->startSection('settings-page'); ?>
<!--settings-->
<form class="form" id="settingsFormProjects">
    <!--form text tem-->
    <div class="form-group row">
        <label class="col-3 control-label col-form-label"><?php echo e(cleanLang(__('lang.default_hourly_rate'))); ?></label>
        <div class="col-3">
            <input type="number" class="form-control form-control-sm" id="settings_projects_default_hourly_rate"
                name="settings_projects_default_hourly_rate" value="<?php echo e($settings->settings_projects_default_hourly_rate ?? ''); ?>">
        </div>
    </div>
    <div class="text-right">
        <button type="submit" id="commonModalSubmitButton"
            class="btn btn-rounded-x btn-danger waves-effect text-left"
            data-url="/settings/projects/general" data-loading-target="" data-ajax-type="PUT" data-type="form"
            data-on-start-submit-button="disable"><?php echo e(cleanLang(__('lang.save_changes'))); ?></button>
    </div>

    
    <div>
        <!--settings documentation help-->
        <!-- <a href="https://growcrm.io/documentation/project-settings/"  target="_blank" class="btn btn-sm btn-info help-documentation"><i class="ti-info-alt"></i> <?php echo e(cleanLang(__('lang.help_documentation'))); ?></a> -->
    </div>

</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('pages.settings.ajaxwrapper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/settings/sections/projects/general.blade.php ENDPATH**/ ?>