<?php
$session = $this->session->userdata('username');
$user_info = $this->Xin_model->read_user_info($session['user_id']);
$role_user = $this->Xin_model->read_user_role_info($user_info[0]->user_role_id);

//$role_user = $this->Xin_model->read_user_role_info($user_info[0]->user_role_id);
if (!is_null($role_user)) {
    $role_resources_ids = explode(',', $role_user[0]->role_resources);
} else {
    $role_resources_ids = explode(',', 0);
}

if (!is_null($role_user)) {
    $user_role_resources = $this->PmUserRole_model->get_user_role_resources($role_user[0]->role_id);
    // echo "<pre>";print_r($user_role_resources);exit;
} else {
    $user_role_resources = false;
}

$base_url = str_replace('/hrms', '', base_url());
?>
<?php $system = $this->Xin_model->read_setting_info(1); ?>
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar" id="js-trigger-nav-team">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" id="main-scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav" id="main-sidenav">
            <ul id="sidebarnav">
                <?php $this->load->view('admin/layout/pms/hr_left_menu'); ?>

                <!--leads-->
                <?php if ($user_role_resources->role_leads >= 1) : ?>
                    <!-- <li class="sidenav-menu-item  menu-tooltip menu-with-tooltip" title="Leads">
                    <a class="waves-effect waves-dark" href="<?php echo $base_url; ?>leads" aria-expanded="false" target="_self">
                        <i class="sl-icon-call-in"></i>
                        <span class="hide-menu">Leads
                        </span>
                    </a>
                </li> -->
                <?php endif; ?>
                <!--leads-->


                <!--leads-->

                <!--[upcoming]subscriptions-->
                <li class="sidenav-menu-item  menu-tooltip menu-with-tooltip hidden" title="Subscriptions">
                    <a class="waves-effect waves-dark p-r-20" href="<?php echo $base_url; ?>subscriptions" aria-expanded="false"
                        target="_self">
                        <i class="sl-icon-docs"></i>
                        <span class="hide-menu">Subscriptions
                        </span>
                    </a>
                </li>


                <!--billing-->
                <?php // if($user_role_resources->role_invoices >= 1 || $user_role_resources->role_payments >= 1 ||
                // $user_role_resources->role_estimates >= 1 || $user_role_resources->role_items >= 1 ||
                // $user_role_resources->role_expenses >= 1) : 
                ?>
                <!-- <li class="sidenav-menu-item ">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="ti-wallet"></i>
                        <span class="hide-menu">Finance
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <?php if ($user_role_resources->role_invoices >= 1) : ?>
                        <li class="sidenav-submenu " id="submenu_invoices">
                            <a href="<?php echo $base_url; ?>invoices" class=" ">Invoices</a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if ($user_role_resources->role_payments >= 1) : ?>
                        <li class="sidenav-submenu " id="submenu_payments">
                            <a href="<?php echo $base_url; ?>payments" class=" ">Payments</a>
                        </li>
                        <?php endif; ?>

                        <?php if ($user_role_resources->role_estimates >= 1) : ?>
                        <li class="sidenav-submenu " id="submenu_estimates">
                            <a href="<?php echo $base_url; ?>estimates" class=" ">Estimates</a>
                        </li>
                        <?php endif; ?>

                        <?php if ($user_role_resources->role_items >= 1) : ?>
                        <li class="sidenav-submenu " id="submenu_products">
                            <a href="<?php echo $base_url; ?>products" class=" ">Products</a>
                        </li>
                        <?php endif; ?>

                        <?php if ($user_role_resources->role_expenses >= 1) : ?>
                        <li class="sidenav-submenu " id="submenu_expenses">
                            <a href="<?php echo $base_url; ?>expenses" class=" ">Expenses</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li> -->
                <?php // endif;
                ?>
                <!--billing-->

                <!-- Assets -->
                <?php if ($system[0]->module_assets == 'true') { ?>
                    <?php if (in_array('24', $role_resources_ids) || in_array('25', $role_resources_ids) || in_array('26', $role_resources_ids)) { ?>
                        <li class="<?php if (!empty($arr_mod['asst_open'])) {
                                        echo $arr_mod['asst_open'];
                                    } ?> sidenav-menu-item" id="assets">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fa fa-flask"></i>
                                <span class="hide-menu"><?php echo $this->lang->line('xin_assets'); ?></span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <?php if (in_array('25', $role_resources_ids)) { ?>
                                    <li class="sidenav-link <?php if (!empty($arr_mod['asst_active'])) {
                                                                echo $arr_mod['asst_active'];
                                                            } ?>">
                                        <a href="<?php echo site_url('admin/assets'); ?>">
                                            <?php echo $this->lang->line('xin_assets'); ?>
                                        </a>
                                    </li>
                                <?php } ?>

                                <?php if (in_array('26', $role_resources_ids)) { ?>
                                    <li
                                        class="sidenav-link <?php if (!empty($arr_mod['asst_cat_active'])) {
                                                                echo $arr_mod['asst_cat_active'];
                                                            } ?>">
                                        <a href="<?php echo site_url('admin/assets/category'); ?>">
                                            <?php echo $this->lang->line('xin_acc_category'); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                <?php } ?>
                <!-- End Assets -->
<!--Reports Start -->
<?php if (in_array('1708', $role_resources_ids)) { 
        ?>

        <li class="sidenav-menu-item " id="reports">
            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                <i class="fa fa-file" aria-hidden="true"></i>
                <span class="hide-menu">Reports
                </span>
            </a>
            <ul aria-expanded="false" class="collapse">
                <li class="sidenav-submenu" id="submenu_aplist">
                    <a class="" href="<?php echo $base_url; ?>hrms/admin/ApList">Debtor/Creditor List
                    </a>
                </li>
            </ul>
        </li>
        <?php } 
                ?>
        <!--Reports End-->
                <!-- Settings -->
                <?php if (in_array('57', $role_resources_ids) || in_array('60', $role_resources_ids) || in_array('61', $role_resources_ids) || in_array('61', $role_resources_ids) || in_array('62', $role_resources_ids) || in_array('63', $role_resources_ids) || in_array('89', $role_resources_ids) || in_array('93', $role_resources_ids)) { ?>
                    <li class="<?php if (!empty($arr_mod['system_open'])) {
                                    echo $arr_mod['system_open'];
                                } ?> sidenav-menu-item" id="settings">
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                            <i class="fa fa-cog"></i>
                            <span class="hide-menu"><?php echo $this->lang->line('xin_system'); ?></span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <?php if ($system[0]->module_language == 'true') { ?>
                                <?php if (in_array('89', $role_resources_ids)) { ?>
                                    <li
                                        class="sidenav-link <?php if (!empty($arr_mod['languages_active'])) {
                                                                echo $arr_mod['languages_active'];
                                                            } ?>" id="submenu_multilang">
                                        <a href="<?php echo site_url('admin/languages'); ?>">
                                            <?php echo $this->lang->line('xin_multi_language'); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>

                            <?php if (in_array('60', $role_resources_ids)) { ?>
                                <li
                                    class="sidenav-link <?php if (!empty($arr_mod['settings_active'])) {
                                                            echo $arr_mod['settings_active'];
                                                        } ?>">
                                    <a href="<?php echo site_url('admin/settings'); ?>">
                                        <?php echo $this->lang->line('left_settings'); ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if (in_array('93', $role_resources_ids)) { ?>
                                <li class="sidenav-link <?php if (!empty($arr_mod['modules_active'])) {
                                                            echo $arr_mod['modules_active'];
                                                        } ?>">
                                    <a href="<?php echo site_url('admin/settings/modules'); ?>">
                                        <?php echo $this->lang->line('xin_setup_modules'); ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if (in_array('94', $role_resources_ids)) { ?>
                                <li class="sidenav-link <?php if (!empty($arr_mod['theme_active'])) {
                                                            echo $arr_mod['theme_active'];
                                                        } ?>">
                                    <a href="<?php echo site_url('admin/theme'); ?>">
                                        <?php echo $this->lang->line('xin_theme_settings'); ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if (in_array('118', $role_resources_ids)) { ?>
                                <li
                                    class="sidenav-link <?php if (!empty($arr_mod['payment_gateway_active'])) {
                                                            echo $arr_mod['payment_gateway_active'];
                                                        } ?>">
                                    <a href="<?php echo site_url('admin/settings/payment_gateway'); ?>">
                                        <?php echo $this->lang->line('xin_acc_payment_gateway'); ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if (in_array('61', $role_resources_ids)) { ?>
                                <li
                                    class="sidenav-link <?php if (!empty($arr_mod['constants_active'])) {
                                                            echo $arr_mod['constants_active'];
                                                        } ?>">
                                    <a href="<?php echo site_url('admin/settings/constants'); ?>">
                                        <?php echo $this->lang->line('left_constants'); ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if (in_array('62', $role_resources_ids)) { ?>
                                <li class="sidenav-link <?php if (!empty($arr_mod['db_active'])) {
                                                            echo $arr_mod['db_active'];
                                                        } ?>">
                                    <a href="<?php echo site_url('admin/settings/database_backup'); ?>">
                                        <?php echo $this->lang->line('left_db_backup'); ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if (in_array('63', $role_resources_ids)) { ?>
                                <li
                                    class="sidenav-link <?php if (!empty($arr_mod['email_template_active'])) {
                                                            echo $arr_mod['email_template_active'];
                                                        } ?>">
                                    <a href="<?php echo site_url('admin/settings/email_template'); ?>">
                                        <?php echo $this->lang->line('left_email_templates'); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <!-- End Settings -->


                <!--tickets-->
                <!-- <?php //if($user_role_resources->role_tickets >= 1) :
                        ?>
                <li class="sidenav-menu-item  menu-tooltip menu-with-tooltip" title="Tickets">
                    <a class="waves-effect waves-dark" href="<?php echo $base_url; ?>tickets" aria-expanded="false" target="_self">
                        <i class="ti-comments"></i>
                        <span class="hide-menu">Support
                        </span>
                    </a>
                </li>
                <?php //endif;
                ?> -->
                <!--tickets-->


                <!--knowledgebase-->
                <!-- <?php //if($user_role_resources->role_knowledgebase >= 1) :
                        ?>
                <li class="sidenav-menu-item  menu-tooltip menu-with-tooltip" title="Knowledgebase">
                    <a class="waves-effect waves-dark p-r-20" href="<?php echo $base_url; ?>knowledgebase" aria-expanded="false"
                        target="_self">
                        <i class="sl-icon-docs"></i>
                        <span class="hide-menu">Knowledgebase
                        </span>
                    </a>
                </li>
                <?php //endif;
                ?> -->
                <!--knowledgebase-->


                <!--team-->
                <?php //if($role_user[0]->role_id == 1) {
                ?>
                <!-- <li class="sidenav-menu-item ">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="ti-archive"></i>
                        <span class="hide-menu">Other
                        </span>
                    </a>
                    <ul aria-expanded="false" class="position-top collapse">
                        <li class="sidenav-submenu mainmenu_team " id="submenu_team">
                            <a href="<?php echo $base_url; ?>team" class="">Team Members</a>
                        </li>
                        <li class="sidenav-submenu mainmenu_timesheets " id="submenu_timesheets">
                            <a href="<?php echo $base_url; ?>timesheets" class="">Time Sheets</a>
                        </li> -->
                <!--[UPCOMING]-->
                <!-- <li class="sidenav-submenu mainmenu_reports  hidden" id="submenu_reports">
                            <a href="<?php echo $base_url; ?>reports" class="">Reports</a>
                        </li>
                    </ul>
                </li> -->
                <?php // } elseif($user_role_resources->role_timesheets >= 1) {
                ?>
                <!-- <li class="sidenav-menu-item  menu-tooltip menu-with-tooltip" title="Time Sheet">
                    <a class="waves-effect waves-dark p-r-20" href="<?php echo $base_url; ?>timesheets" aria-expanded="false"
                        target="_self">
                        <i class="ti-timer"></i>
                        <span class="hide-menu">Time Sheets
                        </span>
                    </a>
                </li> -->
                <?php // } 
                ?>
                <!--team-->

                <!--mail chimp-->
                <?php // if($role_user[0]->role_id == 1) :
                ?>
                <!-- <li class="sidenav-menu-item  menu-tooltip menu-with-tooltip" title="Mailchimp">
                    <a class="waves-effect waves-dark p-r-20" href="https://login.mailchimp.com/" aria-expanded="false"
                        target="_blank">
                        <i class="ti-email"></i>
                        <span class="hide-menu">Mailchimp
                        </span>
                    </a>
                </li> -->
                <?php // endif;
                ?>
                <!--mailchimp-->

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>