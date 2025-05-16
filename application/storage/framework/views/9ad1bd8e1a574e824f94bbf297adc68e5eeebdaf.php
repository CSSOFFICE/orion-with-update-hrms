<!--CRUMBS CONTAINER (RIGHT)-->
<div class="col-md-12  col-lg-7 p-b-9 align-self-center text-right <?php echo e($page['list_page_actions_size'] ?? ''); ?> <?php echo e($page['list_page_container_class'] ?? ''); ?>"
    id="list-page-actions-container">
    <div id="list-page-actions">
        <!--search box-->
        <?php if(config('visibility.list_page_actions_search')): ?>
        <div class="header-search" id="header-search">
            <i class="sl-icon-magnifier"></i>
            <input type="text" class="form-control search-records list-actions-search"
                data-url="<?php echo e($page['dynamic_search_url'] ?? ''); ?>" data-type="form" data-ajax-type="post"
                data-form-id="header-search" id="search_query" name="search_query"
                placeholder="<?php echo e(cleanLang(__('lang.search'))); ?>">
        </div>
        <?php endif; ?>

        <!--filter own tasks-->
        <?php if(config('visibility.own_tasks_toggle_button')): ?>
        <button type="button" data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.my_tasks'))); ?>"
            id="pref_filter_own_tasks"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request <?php echo e(runtimeActive(auth()->user()->pref_filter_own_tasks)); ?>"
            data-url="<?php echo e(url('/tasks/search?action=search&toggle=pref_filter_own_tasks')); ?>">
            <i class="sl-icon-user"></i>
        </button>
        <?php endif; ?>

        <!--filter own project-->
        <?php if(config('visibility.own_projects_toggle_button')): ?>
        <button type="button" data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.my_projects'))); ?>"
            id="pref_filter_own_projects"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request <?php echo e(runtimeActive(auth()->user()->pref_filter_own_projects)); ?>"
            data-url="<?php echo e(url('/projects/search?action=search&toggle=pref_filter_own_projects')); ?>">
            <i class="sl-icon-user"></i>
        </button>
        <?php endif; ?>


        <!--filter own lead-->
        <?php if(config('visibility.own_leads_toggle_button')): ?>
        <button type="button" data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.my_leads'))); ?>"
            id="pref_filter_own_leads"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request <?php echo e(runtimeActive(auth()->user()->pref_filter_own_leads)); ?>"
            data-url="<?php echo e(url('/leads/search?action=search&toggle=pref_filter_own_leads')); ?>">
            <i class="sl-icon-user"></i>
        </button>
        <?php endif; ?>

        <!--stats-->
        <?php if(config('visibility.stats_toggle_button')): ?>
        <button type="button" data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.quick_stats'))); ?>"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-stats-widget update-user-ux-preferences"
            data-type="statspanel" data-progress-bar="hidden"
            data-url-temp="<?php echo e(url('/')); ?>/<?php echo e(auth()->user()->team_or_contact); ?>/updatepreferences"
            data-url="" data-target="list-pages-stats-widget">
            <i class="ti-stats-up"></i>
        </button>
        <?php endif; ?>

        <!-- <button type="button" data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.kanban_view'))); ?>" id="pref_view_tasks_layout"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request <?php echo e(runtimeActive(auth()->user()->pref_view_tasks_layout)); ?>"
            data-url="<?php echo e(urlResource('/tasks/search?action=search&toggle=layout')); ?>">
            <i class="sl-icon-list"></i>
        </button> -->

        <!--kanban view quotation-->
        <?php if(config('visibility.quo_kanban_actions')): ?>
        <button type="button" data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.kanban_view'))); ?>"
            id="pref_view_quo_layout"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request <?php echo e(runtimeActive(auth()->user()->pref_view_tasks_layout)); ?>"
            data-url="<?php echo e(urlResource('/estimates/search?action=search&toggle=layout')); ?>">
            <i class="sl-icon-list"></i>
        </button>
        <?php endif; ?>
        <!--kanban view of qutation-->
        <!--kanban view-->
        <?php if(config('visibility.taskss_kanban_actions')): ?>
        <button type="button" data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.kanban_view'))); ?>"
            id="pref_view_tasks_layout"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request <?php echo e(runtimeActive(auth()->user()->pref_view_tasks_layout)); ?>"
            data-url="<?php echo e(urlResource('/quo/search?action=search&toggle=layout')); ?>">
            <i class="sl-icon-list"></i>
        </button>
        <!--kanban task sorting-->
        <div class="btn-group " id="list_actions_sort_kanban">
            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                class="list-actions-button btn waves-effect waves-dark dropdown-toggle">
                <i class="mdi mdi-sort"></i></button>
            <div class="dropdown-menu dropdown-menu-right fx-kaban-sorting-dropdown">
                <div class="fx-kaban-sorting-dropdown-container"><?php echo e(cleanLang(__('lang.sort_by'))); ?></div>
                <a class="dropdown-item js-ajax-ux-request" id="sort_task_created" href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/tasks?action=sort&orderby=task_created&sortorder=asc')); ?>"><?php echo e(cleanLang(__('lang.date_created'))); ?></a>
                <a class="dropdown-item js-ajax-ux-request" id="sort_task_date_start" href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/tasks?action=sort&orderby=task_date_start&sortorder=asc')); ?>"><?php echo e(cleanLang(__('lang.start_date'))); ?></a>
                <a class="dropdown-item js-ajax-ux-request" id="sort_task_date_due" href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/tasks?action=sort&orderby=task_date_due&sortorder=asc')); ?>"><?php echo e(cleanLang(__('lang.due_date'))); ?></a>
                <a class="dropdown-item js-ajax-ux-request" id="sort_task_title" href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/tasks?action=sort&orderby=task_title&sortorder=asc')); ?>"><?php echo e(cleanLang(__('lang.title'))); ?></a>
            </div>
        </div>
        <?php endif; ?>
        <?php if(config('visibility.tasks_kanban_actions')): ?>
        <button type="button" data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.kanban_view'))); ?>"
            id="pref_view_tasks_layout"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request <?php echo e(runtimeActive(auth()->user()->pref_view_tasks_layout)); ?>"
            data-url="<?php echo e(urlResource('/tasks/search?action=search&toggle=layout')); ?>">
            <i class="sl-icon-list"></i>
        </button>
        <!--kanban task sorting-->
        <div class="btn-group" id="list_actions_sort_kanban">
            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                class="list-actions-button btn waves-effect waves-dark dropdown-toggle">
                <i class="mdi mdi-sort"></i></button>
            <div class="dropdown-menu dropdown-menu-right fx-kaban-sorting-dropdown">
                <div class="fx-kaban-sorting-dropdown-container"><?php echo e(cleanLang(__('lang.sort_by'))); ?></div>
                <a class="dropdown-item js-ajax-ux-request" id="sort_task_created" href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/tasks?action=sort&orderby=task_created&sortorder=asc')); ?>"><?php echo e(cleanLang(__('lang.date_created'))); ?></a>
                <a class="dropdown-item js-ajax-ux-request" id="sort_task_date_start" href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/tasks?action=sort&orderby=task_date_start&sortorder=asc')); ?>"><?php echo e(cleanLang(__('lang.start_date'))); ?></a>
                <a class="dropdown-item js-ajax-ux-request" id="sort_task_date_due" href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/tasks?action=sort&orderby=task_date_due&sortorder=asc')); ?>"><?php echo e(cleanLang(__('lang.due_date'))); ?></a>
                <a class="dropdown-item js-ajax-ux-request" id="sort_task_title" href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/tasks?action=sort&orderby=task_title&sortorder=asc')); ?>"><?php echo e(cleanLang(__('lang.title'))); ?></a>
            </div>
        </div>
        <?php endif; ?>

        <?php if(config('visibility.leads_kanban_actions')): ?>
        <!--leads kanban toggle-->
        <button type="button" data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.kanban_view'))); ?>"
            id="pref_view_leads_layout"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-ajax-ux-request <?php echo e(runtimeActive(auth()->user()->pref_view_leads_layout)); ?>"
            data-url="<?php echo e(urlResource('/leads/search?action=search&toggle=layout')); ?>">
            <i class="sl-icon-list"></i>
        </button>
        <!--leads kanban task sorting-->
        <div class="btn-group" id="list_actions_sort_kanban">
            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                class="list-actions-button btn waves-effect waves-dark dropdown-toggle">
                <i class="mdi mdi-sort"></i></button>
            <div class="dropdown-menu dropdown-menu-right fx-kaban-sorting-dropdown">
                <div class="fx-kaban-sorting-dropdown-container"><?php echo e(cleanLang(__('lang.sort_by'))); ?></div>
                <a class="dropdown-item js-ajax-ux-request" id="sort_lead_created" href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/leads?action=sort&orderby=lead_created&sortorder=asc')); ?>"><?php echo e(cleanLang(__('lang.date_created'))); ?></a>
                <a class="dropdown-item js-ajax-ux-request" id="sort_lead_firstname" href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/leads?action=sort&orderby=lead_firstname&sortorder=asc')); ?>"><?php echo e(cleanLang(__('lang.name'))); ?></a>
                <a class="dropdown-item js-ajax-ux-request" id="sort_lead_value" href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/leads?action=sort&orderby=lead_value&sortorder=desc')); ?>"><?php echo e(cleanLang(__('lang.value'))); ?></a>
                <a class="dropdown-item js-ajax-ux-request" id="sort_lead_last_contacted"
                    href="javascript:void(0)"
                    data-url="<?php echo e(urlResource('/leads?action=sort&orderby=lead_last_contacted&sortorder=desc')); ?>"><?php echo e(cleanLang(__('lang.date_last_contacted'))); ?></a>
            </div>
        </div>
        <?php endif; ?>


        <!--filter-->
        <?php if(config('visibility.list_page_actions_filter_button')): ?>
        <button type="button" data-toggle="tooltip" title="<?php echo e(cleanLang(__('lang.filter'))); ?>"
            class="list-actions-button btn btn-page-actions waves-effect waves-dark js-toggle-side-panel"
            data-target="<?php echo e($page['sidepanel_id'] ?? ''); ?>">
            <i class="mdi mdi-filter-outline"></i>
        </button>
        <?php endif; ?>

        <!--add new button (modal)-->
        <?php if(config('visibility.list_page_actions_add_button')): ?>
        <button type="button"
            class="btn btn-danger btn-add-circle  edit-add-modal-button js-ajax-ux-request reset-target-modal-form <?php echo e($page['add_button_classes'] ?? ''); ?>"
            data-toggle="modal" data-target="#commonModal" data-url="<?php echo e($page['add_modal_create_url'] ?? ''); ?>"
            data-loading-target="commonModalBody" data-modal-title="<?php echo e($page['add_modal_title'] ?? ''); ?>"
            data-action-url="<?php echo e($page['add_modal_action_url'] ?? ''); ?>"
            data-action-method="<?php echo e($page['add_modal_action_method'] ?? ''); ?>"
            data-action-ajax-class="<?php echo e($page['add_modal_action_ajax_class'] ?? ''); ?>"
            data-action-ajax-loading-target="<?php echo e($page['add_modal_action_ajax_loading_target'] ?? ''); ?>"
            data-save-button-class="<?php echo e($page['add_modal_save_button_class'] ?? ''); ?>" data-project-progress="0">
            <i class="ti-plus"></i>
        </button>
        <?php endif; ?>

        <!--add new button (link)-->
        <?php if(config('visibility.list_page_actions_add_button_link')): ?>
        <a id="fx-page-actions-add-button" type="button"
            class="btn btn-danger btn-add-circle edit-add-modal-button"
            href="<?php echo e($page['add_button_link_url'] ?? ''); ?>">
            <i class="ti-plus"></i>
        </a>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/orion/application/resources/views/misc/list-pages-actions.blade.php ENDPATH**/ ?>