<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.milestone_name'))); ?>*</label>
    <div class="col-12">
        <input type="text" class="form-control  form-control-sm" autocomplete="off" id="milestone_title"
            name="milestone_title" value="<?php echo e($milestone->milestone_title ?? ''); ?>">
        <input type="hidden" name="milestone_projectid" value="<?php echo e(request('project_id')); ?>">
    </div>
</div><?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/milestones/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>