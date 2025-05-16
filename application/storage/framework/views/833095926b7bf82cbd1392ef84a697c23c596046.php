<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<?php
$resources = auth()->user()->hrRole()->first()->getRoleResources();
$system = \App\Models\XinSystemSetting::find(1);
?>

<aside class="left-sidebar" id="js-trigger-nav-team">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" id="main-scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav" id="main-sidenav">

            <ul id="sidebarnav">
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <span class="hide-menu">HRMS</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">

                        <!--Dashboard-->
                        <li class="sidenav-menu-item menu-tooltip menu-with-tooltip" title="Home">
                            <a href=<?php echo e(url('hrms/admin/dashboard')); ?> class="waves-effect waves-dark" aria-expanded="false" target="_self">
                                <i class="ti-home"></i>
                                <span class="hide-menu1">HR Dashboard</span>
                            </a>
                        </li>
                        <!--End Dashboard-->
                        <!--Staff-->
                        <?php if(in_array('13', $resources) || in_array('88', $resources) || in_array('92', $resources) || in_array('22', $resources) || in_array('23', $resources) || in_array('422', $resources) || in_array('400', $resources) || in_array('429', $resources) || auth()->user()->hrRole()->first()->role_id == 1): ?>
                         <li class="sidenav-menu-item">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fa fa-user"></i>
                                <span class="hide-menu1">Staff</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <?php if(in_array('422', $resources)): ?>
                                <li class="">
                                    <a href=<?php echo e(url('hrms/admin/employees/staff_dashboard')); ?>>
                                        Staff Dashboard
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('13', $resources)): ?>
                                <li class="">
                                    <a href=<?php echo e(url('hrms/admin/employees')); ?>>
                                        Employees
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('429', $resources)): ?>
                                <li class="">
                                    <a href=<?php echo e(url('hrms/admin/employeebenefits')); ?>> Employee Benefits</a>
                                </li>
                                <?php endif; ?>

                                <?php if(auth()->user()->hrRole()->first()->role_id == 1): ?>
                                <li class="">
                                    <a href=<?php echo e(url('hrms/admin/roles')); ?>>
                                        Roles & Privileges
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('88', $resources)): ?>
                                <li class="">
                                    <a href=<?php echo e(url('hrms/admin/employees/hr')); ?>>
                                        Staff Directory
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('23', $resources)): ?>
                                <li class="">
                                    <a href=<?php echo e(url('hrms/admin/employee_exit')); ?>>
                                        Employees Exit
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('400', $resources)): ?>
                                <li class="">
                                    <a href=<?php echo e(url('hrms/admin/employees/expired_documents')); ?>>
                                        Expired Documents
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('22', $resources) || $reports_to > 0): ?>
                                <li class="">
                                    <a href=<?php echo e(url('hrms/admin/employees_last_login')); ?>>
                                        Employees Last login
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <!--End Staff-->
                        <!--Core HR-->
                        <?php if(in_array('12', $resources) || in_array('14', $resources) || in_array('15', $resources) || in_array('16', $resources) || in_array('17', $resources) || in_array('18', $resources) || in_array('19', $resources) || in_array('20', $resources) || in_array('21', $resources) || in_array('95', $resources) || in_array('92', $resources)): ?>
                        <li class="sidenav-menu-item">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fa fa-futbol-o"></i>
                                <span class="hide-menu1">Core HR</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <?php if($system->module_awards == 'true'): ?>
                                <?php if(in_array('14', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/awards')); ?>>
                                        Awards
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if(in_array('15', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/transfers')); ?>>
                                        Transfers
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('16', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/resignation')); ?>>
                                        Resignations
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if($system->module_travel == 'true'): ?>
                                <?php if(in_array('17', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/travel')); ?>>
                                        Travels
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if(in_array('18', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/promotion')); ?>>
                                        Promotions
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('19', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/complaints')); ?>>
                                        Complaints
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('20', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/warning')); ?>>
                                        Warnings
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('21', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/termination')); ?>>
                                        Terminations
                                    </a>
                                </li>
                                <?php endif; ?>

                                <!--HR Calendar-->
                                <?php if(in_array('95', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/calendar/hr')); ?>>
                                        HR Calendar
                                    </a>
                                </li>
                                <?php endif; ?>

                                <!--HR Imports-->
                                <?php if(in_array('92', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/import')); ?>>
                                        HR Imports
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <!--End Core HR-->
                        <!--Organization-->
                        <?php if(in_array('2', $resources) || in_array('3', $resources) || in_array('5', $resources) || in_array('6', $resources) || in_array('4', $resources) || in_array('11', $resources) || in_array('9', $resources) || in_array('96', $resources)): ?>
                        <li class="sidenav-menu-item">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fa fa-building"></i>
                                <span class="hide-menu1">Organization</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <?php if(in_array('5', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/company')); ?>>
                                        Company
                                    </a>
                                </li>

                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/company/official_documents')); ?>>
                                        Official Documents
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('6', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/location')); ?>>
                                        Location
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('3', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/department')); ?>>
                                        Department
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if($system->is_active_sub_departments == 'yes'): ?>
                                <?php if(in_array('3', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/department/sub_departments')); ?>>
                                        Sub Departments
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if(in_array('4', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/designation')); ?>>
                                        Designation
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('11', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/announcement')); ?>>
                                        Announcements
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('9', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/policy')); ?>>
                                        Company Policy
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if($system->module_orgchart == 'true'): ?>
                                <?php if(in_array('96', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/organization/chart')); ?>>
                                        Organization Chart
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <!--End Organization-->

                        <!--Timesheet-->
                        <?php if(in_array('27', $resources) || in_array('28', $resources) || in_array('29', $resources) || in_array('30', $resources) || in_array('31', $resources) || in_array('7', $resources) || in_array('8', $resources) || in_array('423', $resources) || in_array('46', $resources) || in_array('401', $resources)): ?>
                        <li class="sidenav-menu-item">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fa fa-clock-o"></i>
                                <span class="hide-menu1">Timesheet</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <?php if(in_array('423', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/timesheet/attendance_dashboard')); ?>>
                                        Timesheet Dashboard
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('28', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/timesheet/attendance')); ?>>
                                        Attendance
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('10', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/timesheet/')); ?>>
                                        Monthly Timesheet
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('261', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/timesheet/timecalendar/')); ?>>
                                        Timesheet Calendar
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('29', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/timesheet/date_wise_attendance')); ?>>
                                        Date wise Attendance
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('30', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/timesheet/update_attendance')); ?>>
                                        Update Attendance
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if($system->module_overtime_request == 'yes' && in_array('401', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/overtime_request')); ?>>
                                        Overtime Request
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('7', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/timesheet/office_shift')); ?>>
                                        Office Shifts
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('8', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/timesheet/holidays')); ?>>
                                        Manage Holidays
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('46', $resources) || $reports_to > 0): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/timesheet/leave')); ?>>
                                        Manage Leaves
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('31', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/reports/employee_leave')); ?>>
                                        Leave Status
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <!--End Timesheet-->
                        <!-- Live location -->
                        <?php if(auth()->user()->is_admin): ?>
                        <li class="sidenav-menu-item <?php echo e($page['mainmenu_livelocation'] ?? ''); ?> menu-tooltip menu-with-tooltip" title="<?php echo e(cleanLang(__('lang.live_location'))); ?>">
                            <a class="waves-effect waves-dark" href="<?php echo e(url('/map')); ?>" aria-expanded="false" target="_self">
                                <i class="ti-map"></i>
                                <span class="hide-menu1"><?php echo e(cleanLang(__('lang.live_location'))); ?>

                                </span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <!-- End Live Location -->
                        <!--Payroll-->
                        <?php if(in_array('36', $resources) || in_array('37', $resources)): ?>
                        <li class="sidenav-menu-item">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fa fa-calculator"></i>
                                <span class="hide-menu1">Payroll</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <?php if($system->module_payroll == 'yes'): ?>
                                <?php if(in_array('36', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/payroll/generate_payslip')); ?>>
                                        Payroll
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php endif; ?>

                                <?php if($system->module_accounting == 'true'): ?>
                                <?php if(in_array('37', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/payroll/payment_history')); ?>>
                                        Payslip History
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php endif; ?>

                            </ul>
                        </li>
                        <?php endif; ?>
                        <!--End Payroll-->
                        <!-- E Fillings -->
                        <?php if($system->module_payroll == 'yes' && in_array('428', $resources)): ?>
                        <li class="sidenav-menu-item">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fa fa-file"></i>
                                <span class="hide-menu1">E Filling</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/efiling/employerdetails')); ?>>
                                        E Filling Details
                                    </a>
                                </li>

                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/efiling/cpf')); ?>> CPF Submission</a>
                                </li>
                                <li class="">
                                    <a href=<?php echo e(url('hrms/admin/efiling/ir8a')); ?>> IRA8 Form</a>
                                </li>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/efiling/appendix8a')); ?>> Appendix 8A</a>
                                </li>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/efiling/appendix8b')); ?>> Appendix 8B</a>
                                </li>
                                <li class="sidenav-link">
                                    <a href="#"> IR8S</a>
                                </li>
                                <li class="">
                                    <a href=<?php echo e(url('hrms/admin/efiling/irassubmission')); ?>> IRAS Submission</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <!-- End E Fillings -->
                        <!-- Recruitment -->
                        <?php if($system->module_recruitment == 'true'): ?>
                        <?php if(in_array('48', $resources) || in_array('49', $resources) || in_array('51', $resources) || in_array('52', $resources)): ?>
                        <li class="sidenav-menu-item">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fa fa-newspaper-o"></i>
                                <span class="hide-menu1">Recruitment</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <?php if(in_array('49', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/job_post')); ?>>
                                        Job Posts
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('51', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/job_candidates')); ?>>
                                        Job Candidates
                                    </a>
                                </li>
                                <?php endif; ?>

                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/job_post/employer')); ?>>
                                        Job Employer
                                    </a>
                                </li>

                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/job_post/pages')); ?>>
                                        CMS Pages
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                        <!-- End Recruitment -->

                        <!-- Reports -->
                        <?php if(in_array('110', $resources) || in_array('111', $resources) || in_array('112', $resources) || in_array('113', $resources) || in_array('114', $resources) || in_array('115', $resources) || in_array('116', $resources) || in_array('117', $resources) || in_array('409', $resources) || in_array('83', $resources) || in_array('84', $resources) || in_array('85', $resources) || in_array('86', $resources)): ?>
                        <li class="sidenav-menu-item menu-tooltip menu-with-tooltip">
                            <a href=<?php echo e(url('hrms/admin/reports')); ?> class="waves-effect waves-dark" aria-expanded="false" target="_self">
                                <i class="fa fa-bar-chart"></i>
                                <span class="hide-menu1">HR Reports</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <!-- End Reports -->
                        <!-- Training -->
                        <?php if($system->module_training == 'true'): ?>
                        <?php if(in_array('53', $resources) || in_array('54', $resources) || in_array('55', $resources) || in_array('56', $resources)): ?>
                        <li class="sidenav-menu-item">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fa fa-graduation-cap"></i>
                                <span class="hide-menu1">Training</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <?php if(in_array('54', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/training')); ?>>
                                        Training List
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('55', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/training_type')); ?>>
                                        Training Type
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('56', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/trainers')); ?>>
                                        Trainers List
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                        <!-- End Training -->

                        <!-- Performance -->
                        <?php if($system->module_performance == 'yes'): ?>
                        <?php if(in_array('40', $resources) || in_array('41', $resources) || in_array('42', $resources) || in_array('107', $resources) || in_array('108', $resources) || in_array('372', $resources) || in_array('373', $resources)): ?>
                        <li class="sidenav-menu-item">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fa fa-cube"></i>
                                <span class="hide-menu1">Performance</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <?php if(in_array('41', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/performance_indicator')); ?>>
                                        Indicator
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('42', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/performance_appraisal')); ?>>
                                        Appraisal
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if($system->module_goal_tracking == 'true'): ?>
                                <?php if(in_array('107', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/goal_tracking')); ?>>
                                        Goal Tracking
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('108', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/goal_tracking/type')); ?>>
                                        Goal Type
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                        <!-- End Performance -->

                        <!-- Tickets -->
                        <?php if($system->module_inquiry == 'true'): ?>
                        <?php if(in_array('43', $resources)): ?>
                        <li class="sidenav-menu-item menu-tooltip menu-with-tooltip">
                            <a href=<?php echo e(url('hrms/admin/tickets')); ?> class="waves-effect waves-dark" aria-expanded="false" target="_self">
                                <i class="fa fa-ticket"></i>
                                <span class="hide-menu1">Tickets</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                        <!-- End Tickets -->

                        <!-- Files -->
                        <?php if($system->module_files == 'true'): ?>
                        <?php if(in_array('47', $resources)): ?>
                        <li class="sidenav-menu-item menu-tooltip menu-with-tooltip">
                            <a href=<?php echo e(url('hrms/admin/files')); ?> class="waves-effect waves-dark" aria-expanded="false" target="_self">
                                <i class="fa fa-file-text-o"></i>
                                <span class="hide-menu1">Files Manager</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                        <!-- End Files -->


                        <!-- Events -->
                        <?php if($system->module_events == 'true'): ?>
                        <?php if(in_array('97', $resources) || in_array('98', $resources) || in_array('99', $resources)): ?>
                        <li class="sidenav-menu-item">
                            <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                                <i class="fa fa-calendar-plus-o"></i>
                                <span class="hide-menu1">Events</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <?php if(in_array('98', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/events')); ?>>
                                        Events
                                    </a>
                                </li>
                                <?php endif; ?>

                                <?php if(in_array('99', $resources)): ?>
                                <li class="sidenav-link">
                                    <a href=<?php echo e(url('hrms/admin/meetings')); ?>>
                                        Meetings
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                        <!-- End Events -->



                    </ul>
                </li>

                <!--projects-->
                <li class="sidenav-menu-item <?php echo e($page['mainmenu_home'] ?? ''); ?> <?php echo e($page['mainmenu_projects'] ?? ''); ?> <?php echo e($page['mainmenu_tasks'] ?? ''); ?>" title="<?php echo e(cleanLang(__('lang.projects'))); ?>">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-book" aria-hidden="true"></i>
                        <span class="hide-menu">Projects
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <!--home-->
                        <li class="sidenav-menu-item <?php echo e($page['mainmenu_home'] ?? ''); ?>" title="<?php echo e(cleanLang(__('lang.home'))); ?>">
                            <a class="<?php echo e($page['mainmenu_home'] ?? ''); ?>" href=<?php echo e(url('/home')); ?>>
                                <span class="hide-menu1"><?php echo e(cleanLang(__('lang.dashboard'))); ?>

                                </span>
                            </a>
                        </li>
                        <!--home-->

                        <!--projects-->
                        <?php if(auth()->user()->role->role_projects >= 1): ?>
                        <li class="sidenav-menu-item <?php echo e($page['mainmenu_projects'] ?? ''); ?>" ">
                            <a class=" <?php echo e($page['mainmenu_projects'] ?? ''); ?>" href=<?php echo e(url('/projects')); ?>>
                            <span class="hide-menu1"><?php echo e(cleanLang(__('lang.projects'))); ?>

                            </span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <!--projects-->

                        <!--tasks-->
                        <?php if(auth()->user()->role->role_tasks >= 1): ?>
                        <li class="sidenav-menu-item <?php echo e($page['mainmenu_tasks'] ?? ''); ?>" title="<?php echo e(cleanLang(__('lang.tasks'))); ?>">
                            <a class="<?php echo e($page['mainmenu_tasks'] ?? ''); ?>" href=<?php echo e(url('/tasks')); ?>>
                                <span class="hide-menu1"><?php echo e(cleanLang(__('lang.tasks'))); ?>

                                </span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <!--tasks-->

                    </ul>
                </li>
                <!--projects-->

                <!--Start Finance -->
                <!-- Purchase -->


                <?php if($system->module_sales == 'yes' && in_array('3001', $resources) || in_array('3101', $resources)): ?>
                <li class="sidenav-menu-item quo <?php echo e($page['quo'] ?? ''); ?> <?php echo e($page['mainmenu_sales'] ?? ''); ?>">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-usd" aria-hidden="true"></i>
                        <span class="hide-menu">Sales</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <!--users-->

                        <!--customers-->
                        <?php if($system->module_quotation == 'yes' && in_array('3001', $resources)): ?>
                        
                        <li class="sidenav-link <?php echo e($page['quo'] ?? ''); ?> <?php echo e($page['mainmenu_sales'] ?? ''); ?>">
                            <a class="quo-menu <?php echo e($page['quo-menu'] ?? ''); ?> <?php echo e($page['mainmenu_sales'] ?? ''); ?>" href=<?php echo e(url('/quo')); ?>>
                            
                                Quotation
                            </a>
                        </li>
                        <?php endif; ?>



                        <?php if($system->module_invoice == 'yes' && in_array('3101', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/finance/invoice_list')); ?>>
                                Invoice
                            </a>
                        </li>
                        <!-- <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/finance/credit_list')); ?>>
                                Credit Notes
                            </a>
                        </li> -->
                        <?php endif; ?>




                    </ul>
                </li>
                <?php endif; ?>

                <!-- End Finance-->

                <!-- Purchase -->
                <?php if($system->module_purchase == 'yes' && (in_array('2801', $resources)|| in_array('2901', $resources) || in_array('2905', $resources))): ?>

                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-cart-plus" aria-hidden="true"></i>
                        <span class="hide-menu">Procurement</span>
                    </a>

                    <ul aria-expanded="false" class="collapse">
                        <?php if($system->module_supplier == 'yes' && in_array('2801', $resources)): ?>
                        <li class="sidenav-menu-item">
                            <a href=<?php echo e(url('hrms/admin/supplier')); ?>>
                                <span class="hide-menu">Suppliers</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if($system->module_purchase_requistion == 'yes' && in_array('2901', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/purchase/purchase_requistion')); ?>>
                                Material Requisition Form (MRF)
                            </a>
                        </li>
                        <?php endif; ?>



                        <?php if($system->module_purchase_order == 'yes' && in_array('2901', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/purchase')); ?>>
                                Purchase Order
                            </a>
                        </li>


                        <!--GRN Module-->
                        <li class="sidenav-submenu" id="submenu_po">
                            <a class="" href=<?php echo e(url('hrms/admin/purchase/grn_view')); ?>>GRN
                            </a>
                        </li>
                        <!--GRN Module-->

                        <!-- Purchase Expense Module-->
                        <li class="sidenav-submenu" id="submenu_po">
                            <a class="" href=<?php echo e(url('expenses')); ?> > Purchase Expense
                            </a>
                        </li>
                        <!--Purchase Expense Module-->

                        <?php endif; ?>



                    </ul>
                </li>
                <?php endif; ?>
                <!-- End Purchase -->

                <!--billing-->

                <li class="sidenav-menu-item ">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-credit-card-alt" aria-hidden="true"></i>

                        <span class="hide-menu"><?php echo e("Finance"); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">

                        <?php if(in_array('2901', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/payable')); ?>>
                                Payable
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('2901', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/receivable')); ?>>
                                Receivable
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(in_array('2901', $resources)): ?>
                        <!-- <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/claim')); ?>>
                                Claims
                            </a>
                        </li> -->
                        <?php endif; ?>
                        <!-- <?php if(in_array('2901', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/ClaimReport')); ?>>
                                GST Report
                            </a>
                        </li>
                        <?php endif; ?> -->
                    </ul>
                </li>
                <!--billing-->



                <!--leads-->
                <!-- <?php if(auth()->user()->role->role_leads >= 1): ?> -->
                <!-- <li class="sidenav-menu-item <?php echo e($page['mainmenu_leads'] ?? ''); ?> menu-tooltip menu-with-tooltip"
                    title="<?php echo e(cleanLang(__('lang.leads'))); ?>">
                    <a class="waves-effect waves-dark" href="<?php echo e(url('/leads')); ?>" aria-expanded="false" target="_self">
                        <i class="sl-icon-call-in"></i>
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.leads'))); ?>

                        </span>
                    </a>
                </li> -->
                <!-- <?php endif; ?> -->
                <!--leads-->

                <!--Inventory Module-->

                <li class="sidenav-menu-item" id="Inventory">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-archive" aria-hidden="true"></i>
                        <span class="hide-menu">Inventory
                        </span>
                    </a>

                    <ul aria-expanded="false" class="collapse">
                    <li class="sidenav-submenu" id="prd">
                            <a class="waves-effect waves-dark p-r-20" href="<?php echo e(url('hrms/admin/category')); ?>" aria-expanded="false">Category
                            </a>
                        </li>
                        <li class="sidenav-submenu" id="prd">
                            <a class="waves-effect waves-dark p-r-20" href="<?php echo e(url('hrms/admin/product')); ?>" aria-expanded="false">Product
                            </a>
                        </li>
                        <li class="sidenav-submenu" id="ware">
                            <a class="waves-effect waves-dark p-r-20" href="<?php echo e(url('hrms/admin/warehouse')); ?>" aria-expanded="false">Warehouse
                            </a>
                        </li>
                        <li class="sidenav-submenu" id="track">
                            <a class="waves-effect waves-dark p-r-20" href="<?php echo e(url('hrms/admin/inventory')); ?>" aria-expanded="false">Inventory Tracking
                            </a>
                        </li>

                    </ul>

                </li>
                                <!-- CRM Module -->

                <li class="sidenav-menu-item  menu-tooltip menu-with-tooltip" title="CRM" id="CRM">
                    <a class="waves-effect waves-dark p-r-20" href="<?php echo e(url('/clients')); ?>" aria-expanded="false">
                        <i class="fa fa-briefcase" aria-hidden="true"></i>
                        <span class="hide-menu">CRM
                        </span>
                    </a>
                </li>
                <!-- CRM Module -->

                <!--Inventory Module-->

                <!-- Assets -->
                <?php if($system->module_assets == 'true'): ?>
                <?php if(in_array('24', $resources) || in_array('25', $resources) || in_array('26', $resources)): ?>
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-flask"></i>
                        <span class="hide-menu">Assets</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <?php if(in_array('25', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/assets')); ?>>
                                Assets
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if(in_array('26', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/assets/category')); ?>>
                                Cateogry
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
                <?php endif; ?>
                <!-- End Assets -->
                <!-- Settings -->
                <?php if(in_array('57', $resources) || in_array('60', $resources) || in_array('61', $resources) || in_array('61', $resources) || in_array('62', $resources) || in_array('63', $resources) || in_array('89', $resources) || in_array('93', $resources)): ?>
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-cog"></i>
                        <span class="hide-menu">System</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <?php if($system->module_language == 'true'): ?>
                        <?php if(in_array('89', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/languages')); ?>>
                                Languages
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(in_array('60', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/settings')); ?>>
                                Settings
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if(in_array('93', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/settings/modules')); ?>>
                                Setup Modules
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if(in_array('94', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/theme')); ?>>
                                Theme Settings
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if(in_array('118', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/settings/payment_gateway')); ?>>
                                Payment Gateway
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if(in_array('61', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/settings/constants')); ?>>
                                Constants
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if(in_array('62', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/settings/database_backup')); ?>>
                                Database Backup
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if(in_array('63', $resources)): ?>
                        <li class="sidenav-link">
                            <a href=<?php echo e(url('hrms/admin/settings/email_template')); ?>>
                                Email Templates
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
                <!-- End Settings -->
                <!--[upcoming]subscriptions-->
                <li class="sidenav-menu-item <?php echo e($page['mainmenu_kb'] ?? ''); ?> menu-tooltip menu-with-tooltip hidden" title="<?php echo e(cleanLang(__('lang.subscriptions'))); ?>">
                    <a class="waves-effect waves-dark p-r-20" href="<?php echo e(url('/subscriptions')); ?>" aria-expanded="false" target="_self">
                        <i class="sl-icon-docs"></i>
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.subscriptions'))); ?>

                        </span>
                    </a>
                </li>



                <!--tickets-->
                <?php if(auth()->user()->role->role_tickets >= 1): ?>
                <li class="sidenav-menu-item <?php echo e($page['mainmenu_tickets'] ?? ''); ?> menu-tooltip menu-with-tooltip" title="<?php echo e(cleanLang(__('lang.tickets'))); ?>">
                    <a class="waves-effect waves-dark" href="<?php echo e(url('/tickets')); ?>" aria-expanded="false" target="_self">
                        <i class="ti-comments"></i>
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.support'))); ?>

                        </span>
                    </a>
                </li>
                <?php endif; ?>
                <!--tickets-->


                <!--knowledgebase-->
                <?php if(auth()->user()->role->role_knowledgebase >= 1): ?>
                <li class="sidenav-menu-item <?php echo e($page['mainmenu_kb'] ?? ''); ?> menu-tooltip menu-with-tooltip" title="<?php echo e(cleanLang(__('lang.knowledgebase'))); ?>">
                    <a class="waves-effect waves-dark p-r-20" href="<?php echo e(url('/knowledgebase')); ?>" aria-expanded="false" target="_self">
                        <i class="sl-icon-docs"></i>
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.knowledgebase'))); ?>

                        </span>
                    </a>
                </li>
                <?php endif; ?>
                <!--knowledgebase-->


                <!--team-->
                <!-- <?php if(auth()->user()->is_admin): ?> -->
                <!-- <li class="sidenav-menu-item <?php echo e($page['mainmenu_settings'] ?? ''); ?>">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="ti-archive"></i>
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.other'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="position-top collapse">
                        <li class="sidenav-submenu mainmenu_team <?php echo e($page['submenu_team'] ?? ''); ?>" id="submenu_team">
                            <a href="<?php echo e(url('/team')); ?>"
                                class="<?php echo e($page['submenu_team'] ?? ''); ?>"><?php echo e(cleanLang(__('lang.team_members'))); ?></a>
                        </li>
                        <li class="sidenav-submenu mainmenu_timesheets <?php echo e($page['submenu_timesheets'] ?? ''); ?>"
                            id="submenu_timesheets">
                            <a href="<?php echo e(url('/timesheets')); ?>"
                                class="<?php echo e($page['submenu_timesheets'] ?? ''); ?>"><?php echo e(cleanLang(__('lang.time_sheets'))); ?></a>
                        </li> -->
                <!--[UPCOMING]-->
                <!--<li class="sidenav-submenu mainmenu_reports <?php echo e($page['submenu_reports'] ?? ''); ?> hidden"
                            id="submenu_reports">
                            <a href="<?php echo e(url('/reports')); ?>"
                                class="<?php echo e($page['submenu_reports'] ?? ''); ?>"><?php echo e(cleanLang(__('lang.reports'))); ?></a>
                        </li>
                    </ul>
                </li> -->
                <!-- <?php else: ?>
                <?php if(auth()->user()->role->role_timesheets >= 1): ?> -->
                <!-- <li class="sidenav-menu-item <?php echo e($page['mainmenu_timesheets'] ?? ''); ?> menu-tooltip menu-with-tooltip"
                    title="<?php echo e(cleanLang(__('lang.time_sheets'))); ?>">
                    <a class="waves-effect waves-dark" href="<?php echo e(url('/timesheets')); ?>" aria-expanded="false" target="_self">
                        <i class="ti-timer"></i>
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.time_sheets'))); ?>

                        </span>
                    </a>
                </li> -->
                <!-- <?php endif; ?> -->
                <!-- <?php endif; ?> -->
                <!--team-->

                <!--mailchimp-->
                <!-- <?php if(auth()->user()->is_admin): ?> -->
                <!-- <li class="sidenav-menu-item  menu-tooltip menu-with-tooltip"
                    title="Mailchimp">
                    <a class="waves-effect waves-dark p-r-20" href="https://login.mailchimp.com/" aria-expanded="false"
                        target="_blank">
                        <i class="ti-email"></i>
                        <span class="hide-menu">Mailchimp
                        </span>
                    </a>
                </li> -->
                <!-- <?php endif; ?> -->
                <!--mailchimp-->

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<?php /**PATH C:\xampp\htdocs\orion-ci-laravel\application\resources\views/nav/leftmenu-team.blade.php ENDPATH**/ ?>