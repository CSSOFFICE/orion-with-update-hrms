<!--CRUMBS CONTAINER (RIGHT)-->
<div class="col-md-12  col-lg-6 align-self-center text-right parent-page-actions p-b-9"
    id="list-page-actions-container">
    <div id="list-page-actions">

        <!--edit project-->
        <?php if(config('visibility.edit_project_button')): ?>
        <span class="dropdown">
            <button type="button" data-toggle="dropdown" title="<?php echo e(cleanLang(__('lang.edit'))); ?>" aria-haspopup="true" aria-expanded="false"
                class="data-toggle-tooltip list-actions-button btn btn-page-actions waves-effect waves-dark">
                <i class="sl-icon-note"></i>
            </button>

            <div class="dropdown-menu" aria-labelledby="listTableAction">
                <!--edit-->
                <a class="dropdown-item edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#commonModal"
                    data-url="<?php echo e(urlResource('/projects/'.$project->project_id.'/edit')); ?>"
                    data-loading-target="commonModalBody" data-modal-title="<?php echo e(cleanLang(__('lang.edit_project'))); ?>"
                    data-action-url="<?php echo e(urlResource('/projects/'.$project->project_id.'?ref=page')); ?>"
                    data-action-method="PUT" data-action-ajax-class=""
                    data-action-ajax-loading-target="projects-td-container">
                    <?php echo e(cleanLang(__('lang.edit_project'))); ?></a>
                <!--change category-->
                <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title="<?php echo e(cleanLang(__('lang.change_category'))); ?>" data-url="<?php echo e(url('/projects/change-category')); ?>"
                    data-action-url="<?php echo e(urlResource('/projects/change-category?ref=page&id='.$project->project_id)); ?>"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    <?php echo e(cleanLang(__('lang.change_category'))); ?></a>

                <!--change status-->
                <a class="dropdown-item actions-modal-button js-ajax-ux-request reset-target-modal-form"
                    href="javascript:void(0)" data-toggle="modal" data-target="#actionsModal"
                    data-modal-title="<?php echo e(cleanLang(__('lang.change_status'))); ?>"
                    data-url="<?php echo e(urlResource('/projects/'.$project->project_id.'/change-status')); ?>"
                    data-action-url="<?php echo e(urlResource('/projects/'.$project->project_id.'/change-status?ref=page')); ?>"
                    data-loading-target="actionsModalBody" data-action-method="POST">
                    <?php echo e(cleanLang(__('lang.change_status'))); ?></a>
                <!--stop all timers-->
                <a href="javascript:void(0)" class="dropdown-item confirm-action-danger d-none" data-confirm-title="<?php echo e(cleanLang(__('lang.stop_all_timers'))); ?>"
                    data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>" data-ajax-type="PUT"
                    data-url="<?php echo e(urlResource('/projects/'.$project->project_id.'/stop-all-timers')); ?>">
                    <?php echo e(cleanLang(__('lang.stop_all_timers'))); ?>

                </a>

            </div>
        </span>
        <?php endif; ?>


        <!--delete project-->
        <?php if(config('visibility.delete_project_button')): ?>
        <!--delete-->
        <button type="button" data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.delete_project'))); ?>"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark confirm-action-danger"
            data-confirm-title="<?php echo e(cleanLang(__('lang.delete_project'))); ?>" data-confirm-text="<?php echo e(cleanLang(__('lang.are_you_sure'))); ?>"
            data-ajax-type="DELETE" data-url="<?php echo e(url('/projects/'.$project->project_id.'?source=page')); ?>"><i
                class="sl-icon-trash"></i></button>
        <?php endif; ?>
    </div>
</div>
<!-- action buttons -->
<?php /**PATH E:\xampp\htdocs\css\orion\application\resources\views/pages/project/components/misc/actions.blade.php ENDPATH**/ ?>