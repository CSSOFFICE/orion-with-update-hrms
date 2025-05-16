<?php $__currentLoopData = $subb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="display-flex flex-row comment-row" id="card_comment_<?php echo e($comment->sub_task_id); ?>">
    <div class="p-2 comment-avatar">
        <img src="<?php echo e(getUsersAvatar($comment->subtask_description, $comment->subtask_description)); ?>" class="img-circle"
            alt="<?php echo e($comment->subtask_description ?? runtimeUnkownUser()); ?>" width="40">
    </div>
    <div class="comment-text w-100 js-hover-actions">
        <div class="row">
            <div class="col-sm-6 x-name"><?php echo e($comment->subtask_description ?? ''); ?></div>
            <div class="col-sm-6 x-meta text-right">
                <!--meta-->
                <span class="x-date"><small><?php echo e($comment->subtask_detail??''); ?></small></span>
                <!--actions: delete-->

            </div>
        </div>
        <div class="p-t-4"><?php echo clean($comment->status); ?></div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\orion\application\resources\views/pages/task/components/subtask.blade.php ENDPATH**/ ?>