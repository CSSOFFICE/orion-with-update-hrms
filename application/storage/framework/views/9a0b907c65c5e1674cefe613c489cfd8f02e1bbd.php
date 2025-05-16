<div class="row">
    <div class="col-lg-12">

        <!--title-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.title'))); ?>*</label>
            <div class="col-sm-12">
                <input type="text" class="form-control form-control-sm" autocomplete="off" id="note_title"
                    name="note_title" value="<?php echo e($note->note_title ?? ''); ?>">
            </div>
        </div>

        <!--description-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label required"><?php echo e(cleanLang(__('lang.description'))); ?>*</label>
            <div class="col-sm-12">
                <textarea id="note_description" name="note_description"
                    class="tinymce-textarea hidden"><?php echo e($note->note_description ?? ''); ?></textarea>
            </div>
        </div>

        <!--tags-->
        <div class="form-group row">
            <label class="col-sm-12 text-left control-label col-form-label"><?php echo e(cleanLang(__('lang.tags'))); ?></label>
            <div class="col-sm-12">
                <select name="tags" id="tags"
                    class="form-control form-control-sm select2-multiple <?php echo e(runtimeAllowUserTags()); ?> select2-hidden-accessible"
                    multiple="multiple" tabindex="-1" aria-hidden="true">
                    <!--array of selected tags-->
                    <?php if(isset($page['section']) && $page['section'] == 'edit'): ?>
                    <?php $__currentLoopData = $note->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $selected_tags[] = $tag->tag_title ; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <!--/#array of selected tags-->
                    <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($tag->tag_title); ?>"
                        <?php echo e(runtimePreselectedInArray($tag->tag_title ?? '', $selected_tags  ?? [])); ?>><?php echo e($tag->tag_title); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <!--/#tags-->


        <!--pass source-->
        <input type="hidden" name="source" value="<?php echo e(request('source')); ?>">

        <!--notes-->
        <div class="row">
            <div class="col-12">
                <div><small><strong>* <?php echo e(cleanLang(__('lang.required'))); ?></strong></small></div>
            </div>
        </div>

        <!--info-->
        <?php if(request('noteresource_type') == 'project' && auth()->user()->is_team): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info"><?php echo e(cleanLang(__('lang.project_notes_not_visible_to_client'))); ?></div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div><?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/notes/components/modals/add-edit-inc.blade.php ENDPATH**/ ?>