<div class="row">
    <div class="col-lg-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs profile-tab project-top-nav list-pages-crumbs" role="tablist">
            <!--overview-->
            <?php if(in_array('1046', $resources) || in_array('1047', $resources)): ?>

            <li class="nav-item">
                <a class="nav-link tabs-menu-item" href="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>" role="tab"
                    id="tabs-menu-overview"><?php echo e(cleanLang(__('lang.overview'))); ?></a>
            </li>
            <?php endif; ?>
            <?php if(in_array('1046', $resources) || in_array('1048', $resources)): ?>

            <!--details-->
            <li class="nav-item">
                <a class="nav-link tabs-menu-item   js-dynamic-url js-ajax-ux-request" data-toggle="tab"
                    id="tabs-menu-details" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/details"
                    data-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/project-details"
                    href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.details'))); ?></a>
            </li>
            <?php endif; ?>
            <!--[tasks]-->

            <?php if(in_array('45', $resources)||config('settings.project_permissions_view_tasks')): ?>

            <li class="nav-item">
                <a class="nav-link tabs-menu-item   js-dynamic-url js-ajax-ux-request" data-toggle="tab"
                    id="tabs-menu-tasks" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/tasks"
                    data-url="<?php echo e(url('/tasks')); ?>?source=ext&taskresource_type=project&taskresource_id=<?php echo e($project->project_id); ?>"
                    href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.tasks'))); ?></a>
            </li>
            <?php endif; ?>
            <?php if(in_array('1049', $resources)): ?>

            <li class="nav-item">
                <a class="nav-link tabs-menu-item   js-dynamic-url js-ajax-ux-request" data-toggle="tab"
                    id="tabs-menu-subtasks" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/subtasks"
                    data-url="<?php echo e(url('/subtasks')); ?>?source=ext&taskresource_type=project&taskresource_id=<?php echo e($project->project_id); ?>"
                    href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.subtask'))); ?></a>
            </li>

            <?php endif; ?>

            <!--[milestones]-->
            <?php if(config('settings.project_permissions_view_milestones')): ?>
            <li class="nav-item">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_milestones'] ?? ''); ?>"
                    data-toggle="tab" id="tabs-menu-milestones" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/milestones"
                    data-url="<?php echo e(url('/milestones')); ?>?source=ext&milestoneresource_type=project&milestoneresource_id=<?php echo e($project->project_id); ?>"
                    href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.milestones'))); ?></a>
            </li>
            <?php endif; ?>

            <!--[files]-->
            <?php if(config('settings.project_permissions_view_files')||in_array('1057', $resources)): ?>
            <li class="nav-item">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_files'] ?? ''); ?>"
                    data-toggle="tab" id="tabs-menu-files" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/files"
                    data-url="<?php echo e(url('/files')); ?>?source=ext&fileresource_type=project&fileresource_id=<?php echo e($project->project_id); ?>"
                    href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.files'))); ?></a>
            </li>
            <?php endif; ?>
            <!--[comments]-->
            <?php if(config('settings.project_permissions_view_comments')|| in_array('1061', $resources)): ?>
            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_discussions'] ?? ''); ?>"
                    id="tabs-menu-comments" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/comments"
                    data-url="<?php echo e(url('/comments')); ?>?source=ext&commentresource_type=project&commentresource_id=<?php echo e($project->project_id); ?>"
                    href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.comments'))); ?></a>
            </li>
            <?php endif; ?>
            <!--tickets-->
            <!-- <?php if(config('settings.project_permissions_view_tickets')): ?>
            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_tickets'] ?? ''); ?>"
                    id="tabs-menu-tickets" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/tickets"
                    data-url="<?php echo e(url('/tickets')); ?>?source=ext&ticketresource_type=project&ticketresource_id=<?php echo e($project->project_id); ?>"
                    href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.tickets'))); ?></a>
            </li>
            <?php endif; ?> -->
            <!--notes-->
            <?php if(config('settings.project_permissions_view_notes')|| in_array('1064', $resources)): ?>
            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_notes'] ?? ''); ?>"
                    id="tabs-menu-notes" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/notes"
                    data-url="<?php echo e(url('/notes')); ?>?source=ext&noteresource_type=project&noteresource_id=<?php echo e($project->project_id); ?>"
                    href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.notes'))); ?></a>
            </li>
            <?php endif; ?>

            <!--Project Inventory-->
            <?php if(in_array('1069', $resources)||config('settings.project_permissions_view_inventary')): ?>

            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_inventory'] ?? ''); ?>"
                    id="tabs-menu-notes" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/projectinventory"
                    data-url="<?php echo e(url('/projectinventory')); ?>?source=ext&noteresource_type=project&noteresource_id=<?php echo e($project->project_id); ?>"
                    href="#projects_ajaxtab" role="tab">Inventory</a>
            </li>
            <?php endif; ?>
            <?php if(in_array('1071', $resources)||config('settings.project_permissions_view_prq')): ?>

            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_inventory'] ?? ''); ?>"
                    id="tabs-menu-notes" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/prq"
                    data-url="<?php echo e(url('/prq')); ?>?source=ext&noteresource_type=project&noteresource_id=<?php echo e($project->project_id); ?>"
                    href="#projects_ajaxtab" role="tab">Purchase Requisiton</a>
            </li>
            <?php endif; ?>
            <?php if(in_array('1076', $resources)||config('settings.project_permissions_view_budget')): ?>

            <!--Budget-->
            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_inventory'] ?? ''); ?>"
                    id="tabs-menu-notes" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/budget"
                    data-url="<?php echo e(url('/budget')); ?>?source=ext&noteresource_type=project&noteresource_id=<?php echo e($project->project_id); ?>"
                    href="#" role="tab">Budget</a>
            </li>
            <?php endif; ?>
            <!--Budget-->
            <li class="nav-item d-none">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_inventory'] ?? ''); ?>"
                    id="tabs-menu-notes" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="#"
                    data-url="#"
                    href="#" role="tab">Petty Cash</a>
            </li>

            <!--billing-->
            <?php if(auth()->user()->is_team || auth()->user()->is_client_owner||in_array('1077', $resources)): ?>


            <li class="nav-item dropdown <?php echo e($page['tabmenu_more'] ?? ''); ?>">
                <a class="nav-link dropdown-toggle  tabs-menu-item" data-loading-class="loading-tabs" data-toggle="dropdown" href="javascript:void(0)"
                    role="button" aria-haspopup="true" id="tabs-menu-billing" aria-expanded="false">
                    <span class="hidden-xs-down"><?php echo e(cleanLang(__('lang.financial'))); ?></span>
                </a>
                <div class="dropdown-menu" x-placement="bottom-start" id="fx-topnav-dropdown">
                    <!--[invoices]-->
                    <?php if(config('settings.project_permissions_view_invoices')|| in_array('1078', $resources)): ?>
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_invoices'] ?? ''); ?>"
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/invoices"
                        data-url="<?php echo e(url('/invoices')); ?>?source=ext&invoiceresource_id=<?php echo e($project->project_id); ?>&invoiceresource_type=project"
                        href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.invoices'))); ?></a>
                    <?php endif; ?>
                    <!--[payments]-->
                    <?php if(config('settings.project_permissions_view_payments')|| in_array('1081', $resources)): ?>
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_invoices'] ?? ''); ?>"
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/payments"
                        data-url="<?php echo e(url('/payments')); ?>?source=ext&paymentresource_id=<?php echo e($project->project_id); ?>&paymentresource_type=project"
                        href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.payments'))); ?></a>
                    <?php endif; ?>
                    <!--[expenses]-->
                    <?php if(config('settings.project_permissions_view_expenses')|| in_array('1086', $resources)): ?>
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_invoices'] ?? ''); ?>"
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/expenses"
                        data-url="<?php echo e(url('/expenses')); ?>?source=ext&expenseresource_id=<?php echo e($project->project_id); ?>&expenseresource_type=project"
                        href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.expenses'))); ?></a>
                    <?php endif; ?>
                    <!--[timesheets]-->
                    <?php if(config('settings.project_permissions_view_timesheets')|| in_array('1091', $resources)): ?>
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_timesheets'] ?? ''); ?>"
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/timesheets"
                        data-url="<?php echo e(url('/timesheets')); ?>?source=ext&timesheetresource_id=<?php echo e($project->project_id); ?>&timesheetresource_type=project"
                        href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.timesheets'))); ?></a>
                    <?php endif; ?>
                    <?php if(config('settings.project_permissions_view_variation_order')): ?>
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request <?php echo e($page['tabmenu_invoices'] ?? ''); ?>"
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/variation"
                        data-url="<?php echo e(url('/variation')); ?>?source=ext&variationresource_id=<?php echo e($project->project_id); ?>&variationresource_type=project"
                        href="#projects_ajaxtab" role="tab">Variation Order</a>
                    <?php endif; ?>
                </div>
            </li>

            <?php endif; ?>
            <?php if(auth()->user()->is_team || auth()->user()->is_client_owner||in_array('1098', $resources)): ?>


            <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle  tabs-menu-item" data-loading-class="loading-tabs" data-toggle="dropdown" href="javascript:void(0)"
                    role="button" aria-haspopup="true" id="tabs-menu-billingg" aria-expanded="false">
                    <span class="hidden-xs-down"><?php echo e(cleanLang(__('lang.report'))); ?></span>
                </a>
                <div class="dropdown-menu" x-placement="bottom-start" id="fx-topnav-dropdown">
                    <!--[invoices]-->
                    <?php if(config('settings.project_permissions_view_invoices')|| in_array('1099', $resources)): ?>
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request "
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="<?php echo e(url('/projects')); ?>/<?php echo e($project->project_id); ?>/project_cost_report"
                        data-url="<?php echo e(url('/project_cost_report')); ?>?source=ext&projectresource_id=<?php echo e($project->project_id); ?>&projectresource_type=project"
                        href="#projects_ajaxtab" role="tab"><?php echo e(cleanLang(__('lang.project_costing_report'))); ?></a>
                    <?php endif; ?>

                </div>
            </li>
           
            <?php endif; ?>
        </ul>
        <!-- Tab panes -->

        <?php echo $__env->make('pages.files.components.actions.checkbox-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\Orion\application\resources\views/pages/project/components/misc/topnav.blade.php ENDPATH**/ ?>