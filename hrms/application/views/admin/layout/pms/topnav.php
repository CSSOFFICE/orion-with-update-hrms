<?php
$session = $this->session->userdata('username');
$user_info = $this->Xin_model->read_user_info($session['user_id']);
 //echo var_dump($user_info); exit;
$role_user = $this->Xin_model->read_user_role_info($user_info[0]->user_role_id);
if (!is_null($role_user)) {
    $user_role_resources = $this->PmUserRole_model->get_user_role_resources($role_user[0]->role_id);
} else {
    $user_role_resources = false;
}

$base_url = str_replace('/hrms', '', base_url());
?>
<header class="topbar">

    <nav class="navbar top-navbar navbar-expand-md navbar-light">

        <div class="navbar-header">


            <!-- @if(request('dashboard_section') == 'settings') -->
            <!--exist-->
            <!-- <div class="sidenav-menu-item exit-panel m-b-17">
                <a class="waves-effect waves-dark text-info" href="/home" id="settings-exit-button"
                    aria-expanded="false" target="_self">
                    <i class="sl-icon-logout text-info"></i>
                    <span id="settings-exit-text">{{ cleanLang(__('lang.exit_settings')) }}</span>
                </a>
            </div> -->
            <!-- @else -->
            <!--logo-->
            <div class="sidenav-menu-item logo m-t-0">
                <a class="navbar-brand" href="<?php echo base_url('/admin/dashboard') ?>">
                    <img src="<?php echo $base_url;?>storage/logos/app/logo-small.png?v=1" alt="homepage" class="logo-small" />
                    <img src="<?php echo $base_url;?>storage/logos/app/logo.png?v=1" alt="homepage" class="logo-large" />
                </a>
            </div>
            <!-- @endif -->
        </div>


        <div class="navbar-collapse header-overlay" id="main-top-nav-bar">
            <!--general page overlay-->
            <div class="page-wrapper-overlay js-toggle-side-panel hidden" data-target=""></div>

            <ul class="navbar-nav mr-auto">

                <!--left menu toogle (hamburger menu) - main application -->
                <!-- @if(request('visibility_left_menu_toggle_button') == 'visible') -->
                <li class="nav-item main-hamburger-menu">
                    <a class="nav-link nav-toggler hidden-md-up waves-effect waves-dark" href="javascript:void(0)">
                        <i class="sl-icon-menu"></i>
                    </a>
                </li>
                <li class="nav-item main-hamburger-menu">
                    <a class="nav-link sidebartoggler hidden-sm-down waves-effect waves-dark update-user-ux-preferences"
                        data-type="leftmenu" data-progress-bar="hidden" data-url=""
                        data-url-temp="<?php echo $base_url;?>team/updatepreferences"
                        data-preference-type="leftmenu" href="javascript:void(0)">
                        <i class="sl-icon-menu"></i>
                    </a>
                </li>
                <!-- @endif -->


                <!--left menu toogle (hamburger menu) - settings section -->
                <!-- @if(request('visibility_settings_left_menu_toggle_button') == 'visible') -->
                <li class="nav-item settings-hamburger-menu hidden">
                    <a class="nav-link waves-effect waves-dark js-toggle-settings-menu" href="javascript:void(0)">
                        <i class="sl-icon-menu"></i>
                    </a>
                </li>
                <!-- @endif -->


                <!--[UPCOMING] search icon-->
                <li class="nav-item hidden-xs-down search-box hidden">
                    <a class="nav-link hidden-sm-down waves-effect waves-dark" href="javascript:void(0)">
                        <i class="icon-Magnifi-Glass2"></i>
                    </a>
                    <form class="app-search">
                        <input type="text" class="form-control" placeholder="Search & enter">
                        <a class="srh-btn">
                            <i class="ti-close"></i>
                        </a>
                    </form>
                </li>
            </ul>


            <!--RIGHT SIDE-->
            <ul class="navbar-nav navbar-top-right my-lg-0">

                <!-- event notifications -->
                <li class="nav-item dropdown" id="topnav-notification-dropdown"
                    data-url="<?php echo $base_url;?>events/topnav?eventtracking_status=unread" data-progress-bar='hidden'
                    data-loading-target="topnav-events-container">
                    <a class="nav-link dropdown-toggle p-t-10 font-23 waves-dark" href="javascript:void(0)"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        >
                        <i class="sl-icon-bell"></i>
                        <div class="notify hidden"
                            id="topnav-notification-icon">
                            <span class="heartbit"></span>
                            <span class="point"></span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown top-nav-events">
                        <ul>
                            <li>
                                <div class="drop-title">Notifications</div>
                            </li>
                            <li>
                                <!--events container-->
                                <div class="message-center" id="topnav-events-container">
                                    <!--events added dynamically here-->
                                </div>
                            </li>
                            <li class="hidden" id="topnav-events-container-footer">
                                <a class="nav-link text-center " href="javascript:void(0);"
                                    id="topnav-notification-mark-all-read"
                                    data-url="<?php echo $base_url;?>events/mark-allread-my-events" data-progress-bar='hidden'>
                                    <strong>Dismiss All Notifications</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--notifications -->


                <!-- Notes -->
                <li class="nav-item hidden">
                    <a class="nav-link waves-effect waves-dark" href="/notes" id="32" aria-expanded="false">
                        <i class="sl-icon-notebook"></i>
                    </a>
                </li>


                <!-- settings -->
                <!-- @if(auth()->user()->is_admin) -->
                <?php if($role_user[0]->role_id == 1):?>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-dark font-22 p-t-10 p-r-10"
                         href="<?php echo $base_url;?>settings" id="32"
                        aria-expanded="false">
                        <i class="sl-icon-settings"></i>
                    </a>
                </li>
                <!-- @endif -->
                <?php endif;?>

                <!-- add content -->
                <!-- @if(auth()->user()->is_team && auth()->user()->can_add_content) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="javascript:void(0)"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="mdi mdi-plus-circle-multiple-outline text-danger font-28"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <!-- project -->
                        <!-- @if(auth()->user()->role->role_projects >= 2) -->
                        <a href="javascript:void(0)"
                            class="dropdown-item dropdown-item-iconed edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                            data-toggle="modal" data-target="#commonModal" data-url="{{ url('projects/create') }}"
                            data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.add_project')) }}"
                            data-action-url="{{ url('/projects') }}" data-action-method="POST"
                            data-action-ajax-loading-target="commonModalBody" data-save-button-class=""
                            data-project-progress="0">
                            <i class="ti-folder"></i> Project </a>
                        <!-- @endif -->

                        <!-- task -->
                        <!-- @if(auth()->user()->role->role_tasks >= 2) -->
                        <a href="javascript:void(0)"
                            class="dropdown-item dropdown-item-iconed edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                            data-toggle="modal" data-target="#commonModal"
                            data-url="{{ url('/tasks/create?ref=quickadd') }}" data-loading-target="commonModalBody"
                            data-modal-title="{{ cleanLang(__('lang.add_task')) }}" data-action-url="{{url('/tasks?ref=quickadd') }}"
                            data-action-method="POST" data-action-ajax-loading-target="commonModalBody"
                            data-save-button-class="" data-project-progress="0">
                            <i class="ti-menu-alt"></i> Task </a>
                        <!-- @endif -->

                        <!-- lead -->
                        <!-- @if(auth()->user()->role->role_leads >= 2) -->
                        <a href="javascript:void(0)"
                            class="dropdown-item dropdown-item-iconed edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                            data-toggle="modal" data-target="#commonModal"
                            data-url="{{ url('/leads/create?ref=quickadd') }}" data-loading-target="commonModalBody"
                            data-modal-title="{{ cleanLang(__('lang.add_lead')) }}" data-action-url="{{url('/leads?ref=quickadd') }}"
                            data-action-method="POST" data-action-ajax-loading-target="commonModalBody"
                            data-save-button-class="" data-project-progress="0">
                            <i class="sl-icon-call-in"></i> Lead </a>
                        <!-- @endif -->

                        <!-- invoice -->
                        <!-- @if(auth()->user()->role->role_invoices >= 2) -->
                        <a href="javascript:void(0)"
                            class="dropdown-item dropdown-item-iconed edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                            data-toggle="modal" data-target="#commonModal"
                            data-url="{{ url('/invoices/create?ref=quickadd') }}" data-loading-target="commonModalBody"
                            data-modal-title="{{ cleanLang(__('lang.add_invoice')) }}"
                            data-action-url="{{url('/invoices?ref=quickadd') }}" data-action-method="POST"
                            data-action-ajax-loading-target="commonModalBody" data-save-button-class=""
                            data-project-progress="0">
                            <i class="sl-icon-doc"></i> Invoice </a>
                        <!-- @endif -->


                        <!-- estimate -->
                        <!-- @if(auth()->user()->role->role_estimates >= 2) -->
                        <a href="javascript:void(0)"
                            class="dropdown-item dropdown-item-iconed edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                            data-toggle="modal" data-target="#commonModal"
                            data-url="{{ url('/estimates/create?ref=quickadd') }}" data-loading-target="commonModalBody"
                            data-modal-title="{{ cleanLang(__('lang.add_estimate')) }}"
                            data-action-url="{{url('/estimates?ref=quickadd') }}" data-action-method="POST"
                            data-action-ajax-loading-target="commonModalBody" data-save-button-class=""
                            data-project-progress="0">
                            <i class="sl-icon-calculator"></i> Estimate </a>
                        <!-- @endif -->


                        <!-- expense -->
                        <!-- @if(auth()->user()->role->role_expenses >= 2) -->
                        <a href="javascript:void(0)"
                            class="dropdown-item dropdown-item-iconed edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                            data-toggle="modal" data-target="#commonModal"
                            data-url="{{ url('/expenses/create?ref=quickadd') }}" data-loading-target="commonModalBody"
                            data-modal-title="{{ cleanLang(__('lang.add_expense')) }}"
                            data-action-url="{{url('/expenses?ref=quickadd') }}" data-action-method="POST"
                            data-action-ajax-loading-target="commonModalBody" data-save-button-class=""
                            data-project-progress="0">
                            <i class="ti-receipt"></i> Expense </a>
                        <!-- @endif -->

                        <!-- payment -->
                        <!-- @if(auth()->user()->role->role_invoices >= 2) -->
                        <a href="javascript:void(0)"
                            class="dropdown-item dropdown-item-iconed edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                            data-toggle="modal" data-target="#commonModal"
                            data-url="{{ url('/payments/create?ref=quickadd') }}" data-loading-target="commonModalBody"
                            data-modal-title="{{ cleanLang(__('lang.add_payment')) }}"
                            data-action-url="{{url('/payments?ref=quickadd') }}" data-action-method="POST"
                            data-action-ajax-loading-target="commonModalBody" data-save-button-class=""
                            data-project-progress="0">
                            <i class="ti-credit-card"></i> Payment</a>
                        <!-- @endif -->

                        <!-- knowledgebase article -->
                        <!-- @if(auth()->user()->role->role_knowledgebase >= 2) -->
                        <a href="javascript:void(0)"
                            class="dropdown-item dropdown-item-iconed edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                            data-toggle="modal" data-target="#commonModal" data-url="{{ url('kb/create') }}"
                            data-loading-target="commonModalBody" data-modal-title="{{ cleanLang(__('lang.add_article')) }}"
                            data-action-url="{{ url('kb') }}" data-action-method="POST"
                            data-action-ajax-loading-target="commonModalBody" data-save-button-class="">
                            <i class="sl-icon-docs"></i> Article </a>
                        <!-- @endif -->

                    </div>
                </li>
                <!-- @endif -->


                <!-- language -->
                <!-- @if(config('system.settings_system_language_allow_users_to_change') == 'yes') -->
                <!-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="javascript:void(0)"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="sl-icon-globe"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated bounceInDown"> -->
                        <!-- @foreach(request('system_languages') as $key => $language) -->
                        <!-- <a class="dropdown-item js-ajax-request text-capitalize" href="javascript:void(0)"
                            data-url="{{ url('user/updatelanguage') }}" data-type="form" data-ajax-type="post"
                            data-form-id="topNavLangauage{{ $key }}">{{ $language }}</a>
                        <span id="topNavLangauage{{ $key }}">
                            <input type="hidden" name="language" value="{{ $language }}">
                            <input type="hidden" name="current_url" value="{{ url()->full() }}">
                        </span> -->
                        <!-- @endforeach -->
                    <!-- </div>
                </li> -->
                <!-- @endif -->
                <!--language -->


                <!-- profile -->
                <li class="nav-item dropdown u-pro">
                    <a class="nav-link dropdown-toggle p-l-20 p-r-20 waves-dark profile-pic" href="javascript:void(0)"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        >
                        <img src="<?php echo $base_url;?>storage/avatars/system/default_avatar.jpg" id="topnav_avatar" alt="user" class="" />
                        <span class="hidden-md-down" id="topnav_username"><?php echo $user_info[0]->first_name ?>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated flipInY">
                        <ul class="dropdown-user">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img"><img src="<?php echo $base_url;?>storage/avatars/system/default_avatar.jpg"
                                            id="topnav_dropdown_avatar" alt="user"></div>
                                    <div class="u-text">
                                        <h4 id="topnav_dropdown_full_name"><?php echo $user_info[0]->first_name ?>
                                        <?php echo $user_info[0]->last_name ?></h4>
                                        <p class="text-muted" id="topnav_dropdown_email"><?php echo $user_info[0]->email ?></p>
                                        <!-- <a href="javascript:void(0)"
                                            class="btn btn-rounded btn-danger btn-sm edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                            data-toggle="modal" data-target="#commonModal"
                                            data-url="<?php echo $base_url;?>user/avatar" data-loading-target="commonModalBody"
                                            data-modal-size="modal-sm" data-modal-title="Update Avatar"
                                            data-header-visibility="hidden" data-header-extra-close-icon="visible"
                                            data-action-url="<?php echo $base_url;?>user/avatar"
                                            data-action-method="PUT">Update Avatar</a> -->
                                            <a href="<?php echo $base_url;?>hrms/admin/profile?profile_picture=true"
                                            class="btn btn-rounded btn-danger btn-sm"
                                            >
                                    
                                            Update Avatar</a>
                                    </div>
                                </div>
                            </li>
                            <li role="separator" class="divider"></li>
                            <!--my profile-->
                            <li>
                                <!-- <a href="javascript:void(0)"
                                    class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                    data-toggle="modal" data-target="#commonModal"
                                    data-url="<?php echo $base_url;?>contacts/1/edit?type=profile"
                                    data-loading-target="commonModalBody"
                                    data-modal-title="Update My Profile"
                                    data-action-url="<?php echo $base_url;?>contacts/1" data-action-method="PUT"
                                    data-action-ajax-class="" data-modal-size="modal-lg"
                                    data-action-ajax-loading-target="team-td-container">
                                    <i class="ti-user p-r-4"></i>
                                    Update My Profile</a> -->
                                    <a href="<?php echo $base_url;?>hrms/admin/profile"
                                            
                                            >
                                            <i class="ti-user p-r-4"></i>
                                    
                                            Update My Profile</a>
                            </li>

                            <!--my timesheets-->
                            <!-- @if(auth()->user()->is_team && auth()->user()->role->role_timesheets >= 1) -->
                            <li>
                                <a href="<?php echo $base_url;?>timesheets/my">
                                    <i class="ti-timer p-r-4"></i>
                                    My Time Sheets</a>
                            </li>
                            <!-- @endif -->

                            <!-- @if(auth()->user()->is_client_owner) -->
                            <!--edit company profile-->
                            <!-- <li>
                                <a href="javascript:void(0)"
                                    class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                    data-toggle="modal" data-target="#commonModal"
                                    data-url="{{ url('/clients/'.auth()->user()->clientid.'/edit') }}"
                                    data-loading-target="commonModalBody"
                                    data-modal-title="{{ cleanLang(__('lang.company_details')) }}"
                                    data-action-url="{{ url('/clients/'.auth()->user()->clientid) }}"
                                    data-action-method="PUT">
                                    <i class="ti-pencil-alt p-r-4"></i>
                                    {{ cleanLang(__('lang.company_details')) }}</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"
                                    class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                    data-toggle="modal" data-target="#commonModal" data-url="{{ url('/clients/logo') }}"
                                    data-loading-target="commonModalBody" data-modal-size="modal-sm"
                                    data-modal-title="{{ cleanLang(__('lang.update_avatar')) }}" data-header-visibility="hidden"
                                    data-header-extra-close-icon="visible" data-action-url="{{ url('/clients/logo') }}"
                                    data-action-method="PUT">
                                    <i class="ti-pencil-alt p-r-4"></i>
                                    {{ cleanLang(__('lang.company_logo')) }}</a>
                            </li>
                            @endif -->

                            <!--update notifcations-->
                            <!-- <li>
                                <a href="javascript:void(0)" id="topnavUpdateNotificationsButton"
                                    class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                    data-toggle="modal" data-target="#commonModal"
                                    data-url="<?php echo $base_url;?>user/updatenotifications"
                                    data-loading-target="commonModalBody"
                                    data-modal-title="Notification Settings"
                                    data-action-url="<?php echo $base_url;?>user/updatenotifications" data-action-method="PUT"
                                    data-modal-size="modal-lg" data-form-design="form-material"
                                    data-header-visibility="hidden" data-header-extra-close-icon="visible"
                                    data-action-ajax-class="js-ajax-ux-request"
                                    data-action-ajax-loading-target="commonModalBody">
                                    <i class="sl-icon-bell p-r-4"></i>
                                    Notification Settings</a>
                            </li> -->

                            <!--update password-->
                            <li>
                                <!-- <a href="javascript:void(0)" id="topnavUpdatePasswordButton"
                                    class="edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
                                    data-toggle="modal" data-target="#commonModal"
                                    data-url="<?php echo $base_url;?>user/updatepassword" data-loading-target="commonModalBody"
                                    data-modal-title="Update Password"
                                    data-action-url="<?php echo $base_url;?>user/updatepassword" data-action-method="PUT"
                                    data-action-ajax-class="" data-modal-size="modal-sm" data-form-design="form-material"
                                    data-header-visibility="hidden" data-header-extra-close-icon="visible"
                                    data-action-ajax-loading-target="commonModalBody">
                                    <i class="ti-lock p-r-4"></i>
                                    Update Password</a> -->
                                    <a href="<?php echo $base_url;?>hrms/admin/profile?change_password=true"
                                            
                                            >
                                            <i class="ti-lock p-r-4"></i>
                                            Update Password</a>
                            </li>

                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="<?php echo $base_url;?>logout">
                                    <i class="fa fa-power-off p-r-4"></i> Logout</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- /#profile -->
            </ul>
        </div>
    </nav>


</header>