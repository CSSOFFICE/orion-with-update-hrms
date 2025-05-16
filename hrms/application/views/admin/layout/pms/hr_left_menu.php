
        
<?php
$session = $this->session->userdata('username');
$user_info = $this->Xin_model->read_user_info($session['user_id']);
if ($user_info[0]->is_active != 1) {
    redirect('admin/');
}
$role_user = $this->Xin_model->read_user_role_info($user_info[0]->user_role_id);
if (!is_null($role_user)) {
    $role_resources_ids = explode(',', $role_user[0]->role_resources);
} else {
    $role_resources_ids = explode(',', 0);
}

if (!is_null($role_user)) {
    $user_role_resources = $this->PmUserRole_model->get_user_role_resources($role_user[0]->role_id);
} else {
    $user_role_resources = false;
}

$base_url = str_replace('/hrms', '', base_url());
?>
<?php $system = $this->Xin_model->read_setting_info(1); ?>
<?php $arr_mod = $this->Xin_model->select_module_class($this->router->fetch_class(), $this->router->fetch_method()); ?>
<?php
$idocuments_expired = 0;
$iimg_documents = 0;
$icompany_license = 0;
$iwarranty_assets = 0;
if ($user_info[0]->user_role_id == 1) {
    $idocuments_expired = $this->Xin_model->count_get_documents_expired_all();
    $iimg_documents = $this->Xin_model->count_get_img_documents_expired_all();
    $icompany_license = $this->Xin_model->iicount_company_license_expired_all();
    $iwarranty_assets = $this->Xin_model->count_warranty_assets_expired_all();
} else {
    $idocuments_expired = $this->Xin_model->count_get_user_documents_expired_all($session['user_id']);
    $iimg_documents = $this->Xin_model->count_get_user_img_documents_expired_all($session['user_id']);
    $icompany_license = $this->Xin_model->count_get_company_license_expired($session['user_id']);
    if (in_array('265', $role_resources_ids)) {
        $iwarranty_assets = $this->Xin_model->count_company_warranty_assets_expired_all($user_info[0]->company_id);
    } else {
        $iwarranty_assets = $this->Xin_model->count_user_warranty_assets_expired_all($session['user_id']);
    }
}
$exp_count = $idocuments_expired + $iimg_documents + $icompany_license + $iwarranty_assets;
// reports to
$reports_to = get_reports_team_data($session['user_id']);
?>
<li class="sidenav-menu-item" id="HRMS">
    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
        <i class="fa fa-home" aria-hidden="true"></i>
        <span class="hide-menu">HRMS
        </span>
    </a>
    <ul aria-expanded="false" class="collapse">
        <!--Dashboard-->
        <li class="sidenav-menu-item menu-tooltip menu-with-tooltip <?php if (!empty($arr_mod['active'])) {
                                                                        echo $arr_mod['active'];
                                                                    } ?>">
            <a href="<?php echo site_url('admin/dashboard'); ?>" class="waves-effect waves-dark" aria-expanded="false" target="_self">
                <i class="fa fa-home" aria-hidden="true"></i>
                <span class="hide-menu1"><?php echo $this->lang->line('hr_dashboard_title'); ?></span>
            </a>
        </li>
        <!--End Dashboard-->

        <!--Staff-->
        <?php if (in_array('13', $role_resources_ids) || in_array('88', $role_resources_ids) || in_array('92', $role_resources_ids) || in_array('22', $role_resources_ids) || in_array('23', $role_resources_ids) || in_array('422', $role_resources_ids) || in_array('400', $role_resources_ids) || in_array('429', $role_resources_ids) || $user_info[0]->user_role_id == 1 || $reports_to > 0) { ?>

            <li class="<?php if (!empty($arr_mod['stff_open'])) {
                            echo $arr_mod['stff_open'];
                        } ?> sidenav-menu-item">
                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                    <i class="fa fa-user"></i>
                    <span class="hide-menu1"><?php echo $this->lang->line('let_staff'); ?></span>
                    <span class="pull-right-container"> <?php if ($exp_count > 0) : ?>
                            <span class="label label-danger pull-right"><?php echo $exp_count; ?></span>
                        <?php endif; ?>
                    </span>
                </a>
                <ul aria-expanded="false" class="collapse">
                    <?php if (in_array('422', $role_resources_ids)) { ?>
                        <li class="<?php if (!empty($arr_mod['emp_dashboard_active'])) {
                                        echo $arr_mod['emp_dashboard_active'];
                                    } ?>">
                            <a href="<?php echo site_url('admin/employees/staff_dashboard'); ?>">
                                <?php echo $this->lang->line('hr_staff_dashboard_title'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('13', $role_resources_ids) || $reports_to > 0) { ?>
                        <li class="<?php if (!empty($arr_mod['emp_active'])) {
                                        echo $arr_mod['emp_active'];
                                    } ?>"><a href="<?php echo site_url('admin/employees'); ?>">
                                <?php echo $this->lang->line('dashboard_employees'); ?></a></li>
                    <?php } ?>

                    <?php if (in_array('429', $role_resources_ids)) { ?>
                        <li class="<?php if (!empty($arr_mod['emp_benefits_active'])) {
                                        echo $arr_mod['emp_benefits_active'];
                                    } ?>">
                            <a href="<?php echo site_url('admin/employeebenefits'); ?>"> Employee Benefits</a>
                        </li>
                    <?php } ?>

                    <?php if ($user_info[0]->user_role_id == 1) { ?>
                        <li class="<?php if (!empty($arr_mod['roles_active'])) {
                                        echo $arr_mod['roles_active'];
                                    } ?>">
                            <a href="<?php echo site_url('admin/roles'); ?>">
                                <?php echo $this->lang->line('xin_role_urole'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('88', $role_resources_ids) || $reports_to > 0) { ?>
                        <li class="<?php if (!empty($arr_mod['hremp_active'])) {
                                        echo $arr_mod['hremp_active'];
                                    } ?>">
                            <a href="<?php echo site_url('admin/employees/hr'); ?>">
                                <?php echo $this->lang->line('left_employees_directory'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('23', $role_resources_ids)) { ?>
                        <li class="<?php if (!empty($arr_mod['emp_ex_active'])) {
                                        echo $arr_mod['emp_ex_active'];
                                    } ?>">
                            <a href="<?php echo site_url('admin/employee_exit'); ?>">
                                <?php echo $this->lang->line('left_employees_exit'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('400', $role_resources_ids)) { ?>
                        <li class="<?php if (!empty($arr_mod['exp_doc_active'])) {
                                        echo $arr_mod['exp_doc_active'];
                                    } ?>">
                            <a href="<?php echo site_url('admin/employees/expired_documents'); ?>">
                                <?php echo $this->lang->line('xin_e_details_exp_documents'); ?>
                                <?php if ($exp_count > 0) : ?>
                                    <span class="label label-danger pull-right"><?php echo $exp_count; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('22', $role_resources_ids) || $reports_to > 0) { ?>
                        <li class="<?php if (!empty($arr_mod['emp_ll_active'])) {
                                        echo $arr_mod['emp_ll_active'];
                                    } ?>">
                            <a href="<?php echo site_url('admin/employees_last_login'); ?>">
                                <?php echo $this->lang->line('left_employees_last_login'); ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>
        <!--End Staff-->

        <!--Core HR-->
        <?php if (in_array('12', $role_resources_ids) || in_array('14', $role_resources_ids) || in_array('15', $role_resources_ids) || in_array('16', $role_resources_ids) || in_array('17', $role_resources_ids) || in_array('18', $role_resources_ids) || in_array('19', $role_resources_ids) || in_array('20', $role_resources_ids) || in_array('21', $role_resources_ids) || in_array('95', $role_resources_ids) || in_array('92', $role_resources_ids)) { ?>

            <li class="<?php if (!empty($arr_mod['emp_open'])) {
                            echo $arr_mod['emp_open'];
                        } ?> sidenav-menu-item">
                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                    <i class="fa fa-futbol-o"></i>
                    <span class="hide-menu1"><?php echo $this->lang->line('xin_hr'); ?></span>
                </a>
                <ul aria-expanded="false" class="collapse">
                    <?php if ($system[0]->module_awards == 'true') { ?>
                        <?php if (in_array('14', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['awar_active'])) {
                                                        echo $arr_mod['awar_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/awards'); ?>">
                                    <?php echo $this->lang->line('left_awards'); ?>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>

                    <?php if (in_array('15', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['tra_active'])) {
                                                    echo $arr_mod['tra_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/transfers'); ?>">
                                <?php echo $this->lang->line('left_transfers'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('16', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['res_active'])) {
                                                    echo $arr_mod['res_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/resignation'); ?>">
                                <?php echo $this->lang->line('left_resignations'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if ($system[0]->module_travel == 'true') { ?>
                        <?php if (in_array('17', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['trav_active'])) {
                                                        echo $arr_mod['trav_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/travel'); ?>">
                                    <?php echo $this->lang->line('left_travels'); ?>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>

                    <?php if (in_array('18', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['pro_active'])) {
                                                    echo $arr_mod['pro_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/promotion'); ?>">
                                <?php echo $this->lang->line('left_promotions'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('19', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['compl_active'])) {
                                                    echo $arr_mod['compl_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/complaints'); ?>">
                                <?php echo $this->lang->line('left_complaints'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('20', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['warn_active'])) {
                                                    echo $arr_mod['warn_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/warning'); ?>">
                                <?php echo $this->lang->line('left_warnings'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('21', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['term_active'])) {
                                                    echo $arr_mod['term_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/termination'); ?>">
                                <?php echo $this->lang->line('left_terminations'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <!--HR Calendar-->
                    <?php if (in_array('95', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['calendar_hr_active'])) {
                                                    echo $arr_mod['calendar_hr_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/calendar/hr'); ?>">
                                <span class="hide-menu1"><?php echo $this->lang->line('xin_hr_calendar_title'); ?></span>
                            </a>
                        </li>
                    <?php } ?>

                    <!--HR Imports-->
                    <?php if (in_array('92', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['import_active'])) {
                                                    echo $arr_mod['import_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/import'); ?>">
                                <span class="hide-menu1"><?php echo $this->lang->line('xin_hr_imports'); ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>
        <!--End Core HR-->

        <!--Organization-->
        <?php if (in_array('2', $role_resources_ids) || in_array('3', $role_resources_ids) || in_array('5', $role_resources_ids) || in_array('6', $role_resources_ids) || in_array('4', $role_resources_ids) || in_array('11', $role_resources_ids) || in_array('9', $role_resources_ids) || in_array('96', $role_resources_ids)) { ?>
            <li class="<?php if (!empty($arr_mod['adm_open'])) {
                            echo $arr_mod['adm_open'];
                        } ?> sidenav-menu-item">
                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                    <i class="fa fa-building"></i>
                    <span class="hide-menu1"><?php echo $this->lang->line('left_organization'); ?></span>
                </a>
                <ul aria-expanded="false" class="collapse">
                    <?php if (in_array('5', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['com_active'])) {
                                                    echo $arr_mod['com_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/company') ?>">
                                <?php echo $this->lang->line('left_company'); ?>
                            </a>
                        </li>

                        <li class="sidenav-link <?php if (!empty($arr_mod['official_documents_active'])) {
                                                    echo $arr_mod['official_documents_active'];
                                                }
                                                ?>">
                            <a href="<?php echo site_url('admin/company/official_documents') ?>">
                                <?php echo $this->lang->line('xin_hr_official_documents'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('6', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['loc_active'])) {
                                                    echo $arr_mod['loc_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/location'); ?>">
                                <?php echo $this->lang->line('left_location'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('3', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['dep_active'])) {
                                                    echo $arr_mod['dep_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/department'); ?>">
                                <?php echo $this->lang->line('left_department'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if ($system[0]->is_active_sub_departments == 'yes') { ?>
                        <?php if (in_array('3', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['sub_departments_active'])) {
                                                        echo $arr_mod['sub_departments_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/department/sub_departments'); ?>">
                                    <?php echo $this->lang->line('xin_hr_sub_departments'); ?>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>

                    <?php if (in_array('4', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['des_active'])) {
                                                    echo $arr_mod['des_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/designation'); ?>">
                                <?php echo $this->lang->line('left_designation'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('11', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['ann_active'])) {
                                                    echo $arr_mod['ann_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/announcement'); ?>">
                                <?php echo $this->lang->line('left_announcements'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('9', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['pol_active'])) {
                                                    echo $arr_mod['pol_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/policy'); ?>">
                                <?php echo $this->lang->line('left_policies'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if ($system[0]->module_orgchart == 'true') { ?>
                        <?php if (in_array('96', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['org_chart_active'])) {
                                                        echo $arr_mod['org_chart_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/organization/chart'); ?>">
                                    <?php echo $this->lang->line('xin_org_chart_lnk'); ?>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>
        <!--End Organization-->

        <!--Timesheet-->
        <?php if (in_array('27', $role_resources_ids) || in_array('28', $role_resources_ids) || in_array('29', $role_resources_ids) || in_array('30', $role_resources_ids) || in_array('31', $role_resources_ids) || in_array('7', $role_resources_ids) || in_array('8', $role_resources_ids) || in_array('423', $role_resources_ids) || in_array('46', $role_resources_ids) || in_array('401', $role_resources_ids) || $reports_to > 0) { ?>
            <li class="<?php if (!empty($arr_mod['attnd_open'])) {
                            echo $arr_mod['attnd_open'];
                        } ?> sidenav-menu-item">
                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                    <i class="fa fa-clock-o"></i>
                    <span class="hide-menu1"><?php echo $this->lang->line('left_timesheet'); ?></span>
                </a>
                <ul aria-expanded="false" class="collapse">
                    <?php if (in_array('423', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['attendance_dashboard_active'])) {
                                                    echo $arr_mod['attendance_dashboard_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/timesheet/attendance_dashboard'); ?>">
                                <?php echo $this->lang->line('hr_timesheet_dashboard_title'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('28', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['attnd_active'])) {
                                                    echo $arr_mod['attnd_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/timesheet/attendance'); ?>">
                                <?php echo $this->lang->line('left_attendance'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('10', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['timesheet_active'])) {
                                                    echo $arr_mod['timesheet_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/timesheet/'); ?>">
                                <?php echo $this->lang->line('xin_month_timesheet_title'); ?>
                            </a>
                            <!-- <a href="<?php echo site_url('admin/timesheet/'); ?>">
                                <?php echo $this->lang->line('xin_month_timesheet_title'); ?>
                            </a> -->
                        </li>
                    <?php } ?>

                    <?php if (in_array('261', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['timecalendar_active'])) {
                                                    echo $arr_mod['timecalendar_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/timesheet/timecalendar/'); ?>">
                                <?php echo $this->lang->line('xin_attendance_timecalendar'); ?> </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('29', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['dtwise_attnd_active'])) {
                                                    echo $arr_mod['dtwise_attnd_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/timesheet/date_wise_attendance'); ?>">
                                <?php echo $this->lang->line('left_date_wise_attendance'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('30', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['upd_attnd_active'])) {
                                                    echo $arr_mod['upd_attnd_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/timesheet/update_attendance'); ?>">
                                <?php echo $this->lang->line('left_update_attendance'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if ($system[0]->module_overtime_request == 'yes'  && in_array('401', $role_resources_ids)) { ?>
                        <li class="<?php if (!empty($arr_mod['overtime_request_act'])) {
                                        echo $arr_mod['overtime_request_act'];
                                    } ?>">
                            <a href="<?php echo site_url('admin/overtime_request'); ?>">
                                <?php echo $this->lang->line('xin_overtime_request'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('7', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['offsh_active'])) {
                                                    echo $arr_mod['offsh_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/timesheet/office_shift'); ?>">
                                <?php echo $this->lang->line('left_office_shifts'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('8', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['hol_active'])) {
                                                    echo $arr_mod['hol_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/timesheet/holidays'); ?>">
                                <?php echo $this->lang->line('xin_manage_holidays'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('46', $role_resources_ids) || $reports_to > 0) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['leave_active'])) {
                                                    echo $arr_mod['leave_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/timesheet/leave'); ?>">
                                <?php echo $this->lang->line('xin_manage_leaves'); ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array('31', $role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if (!empty($arr_mod['leave_status_active'])) {
                                                    echo $arr_mod['leave_status_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/reports/employee_leave'); ?>">
                                <?php echo $this->lang->line('xin_leave_status'); ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } ?>
        <!--End Timesheet-->

        <!-- Live Location -->
        <?php if ($role_user[0]->role_id == 1) {

        ?>
            <li class="sidenav-menu-item menu-tooltip menu-with-tooltip">
                <a href="<?php echo $base_url; ?>map" class="waves-effect waves-dark" aria-expanded="false" target="_self">
                    <i class="ti-map"></i>
                    <span class="hide-menu1">Live Location</span>
                </a>
            </li>
        <?php } ?>
        <!-- End Live Location -->

        <!--Payroll-->
        <?php if (in_array('36', $role_resources_ids) || in_array('37', $role_resources_ids)) { ?>
            <li class="<?php if (!empty($arr_mod['attnd_open'])) {
                            echo $arr_mod['attnd_open'];
                        } ?> sidenav-menu-item" id="payroll">
                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                    <i class="fa fa-calculator"></i>
                    <span class="hide-menu1"><?php echo $this->lang->line('left_payroll'); ?></span>
                </a>
                <ul aria-expanded="false" class="collapse">
                    <?php if ($system[0]->module_payroll == 'yes') { ?>
                        <?php if (in_array('36', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['pay_generate_active'])) {
                                                        echo $arr_mod['pay_generate_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/payroll/generate_payslip'); ?>">
                                    <span class="hide-menu1"><?php echo $this->lang->line('left_payroll'); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($system[0]->module_accounting == 'true') { ?>
                        <?php if (in_array('37', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['pay_his_active'])) {
                                                        echo $arr_mod['pay_his_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/payroll/payment_history'); ?>">
                                    <?php echo $this->lang->line('xin_payslip_history'); ?>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>

                </ul>
            </li>

        <?php } ?>
        <!--End Payroll-->

        <!-- E Fillings -->
        <?php if ($system[0]->module_payroll == 'yes'  && in_array('428', $role_resources_ids)) { ?>

            <li class="<?php if (!empty($arr_mod['efiling_open'])) {
                            echo $arr_mod['efiling_open'];
                        } ?> sidenav-menu-item">
                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                    <i class="fa fa-file"></i>
                    <span class="hide-menu1">E Filling</span>
                </a>
                <ul aria-expanded="false" class="collapse">
                    <li class="<?php if (!empty($arr_mod['employerdetails_active'])) {
                                    echo $arr_mod['employerdetails_active'];
                                } ?>">
                        <a href="<?php echo site_url('admin/efiling/employerdetails'); ?>">
                            E Filling Details
                        </a>
                    </li>

                    <li class="<?php if (!empty($arr_mod['efiling_cpf_active'])) {
                                    echo $arr_mod['efiling_cpf_active'];
                                } ?>">
                        <a href="<?php echo site_url('admin/efiling/cpf'); ?>"> CPF Submission</a>
                    </li>
                    <li class="<?php if (!empty($arr_mod['efiling_ir8a_active'])) {
                                    echo $arr_mod['efiling_ir8a_active'];
                                } ?>">
                        <a href="<?php echo site_url('admin/efiling/ir8a'); ?>"> IRA8 Form</a>
                    </li>
                    <li class="<?php if (!empty($arr_mod['efiling_appendix8a_active'])) {
                                    echo $arr_mod['efiling_appendix8a_active'];
                                } ?>">
                        <a href="<?php echo site_url('admin/efiling/appendix8a'); ?>"> Appendix 8A</a>
                    </li>
                    <li class="<?php if (!empty($arr_mod['efiling_appendix8b_active'])) {
                                    echo $arr_mod['efiling_appendix8b_active'];
                                } ?>">
                        <a href="<?php echo site_url('admin/efiling/appendix8b'); ?>"> Appendix 8B</a>
                    </li>
                    <li class="">
                        <a href="#"> IR8S</a>
                    </li>
                    <li class="<?php if (!empty($arr_mod['efiling_iras_submission_active'])) {
                                    echo $arr_mod['efiling_iras_submission_active'];
                                } ?>">
                        <a href="<?php echo site_url('admin/efiling/irassubmission'); ?>"> IRAS Submission</a>
                    </li>
                </ul>
            </li>
        <?php } ?>
        <!-- End E Fillings -->
        <!-- Recruitment -->
        <?php if ($system[0]->module_recruitment == 'true') { ?>
            <?php if (in_array('48', $role_resources_ids) || in_array('49', $role_resources_ids) || in_array('51', $role_resources_ids) || in_array('52', $role_resources_ids)) { ?>
                <li class="<?php if (!empty($arr_mod['recruit_open'])) {
                                echo $arr_mod['recruit_open'];
                            } ?> sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-newspaper-o"></i>
                        <span class="hide-menu1"><?php echo $this->lang->line('left_recruitment'); ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <?php if (in_array('49', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['jb_post_active'])) {
                                                        echo $arr_mod['jb_post_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/job_post'); ?>">
                                    <?php echo $this->lang->line('left_job_posts'); ?>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (in_array('51', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['jb_cand_active'])) {
                                                        echo $arr_mod['jb_cand_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/job_candidates'); ?>">
                                    <?php echo $this->lang->line('left_job_candidates'); ?>
                                </a>
                            </li>
                        <?php } ?>

                        <li class="sidenav-link <?php if (!empty($arr_mod['jb_emp_active'])) {
                                                    echo $arr_mod['jb_emp_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/job_post/employer'); ?>">
                                <?php echo $this->lang->line('xin_jobs_employer'); ?>
                            </a>
                        </li>

                        <li class="sidenav-link <?php if (!empty($arr_mod['jb_pages_active'])) {
                                                    echo $arr_mod['jb_pages_active'];
                                                } ?>">
                            <a href="<?php echo site_url('admin/job_post/pages'); ?>">
                                <?php echo $this->lang->line('xin_jobs_cms_pages'); ?>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } ?>
        <?php } ?>
        <!-- End Recruitment -->
        <!-- Reports -->
        <?php if (in_array('110', $role_resources_ids) || in_array('111', $role_resources_ids) || in_array('112', $role_resources_ids) || in_array('113', $role_resources_ids) || in_array('114', $role_resources_ids) || in_array('115', $role_resources_ids) || in_array('116', $role_resources_ids) || in_array('117', $role_resources_ids) || in_array('409', $role_resources_ids) || in_array('83', $role_resources_ids) || in_array('84', $role_resources_ids) || in_array('85', $role_resources_ids) || in_array('86', $role_resources_ids)) { ?>
            <li class="sidenav-menu-item menu-tooltip menu-with-tooltip <?php if (!empty($arr_mod['reports_active'])) {
                                                                            echo $arr_mod['reports_active'];
                                                                        } ?>">
                <a href="<?php echo site_url('admin/reports'); ?>" class="waves-effect waves-dark" aria-expanded="false" target="_self">
                    <i class="fa fa-bar-chart"></i>
                    <span class="hide-menu1"><?php echo $this->lang->line('xin_hr_report_title'); ?></span>
                </a>
            </li>
        <?php } ?>
        <!-- End Reports -->

        <!-- Training -->
        <?php if ($system[0]->module_training == 'true') { ?>
            <?php if (in_array('53', $role_resources_ids) || in_array('54', $role_resources_ids) || in_array('55', $role_resources_ids) || in_array('56', $role_resources_ids)) { ?>
                <li class="<?php if (!empty($arr_mod['training_open'])) {
                                echo $arr_mod['training_open'];
                            } ?> sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-graduation-cap"></i>
                        <span class="hide-menu1"><?php echo $this->lang->line('left_training'); ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <?php if (in_array('54', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['training_active'])) {
                                                        echo $arr_mod['training_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/training'); ?>">
                                    <?php echo $this->lang->line('left_training_list'); ?>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (in_array('55', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['tr_type_active'])) {
                                                        echo $arr_mod['tr_type_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/training_type'); ?>">
                                    <?php echo $this->lang->line('left_training_type'); ?>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (in_array('56', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['trainers_active'])) {
                                                        echo $arr_mod['trainers_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/trainers'); ?>">
                                    <?php echo $this->lang->line('left_trainers_list'); ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        <?php } ?>
        <!-- End Training -->

        <!-- Performance -->
        <?php if ($system[0]->module_performance == 'yes') { ?>
            <?php if (in_array('40', $role_resources_ids) || in_array('41', $role_resources_ids) || in_array('42', $role_resources_ids) || in_array('107', $role_resources_ids) || in_array('108', $role_resources_ids) || in_array('372', $role_resources_ids) || in_array('373', $role_resources_ids)) { ?>
                <li class="<?php if (!empty($arr_mod['performance_open'])) {
                                echo $arr_mod['performance_open'];
                            } ?> sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-cube"></i>
                        <span class="hide-menu1"><?php echo $this->lang->line('left_performance'); ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <?php if (in_array('41', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['per_indi_active'])) {
                                                        echo $arr_mod['per_indi_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/performance_indicator'); ?>">
                                    <?php echo $this->lang->line('left_performance_xindicator'); ?>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (in_array('42', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['per_app_active'])) {
                                                        echo $arr_mod['per_app_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/performance_appraisal'); ?>">
                                    <?php echo $this->lang->line('left_performance_xappraisal'); ?>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($system[0]->module_goal_tracking == 'true') { ?>
                            <?php if (in_array('107', $role_resources_ids)) { ?>
                                <li class="sidenav-link <?php if (!empty($arr_mod['goal_tracking_active'])) {
                                                            echo $arr_mod['goal_tracking_active'];
                                                        } ?>">
                                    <a href="<?php echo site_url('admin/goal_tracking'); ?>">
                                        <?php echo $this->lang->line('xin_hr_goal_tracking'); ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if (in_array('108', $role_resources_ids)) { ?>
                                <li class="sidenav-link <?php if (!empty($arr_mod['goal_tracking_type_active'])) {
                                                            echo $arr_mod['goal_tracking_type_active'];
                                                        } ?>">
                                    <a href="<?php echo site_url('admin/goal_tracking/type'); ?>">
                                        <?php echo $this->lang->line('xin_hr_goal_tracking_type_se'); ?>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        <?php } ?>
        <!-- End Performance -->

        <!-- Tickets -->
        <?php if ($system[0]->module_inquiry == 'true') { ?>
            <?php if (in_array('43', $role_resources_ids)) { ?>
                <li class="sidenav-menu-item menu-tooltip menu-with-tooltip <?php if (!empty($arr_mod['ticket_active'])) {
                                                                                echo $arr_mod['ticket_active'];
                                                                            } ?>">
                    <a href="<?php echo site_url('admin/tickets'); ?>" class="waves-effect waves-dark" aria-expanded="false" target="_self">
                        <i class="fa fa-ticket"></i>
                        <span class="hide-menu1"><?php echo $this->lang->line('left_tickets'); ?></span>
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
        <!-- End Tickets -->

        <!-- Files -->
        <?php if ($system[0]->module_files == 'true') { ?>
            <?php if (in_array('47', $role_resources_ids)) { ?>
                <li class="sidenav-menu-item menu-tooltip menu-with-tooltip <?php if (!empty($arr_mod['file_active'])) {
                                                                                echo $arr_mod['file_active'];
                                                                            } ?>">
                    <a href="<?php echo site_url('admin/files'); ?>" class="waves-effect waves-dark" aria-expanded="false" target="_self">
                        <i class="fa fa-file-text-o"></i>
                        <span class="hide-menu1"><?php echo $this->lang->line('xin_files_manager'); ?></span>
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
        <!-- End Files -->



        <!-- Events -->
        <?php if ($system[0]->module_events == 'true') { ?>
            <?php if (in_array('97', $role_resources_ids) || in_array('98', $role_resources_ids) || in_array('99', $role_resources_ids)) { ?>
                <li class="<?php if (!empty($arr_mod['hr_events_open'])) {
                                echo $arr_mod['hr_events_open'];
                            } ?> sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-calendar-plus-o"></i>
                        <span class="hide-menu1"><?php echo $this->lang->line('xin_hr_events_meetings'); ?></span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <?php if (in_array('98', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['hr_events_active'])) {
                                                        echo $arr_mod['hr_events_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/events'); ?>">
                                    <?php echo $this->lang->line('xin_hr_events'); ?>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (in_array('99', $role_resources_ids)) { ?>
                            <li class="sidenav-link <?php if (!empty($arr_mod['hr_meetings_active'])) {
                                                        echo $arr_mod['hr_meetings_active'];
                                                    } ?>">
                                <a href="<?php echo site_url('admin/meetings'); ?>">
                                    <?php echo $this->lang->line('xin_hr_meetings'); ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        <?php } ?>
        <!-- End Events -->


    </ul>
</li>
<!--projects-->
<?php if (in_array('1041', $role_resources_ids)) { ?>

    <li class="sidenav-menu-item">
        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
            <i class="fa fa-book" aria-hidden="true"></i>
            <span class="hide-menu">Projects
            </span>
        </a>
        <ul aria-expanded="false" class="collapse">
            <?php if (in_array('2004', $role_resources_ids)) { ?>
                <!--home-->
                <li class="sidenav-submenu" id="submenu_home">
                    <a class="" href="<?php echo $base_url; ?>home">Dashboard
                    </a>
                </li>
            <?php } ?>
            <!--home-->
            <?php if ($user_role_resources->role_projects >= 1 || in_array('1041', $role_resources_ids)) : ?>
                <li class="sidenav-submenu" id="submenu_projects">
                    <a class="" href="<?php echo $base_url; ?>projects">
                        Projects
                    </a>
                </li>
            <?php endif; ?>
            <?php if (in_array('45', $role_resources_ids)) { ?>

                <!--tasks-->
                <?php if ($user_role_resources->role_tasks >= 1) : ?>
                    <li class="sidenav-submenu" id="submenu_tasks">
                        <a class="" href="<?php echo $base_url; ?>tasks">Tasks
                        </a>
                    </li>
                <?php endif; ?>
                <!--tasks-->
            <?php } ?>

        </ul>
    </li>
    <!--projects-->
<?php } ?>
<!-- Start Finance-->

<?php if (($system[0]->module_sales == 'yes') && (in_array('3001', $role_resources_ids) || in_array('3101', $role_resources_ids))) { ?>
    <li class="sidenav-menu-item " id="Sales">
        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
            <i class="fa fa-usd" aria-hidden="true"></i>
            <span class="hide-menu">Sales
            </span>
        </a>
        <ul aria-expanded="false" class="collapse">



            <!--home-->
            <?php if (in_array('3001', $role_resources_ids)) { ?>

                <li class="sidenav-submenu" id="">
                    <a class="" href="<?php echo $base_url; ?>quo">Quotation
                    </a>
                </li>
            <?php } ?>

            <!--home-->
            <?php if (in_array('3101', $role_resources_ids)) { ?>
                <li class="sidenav-submenu" id="submenu_Sinvoice">
                    <a class="" href="<?php echo $base_url; ?>hrms/admin/finance/invoice_list">Invoice
                    </a>
                </li>

            <?php } ?>



        </ul>
    </li>
<?php } ?>
<!-- End Purchase-->
<!-- Start Purchase-->

<!-- <?php //if (($system[0]->module_purchase_requistion_request == 'yes') &&  in_array('8001', $role_resources_ids)) { 
        ?>
    <li class="sidenav-menu-item ">
        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
            <i class="fa fa-cart-plus" aria-hidden="true"></i>
            <span class="hide-menu">Procurement
            </span>
        </a>
        <ul aria-expanded="false" class="collapse">

            <li class="sidenav-submenu" id="submenu_home">
                <a class="" href="<?php echo $base_url; ?>hrms/admin/purchase/purchase_requistion">Request Material Requisition Form (MRF)
                </a>
            </li>
        </ul>
    </li>
<?php // } 
?> -->

<?php if (($system[0]->module_purchase == 'yes') && in_array('2801', $role_resources_ids) || in_array('2901', $role_resources_ids) || in_array('8001', $role_resources_ids)) { ?>
    <li class="sidenav-menu-item " id="Procurement">
        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
            <i class="fa fa-cart-plus" aria-hidden="true"></i>
            <span class="hide-menu">Procurement
            </span>
        </a>
        <ul aria-expanded="false" class="collapse">

            <?php if ($system[0]->module_supplier == 'yes' && in_array('2801', $role_resources_ids)) { ?>
                <li class="sidenav-submenu" id="submenu_supplier">
                    <a href="<?php echo base_url('admin/supplier'); ?>">
                        <span class="hide-menu"><?php echo $this->lang->line('xin_suppliers'); ?></span>
                    </a>
                </li>
            <?php } ?>
            <?php if ($system[0]->module_supplier == 'yes' && in_array('2806', $role_resources_ids)) { ?>
                <li class="sidenav-submenu" id="submenu_subcontractors">
                    <a href="<?php echo base_url('admin/supplier/subcontractors'); ?>">
                        <span class="hide-menu">Sub Contractor Agreement</span>
                    </a>
                </li>
            <?php } ?>


            <!--home-->
            <?php if ($system[0]->module_purchase_requistion == 'yes' &&  in_array('2901', $role_resources_ids)) { ?>
                <li class="sidenav-submenu" id="submenu_pr">
                    <a class="" href="<?php echo $base_url; ?>hrms/admin/purchase/purchase_requistion">Material Requisition Form (MRF)
                    </a>
                </li>
            <?php } ?>

            <!-- <?php //if($system[0]->module_purchase_requistion_request == 'yes'){ 
                    ?>
        <li class="sidenav-submenu" id="submenu_home">
            <a class="" href="<?php //echo $base_url;
                                ?>hrms/admin/purchase/viewpurchaserequest">Request Material Requisition Form (MRF)
            </a>
        </li>
        <?php //} 
        ?> -->

            <!--home-->
            <?php if ($system[0]->module_purchase_order == 'yes' &&  in_array('2907', $role_resources_ids)) { ?>
                <li class="sidenav-submenu" id="submenu_po">
                    <a class="" href="<?php echo $base_url; ?>hrms/admin/purchase">Purchase Order
                    </a>
                </li>
            <?php } ?>
            <!--GRN Module-->
            <?php if (in_array('8001', $role_resources_ids)) { ?>
                <li class="sidenav-submenu" id="submenu_po">
                    <a class="" href="<?php echo $base_url; ?>hrms/admin/purchase/grn_view">GRN
                    </a>
                </li>
            <?php } ?>

            <!--GRN Module-->
            <!-- Purchase Expense Module-->

            <?php if (in_array('2914', $role_resources_ids)) { ?>
                <li class="sidenav-submenu" id="submenu_po">
                    <a class="" href="<?php echo $base_url; ?>expenses"> Purchase Expense
                    </a>
                </li>
            <?php } ?>


            <!--Purchase Expense Module-->

            <!--Tools/Machinery Module-->
            <?php if (in_array('1707', $role_resources_ids)) { ?>
                <li class="sidenav-submenu" id="submenu_po">
                    <a class="" href="<?php echo $base_url; ?>hrms/admin/tools"> Tools/Machinery
                    </a>
                </li>
            <?php } ?>
            <!--Tools/Machinery Module-->



        </ul>
    </li>
<?php } ?>
<!-- End Purchase-->

<!--billing-->
<?php if (in_array('3301', $role_resources_ids) || in_array('3307', $role_resources_ids)) { ?>
    <li class="sidenav-menu-item " id="finance">
        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
            <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
            <span class="hide-menu">Finance
            </span>
        </a>
        <ul aria-expanded="false" class="collapse">
            <?php if (in_array('3301', $role_resources_ids)) { ?>
                <li class="sidenav-submenu " id="submenu_invoices">
                    <a href="<?php echo $base_url; ?>hrms/admin/payable" class=" ">Payable</a>
                </li>
            <?php } ?>


            <?php if (in_array('3307', $role_resources_ids)) { ?>
                <li class="sidenav-submenu " id="submenu_payments">
                    <a href="<?php echo $base_url; ?>hrms/admin/receivable" class=" ">Receivable</a>
                </li>
            <?php } ?>


        </ul>
    </li>
<?php } ?>
<!--billing-->



<!--Inventory Module-->
<?php if (in_array('9002', $role_resources_ids) || in_array('9007', $role_resources_ids) || in_array('1701', $role_resources_ids) || in_array('1705', $role_resources_ids)) { ?>

    <li class="sidenav-menu-item" id="Inventory">
        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
            <i class="fa fa-archive" aria-hidden="true"></i>
            <span class="hide-menu">Inventory
            </span>
        </a>

        <ul aria-expanded="false" class="collapse">
            <?php if (in_array('1705', $role_resources_ids)) { ?>
                <li class="sidenav-submenu" id="prd">
                    <a class="waves-effect waves-dark p-r-20" href="<?php echo $base_url; ?>hrms/admin/category" aria-expanded="false">Category
                    </a>
                </li>
            <?php } ?>
            <?php if (in_array('1701', $role_resources_ids)) { ?>

                <li class="sidenav-submenu" id="prd">
                    <a class="waves-effect waves-dark p-r-20" href="<?php echo $base_url; ?>hrms/admin/product" aria-expanded="false">Product
                    </a>
                </li>
            <?php } ?>

            <?php if (in_array('9002', $role_resources_ids)) { ?>

                <li class="sidenav-submenu" id="ware">
                    <a class="waves-effect waves-dark p-r-20" href="<?php echo $base_url; ?>hrms/admin/warehouse" aria-expanded="false">Warehouse
                    </a>
                </li>
            <?php } ?>
            <?php if (in_array('9007', $role_resources_ids)) { ?>
                <li class="sidenav-submenu" id="track">
                    <a class="waves-effect waves-dark p-r-20" href="<?php echo $base_url; ?>hrms/admin/inventory" aria-expanded="false">Inventory Tracking
                    </a>
                </li>
            <?php } ?>


        </ul>

    </li>
<?php } ?>

<!--Inventory Module-->


<!-- CRM Module -->
<!-- <li class="sidenav-menu-item  menu-tooltip menu-with-tooltip" title="CRM" id="CRM">
    <a class="waves-effect waves-dark p-r-20" href="<?php echo $base_url; ?>hrms/admin/crm" aria-expanded="false"
       >
       <i class="fa fa-briefcase" aria-hidden="true"></i>
        <span class="hide-menu">CRM
        </span>
    </a>
</li> -->

<?php if (in_array('1901', $role_resources_ids)) { ?>
    <li class="sidenav-menu-item  menu-tooltip menu-with-tooltip" title="CRM" id="CRM">
        <a class="waves-effect waves-dark p-r-20" href="<?php echo $base_url; ?>clients" aria-expanded="false">
            <i class="fa fa-briefcase" aria-hidden="true"></i>
            <span class="hide-menu">CRM
            </span>
        </a>
    </li>
<?php } ?>
<!-- CRM Module -->