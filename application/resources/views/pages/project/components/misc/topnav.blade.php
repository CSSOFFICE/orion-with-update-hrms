<div class="row">
    <div class="col-lg-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs profile-tab project-top-nav list-pages-crumbs" role="tablist">
            <!--overview-->
            @if (in_array('1046', $resources) || in_array('1047', $resources))

            <li class="nav-item">
                <a class="nav-link tabs-menu-item" href="{{ url('/projects')}}/{{ $project->project_id }}" role="tab"
                    id="tabs-menu-overview">{{ cleanLang(__('lang.overview')) }}</a>
            </li>
            @endif
            @if (in_array('1046', $resources) || in_array('1048', $resources))

            <!--details-->
            <li class="nav-item">
                <a class="nav-link tabs-menu-item   js-dynamic-url js-ajax-ux-request" data-toggle="tab"
                    id="tabs-menu-details" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/details"
                    data-url="{{ url('/projects') }}/{{ $project->project_id }}/project-details"
                    href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.details')) }}</a>
            </li>
            @endif
            <!--[tasks]-->

            @if (in_array('45', $resources)||config('settings.project_permissions_view_tasks'))

            <li class="nav-item">
                <a class="nav-link tabs-menu-item   js-dynamic-url js-ajax-ux-request" data-toggle="tab"
                    id="tabs-menu-tasks" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/tasks"
                    data-url="{{ url('/tasks') }}?source=ext&taskresource_type=project&taskresource_id={{ $project->project_id }}"
                    href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.tasks')) }}</a>
            </li>
            @endif
            @if (in_array('1049', $resources))

            <li class="nav-item">
                <a class="nav-link tabs-menu-item   js-dynamic-url js-ajax-ux-request" data-toggle="tab"
                    id="tabs-menu-subtasks" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/subtasks"
                    data-url="{{ url('/subtasks') }}?source=ext&taskresource_type=project&taskresource_id={{ $project->project_id }}"
                    href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.subtask')) }}</a>
            </li>

            @endif

            <!--[milestones]-->
            @if(config('settings.project_permissions_view_milestones'))
            <li class="nav-item">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_milestones'] ?? '' }}"
                    data-toggle="tab" id="tabs-menu-milestones" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/milestones"
                    data-url="{{ url('/milestones') }}?source=ext&milestoneresource_type=project&milestoneresource_id={{ $project->project_id }}"
                    href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.milestones')) }}</a>
            </li>
            @endif

            <!--[files]-->
            @if(config('settings.project_permissions_view_files')||in_array('1057', $resources))
            <li class="nav-item">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_files'] ?? '' }}"
                    data-toggle="tab" id="tabs-menu-files" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/files"
                    data-url="{{ url('/files') }}?source=ext&fileresource_type=project&fileresource_id={{ $project->project_id }}"
                    href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.files')) }}</a>
            </li>
            @endif
            <!--[comments]-->
            @if(config('settings.project_permissions_view_comments')|| in_array('1061', $resources))
            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_discussions'] ?? '' }}"
                    id="tabs-menu-comments" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/comments"
                    data-url="{{ url('/comments') }}?source=ext&commentresource_type=project&commentresource_id={{ $project->project_id }}"
                    href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.comments')) }}</a>
            </li>
            @endif
            <!--tickets-->
            <!-- @if(config('settings.project_permissions_view_tickets'))
            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_tickets'] ?? '' }}"
                    id="tabs-menu-tickets" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/tickets"
                    data-url="{{ url('/tickets') }}?source=ext&ticketresource_type=project&ticketresource_id={{ $project->project_id }}"
                    href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.tickets')) }}</a>
            </li>
            @endif -->
            <!--notes-->
            @if(config('settings.project_permissions_view_notes')|| in_array('1064', $resources))
            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_notes'] ?? '' }}"
                    id="tabs-menu-notes" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/notes"
                    data-url="{{ url('/notes') }}?source=ext&noteresource_type=project&noteresource_id={{ $project->project_id }}"
                    href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.notes')) }}</a>
            </li>
            @endif

            <!--Project Inventory-->
            @if (in_array('1069', $resources)||config('settings.project_permissions_view_inventary'))

            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_inventory'] ?? '' }}"
                    id="tabs-menu-notes" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/projectinventory"
                    data-url="{{ url('/projectinventory') }}?source=ext&noteresource_type=project&noteresource_id={{ $project->project_id }}"
                    href="#projects_ajaxtab" role="tab">Inventory</a>
            </li>
            @endif
            @if (in_array('1071', $resources)||config('settings.project_permissions_view_prq'))

            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_inventory'] ?? '' }}"
                    id="tabs-menu-notes" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/prq"
                    data-url="{{ url('/prq') }}?source=ext&noteresource_type=project&noteresource_id={{ $project->project_id }}"
                    href="#projects_ajaxtab" role="tab">Purchase Requisiton</a>
            </li>
            @endif
            @if (in_array('1076', $resources)||config('settings.project_permissions_view_budget'))

            <!--Budget-->
            <li class="nav-item ">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_inventory'] ?? '' }}"
                    id="tabs-menu-notes" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/budget"
                    data-url="{{ url('/budget') }}?source=ext&noteresource_type=project&noteresource_id={{ $project->project_id }}"
                    href="#" role="tab">Budget</a>
            </li>
            @endif
            <!--Budget-->
            <li class="nav-item d-none">
                <a class="nav-link  tabs-menu-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_inventory'] ?? '' }}"
                    id="tabs-menu-notes" data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                    data-dynamic-url="#"
                    data-url="#"
                    href="#" role="tab">Petty Cash</a>
            </li>

            <!--billing-->
            @if(auth()->user()->is_team || auth()->user()->is_client_owner||in_array('1077', $resources))


            <li class="nav-item dropdown {{ $page['tabmenu_more'] ?? '' }}">
                <a class="nav-link dropdown-toggle  tabs-menu-item" data-loading-class="loading-tabs" data-toggle="dropdown" href="javascript:void(0)"
                    role="button" aria-haspopup="true" id="tabs-menu-billing" aria-expanded="false">
                    <span class="hidden-xs-down">{{ cleanLang(__('lang.financial')) }}</span>
                </a>
                <div class="dropdown-menu" x-placement="bottom-start" id="fx-topnav-dropdown">
                    <!--[invoices]-->
                    @if(config('settings.project_permissions_view_invoices')|| in_array('1078', $resources))
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_invoices'] ?? '' }}"
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/invoices"
                        data-url="{{ url('/invoices') }}?source=ext&invoiceresource_id={{ $project->project_id }}&invoiceresource_type=project"
                        href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.invoices')) }}</a>
                    @endif
                    <!--[payments]-->
                    @if(config('settings.project_permissions_view_payments')|| in_array('1081', $resources))
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_invoices'] ?? '' }}"
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/payments"
                        data-url="{{ url('/payments') }}?source=ext&paymentresource_id={{ $project->project_id }}&paymentresource_type=project"
                        href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.payments')) }}</a>
                    @endif
                    <!--[expenses]-->
                    @if(config('settings.project_permissions_view_expenses')|| in_array('1086', $resources))
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_invoices'] ?? '' }}"
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/expenses"
                        data-url="{{ url('/expenses') }}?source=ext&expenseresource_id={{ $project->project_id }}&expenseresource_type=project"
                        href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.expenses')) }}</a>
                    @endif
                    <!--[timesheets]-->
                    @if(config('settings.project_permissions_view_timesheets')|| in_array('1091', $resources))
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_timesheets'] ?? '' }}"
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/timesheets"
                        data-url="{{ url('/timesheets') }}?source=ext&timesheetresource_id={{ $project->project_id }}&timesheetresource_type=project"
                        href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.timesheets')) }}</a>
                    @endif
                    @if(config('settings.project_permissions_view_variation_order'))
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request {{ $page['tabmenu_invoices'] ?? '' }}"
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/variation"
                        data-url="{{ url('/variation') }}?source=ext&variationresource_id={{ $project->project_id }}&variationresource_type=project"
                        href="#projects_ajaxtab" role="tab">Variation Order</a>
                    @endif
                </div>
            </li>

            @endif
            @if(auth()->user()->is_team || auth()->user()->is_client_owner||in_array('1098', $resources))


            <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle  tabs-menu-item" data-loading-class="loading-tabs" data-toggle="dropdown" href="javascript:void(0)"
                    role="button" aria-haspopup="true" id="tabs-menu-billingg" aria-expanded="false">
                    <span class="hidden-xs-down">{{ cleanLang(__('lang.report')) }}</span>
                </a>
                <div class="dropdown-menu" x-placement="bottom-start" id="fx-topnav-dropdown">
                    <!--[invoices]-->
                    @if(config('settings.project_permissions_view_invoices')|| in_array('1099', $resources))
                    <a class="dropdown-item   js-dynamic-url js-ajax-ux-request "
                        data-toggle="tab" data-loading-class="loading-tabs" data-loading-target="embed-content-container"
                        data-dynamic-url="{{ url('/projects') }}/{{ $project->project_id }}/project_cost_report"
                        data-url="{{ url('/project_cost_report') }}?source=ext&projectresource_id={{ $project->project_id }}&projectresource_type=project"
                        href="#projects_ajaxtab" role="tab">{{ cleanLang(__('lang.project_costing_report')) }}</a>
                    @endif

                </div>
            </li>
           
            @endif
        </ul>
        <!-- Tab panes -->

        @include('pages.files.components.actions.checkbox-actions')

    </div>
</div>
