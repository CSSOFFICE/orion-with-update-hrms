<?php
$theme = $this->Xin_model->read_theme_info(1);
$session = $this->session->userdata('username');
$user_info = $this->Xin_model->read_user_info($session['user_id']);
?>
<!DOCTYPE html>
<html lang="en" class="team">

<!--html header-->
<?php $this->load->view('admin/layout/pms/header.php'); ?>
<!--html header-->
<style>
     body {
        font-weight: bold !important;
    }
</style>
<body id="main-body"
    class="loggedin fix-header card-no-border fix-sidebar {{ config('settings.css_kanban') }} {{ runtimePreferenceLeftmenuPosition(auth()->user()->left_menu_position) }} {{ $page['page'] ?? '' }}">

    <!--main wrapper-->
    <div id="main-wrapper">


        <!---------------------------------------------------------------------------------------
            [NEXTLOOP}
             always collapse left menu for small devices
            (NB: this code is in the correct place. It must run before menu is added to DOM)
         --------------------------------------------------------------------------------------->

        <!--top nav-->
        <?php $this->load->view('admin/layout/pms/topnav.php'); ?>
        <?php $this->load->view('admin/layout/pms/leftmenu.php'); ?>
        <!--top nav-->


        <!--page wrapper-->
        <div class="page-wrapper">

            <!--overlay-->
            <div class="page-wrapper-overlay js-toggle-side-panel hidden" data-target=""></div>
            <!--overlay-->

            <!--preloader-->
            <div class="preloader">
                <div class="loader">
                    <div class="loader-loading"></div>
                </div>
            </div>
            <!--preloader-->

            <!--Header wrapper-->
            <?php if ($this->router->fetch_class() == 'dashboard' || $this->router->fetch_class() == 'chat' || $this->router->fetch_class() == '1calendar' || $this->router->fetch_class() == 'profile' || $this->router->fetch_method() == 'attendance_dashboard') { ?>
                <div id="header_wrapper" class="header-lg overlay ecom-header">
                    <div class="container">
                    </div>
                </div>
            <?php } ?>
            <?php if ($this->router->fetch_method() == 'staff_dashboard') { ?>
                <div id="header_wrapper" class="header-lg overlay ecom-stff-header">
                    <div class="container">
                    </div>
                </div>
            <?php } ?>
            <?php if ($this->router->fetch_method() == 'projects_dashboard') { ?>
                <div id="header_wrapper" class="header-lg overlay ecom-proj-header">
                    <div class="container">
                    </div>
                </div>
            <?php } ?>
            <?php if ($this->router->fetch_method() == 'accounting_dashboard') { ?>
                <div id="header_wrapper" class="header-lg overlay ecom-acc-header">
                    <div class="container">
                    </div>
                </div>
            <?php } ?>
            <!--Header wrapper-->


            <!-- Content Header (Page header) -->
            <?php if ($this->router->fetch_class() != 'dashboard' && $this->router->fetch_class() != 'chat' && $this->router->fetch_class() != 'calendar' && $this->router->fetch_class() != 'profile' && $this->router->fetch_method() != 'staff_dashboard' && $this->router->fetch_method() != 'projects_dashboard' && $this->router->fetch_method() != 'accounting_dashboard' && $this->router->fetch_method() != 'attendance_dashboard') { ?>
                <section class="<?php echo $theme[0]->page_header; ?> content-header">
                    <h1>
                        <?php echo $breadcrumbs; ?>
                        <!--<small><?php echo $breadcrumbs; ?></small>-->
                        <div class="row breadcrumbs-hr-top">
                            <div class="breadcrumb-wrapper col-xs-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard/'); ?>"><i
                                                class="fa fa-dashboard"></i>
                                            <?php echo $this->lang->line('xin_e_details_home'); ?></a>
                                    </li>
                                    <li class="breadcrumb-item active"><?php echo $breadcrumbs; ?></li>
                                </ol>
                            </div>
                        </div>
                    </h1>
                    <img id="hrload-img" src="<?php echo base_url() ?>skin/img/loading.gif" style="">
                    <style type="text/css">
                        #hrload-img {
                            display: none;
                            z-index: 87896969;
                            float: right;
                            margin-right: 25px;
                            margin-top: -32px;
                        }
                    </style>
                    <?php if ($user_info[0]->user_role_id == 1): ?>
                        <ol class="breadcrumb">
                            <li><a href="<?php echo site_url('admin/theme/'); ?>"><i class="fa fa-columns"></i>
                                    <?php echo $this->lang->line('xin_theme_settings'); ?></a></li>
                            <li><a href="<?php echo site_url('admin/settings/'); ?>"><i class="fa fa-cog"></i>
                                    <?php echo $this->lang->line('header_configuration'); ?></a></li>
                        </ol>
                    <?php endif; ?>
                </section>
            <?php } ?>

            <!-- main content -->
            <!-- @yield('content') -->
            <section class="content">
                <?php echo $subview; ?>
            </section>
            <!-- /#main content -->

        </div>
        <!--page wrapper-->
    </div>

    <!--common modals-->
    <!-- @include('modals.actions-modal-wrapper')
    @include('modals.common-modal-wrapper')
    @include('modals.plain-modal-wrapper')
    @include('pages.authentication.modal.relogin') -->

    <!--js footer-->
    <?php $this->load->view('admin/layout/pms/footerjs.php'); ?>

    <!--js automations-->
    <!-- @include('layout.automationjs') -->

    <!--[note: no sanitizing required] for this trusted content, which is added by the admin-->
</body>

</html>