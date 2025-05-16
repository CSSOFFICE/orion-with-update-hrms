<div class="form-group row">
    <label class="col-sm-12 col-lg-3 text-left control-label col-form-label">Project Category</label>
    <div class="col-sm-12 col-lg-9">
        <select class="form-control form-control-sm" required id="project_cat" name="project_cat">
            <option value="">Please Select</option>
            <option value="<?php echo e($project->project_cat ?? 'Project'); ?>">Project</option>
            <option value="<?php echo e($project->project_cat ?? 'Maintenance'); ?>">Maintenance</option>
            <option value="<?php echo e($project->project_cat ?? 'Manpower supply'); ?>">Manpower supply</option>
            <option value="<?php echo e($project->project_cat ?? 'Spare parts, Equipment'); ?>">Spare parts, Equipment
            </option>
            <option value="<?php echo e($project->project_cat ?? 'LSS (Life Safety System)'); ?>">LSS (Life Safety System)
            </option>
            <option value="<?php echo e($project->project_cat ?? 'TGCM'); ?>">TGCM</option>
        </select>
    </div>
</div>

<div class="form-group row">
    <label
        class="col-sm-12 col-lg-3 text-left control-label col-form-label " ><?php echo e(cleanLang(__('lang.project_title'))); ?>*</label>
    <div class="col-sm-12 col-lg-9">
        <input type="text" required class="form-control form-control-sm" required id="project_title" name="project_title"
            placeholder="" value="<?php echo e($project->project_title ?? ''); ?>">
    </div>
</div>
<div class="form-group row">
    <label
        class="col-sm-12 col-lg-3 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.start_date'))); ?>*</label>
    <div class="col-sm-12 col-lg-9">
        <input type="text" required class="form-control form-control-sm pickadate" name="project_date_start"
            autocomplete="off" value="<?php echo e(runtimeDatepickerDate($project->project_date_start ?? '')); ?>">
        <input class="mysql-date" type="hidden" name="project_date_start" id="project_date_start"
            value="<?php echo e($project->project_date_start ?? ''); ?>">
    </div>
</div>
<input type="hidden" name="project_clientid" value="<?php echo e($estimate->bill_clientid); ?>">
<div class="form-group row">
    <label
        class="col-sm-12 col-lg-3 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.deadline'))); ?></label>
    <div class="col-sm-12 col-lg-9">
        <input type="text" class="form-control form-control-sm pickadate" name="project_date_due" autocomplete="off"
            value="<?php echo e(runtimeDatepickerDate($project->project_date_due ?? '')); ?>">
        <input class="mysql-date" type="hidden" name="project_date_due" id="project_date_due"
            value="<?php echo e($project->project_date_due ?? ''); ?>">
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/estimates/components/actions/add-form.blade.php ENDPATH**/ ?>