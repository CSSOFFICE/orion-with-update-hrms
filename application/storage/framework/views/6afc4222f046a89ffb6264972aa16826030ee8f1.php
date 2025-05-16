<!--title-->
<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.title'))); ?>*</label>
    <div class="col-12">
        <input type="text" class="form-control form-control-sm" id="knowledgebase_title" name="knowledgebase_title"
            value="<?php echo e($knowledgebase->knowledgebase_title ?? ''); ?>">
    </div>
</div>

<!--description-->
<div class="form-group row">
    <label class="col-12 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.description'))); ?>*</label>
    <div class="col-12">
        <textarea class="form-control form-control-sm tinymce-textarea" rows="5" name="knowledgebase_text"
            id="knowledgebase_text"><?php echo e($knowledgebase->knowledgebase_text ?? ''); ?></textarea>
    </div>
</div>

<!--category-->
<div class="form-group row">
    <label for="example-month-input"
        class="col-12 control-label  col-form-label text-left required"><?php echo e(cleanLang(__('lang.category'))); ?>*</label>
    <div class="col-12">
        <select class="select2-basic form-control form-control-sm" id="knowledgebase_categoryid"
            name="knowledgebase_categoryid">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($category->kbcategory_id); ?>"
                <?php echo e(runtimePreselected(@request('knowledgebase_categoryid'), $category->kbcategory_id)); ?>><?php echo e(runtimeLang($category->kbcategory_title)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<!--notes-->
<div class="row">
    <div class="col-12">
        <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
    </div>
</div><?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/knowledgebase/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>