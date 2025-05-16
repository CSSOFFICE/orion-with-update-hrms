<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->

@php

    // Fetch resources and system settings
    $resources = auth()->user()->hrRole()->first()->getRoleResources();
    $system = \App\Models\XinSystemSetting::find(1);
@endphp

<aside class="left-sidebar" id="js-trigger-nav-team">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" id="main-scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav" id="main-sidenav">

            <ul id="sidebarnav">
                <li class="sidenav-menu-item"" id="HRMS">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <span class="hide-menu">HRMS
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <!--Dashboard-->
                        <li class="sidenav-menu-item">
                            <a href="{{ url('hrms/admin/dashboard') }}" class="waves-effect waves-dark"
                                aria-expanded="false" target="_self">
                                <i class="fa fa-home" aria-hidden="true"></i>
                                <span class="hide-menu1">HR Dashboard</span>
                            </a>
                        </li>
                        <!--End Dashboard-->

                        <!--Staff-->
                        @if (in_array('13', $resources) ||
                                in_array('88', $resources) ||
                                in_array('92', $resources) ||
                                in_array('22', $resources) ||
                                in_array('23', $resources) ||
                                in_array('422', $resources) ||
                                in_array('400', $resources) ||
                                in_array('429', $resources))

                            <li class="sidenav-menu-item"">
                                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                                    aria-expanded="false">
                                    <i class="fa fa-user"></i>
                                    <span class="hide-menu1">Staff</span>

                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    @if (in_array('422', $resources))
                                        <li class="sidenav-menu-item">
                                            <a href="{{ url('hrms/admin/employees/staff_dashboard') }}">
                                                Staff Dashboard
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('13', $resources) || $reports_to > 0)
                                        <li class="sidenav-menu-item">
                                            <a href="{{ url('hrms/admin/employees') }}">
                                                Employee Dashboard</a>
                                        </li>
                                    @endif

                                    @if (in_array('429', $resources))
                                        <li class="sidenav-menu-item">
                                            <a href="{{ url('hrms/admin/employeebenefits') }}"> Employee Benefits</a>
                                        </li>
                                    @endif


                                    <li class="sidenav-menu-item">
                                        <a href="{{ url('hrms/admin/roles') }}">
                                            User Roles
                                        </a>
                                    </li>


                                    @if (in_array('88', $resources))
                                        <li class="sidenav-menu-item">
                                            <a href="{{ url('hrms/admin/employees/hr') }}">
                                                Employee Directory
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('23', $resources))
                                        <li class="sidenav-menu-item">
                                            <a href="{{ url('hrms/admin/employee_exit') }}">
                                                Employee Exit
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('400', $resources))
                                        <li class="sidenav-menu-item">
                                            <a href="{{ url('hrms/admin/employees/expired_documents') }}">
                                                Expired Documents

                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('22', $resources))
                                        <li class="sidenav-menu-item">
                                            <a href="{{ url('hrms/admin/employees_last_login') }}">
                                                Employee Last Login
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        <!--End Staff-->

                        <!--Core HR-->
                        @if (in_array('12', $resources) ||
                                in_array('14', $resources) ||
                                in_array('15', $resources) ||
                                in_array('16', $resources) ||
                                in_array('17', $resources) ||
                                in_array('18', $resources) ||
                                in_array('19', $resources) ||
                                in_array('20', $resources) ||
                                in_array('21', $resources) ||
                                in_array('95', $resources) ||
                                in_array('92', $resources))

                            <li class="sidenav-menu-item">
                                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                                    aria-expanded="false">
                                    <i class="fa fa-futbol-o"></i>
                                    <span class="hide-menu1">HR</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">

                                    @if (in_array('14', $resources))
                                        <li class="sidenav-menu-item">
                                            <a href="{{ url('hrms/admin/awards') }}">
                                                Awards
                                            </a>
                                        </li>
                                    @endif


                                    @if (in_array('15', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/transfers') }}">
                                                Transfers
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('16', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/resignation') }}">
                                                Resignation
                                            </a>
                                        </li>
                                    @endif


                                    @if (in_array('17', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/travel') }}">
                                                Travels
                                            </a>
                                        </li>
                                    @endif


                                    @if (in_array('18', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/promotion') }}">
                                                Promotions
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('19', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/complaints') }}">
                                                Complaints
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('20', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/warning') }}">
                                                Warning
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('21', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/termination') }}">
                                                Termination
                                            </a>
                                        </li>
                                    @endif

                                    <!--HR Calendar-->
                                    @if (in_array('95', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/calendar/hr') }}">
                                                <span class="hide-menu1">HR Calender</span>
                                            </a>
                                        </li>
                                    @endif

                                    <!--HR Imports-->
                                    @if (in_array('92', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/import') }}">
                                                <span class="hide-menu1">HR Imports</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        <!--End Core HR-->

                        <!--Organization-->
                        @if (in_array('2', $resources) ||
                                in_array('3', $resources) ||
                                in_array('5', $resources) ||
                                in_array('6', $resources) ||
                                in_array('4', $resources) ||
                                in_array('11', $resources) ||
                                in_array('9', $resources) ||
                                in_array('96', $resources))
                            <li class="sidenav-menu-item"">
                                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                                    aria-expanded="false">
                                    <i class="fa fa-building"></i>
                                    <span class="hide-menu1">Organization</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    @if (in_array('5', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/company') }}">
                                                Company
                                            </a>
                                        </li>

                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/company/official_documents') }}">
                                                Official Documents
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('6', $resources))
                                        <li class="sidenav-link ">
                                            <a href="{{ url('hrms/admin/location') }}">
                                                Location
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('3', $resources))
                                        <li
                                            class="sidenav-link @if (!empty($arr_mod['dep_active'])) echo $arr_mod['dep_active']; @endif">
                                            <a href="{{ url('hrms/admin/department') }}">
                                                Department
                                            </a>
                                        </li>
                                    @endif


                                    @if (in_array('3', $resources))
                                        <li
                                            class="sidenav-link @if (!empty($arr_mod['sub_departments_active'])) echo $arr_mod['sub_departments_active']; @endif">
                                            <a href="{{ url('hrms/admin/department/sub_departments') }}">
                                                Sub Departments
                                            </a>
                                        </li>
                                    @endif


                                    @if (in_array('4', $resources))
                                        <li
                                            class="sidenav-link @if (!empty($arr_mod['des_active'])) echo $arr_mod['des_active']; @endif">
                                            <a href="{{ url('hrms/admin/designation') }}">
                                                Designation
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('11', $resources))
                                        <li
                                            class="sidenav-link @if (!empty($arr_mod['ann_active'])) echo $arr_mod['ann_active']; @endif">
                                            <a href="{{ url('hrms/admin/announcement') }}">
                                                Announcements
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('9', $resources))
                                        <li
                                            class="sidenav-link @if (!empty($arr_mod['pol_active'])) echo $arr_mod['pol_active']; @endif">
                                            <a href="{{ url('hrms/admin/policy') }}">
                                                Policies
                                            </a>
                                        </li>
                                    @endif


                                    @if (in_array('96', $resources))
                                        <li
                                            class="sidenav-link @if (!empty($arr_mod['org_chart_active'])) echo $arr_mod['org_chart_active']; @endif">
                                            <a href="{{ url('hrms/admin/organization/chart') }}">
                                                Organization Chart
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif
                        <!--End Organization-->

                        <!--Timesheet-->
                        @if (in_array('27', $resources) ||
                                in_array('28', $resources) ||
                                in_array('29', $resources) ||
                                in_array('30', $resources) ||
                                in_array('31', $resources) ||
                                in_array('7', $resources) ||
                                in_array('8', $resources) ||
                                in_array('423', $resources) ||
                                in_array('46', $resources) ||
                                in_array('401', $resources))
                            <li class="sidenav-menu-item"">
                                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                                    aria-expanded="false">
                                    <i class="fa fa-clock-o"></i>
                                    <span class="hide-menu1">Timesheet</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    @if (in_array('423', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/timesheet/attendance_dashboard') }}">
                                                Attendance Dashboard
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('28', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/timesheet/attendance') }}">
                                                Attendance
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('10', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/timesheet/') }}">
                                                Monthly Timesheet
                                            </a>

                                        </li>
                                    @endif

                                    @if (in_array('261', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/timesheet/timecalendar/') }}">
                                                Attendance Calender</a>
                                        </li>
                                    @endif

                                    @if (in_array('29', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/timesheet/date_wise_attendance') }}">
                                                Date Wise Attendance
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('30', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/timesheet/update_attendance') }}">
                                                Update Attendance
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('401', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/overtime_request') }}">
                                                Overtime
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('7', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/timesheet/office_shift') }}">
                                                Office Shift
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('8', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/timesheet/holidays') }}">
                                                Holidays
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('46', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/timesheet/leave') }}">
                                                Manage Leave
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('31', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/reports/employee_leave') }}">
                                                Leave Status
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        <!--End Timesheet-->

                        <!-- Live Location -->



                        <li class="sidenav-menu-item" menu-tooltip menu-with-tooltip">
                            <a href="{{ url('map') }}" class="waves-effect waves-dark" aria-expanded="false"
                                target="_self">
                                <i class="ti-map"></i>
                                <span class="hide-menu1">Live Location</span>
                            </a>
                        </li>

                        <!-- End Live Location -->

                        <!--Payroll-->
                        @if (in_array('36', $resources) || in_array('37', $resources))
                            <li class="sidenav-menu-item"" id="payroll">
                                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                                    aria-expanded="false">
                                    <i class="fa fa-calculator"></i>
                                    <span class="hide-menu1">Payroll</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">

                                    @if (in_array('36', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/payroll/generate_payslip') }}">
                                                <span class="hide-menu1">Payroll</span>
                                            </a>
                                        </li>
                                    @endif



                                    @if (in_array('37', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/payroll/payment_history') }}">
                                                Payslip History
                                            </a>
                                        </li>
                                    @endif


                                </ul>
                            </li>

                        @endif
                        <!--End Payroll-->

                        <!-- E Fillings -->
                        @if (in_array('428', $resources))
                            <li class="sidenav-menu-item"">
                                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                                    aria-expanded="false">
                                    <i class="fa fa-file"></i>
                                    <span class="hide-menu1">E Filling</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    <li class="sidenav-link">
                                        <a href="{{ url('hrms/admin/efiling/employerdetails') }}">
                                            E Filling Details
                                        </a>
                                    </li>

                                    <li class="sidenav-link">
                                        <a href="{{ url('hrms/admin/efiling/cpf') }}"> CPF Submission</a>
                                    </li>
                                    <li class="sidenav-link">
                                        <a href="{{ url('hrms/admin/efiling/ir8a') }}"> IRA8 Form</a>
                                    </li>
                                    <li class="sidenav-link">
                                        <a href="{{ url('hrms/admin/efiling/appendix8a') }}"> Appendix 8A</a>
                                    </li>
                                    <li class="sidenav-link">
                                        <a href="{{ url('hrms/admin/efiling/appendix8b') }}"> Appendix 8B</a>
                                    </li>
                                    <li class="">
                                        <a href="#"> IR8S</a>
                                    </li>
                                    <li class="sidenav-link">
                                        <a href="{{ url('hrms/admin/efiling/irassubmission') }}"> IRAS Submission</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <!-- End E Fillings -->
                        <!-- Recruitment -->

                        @if (in_array('48', $resources) ||
                                in_array('49', $resources) ||
                                in_array('51', $resources) ||
                                in_array('52', $resources))
                            <li class="sidenav-menu-item"">
                                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                                    aria-expanded="false">
                                    <i class="fa fa-newspaper-o"></i>
                                    <span class="hide-menu1">Recruitment</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    @if (in_array('49', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/job_post') }}">
                                                Job Post
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('51', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/Candidates') }}">
                                                Candidates
                                            </a>
                                        </li>
                                    @endif

                                    <li class="sidenav-link">
                                        <a href="{{ url('hrms/admin/job_post/employer') }}">
                                            Employeer
                                        </a>
                                    </li>

                                    <li class="sidenav-link">
                                        <a href="{{ url('hrms/admin/job_post/pages') }}">
                                            Job Pages
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <!-- End Recruitment -->
                        <!-- Reports -->
                        @if (in_array('110', $resources) ||
                                in_array('111', $resources) ||
                                in_array('112', $resources) ||
                                in_array('113', $resources) ||
                                in_array('114', $resources) ||
                                in_array('115', $resources) ||
                                in_array('116', $resources) ||
                                in_array('117', $resources) ||
                                in_array('409', $resources) ||
                                in_array('83', $resources) ||
                                in_array('84', $resources) ||
                                in_array('85', $resources) ||
                                in_array('86', $resources))
                            <li class="sidenav-menu-item" menu-tooltip menu-with-tooltip">
                                <a href="{{ url('hrms/admin/reports') }}" class="waves-effect waves-dark"
                                    aria-expanded="false" target="_self">
                                    <i class="fa fa-bar-chart"></i>
                                    <span class="hide-menu1">Report</span>
                                </a>
                            </li>
                        @endif
                        <!-- End Reports -->

                        <!-- Training -->

                        @if (in_array('53', $resources) ||
                                in_array('54', $resources) ||
                                in_array('55', $resources) ||
                                in_array('56', $resources))
                            <li class="sidenav-menu-item"">
                                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                                    aria-expanded="false">
                                    <i class="fa fa-graduation-cap"></i>
                                    <span class="hide-menu1">Training</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    @if (in_array('54', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/training') }}">
                                                Training List
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('55', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/training_type') }}">
                                                Training Type
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('56', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/trainers') }}">
                                                Trainers List
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        <!-- End Training -->

                        <!-- Performance -->

                        @if (in_array('40', $resources) ||
                                in_array('41', $resources) ||
                                in_array('42', $resources) ||
                                in_array('107', $resources) ||
                                in_array('108', $resources) ||
                                in_array('372', $resources) ||
                                in_array('373', $resources))
                            <li class="sidenav-menu-item"">
                                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                                    aria-expanded="false">
                                    <i class="fa fa-cube"></i>
                                    <span class="hide-menu1">Performance</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    @if (in_array('41', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/performance_indicator') }}">
                                                Performance Indicator
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('42', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/performance_appraisal') }}">
                                                Performance Appraisal
                                            </a>
                                        </li>
                                    @endif


                                    @if (in_array('107', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/goal_tracking') }}">
                                                Goal Tracking
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('108', $resources))
                                        <li class="sidenav-linkf">
                                            <a href="{{ url('hrms/admin/goal_tracking/type') }}">
                                                Goal Tracking Type
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif

                        <!-- End Performance -->

                        <!-- Tickets -->

                        @if (in_array('43', $resources))
                            <li class="sidenav-menu-item" menu-tooltip menu-with-tooltip">
                                <a href="{{ url('hrms/admin/tickets') }}" class="waves-effect waves-dark"
                                    aria-expanded="false" target="_self">
                                    <i class="fa fa-ticket"></i>
                                    <span class="hide-menu1">Tickets</span>
                                </a>
                            </li>
                        @endif

                        <!-- End Tickets -->

                        <!-- Files -->

                        @if (in_array('47', $resources))
                            <li class="sidenav-menu-item" menu-tooltip menu-with-tooltip">
                                <a href="{{ url('hrms/admin/files') }}" class="waves-effect waves-dark"
                                    aria-expanded="false" target="_self">
                                    <i class="fa fa-file-text-o"></i>
                                    <span class="hide-menu1">File Manager</span>
                                </a>
                            </li>
                        @endif

                        <!-- End Files -->



                        <!-- Events -->

                        @if (in_array('97', $resources) || in_array('98', $resources) || in_array('99', $resources))
                            <li class="sidenav-menu-item"">
                                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                                    aria-expanded="false">
                                    <i class="fa fa-calendar-plus-o"></i>
                                    <span class="hide-menu1">Events Meetings</span>
                                </a>
                                <ul aria-expanded="false" class="collapse">
                                    @if (in_array('98', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/events') }}">
                                                Events
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('99', $resources))
                                        <li class="sidenav-link">
                                            <a href="{{ url('hrms/admin/meetings') }}">
                                                Meetings
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        <!-- End Events -->


                    </ul>
                </li>
                <!--projects-->
                @if (in_array('1041', $resources))
                    <li class="sidenav-menu-item {{ $page['mainmenu_home'] ?? '' }} {{ $page['mainmenu_projects'] ?? '' }} {{ $page['mainmenu_tasks'] ?? '' }}"
                        title="{{ cleanLang(__('lang.projects')) }}">
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                            aria-expanded="false">
                            <i class="fa fa-book" aria-hidden="true"></i>
                            <span class="hide-menu">Projects
                            </span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            @if (in_array('2004', $resources))
                                <!--home-->
                                <li class="sidenav-menu-item {{ $page['mainmenu_home'] ?? '' }}"
                                    title="{{ cleanLang(__('lang.home')) }}">
                                    <a class="{{ $page['mainmenu_home'] ?? '' }}" href={{ url('/home') }}>
                                        <span class="hide-menu1">{{ cleanLang(__('lang.dashboard')) }}
                                        </span>
                                    </a>
                                </li>
                            @endif
                            <!--home-->
                            @if (in_array('1041', $resources))
                                <li class="sidenav-menu-item {{ $page['mainmenu_projects'] ?? '' }}" ">
                                <a class=" {{ $page['mainmenu_projects'] ?? '' }}" href={{ url('/projects') }}>
                                <span class="hide-menu1">{{ cleanLang(__('lang.projects')) }}
                                </span>
                                </a>
                            </li>
 @endif
                                    <!--tasks-->
                                    @if (in_array('45', $resources))
                                <li class="sidenav-menu-item {{ $page['mainmenu_tasks'] ?? '' }}"
                                    title="{{ cleanLang(__('lang.tasks')) }}">
                                    <a class="{{ $page['mainmenu_tasks'] ?? '' }}" href={{ url('/tasks') }}>
                                        <span class="hide-menu1">{{ cleanLang(__('lang.tasks')) }}
                                        </span>
                                    </a>
                                </li>
                            @endif
                            <!--tasks-->

                        </ul>
                    </li>
                    <!--projects-->
                @endif
                <!-- Start Finance-->

                @if (in_array('3001', $resources) || in_array('3101', $resources))
                    <li class="sidenav-menu-item quo {{ $page['quo'] ?? '' }} {{ $page['mainmenu_sales'] ?? '' }}">
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                            aria-expanded="false">
                            <i class="fa fa-usd" aria-hidden="true"></i>
                            <span class="hide-menu">Sales</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">

                            <!--customers-->

                            @if (in_array('3001', $resources))
                                <li
                                    class="sidenav-link {{ $page['quo'] ?? '' }} {{ $page['mainmenu_sales'] ?? '' }}">
                                    <a class="quo-menu {{ $page['quo-menu'] ?? '' }} {{ $page['mainmenu_sales'] ?? '' }}"
                                        href={{ url('/quo') }}>

                                        Quotation
                                    </a>
                                </li>
                            @endif


                            <!--home-->
                            @if (in_array('3101', $resources))
                                <li class="sidenav-menu-item" id="submenu_Sinvoice">
                                    <a class="" href="{{ url('hrms/admin/finance/invoice_list') }}">Invoice
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                <!-- End Purchase-->
                <!-- Start Purchase-->


                @if (in_array('2801', $resources) || in_array('2901', $resources) || in_array('8001', $resources))
                    <li class="sidenav-menu-item" " id="Procurement">
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                            aria-expanded="false">
                            <i class="fa fa-cart-plus" aria-hidden="true"></i>
                            <span class="hide-menu">Procurement
                            </span>
                        </a>
                        <ul aria-expanded="false" class="collapse">

                                @if (in_array('2801', $resources))
                    <li class="sidenav-menu-item" id="submenu_supplier">
                        <a href="{{ url('hrms/admin/supplier') }}">
                            <span class="hide-menu">Suppliers</span>
                        </a>
                    </li>
                @endif

                @if (in_array('2806', $resources))
                    <li class="sidenav-menu-item" id="submenu_subcontractors">
                        <a href="{{ url('hrms/admin/supplier/subcontractors') }}">
                            <span class="hide-menu">Sub Contractor Agreement</span>
                        </a>
                    </li>
                @endif
                <!--home-->
                @if (in_array('2901', $resources))
                    <li class="sidenav-menu-item" id="submenu_pr">
                        <a class="" href="{{ url('hrms/admin/purchase/purchase_requistion') }}">Material
                            Request
                            Form
                        </a>
                    </li>
                @endif



                <!--home-->
                @if (in_array('2907', $resources))
                    <li class="sidenav-menu-item" id="submenu_po">
                        <a class="" href="{{ url('hrms/admin/purchase') }}">Purchase Order
                        </a>
                    </li>
                @endif
                <!--GRN Module-->
                @if (in_array('8001', $resources))
                    <li class="sidenav-menu-item" id="submenu_po">
                        <a class="" href="{{ url('hrms/admin/purchase/grn_view') }}">GRN
                        </a>
                    </li>
                @endif
                <!--GRN Module-->

                <!-- Purchase Expense Module-->
                @if (in_array('2914', $resources))
                    <li class="sidenav-link  {{ $page['expenses'] ?? '' }} {{ $page['mainmenu_expenses'] ?? '' }}" id="submenu_expenses">
                        <a class="expenses-menu {{ $page['expenses-menu'] ?? '' }} {{ $page['mainmenu_expenses'] ?? '' }}" href="{{ url('expenses') }}"> Purchase Expense
                        </a>
                    </li>
                @endif
                @if (in_array('1707', $resources))
                    <li class="sidenav-menu-item" id="submenu_po">
                        <a class="" href="{{ url('hrms/admin/tools') }}"> Tools/Machinery
                        </a>
                    </li>
                @endif
                <!--Purchase Expense Module-->
            </ul>
            </li>
            @endif
            <!-- End Purchase-->

            

            <!--billing-->
            @if (in_array('3301', $resources) || in_array('3307', $resources))
                <li class="sidenav-menu-item" " id="finance">
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);"
                            aria-expanded="false">
                            <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                            <span class="hide-menu">Finance
                            </span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                                @if (in_array('3301', $resources))
                <li class="sidenav-submenu " id="submenu_invoices">
                    <a href="{{ url('hrms/admin/payable') }}">Payable</a>
                </li>
            @endif


            @if (in_array('3307', $resources))
                <li class="sidenav-submenu " id="submenu_payments">
                    <a href="{{ url('hrms/admin/receivable') }}" class=" ">Receivable</a>
                </li>
            @endif

           

            </ul>
            </li>
            @endif

            <!--billing-->



            <!--Inventory Module-->
            @if (in_array('9002', $resources) ||
                    in_array('9007', $resources) ||
                    in_array('1701', $resources) ||
                    in_array('1705', $resources))

                <li class="sidenav-menu-item"" id="Inventory">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-archive" aria-hidden="true"></i>
                        <span class="hide-menu">Inventory
                        </span>
                    </a>

                    <ul aria-expanded="false" class="collapse">
                        @if (in_array('1705', $resources))
                            <li class="sidenav-menu-item" id="prd">
                                <a class="waves-effect waves-dark p-r-20" href="{{ url('hrms/admin/category') }}"
                                    aria-expanded="false">Category
                                </a>
                            </li>
                        @endif
                        @if (in_array('1701', $resources))
                            <li class="sidenav-menu-item" id="prd">
                                <a class="waves-effect waves-dark p-r-20" href="{{ url('hrms/admin/product') }}"
                                    aria-expanded="false">Product
                                </a>
                            </li>
                        @endif

                        @if (in_array('9002', $resources))
                            <li class="sidenav-menu-item" id="ware">
                                <a class="waves-effect waves-dark p-r-20" href="{{ url('hrms/admin/warehouse') }}"
                                    aria-expanded="false">Warehouse
                                </a>
                            </li>
                        @endif
                        @if (in_array('9007', $resources))
                            <li class="sidenav-menu-item" id="track">
                                <a class="waves-effect waves-dark p-r-20" href="{{ url('hrms/admin/inventory') }}"
                                    aria-expanded="false">Inventory
                                    Tracking
                                </a>
                            </li>
                        @endif


                    </ul>

                </li>
            @endif

            <!--Inventory Module-->


            <!-- CRM Module -->
            <!-- <li class="sidenav-menu-item"  menu-tooltip menu-with-tooltip" title="CRM" id="CRM">
                    <a class="waves-effect waves-dark p-r-20" href="{{ url('hrms/admin/crm') }}" aria-expanded="false"
                       >
                       <i class="fa fa-briefcase" aria-hidden="true"></i>
                        <span class="hide-menu">CRM
                        </span>
                    </a>
                </li> -->

            @if (in_array('1901', $resources))
                <li class="sidenav-menu-item clients {{ $page['clients'] ?? '' }}">

                    <a class="waves-effect waves-dark p-r-20" href="{{ url('clients') }}" aria-expanded="false">
                        <i class="fa fa-briefcase" aria-hidden="true"></i>
                        <span class="hide-menu">CRM
                        </span>
                    </a>
                </li>
            @endif


            <!-- CRM Module -->

            <!-- Assets -->

            @if (in_array('24', $resources) || in_array('25', $resources) || in_array('26', $resources))
                <li class="sidenav-menu-item"" id="assets">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-flask"></i>
                        <span class="hide-menu">Assets</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        @if (in_array('25', $resources))
                            <li class="sidenav-link">
                                <a href="{{ url('hrms/admin/assets') }}">
                                    Assets
                                </a>
                            </li>
                        @endif

                        @if (in_array('26', $resources))
                            <li class="sidenav-link">
                                <a href="{{ url('admin/assets/category') }}">
                                    Asset Cetegory
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

            <!-- End Assets -->
<!--Report Start-->
            {{-- @if (in_array('3301', $resources) || in_array('3307', $resources)) --}}
            <li class="sidenav-menu-item" id="reports">
                <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                    <i class="fa fa-file" aria-hidden="true"></i>
                    <span class="hide-menu">Reports</span>
                </a>
                <ul aria-expanded="false" class="collapse">
                    <li class="sidenav-menu-item" id="submenu_aplist">
                        <a class="" href="{{ url('hrms/admin/ApList') }}"> Debtor/Creditor List
                        </a>
                    </li>

                </ul>
            </li>
            {{-- @endif --}}

            <!--End Report-->
            <!-- Settings -->
            @if (in_array('57', $resources) ||
                    in_array('60', $resources) ||
                    in_array('61', $resources) ||
                    in_array('61', $resources) ||
                    in_array('62', $resources) ||
                    in_array('63', $resources) ||
                    in_array('89', $resources) ||
                    in_array('93', $resources))
                <li class="sidenav-menu-item"" id="settings">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa fa-cog"></i>
                        <span class="hide-menu">System</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">

                        @if (in_array('89', $resources))
                            <li class="sidenav-link" id="submenu_multilang">
                                <a href="{{ url('hrms/admin/languages') }}">
                                    Multi Language
                                </a>
                            </li>
                        @endif


                        @if (in_array('60', $resources))
                            <li class="sidenav-link">
                                <a href="{{ url('hrms/admin/settings') }}">
                                    Settings
                                </a>
                            </li>
                        @endif

                        @if (in_array('93', $resources))
                            <li class="sidenav-link">
                                <a href="{{ url('hrms/admin/settings/modules') }}">
                                    Setup Module
                                </a>
                            </li>
                        @endif

                        @if (in_array('94', $resources))
                            <li class="sidenav-link">
                                <a href="{{ url('hrms/admin/theme') }}">
                                    Theme Settings
                                </a>
                            </li>
                        @endif

                        @if (in_array('118', $resources))
                            <li class="sidenav-link">
                                <a href="{{ url('hrms/admin/settings/payment_gateway') }}">
                                    Payment Gateway
                                </a>
                            </li>
                        @endif

                        @if (in_array('61', $resources))
                            <li class="sidenav-link">
                                <a href="{{ url('hrms/admin/settings/constants') }}">
                                    Constants
                                </a>
                            </li>
                        @endif

                        @if (in_array('62', $resources))
                            <li class="sidenav-link">
                                <a href="{{ url('hrms/admin/settings/database_backup') }}">
                                    Database Backup
                                </a>
                            </li>
                        @endif

                        @if (in_array('63', $resources))
                            <li class="sidenav-link">
                                <a href="{{ url('hrms/admin/settings/email_template') }}">
                                    Email Template
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
