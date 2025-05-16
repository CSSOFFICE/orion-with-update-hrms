<div class="form-group row">
    <label for="example-month-input" class="col-12 col-form-label text-left"><?php echo e(cleanLang(__('lang.project'))); ?></label>
    <div class="col-12">
        <select name="attach_project_id" id="attach_project_id" class="form-control form-control-sm js-select2-basic-search select2-hidden-accessible"
        data-ajax--url="<?php echo e($payload['projects_feed_url']); ?>"></select>
    </div>
</div><?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/estimates/components/actions/attach-project.blade.php ENDPATH**/ ?>