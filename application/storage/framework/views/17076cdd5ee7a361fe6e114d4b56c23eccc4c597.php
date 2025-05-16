<!--fileupload-->
<div class="form-group row">
    <div class="col-12">
        <div class="dropzone dz-clickable" id="fileupload_files">
            <div class="dz-default dz-message">
                <i class="icon-Upload-toCloud"></i>
                <span><?php echo e(cleanLang(__('lang.drag_drop_file'))); ?></span>
            </div>
        </div>
    </div>

    <input type="hidden" name="fileresource_id" value="<?php echo e(request('fileresource_id')); ?>">
    <input type="hidden" name="fileresource_type" value="<?php echo e(request('fileresource_type')); ?>">
</div>

<?php if(config('visibility.file_modal_visible_to_client_option')): ?>
<div class="form-group form-group-checkbox row p-l-15">
    <label class="float-left"><?php echo e(cleanLang(__('lang.visible_to_client'))); ?>?</label>
    <div class="float-left clearfix p-t-0 p-l-10">
        <input type="checkbox" id="file_visibility_client" name="file_visibility_client"
            class="filled-in chk-col-light-blue" checked="checked">
        <label for="file_visibility_client"></label>
    </div>
</div>
<?php endif; ?>

<!--fileupload-->
<!--pass source-->
<input type="hidden" name="source" value="<?php echo e(request('source')); ?>"><?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/files/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>