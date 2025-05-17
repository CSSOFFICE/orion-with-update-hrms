<?php

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the HRSALE License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.hrsale.com/license.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to hrsalesoft@gmail.com so we can send you a copy immediately.
 *
 * @author   HRSALE
 * @author-email  hrsalesoft@gmail.com
 * @copyright  Copyright © hrsale.com. All Rights Reserved
 */


require_once(APPPATH . 'third_party/dompdf/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options; //


defined('BASEPATH') or exit('No direct script access allowed');

class Payroll extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Pdf');
		//load the model
		$this->load->model("Payroll_model");
		$this->load->model("Xin_model");
		$this->load->model("Employees_model");
		$this->load->model("Designation_model");
		$this->load->model("Department_model");
		$this->load->model("Location_model");
		$this->load->model("Timesheet_model");

		$this->load->model("Overtime_request_model");
		$this->load->model("Company_model");
		$this->load->model("Finance_model");
		$this->load->model("Cpf_options_model");
		$this->load->model("Cpf_percentage_model");
		$this->load->model("Cpf_payslip_model");
		$this->load->model("Contribution_fund_model");
		$this->load->model("PaymentDeduction_Model");

		$this->load->helper('string');
	}

	/*Function to set JSON output*/
	public function output($Return = array())
	{
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}

	// payroll templates
	public function templates()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] 			= 	$this->lang->line('left_payroll_templates') . ' | ' . $this->Xin_model->site_title();
		$data['all_companies'] 	= 	$this->Xin_model->get_companies();
		$data['breadcrumbs'] 	= 	$this->lang->line('left_payroll_templates');
		$data['path_url'] 		= 	'payroll_templates';
		$role_resources_ids 	= 	$this->Xin_model->user_role_resource();
		if (in_array('34', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/payroll/templates", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data);; //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	// generate payslips
	public function generate_payslip()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}

		$data['title'] 			= 	$this->lang->line('left_generate_payslip') . ' | ' . $this->Xin_model->site_title();
		$data['all_employees'] 	= 	$this->Xin_model->all_employees();
		$data['all_companies'] 	= 	$this->Xin_model->get_companies();
		$data['breadcrumbs'] 	= 	$this->lang->line('left_generate_payslip');
		$data['path_url'] 		= 	'generate_payslip';
		$role_resources_ids 	= 	$this->Xin_model->user_role_resource();
		if (in_array('36', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/payroll/generate_payslip", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data);; //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	// payment history
	public function payment_history()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] 			= 	$this->lang->line('xin_payslip_history');
		$data['all_employees'] 	= 	$this->Xin_model->all_employees();
		$data['breadcrumbs'] 	= 	$this->lang->line('xin_payslip_history');
		$data['path_url'] 		= 	'payment_history';
		$data['get_all_companies'] 	= 	$this->Xin_model->get_companies();
		$role_resources_ids 		= 	$this->Xin_model->user_role_resource();
		if (in_array('37', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/payroll/payment_history", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data);; //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	// payslip > employees
	public function payslip_list()
	{
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/payroll/generate_payslip", $data);
		} else {
			redirect('admin/');
		}

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		// payment month
		$pay_date = $this->input->get("month_year");

		$role_resources_ids = $this->Xin_model->user_role_resource();
		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		if ($user_info[0]->user_role_id == 1 || in_array('314', $role_resources_ids)) {
			if ($this->input->get("employee_id") == 0 && $this->input->get("company_id") == 0) {
				$payslip = $this->Employees_model->get_employees_payslip($pay_date);
			} else if ($this->input->get("employee_id") == 0 && $this->input->get("company_id") != 0) {
				$payslip = $this->Payroll_model->get_comp_template($this->input->get("company_id"), 0, $pay_date);
			} else if ($this->input->get("employee_id") != 0 && $this->input->get("company_id") != 0) {
				$payslip = $this->Payroll_model->get_employee_comp_template($this->input->get("company_id"), $this->input->get("employee_id"), $pay_date);
			} else {
				$payslip = $this->Employees_model->get_employees_payslip($pay_date);
			}
		} else {
			$payslip = $this->Payroll_model->get_employee_comp_template($user_info[0]->company_id, $session['user_id']);
		}
		
		$system = $this->Xin_model->read_setting_info(1);
		$data = array();
		foreach ($payslip->result() as $r) {
			$exit_employee = $this->Employees_model->get_employee_exit($r->user_id);

			if (count($exit_employee) > 0) {
				$e_date = date('Y-m-d', strtotime($exit_employee[0]->exit_date));
				$exit_date = date('m-Y', strtotime($e_date));
				if ($exit_date >= $pay_date) {
					$emp_name = $r->first_name . ' ' . $r->last_name;
					// office shift
					$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);
	
					//overtime request
					$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $this->input->get('month_year'));
					$re_hrs_old_int1 = 0;
					$re_hrs_old_seconds = 0;
					$re_pcount = 0;
					foreach ($overtime_count as $overtime_hr) {
						$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
						$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
						$re_interval_late = $request_clock_in->diff($request_clock_out);
						$re_hours_r  = $re_interval_late->format('%h');
						$re_minutes_r = $re_interval_late->format('%i');
						$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';
	
						$re_str_time = $re_total_time;
	
						$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);
	
						sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$re_hrs_old_int1 += $re_hrs_old_seconds;
	
						$re_pcount = gmdate("H", $re_hrs_old_int1);
					}
	
					$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
					$hrs_old_int1 = 0;
					$pcount = 0;
					foreach ($result->result() as $hour_work) {
						$clock_in =  new DateTime($hour_work->clock_in);
						$clock_out =  new DateTime($hour_work->clock_out);
						$interval_late = $clock_in->diff($clock_out);
						$hours_r  = $interval_late->format('%h');
						$minutes_r = $interval_late->format('%i');
						$total_time = $hours_r . ":" . $minutes_r . ":" . '00';
	
						$str_time = $total_time;
	
						$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
	
						sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$hrs_old_int1 += $hrs_old_seconds;
	
						$pcount = gmdate("H", $hrs_old_int1);
					}
					$pcount = $pcount + $re_pcount;
	
					// get company
					$company = $this->Xin_model->read_company_info($r->company_id);
					if (!is_null($company)) {
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';
					}
	
					/**
					 * Local Variable
					 */
					$g_ordinary_wage = 0;
					$g_additional_wage = 0;
					$g_shg = 0;
					$g_sdl = 0;
	
					// 1: salary type
					if ($r->wages_type == 1) {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					} else if ($r->wages_type == 2) {
						$wages_type = $this->lang->line('xin_employee_daily_wages');
						if ($pcount > 0) {
							$basic_salary = $pcount * $r->basic_salary;
						} else {
							$basic_salary = $pcount;
						}
						// $p_class = 'emo_hourly_pay';
						// $view_p_class = 'hourlywages_template_modal';
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					} else {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					}
	
					
					// employee deduction
					$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
					$pa_month = date('m-Y', strtotime('01-' . $pay_date));
					$deduction_amount = 0;
					if ($employee_deduction) {
						foreach ($employee_deduction as $deduction) {
							if ($deduction->type_id == 1) {
								$deduction_amount +=  $deduction->amount;
							}
							if ($deduction->type_id == 2) {
								$from_month_year = date('m-Y', strtotime($deduction->from_date));
								$to_month_year = date('d-m-Y', strtotime($deduction->to_date));
	
	
								if ($from_month_year != "" && $to_month_year != "") {
									if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
										$deduction_amount +=  $deduction->amount;
									}
								}
							}
						}
					}
	
	
	
					//3: Gross rate of pay (unpaid leave deduction)
					$holidays_count = 0;
					$no_of_working_days = 0;
					$month_start_date = new DateTime('01-' . $pay_date);
					$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
					$month_end_date->modify('+1 day');
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($month_start_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$period_day = $p->format('l');
						$period_date = $p->format('Y-m-d');
	
						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
						if ($is_holiday) {
							$holidays_count += 1;
						}
	
						//working days excluding holidays based on office shift
						if ($period_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_working_days += 1;
							}
						}
					}
	
					//unpaid leave
					$unpaid_leave_amount = 0;
					$leaves_taken_count = 0;
					$leave_period = array();
					$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
					if ($unpaid_leaves) {
						foreach ($unpaid_leaves as $k => $l) {
							$pay_date_month = new DateTime('01-' . $pay_date);
							$l_from_date = new DateTime($l->from_date);
							$l_to_date = new DateTime($l->to_date);
	
							if ($l_from_date->format('m') == $l_to_date->format('m')) {
								$start_date = $l_from_date;
								$end_date = $l_to_date;
							} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $l_from_date;
								$end_date = new DateTime($start_date->format('Y-m-t'));
							} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $pay_date_month;
								$end_date = $l_to_date;
							}
							$end_date->modify('+1 day');
							$interval = new DateInterval('P1D');
							$period = new DatePeriod($start_date, $interval, $end_date);
							foreach ($period as $d) {
								$p_day = $d->format('l');
								if ($p_day == 'Monday') {
									if ($office_shift[0]->monday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Tuesday') {
									if ($office_shift[0]->tuesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Wednesday') {
									if ($office_shift[0]->wednesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Thursday') {
									if ($office_shift[0]->thursday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Friday') {
									if ($office_shift[0]->friday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Saturday') {
									if ($office_shift[0]->saturday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Sunday') {
									if ($office_shift[0]->sunday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								}
							}
							$leave_period[$k]['is_half'] = $l->is_half_day;
						}
					}
	
					// if joining date and pay date same then this logic work
					$month_date_join = date('m-Y', strtotime($r->date_of_joining));
					$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
					$month_last_date = date('m-Y', strtotime($lastday));
	
					$first_date =  new DateTime($r->date_of_joining);
					$no_of_days_worked = 0;
					$same_month_holidays_count = 0;
					if ($month_date_join == $pay_date) {
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($first_date, $interval, $month_end_date);
						foreach ($period as $p) {
							$p_day = $p->format('l');
							$m_p_date = $p->format('Y-m-d');
	
							//holidays in a month
							$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
							if ($is_holiday) {
								$same_month_holidays_count += 1;
							}
	
							//working days excluding holidays based on office shift
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$no_of_days_worked += 1;
								}
							}
						}
					// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
					$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
					} else {
						// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
						$no_of_days_worked = $no_of_working_days -  $holidays_count;
					}
	
	
					if ($month_date_join == $pay_date){
						$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
					}else{
						$show_main_salary = $basic_salary;
					}
				
					$g_ordinary_wage += $show_main_salary;
					$g_shg += $show_main_salary;
					$g_sdl += $show_main_salary;
				
					$g_ordinary_wage -= $deduction_amount;
					$g_shg -= $deduction_amount;
					$g_sdl -= $deduction_amount;
	
	
					// 3: all loan/deductions
					$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
					$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
					$loan_de_amount = 0;
					if ($count_loan_deduction > 0) {
						foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
								} else {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
								}
							} else {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
							}
							$loan_de_amount += $er_loan;
						}
					} else {
						$loan_de_amount = 0;
					}
					$loan_de_amount = round($loan_de_amount, 2);
	
					
					// 2: all allowances
					$allowance_amount = 0;
					$gross_allowance_amount = 0;
					$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
					if ($salary_allowances) {
						foreach ($salary_allowances as $sa) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$eallowance_amount = $sa->allowance_amount / 2;
								} else {
									$eallowance_amount = $sa->allowance_amount;
								}
							} else {
								$eallowance_amount = $sa->allowance_amount;
							}
	
	
							if (!empty($sa->salary_month)) {
								$g_additional_wage += $eallowance_amount;
							} else {
								if ($month_date_join == $pay_date){
									$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
								}
								$g_ordinary_wage += $eallowance_amount;
								if ($sa->id == 2) {
									$gross_allowance_amount = $eallowance_amount;
								}
							}
	
							if ($sa->sdl == 1) {
								$g_sdl += $eallowance_amount;
							}
							if ($sa->shg == 1) {
								$g_shg += $eallowance_amount;
							}
	
							$allowance_amount += $eallowance_amount;
						}
					}
					$gross_allowance_amount = $allowance_amount;
	
	
	
					// commissions
					$commissions_amount = 0;
					$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
					if ($commissions) {
						foreach ($commissions as $c) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$ecommissions_amount = $c->commission_amount / 2;
								} else {
									$ecommissions_amount = $c->commission_amount;
								}
							} else {
								$ecommissions_amount = $c->commission_amount;
							}
	
							if ($c->commission_type == 9) {
								$g_ordinary_wage += $ecommissions_amount;
							} elseif ($c->commission_type == 10) {
								$g_additional_wage += $ecommissions_amount;
							}
	
							if ($c->sdl == 1) {
								$g_sdl += $ecommissions_amount;
							}
							if ($c->shg == 1) {
								$g_shg += $ecommissions_amount;
							}
	
							$commissions_amount += $ecommissions_amount;
						}
					}
	
					//share options
					$share_options_amount = 0;
					$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
					if ($share_options) {
						$eebr_amount = 0;
						$eris_amount = 0;
						foreach ($share_options as $s) {
							$scheme = $s->so_scheme;
							if ($scheme == 1) {
								$price_doe = $s->price_date_of_excercise;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
								$amount = ($price_doe - $price_ex) * $no_shares;
								$eebr_amount += $amount;
							} else {
								$price_doe = $s->price_date_of_excercise;
								$price_dog = $s->price_date_of_grant;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
	
								$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
								$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
								$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
							}
						}
						$share_options_amount = round($eebr_amount + $eris_amount, 2);
						$g_additional_wage += $share_options_amount;
						$g_sdl += $share_options_amount;
						$g_shg += $share_options_amount;
					}
	
					
					// otherpayments
					$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
					$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
					$other_payments_amount = 0;
					if ($count_other_payments > 0) {
						foreach ($other_payments->result() as $sl_other_payments) {
							if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
								if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$epayments_amount = $sl_other_payments->payments_amount / 2;
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}
	
									if ($sl_other_payments->cpf_applicable == 1) {
										$g_additional_wage += $epayments_amount;
										$g_shg += $epayments_amount;
										$g_sdl += $epayments_amount;
									}
	
									$other_payments_amount += $epayments_amount;
								}
							} else {
								$first_date = new DateTime($sl_other_payments->date);
								if ($first_date->format('m-Y') == $pay_date) {
									$first_date =  new DateTime($sl_other_payments->date);
								} else {
									$first_date = new DateTime('01-' . $pay_date);
								}
	
								$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));
	
								if (!empty($sl_other_payments->end_date)) {
									$last_date = new DateTime($sl_other_payments->end_date);
									if ($last_date->format('m-Y') == $pay_date) {
										$last_date = new DateTime($sl_other_payments->end_date);
									} else if($last_date->format('m-Y') >= $pay_date){
										$last_date = $month_end_date_for_other;
									} else {
										$last_date = '';
									}
								} else {
									$last_date = $month_end_date_for_other;
								}
								if(!empty($last_date)){
									$last_date->modify('+1 day');
									$final_last_day = new DateTime($last_date->format('d-m-Y'));
									if ($final_last_day->format('m-Y') >= $pay_date) {
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$epayments_amount = $sl_other_payments->payments_amount / 2;
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
	
	
										// it for no of working day
										$no_of_days_worked_for_other_payment = 0;
										$same_month_holidays_count_for_other_payment = 0;
										$interval = new DateInterval('P1D');
										$period = new DatePeriod($first_date, $interval, $last_date);
										foreach ($period as $p) {
											$p_day = $p->format('l');
											$p_date = $p->format('Y-m-d');
	
											//holidays in a month
	
											$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
											if ($is_holiday) {
												$same_month_holidays_count_for_other_payment += 1;
											}
	
											//working days excluding holidays based on office shift
											if ($p_day == 'Monday') {
												if ($office_shift[0]->monday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Tuesday') {
												if ($office_shift[0]->tuesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Wednesday') {
												if ($office_shift[0]->wednesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Thursday') {
												if ($office_shift[0]->thursday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Friday') {
												if ($office_shift[0]->friday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Saturday') {
												if ($office_shift[0]->saturday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Sunday') {
												if ($office_shift[0]->sunday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											}
										}
	
										$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
										$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);
	
	
										if ($sl_other_payments->cpf_applicable == 1) {
											$g_additional_wage += $epayments_amount;
											$g_shg += $epayments_amount;
											$g_sdl += $epayments_amount;
										}
										$other_payments_amount += $epayments_amount;
									}
								}
							}
						}
					} else {
						$other_payments_amount = 0;
					}
	
					$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
		
					if ($unpaid_leaves) {
						$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
					}
					
					// $g_ordinary_wage = $gross_pay;
					$g_shg = $gross_pay;
					$g_sdl = $gross_pay;
	
					$g_ordinary_wage -= $unpaid_leave_amount;
	
					
	
					// other benefit
					$other_benefit_mount = 0;
					$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
					foreach ($other_benefit_list->result() as $benefit_list) {
						$other_benefit_mount += $benefit_list->other_benefit_cost;
					}
	
	
					// statutory_deductions
					$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
					$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
					$statutory_deductions_amount = 0;
					if ($count_statutory_deductions > 0) {
						foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
							if ($system[0]->statutory_fixed != 'yes') {
								$sta_salary = $gross_pay;
								$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $st_amount / 2;
									} else {
										$single_sd = $st_amount;
									}
								} else {
									$single_sd = $st_amount;
								}
								$statutory_deductions_amount += $single_sd;
							} else {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
									} else {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount;
									}
								} else {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount;
								}
								$statutory_deductions_amount += $single_sd;
							}
						}
					} else {
						$statutory_deductions_amount = 0;
					}
	
					// overtime
					$overtime_amount = 0;
					$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
					if ($overtime) {
						$ot_hrs = 0;
						$ot_mins = 0;
						foreach ($overtime as $ot) {
							$total_hours = explode(':', $ot->total_hours);
							$ot_hrs += $total_hours[0];
							$ot_mins += $total_hours[1];
						}
						if ($ot_mins > 0) {
							$ot_hrs += round($ot_mins / 60, 2);
						}
	
						//overtime rate
						$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
						if ($overtime_rate) {
							$rate = $overtime_rate->overtime_pay_rate;
						} else {
							$week_hours = 44;
							$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
							$rate = $rate * 1.5;
						}
	
						if ($ot_hrs > 0) {
							$overtime_amount = round($ot_hrs * $rate, 2);
						}
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$overtime_amount = $overtime_amount / 2;
							}
						}
						$g_ordinary_wage += $overtime_amount;
						$g_sdl += $overtime_amount;
					}
	
					// make payment
					// if (true || $system[0]->is_half_monthly == 1) {
						
					// 	$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($r->user_id, $pay_date);
					// 	$payment_last = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($r->user_id, $pay_date);
					// 	if ($payment_check->num_rows() > 1) {
	
					// 		//foreach($payment_last as $payment_half_last){
					// 		$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
					// 		$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
					// 		$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
					// 		//$mpay = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_make_payment').'"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '" data-company_id="'.$this->input->get("company_id").'"><span class="fa fas fa-money"></span></button></span>';
	
					// 		$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
					// 		if (in_array('313', $role_resources_ids)) {
					// 			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
					// 		} else {
					// 			$delete = '';
					// 		}
	
					// 		$delete = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code><br>' . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $payment_last[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span><code>' . $this->lang->line('xin_title_second_half') . '</code>';
					// 		//}
					// 		//detail link
					// 		$detail = '';
					// 	} else if ($payment_check->num_rows() > 0) {
	
					// 		$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
					// 		$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
					// 		$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
	
					// 		$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
	
					// 		$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
					// 		if (in_array('313', $role_resources_ids)) {
					// 			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
					// 		} else {
					// 			$delete = '';
					// 		}
					// 		$delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
					// 		$detail = '';
					// 	} else {
	
					// 		$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
					// 		$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
					// 		$delete = '';
					// 		//detail link
					// 		$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					// 	}
					// 	//detail link
					// 	//$detail = '';
					// } else {
					// 	$payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
					// 	if ($payment_check->num_rows() > 0) {
					// 		$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
					// 		$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
					// 		if ($make_payment[0]->status == 1) {
					// 			$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
					// 		} else if ($make_payment[0]->status == 2) {
					// 			$status = '<span class="label label-warning">' . "Partially" . '</span>';
					// 		}
					// 		$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
					// 		if (in_array('313', $role_resources_ids)) {
					// 			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
					// 		} else {
					// 			$delete = '';
					// 		}
					// 	} else {
	
					// 		$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
					// 		$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '">
					// 			<button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '">
					// 				<span class="fa fas fa-money"></span>
					// 			</button>
					// 		</span>';
					// 		$delete = '';
					// 	}
					// 	//detail link
					// 	$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					// }
	
					$payment_check = $this->Payroll_model->check_make_payment_payslip_for_first_payment($r->user_id, $pay_date);
					$payment_check_final = $this->Payroll_model->check_make_payment_payslip_for_final_payment($r->user_id, $pay_date);
					$balance = 0;
					$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
					if($check && $check[0]->balance_amount > 0){
						$balance = $check[0]->balance_amount;
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
					
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
						$status = '<span class="label label-warning">' . "Partially Paid" . '</span>';
	
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
						$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
						
						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
						// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
						$delete  = $delete;
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						
					}else if($check && $check[0]->is_advance != 1  && $check[0]->balance_amount == 0){
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
					
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
						$status = '<span class="label label-success">Paid</span>';
	
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
						
						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
						// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
						$delete  = $delete;
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						
					}else {
							$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
							$delete = '';
							$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
							$balance = $this->Xin_model->currency_sign(0, $r->user_id);
						}
	
					
					// employee accommodations
					$employee_accommodations = 0;
					$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
					foreach ($get_employee_accommodations as $get_employee_accommodation) {
						$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
						$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
						if ($period_from == $pay_date || $period_to == $pay_date) {
							if (!empty($get_employee_accommodation->rent_paid)) {
								$employee_accommodations += $get_employee_accommodation->rent_paid;
							}
						}
					}
	
					// employee claims
					$claim_amount = 0;
					$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
					foreach ($get_employee_claims->result() as $claims) {
						$date 	= 	date('m-Y', strtotime($claims->date));
						if ($date == $pay_date) {
							$claim_amount += $claims->amount;
						}
					}
	
	
	
					$total_earning = $show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
					$total_deduction = $loan_de_amount + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
					$total_net_salary = ($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;
	
	
					// cpf calculation
					$emp_dob = $r->date_of_birth;
					$dob = new DateTime($emp_dob);
	
					$today = new DateTime('01-' . $pay_date);
					$age = $dob->diff($today);
					$age_year = $age->y;
					$age_month = $age->m;
	
					$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
					$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
					$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;
	
					if ($age_month > 0) {
						$age_year = $age_year + 1;
					}
	
					$cpf_employee 	= 	0;
					$cpf_employer	=	0;
					$total_cpf		=	0;
					$cpf_total		=	0;
					$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
					if ($im_status) {
						$immigration_id = $im_status->immigration_id;
						if ($immigration_id == 2) {
							$issue_date = $im_status->issue_date;
							$i_date = new DateTime($issue_date);
							$today = new DateTime();
							$pr_age = $i_date->diff($today);
							$pr_age_year = $pr_age->y;
							$pr_age_month = $pr_age->m;
						}
	
						if ($immigration_id == 1 || $immigration_id == 2) {
	
							$ordinary_wage = $g_ordinary_wage;
							if ($ordinary_wage > $ordinary_wage_cap) {
								$ow = $ordinary_wage_cap;
							} else {
								$ow = $ordinary_wage;
							}
	
							//additional wage
							$additional_wage = $g_additional_wage;
							$aw = $g_additional_wage;
							$tw = $ow + $additional_wage;
							if ($im_status->issue_date != "") {
								if ($pr_age_year == 1) {
	
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(4 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 666) {
												$total_cpf = 666;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(4 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 666) {
												$total_cpf = 666;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(3.5 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(3.5 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
								if ($pr_age_year == 2) {
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(9 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.45 * ($tw - 500));
											$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
											$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
											if ($count_total_cpf > 1776) {
												$total_cpf = 1776;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1110) {
												$cpf_employee = 1110;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(6 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.375 * ($tw - 500));
											$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
											$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
											if ($count_total_cpf > 1369) {
												$total_cpf = 1369;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 925) {
												$cpf_employee = 925;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(3.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.225 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
											$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
											if ($count_total_cpf > 814) {
												$total_cpf = 814;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 555) {
												$cpf_employee = 555;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(3.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
											$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
								if ($pr_age_year == 3 || $pr_age_year > 3) {
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(17 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.6 * ($tw - 500));
											$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
											$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
											if ($count_total_cpf > 2738) {
												$total_cpf = 2738;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1480) {
												$cpf_employee = 1480;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = $total_cpf - $cpf_employee;
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(15.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.51 * ($tw - 500));
											$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
											$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
											if ($count_total_cpf > 2405) {
												$total_cpf = 2405;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1258) {
												$cpf_employee = 1258;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(12 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.345 * ($tw - 500));
											$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
											$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
	
											if ($count_total_cpf > 1739) {
												$total_cpf = 1739;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 851) {
												$cpf_employee = 851;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65 && $age_year <= 70) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(9 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.225 * ($tw - 500));
											$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
											$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
	
											if ($count_total_cpf > 1221) {
												$total_cpf = 1221;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 555) {
												$cpf_employee = 555;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 70) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(7.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
											$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
	
											if ($count_total_cpf > 925) {
												$total_cpf = 925;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
							}
	
	
	
							if ($immigration_id == 1) {
	
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(17 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.6 * ($tw - 500));
										$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
										$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
										if ($count_total_cpf > 2738) {
											$total_cpf = 2738;
										} else {
											$total_cpf = $count_total_cpf;
										}
				
										
										if ($count_cpf_employee > 1480) {
											$cpf_employee = 1480;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(15.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.51 * ($tw - 500));
										$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
				
										$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
										if ($count_total_cpf > 2405) {
											$total_cpf = 2405;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1258) {
											$cpf_employee = 1258;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(12 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.345 * ($tw - 500));
										$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
										$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
				
										if ($count_total_cpf > 1739) {
											$total_cpf = 1739;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 851) {
											$cpf_employee = 851;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65 && $age_year <= 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
				
										if ($count_total_cpf > 1221) {
											$total_cpf = 1221;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(7.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
				
										if ($count_total_cpf > 925) {
											$total_cpf = 925;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
	
							$total_net_salary = $total_net_salary - $cpf_employee;
							$cpf_total = $cpf_employee + $cpf_employer;
	
							$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
							$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
							$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
						}
					}
	
	
					$shg_fund_deduction_amount = 0;
					//Other Fund Contributions
					$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);
	
					if ($employee_contributions && $g_shg > 0) {
						$gross_s = $g_shg;
						$contribution_id = $employee_contributions->contribution_id;
						$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
						$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
						$shg_fund_name = $contribution_type[0]->contribution;
						$shg_fund_deduction_amount += $contribution_amount;
						$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
					}
					$ashg_fund_deduction_amount = 0;
	
					$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
					if ($employee_ashg_contributions  && $g_shg > 0) {
						$gross_s = $g_shg;
						$contribution_id = $employee_ashg_contributions->contribution_id;
						$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
						$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
						$ashg_fund_name = $contribution_type[0]->contribution;
	
						$ashg_fund_deduction_amount += $contribution_amount;
						$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
					}
	
					// echo $total_net_salary;
	
					// $sdl_total_amount = 0;
					// if ($g_sdl > 1 && $g_sdl <= 800) {
					// 	$sdl_total_amount = 2;
					// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
					// 	$sdl_amount = (0.25 * $g_sdl) / 100;
					// 	$sdl_total_amount = $sdl_amount;
					// } elseif ($g_sdl > 4500) {
					// 	$sdl_total_amount = 11.25;
					// }
	
	
					$net_salary = number_format((float)$total_net_salary, 2, '.', '');
					$basic_salary = number_format((float)$basic_salary, 2, '.', '');
					// if ($basic_salary == 0 || $basic_salary == '') {
					// 	$fmpay = '';
					// } else {
						$fmpay = $mpay;
					// }
	
	
					$company_info = $this->Company_model->read_company_information($r->company_id);
					if (!is_null($company_info)) {
						$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
						$net_salary = $this->Xin_model->company_currency_sign($net_salary, $r->company_id);
						//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
					} else {
						//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
						$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary, $r->user_id);
	
						$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary, $r->user_id);
	
						//$net_salary = $this->Xin_model->currency_sign($net_salary);	
						//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
						$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount, $r->user_id);
					}
	
					$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';
	
					//action link
					$act = $detail . $fmpay . $delete;
					// $act = $fmpay . $delete;
	
					if ($r->wages_type == 1) {
						if ($system[0]->is_half_monthly == 1) {
							$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
						} else {
							$emp_payroll_wage = $wages_type;
						}
					} else {
						$emp_payroll_wage = $wages_type;
					}
	
					$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
					$p = $payslips->result();
					
					$mode_of_payment = $check[0]->payment_mode ?? '';
	
	
					$data[] = array(
						$act,
						$iemp_name,
						$emp_payroll_wage,
						// $basic_salary,
						$this->Xin_model->currency_sign($show_main_salary, $r->user_id),
						$this->Xin_model->currency_sign($cpf_employee, $r->user_id),
						$net_salary,
						$balance,
						$mode_of_payment,
						$status
					);
				}
			} else {
				$emp_name = $r->first_name . ' ' . $r->last_name;
				// office shift
				$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);

				//overtime request
				$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $this->input->get('month_year'));
				$re_hrs_old_int1 = 0;
				$re_hrs_old_seconds = 0;
				$re_pcount = 0;
				foreach ($overtime_count as $overtime_hr) {
					$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
					$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
					$re_interval_late = $request_clock_in->diff($request_clock_out);
					$re_hours_r  = $re_interval_late->format('%h');
					$re_minutes_r = $re_interval_late->format('%i');
					$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';

					$re_str_time = $re_total_time;

					$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);

					sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$re_hrs_old_int1 += $re_hrs_old_seconds;

					$re_pcount = gmdate("H", $re_hrs_old_int1);
				}

				$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
				$hrs_old_int1 = 0;
				$pcount = 0;
				foreach ($result->result() as $hour_work) {
					$clock_in =  new DateTime($hour_work->clock_in);
					$clock_out =  new DateTime($hour_work->clock_out);
					$interval_late = $clock_in->diff($clock_out);
					$hours_r  = $interval_late->format('%h');
					$minutes_r = $interval_late->format('%i');
					$total_time = $hours_r . ":" . $minutes_r . ":" . '00';

					$str_time = $total_time;

					$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

					sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$hrs_old_int1 += $hrs_old_seconds;

					$pcount = gmdate("H", $hrs_old_int1);
				}
				$pcount = $pcount + $re_pcount;

				// get company
				$company = $this->Xin_model->read_company_info($r->company_id);
				if (!is_null($company)) {
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';
				}

				/**
				 * Local Variable
				 */
				$g_ordinary_wage = 0;
				$g_additional_wage = 0;
				$g_shg = 0;
				$g_sdl = 0;

				// 1: salary type
				if ($r->wages_type == 1) {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				} else if ($r->wages_type == 2) {
					$wages_type = $this->lang->line('xin_employee_daily_wages');
					if ($pcount > 0) {
						$basic_salary = $pcount * $r->basic_salary;
					} else {
						$basic_salary = $pcount;
					}
					// $p_class = 'emo_hourly_pay';
					// $view_p_class = 'hourlywages_template_modal';
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				} else {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				}

				
				// employee deduction
				$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
				$pa_month = date('m-Y', strtotime('01-' . $pay_date));
				$deduction_amount = 0;
				if ($employee_deduction) {
					foreach ($employee_deduction as $deduction) {
						if ($deduction->type_id == 1) {
							$deduction_amount +=  $deduction->amount;
						}
						if ($deduction->type_id == 2) {
							$from_month_year = date('m-Y', strtotime($deduction->from_date));
							$to_month_year = date('d-m-Y', strtotime($deduction->to_date));


							if ($from_month_year != "" && $to_month_year != "") {
								if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
									$deduction_amount +=  $deduction->amount;
								}
							}
						}
					}
				}



				//3: Gross rate of pay (unpaid leave deduction)
				$holidays_count = 0;
				$no_of_working_days = 0;
				$month_start_date = new DateTime('01-' . $pay_date);
				$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
				$month_end_date->modify('+1 day');
				$interval = new DateInterval('P1D');
				$period = new DatePeriod($month_start_date, $interval, $month_end_date);
				foreach ($period as $p) {
					$period_day = $p->format('l');
					$period_date = $p->format('Y-m-d');

					//holidays in a month
					$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
					if ($is_holiday) {
						$holidays_count += 1;
					}

					//working days excluding holidays based on office shift
					if ($period_day == 'Monday') {
						if ($office_shift[0]->monday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Tuesday') {
						if ($office_shift[0]->tuesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Wednesday') {
						if ($office_shift[0]->wednesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Thursday') {
						if ($office_shift[0]->thursday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Friday') {
						if ($office_shift[0]->friday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Saturday') {
						if ($office_shift[0]->saturday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Sunday') {
						if ($office_shift[0]->sunday_in_time != '') {
							$no_of_working_days += 1;
						}
					}
				}

				//unpaid leave
				$unpaid_leave_amount = 0;
				$leaves_taken_count = 0;
				$leave_period = array();
				$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
				if ($unpaid_leaves) {
					foreach ($unpaid_leaves as $k => $l) {
						$pay_date_month = new DateTime('01-' . $pay_date);
						$l_from_date = new DateTime($l->from_date);
						$l_to_date = new DateTime($l->to_date);

						if ($l_from_date->format('m') == $l_to_date->format('m')) {
							$start_date = $l_from_date;
							$end_date = $l_to_date;
						} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
							$start_date = $l_from_date;
							$end_date = new DateTime($start_date->format('Y-m-t'));
						} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
							$start_date = $pay_date_month;
							$end_date = $l_to_date;
						}
						$end_date->modify('+1 day');
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($start_date, $interval, $end_date);
						foreach ($period as $d) {
							$p_day = $d->format('l');
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							}
						}
						$leave_period[$k]['is_half'] = $l->is_half_day;
					}
				}

				// if joining date and pay date same then this logic work
				$month_date_join = date('m-Y', strtotime($r->date_of_joining));
				$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
				$month_last_date = date('m-Y', strtotime($lastday));

				$first_date =  new DateTime($r->date_of_joining);
				$no_of_days_worked = 0;
				$same_month_holidays_count = 0;
				if ($month_date_join == $pay_date) {
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($first_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$p_day = $p->format('l');
						$m_p_date = $p->format('Y-m-d');

						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
						if ($is_holiday) {
							$same_month_holidays_count += 1;
						}

						//working days excluding holidays based on office shift
						if ($p_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_days_worked += 1;
							}
						}
					}
				// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
				$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
				} else {
					// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
					$no_of_days_worked = $no_of_working_days -  $holidays_count;
				}


				if ($month_date_join == $pay_date){
					$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
				}else{
					$show_main_salary = $basic_salary;
				}
			
				$g_ordinary_wage += $show_main_salary;
				$g_shg += $show_main_salary;
				$g_sdl += $show_main_salary;
			
				$g_ordinary_wage -= $deduction_amount;
				$g_shg -= $deduction_amount;
				$g_sdl -= $deduction_amount;


				// 3: all loan/deductions
				$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
				$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
				$loan_de_amount = 0;
				if ($count_loan_deduction > 0) {
					foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
							} else {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
							}
						} else {
							$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
						}
						$loan_de_amount += $er_loan;
					}
				} else {
					$loan_de_amount = 0;
				}
				$loan_de_amount = round($loan_de_amount, 2);

				
				// 2: all allowances
				$allowance_amount = 0;
				$gross_allowance_amount = 0;
				$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
				if ($salary_allowances) {
					foreach ($salary_allowances as $sa) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$eallowance_amount = $sa->allowance_amount / 2;
							} else {
								$eallowance_amount = $sa->allowance_amount;
							}
						} else {
							$eallowance_amount = $sa->allowance_amount;
						}


						if (!empty($sa->salary_month)) {
							$g_additional_wage += $eallowance_amount;
						} else {
							if ($month_date_join == $pay_date){
								$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
							}
							$g_ordinary_wage += $eallowance_amount;
							if ($sa->id == 2) {
								$gross_allowance_amount = $eallowance_amount;
							}
						}

						if ($sa->sdl == 1) {
							$g_sdl += $eallowance_amount;
						}
						if ($sa->shg == 1) {
							$g_shg += $eallowance_amount;
						}

						$allowance_amount += $eallowance_amount;
					}
				}
				$gross_allowance_amount = $allowance_amount;



				// commissions
				$commissions_amount = 0;
				$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
				if ($commissions) {
					foreach ($commissions as $c) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$ecommissions_amount = $c->commission_amount / 2;
							} else {
								$ecommissions_amount = $c->commission_amount;
							}
						} else {
							$ecommissions_amount = $c->commission_amount;
						}

						if ($c->commission_type == 9) {
							$g_ordinary_wage += $ecommissions_amount;
						} elseif ($c->commission_type == 10) {
							$g_additional_wage += $ecommissions_amount;
						}

						if ($c->sdl == 1) {
							$g_sdl += $ecommissions_amount;
						}
						if ($c->shg == 1) {
							$g_shg += $ecommissions_amount;
						}

						$commissions_amount += $ecommissions_amount;
					}
				}

				//share options
				$share_options_amount = 0;
				$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
				if ($share_options) {
					$eebr_amount = 0;
					$eris_amount = 0;
					foreach ($share_options as $s) {
						$scheme = $s->so_scheme;
						if ($scheme == 1) {
							$price_doe = $s->price_date_of_excercise;
							$price_ex = $s->excercise_price;
							$no_shares = $s->no_of_shares;
							$amount = ($price_doe - $price_ex) * $no_shares;
							$eebr_amount += $amount;
						} else {
							$price_doe = $s->price_date_of_excercise;
							$price_dog = $s->price_date_of_grant;
							$price_ex = $s->excercise_price;
							$no_shares = $s->no_of_shares;

							$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
							$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
							$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
						}
					}
					$share_options_amount = round($eebr_amount + $eris_amount, 2);
					$g_additional_wage += $share_options_amount;
					$g_sdl += $share_options_amount;
					$g_shg += $share_options_amount;
				}

				
				// otherpayments
				$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
				$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
				$other_payments_amount = 0;
				if ($count_other_payments > 0) {
					foreach ($other_payments->result() as $sl_other_payments) {
						if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
							if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$epayments_amount = $sl_other_payments->payments_amount / 2;
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}
								} else {
									$epayments_amount = $sl_other_payments->payments_amount;
								}

								if ($sl_other_payments->cpf_applicable == 1) {
									$g_additional_wage += $epayments_amount;
									$g_shg += $epayments_amount;
									$g_sdl += $epayments_amount;
								}

								$other_payments_amount += $epayments_amount;
							}
						} else {
							$first_date = new DateTime($sl_other_payments->date);
							if ($first_date->format('m-Y') == $pay_date) {
								$first_date =  new DateTime($sl_other_payments->date);
							} else {
								$first_date = new DateTime('01-' . $pay_date);
							}

							$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));

							if (!empty($sl_other_payments->end_date)) {
								$last_date = new DateTime($sl_other_payments->end_date);
								if ($last_date->format('m-Y') == $pay_date) {
									$last_date = new DateTime($sl_other_payments->end_date);
								} else if($last_date->format('m-Y') >= $pay_date){
									$last_date = $month_end_date_for_other;
								} else {
									$last_date = '';
								}
							} else {
								$last_date = $month_end_date_for_other;
							}
							if(!empty($last_date)){
								$last_date->modify('+1 day');
								$final_last_day = new DateTime($last_date->format('d-m-Y'));
								if ($final_last_day->format('m-Y') >= $pay_date) {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$epayments_amount = $sl_other_payments->payments_amount / 2;
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}


									// it for no of working day
									$no_of_days_worked_for_other_payment = 0;
									$same_month_holidays_count_for_other_payment = 0;
									$interval = new DateInterval('P1D');
									$period = new DatePeriod($first_date, $interval, $last_date);
									foreach ($period as $p) {
										$p_day = $p->format('l');
										$p_date = $p->format('Y-m-d');

										//holidays in a month

										$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
										if ($is_holiday) {
											$same_month_holidays_count_for_other_payment += 1;
										}

										//working days excluding holidays based on office shift
										if ($p_day == 'Monday') {
											if ($office_shift[0]->monday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Tuesday') {
											if ($office_shift[0]->tuesday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Wednesday') {
											if ($office_shift[0]->wednesday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Thursday') {
											if ($office_shift[0]->thursday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Friday') {
											if ($office_shift[0]->friday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Saturday') {
											if ($office_shift[0]->saturday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Sunday') {
											if ($office_shift[0]->sunday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										}
									}

									$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
									$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);


									if ($sl_other_payments->cpf_applicable == 1) {
										$g_additional_wage += $epayments_amount;
										$g_shg += $epayments_amount;
										$g_sdl += $epayments_amount;
									}
									$other_payments_amount += $epayments_amount;
								}
							}
						}
					}
				} else {
					$other_payments_amount = 0;
				}

				$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
	
				if ($unpaid_leaves) {
					$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
				}
				
				// $g_ordinary_wage = $gross_pay;
				$g_shg = $gross_pay;
				$g_sdl = $gross_pay;

				$g_ordinary_wage -= $unpaid_leave_amount;

				

				// other benefit
				$other_benefit_mount = 0;
				$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
				foreach ($other_benefit_list->result() as $benefit_list) {
					$other_benefit_mount += $benefit_list->other_benefit_cost;
				}


				// statutory_deductions
				$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
				$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
				$statutory_deductions_amount = 0;
				if ($count_statutory_deductions > 0) {
					foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
						if ($system[0]->statutory_fixed != 'yes') {
							$sta_salary = $gross_pay;
							$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$single_sd = $st_amount / 2;
								} else {
									$single_sd = $st_amount;
								}
							} else {
								$single_sd = $st_amount;
							}
							$statutory_deductions_amount += $single_sd;
						} else {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
								} else {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount;
								}
							} else {
								$single_sd = $sl_salary_statutory_deductions->deduction_amount;
							}
							$statutory_deductions_amount += $single_sd;
						}
					}
				} else {
					$statutory_deductions_amount = 0;
				}

				// overtime
				$overtime_amount = 0;
				$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
				if ($overtime) {
					$ot_hrs = 0;
					$ot_mins = 0;
					foreach ($overtime as $ot) {
						$total_hours = explode(':', $ot->total_hours);
						$ot_hrs += $total_hours[0];
						$ot_mins += $total_hours[1];
					}
					if ($ot_mins > 0) {
						$ot_hrs += round($ot_mins / 60, 2);
					}

					//overtime rate
					$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
					if ($overtime_rate) {
						$rate = $overtime_rate->overtime_pay_rate;
					} else {
						$week_hours = 44;
						$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
						$rate = $rate * 1.5;
					}

					if ($ot_hrs > 0) {
						$overtime_amount = round($ot_hrs * $rate, 2);
					}
					if ($system[0]->is_half_monthly == 1) {
						if ($system[0]->half_deduct_month == 2) {
							$overtime_amount = $overtime_amount / 2;
						}
					}
					$g_ordinary_wage += $overtime_amount;
					$g_sdl += $overtime_amount;
				}

				// make payment
				// if (true || $system[0]->is_half_monthly == 1) {
					
				// 	$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($r->user_id, $pay_date);
				// 	$payment_last = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($r->user_id, $pay_date);
				// 	if ($payment_check->num_rows() > 1) {

				// 		//foreach($payment_last as $payment_half_last){
				// 		$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
				// 		$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

				// 		$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
				// 		//$mpay = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_make_payment').'"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '" data-company_id="'.$this->input->get("company_id").'"><span class="fa fas fa-money"></span></button></span>';

				// 		$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

				// 		if (in_array('313', $role_resources_ids)) {
				// 			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
				// 		} else {
				// 			$delete = '';
				// 		}

				// 		$delete = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code><br>' . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $payment_last[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span><code>' . $this->lang->line('xin_title_second_half') . '</code>';
				// 		//}
				// 		//detail link
				// 		$detail = '';
				// 	} else if ($payment_check->num_rows() > 0) {

				// 		$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
				// 		$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

				// 		$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

				// 		$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';

				// 		$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

				// 		if (in_array('313', $role_resources_ids)) {
				// 			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
				// 		} else {
				// 			$delete = '';
				// 		}
				// 		$delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
				// 		$detail = '';
				// 	} else {

				// 		$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
				// 		$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
				// 		$delete = '';
				// 		//detail link
				// 		$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
				// 	}
				// 	//detail link
				// 	//$detail = '';
				// } else {
				// 	$payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
				// 	if ($payment_check->num_rows() > 0) {
				// 		$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
				// 		$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

				// 		if ($make_payment[0]->status == 1) {
				// 			$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
				// 		} else if ($make_payment[0]->status == 2) {
				// 			$status = '<span class="label label-warning">' . "Partially" . '</span>';
				// 		}
				// 		$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

				// 		if (in_array('313', $role_resources_ids)) {
				// 			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
				// 		} else {
				// 			$delete = '';
				// 		}
				// 	} else {

				// 		$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
				// 		$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '">
				// 			<button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '">
				// 				<span class="fa fas fa-money"></span>
				// 			</button>
				// 		</span>';
				// 		$delete = '';
				// 	}
				// 	//detail link
				// 	$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
				// }

				$payment_check = $this->Payroll_model->check_make_payment_payslip_for_first_payment($r->user_id, $pay_date);
				$payment_check_final = $this->Payroll_model->check_make_payment_payslip_for_final_payment($r->user_id, $pay_date);
				$balance = 0;
				$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
				if($check && $check[0]->balance_amount > 0){
					$balance = $check[0]->balance_amount;
					$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
				
					$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

					$status = '<span class="label label-warning">' . "Partially Paid" . '</span>';

					$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
					$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
					
					if (in_array('313', $role_resources_ids)) {
						$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
					} else {
						$delete = '';
					}
					// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
					$delete  = $delete;
					$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					
				}else if($check && $check[0]->is_advance != 1  && $check[0]->balance_amount == 0){
					$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
				
					$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

					$status = '<span class="label label-success">Paid</span>';

					$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
					
					if (in_array('313', $role_resources_ids)) {
						$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
					} else {
						$delete = '';
					}
					// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
					$delete  = $delete;
					$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					
				}else {
						$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
						$delete = '';
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						$balance = $this->Xin_model->currency_sign(0, $r->user_id);
					}

				
				// employee accommodations
				$employee_accommodations = 0;
				$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
				foreach ($get_employee_accommodations as $get_employee_accommodation) {
					$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
					$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
					if ($period_from == $pay_date || $period_to == $pay_date) {
						if (!empty($get_employee_accommodation->rent_paid)) {
							$employee_accommodations += $get_employee_accommodation->rent_paid;
						}
					}
				}

				// employee claims
				$claim_amount = 0;
				$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
				foreach ($get_employee_claims->result() as $claims) {
					$date 	= 	date('m-Y', strtotime($claims->date));
					if ($date == $pay_date) {
						$claim_amount += $claims->amount;
					}
				}



				$total_earning = $show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
				$total_deduction = $loan_de_amount + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
				$total_net_salary = ($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;


				// cpf calculation
				$emp_dob = $r->date_of_birth;
				$dob = new DateTime($emp_dob);

				$today = new DateTime('01-' . $pay_date);
				$age = $dob->diff($today);
				$age_year = $age->y;
				$age_month = $age->m;

				$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
				$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
				$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;

				if ($age_month > 0) {
					$age_year = $age_year + 1;
				}

				$cpf_employee 	= 	0;
				$cpf_employer	=	0;
				$total_cpf		=	0;
				$cpf_total		=	0;
				$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
				if ($im_status) {
					$immigration_id = $im_status->immigration_id;
					if ($immigration_id == 2) {
						$issue_date = $im_status->issue_date;
						$i_date = new DateTime($issue_date);
						$today = new DateTime();
						$pr_age = $i_date->diff($today);
						$pr_age_year = $pr_age->y;
						$pr_age_month = $pr_age->m;
					}

					if ($immigration_id == 1 || $immigration_id == 2) {

						$ordinary_wage = $g_ordinary_wage;
						if ($ordinary_wage > $ordinary_wage_cap) {
							$ow = $ordinary_wage_cap;
						} else {
							$ow = $ordinary_wage;
						}

						//additional wage
						$additional_wage = $g_additional_wage;
						$aw = $g_additional_wage;
						$tw = $ow + $additional_wage;
						if ($im_status->issue_date != "") {
							if ($pr_age_year == 1) {

								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(4 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 666) {
											$total_cpf = 666;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(4 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 666) {
											$total_cpf = 666;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(3.5 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(3.5 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
							if ($pr_age_year == 2) {
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.45 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
										$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
										if ($count_total_cpf > 1776) {
											$total_cpf = 1776;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1110) {
											$cpf_employee = 1110;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(6 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.375 * ($tw - 500));
										$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
										$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
										if ($count_total_cpf > 1369) {
											$total_cpf = 1369;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 925) {
											$cpf_employee = 925;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(3.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
										if ($count_total_cpf > 814) {
											$total_cpf = 814;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(3.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
							if ($pr_age_year == 3 || $pr_age_year > 3) {
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(17 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.6 * ($tw - 500));
										$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
										$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
										if ($count_total_cpf > 2738) {
											$total_cpf = 2738;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1480) {
											$cpf_employee = 1480;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = $total_cpf - $cpf_employee;
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(15.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.51 * ($tw - 500));
										$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
										$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
										if ($count_total_cpf > 2405) {
											$total_cpf = 2405;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1258) {
											$cpf_employee = 1258;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(12 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.345 * ($tw - 500));
										$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
										$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;

										if ($count_total_cpf > 1739) {
											$total_cpf = 1739;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 851) {
											$cpf_employee = 851;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65 && $age_year <= 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;

										if ($count_total_cpf > 1221) {
											$total_cpf = 1221;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(7.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;

										if ($count_total_cpf > 925) {
											$total_cpf = 925;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
						}



						if ($immigration_id == 1) {

							if ($age_year <= 55) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(17 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.6 * ($tw - 500));
									$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
									$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
									if ($count_total_cpf > 2738) {
										$total_cpf = 2738;
									} else {
										$total_cpf = $count_total_cpf;
									}
			
									
									if ($count_cpf_employee > 1480) {
										$cpf_employee = 1480;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 55 && $age_year <= 60) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(15.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.51 * ($tw - 500));
									$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
			
									$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
									if ($count_total_cpf > 2405) {
										$total_cpf = 2405;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 1258) {
										$cpf_employee = 1258;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 60 && $age_year <= 65) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(12 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.345 * ($tw - 500));
									$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
									$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
			
									if ($count_total_cpf > 1739) {
										$total_cpf = 1739;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 851) {
										$cpf_employee = 851;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 65 && $age_year <= 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(9 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.225 * ($tw - 500));
									$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
									$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
			
									if ($count_total_cpf > 1221) {
										$total_cpf = 1221;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 555) {
										$cpf_employee = 555;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(7.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.15 * ($tw - 500));
									$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
									$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
			
									if ($count_total_cpf > 925) {
										$total_cpf = 925;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 370) {
										$cpf_employee = 370;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							}
						}

						$total_net_salary = $total_net_salary - $cpf_employee;
						$cpf_total = $cpf_employee + $cpf_employer;

						$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
						$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
						$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
					}
				}


				$shg_fund_deduction_amount = 0;
				//Other Fund Contributions
				$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);

				if ($employee_contributions && $g_shg > 0) {
					$gross_s = $g_shg;
					$contribution_id = $employee_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					$shg_fund_name = $contribution_type[0]->contribution;
					$shg_fund_deduction_amount += $contribution_amount;
					$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
				}
				$ashg_fund_deduction_amount = 0;

				$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
				if ($employee_ashg_contributions  && $g_shg > 0) {
					$gross_s = $g_shg;
					$contribution_id = $employee_ashg_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					$ashg_fund_name = $contribution_type[0]->contribution;

					$ashg_fund_deduction_amount += $contribution_amount;
					$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
				}

				// echo $total_net_salary;

				// $sdl_total_amount = 0;
				// if ($g_sdl > 1 && $g_sdl <= 800) {
				// 	$sdl_total_amount = 2;
				// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
				// 	$sdl_amount = (0.25 * $g_sdl) / 100;
				// 	$sdl_total_amount = $sdl_amount;
				// } elseif ($g_sdl > 4500) {
				// 	$sdl_total_amount = 11.25;
				// }


				$net_salary = number_format((float)$total_net_salary, 2, '.', '');
				$basic_salary = number_format((float)$basic_salary, 2, '.', '');
				// if ($basic_salary == 0 || $basic_salary == '') {
				// 	$fmpay = '';
				// } else {
					$fmpay = $mpay;
				// }


				$company_info = $this->Company_model->read_company_information($r->company_id);
				if (!is_null($company_info)) {
					$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
					$net_salary = $this->Xin_model->company_currency_sign($net_salary, $r->company_id);
					//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
				} else {
					//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
					$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary, $r->user_id);

					$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary, $r->user_id);

					//$net_salary = $this->Xin_model->currency_sign($net_salary);	
					//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
					$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount, $r->user_id);
				}

				$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';

				//action link
				$act = $detail . $fmpay . $delete;
				// $act = $fmpay . $delete;

				if ($r->wages_type == 1) {
					if ($system[0]->is_half_monthly == 1) {
						$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
					} else {
						$emp_payroll_wage = $wages_type;
					}
				} else {
					$emp_payroll_wage = $wages_type;
				}

				$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
				$p = $payslips->result();
				
				$mode_of_payment = $check[0]->payment_mode ?? '';


				$data[] = array(
					$act,
					$iemp_name,
					$emp_payroll_wage,
					// $basic_salary,
					$this->Xin_model->currency_sign($show_main_salary, $r->user_id),
					$this->Xin_model->currency_sign($cpf_employee, $r->user_id),
					$net_salary,
					$balance,
					$mode_of_payment,
					$status
				);
			}
		}
		$output = array(
			"draw" 				=> 	$draw,
			"recordsTotal" 		=> 	$payslip->num_rows(),
			"recordsFiltered" 	=> 	$payslip->num_rows(),
			"data" 				=> 	$data
		);
		echo json_encode($output);
		exit();
	}


	// get payroll template info by id
	public function payroll_template_read()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');

		$user = $this->Xin_model->read_user_info($id);

		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($designation)) {
			$designation_name = $designation[0]->designation_name;
		} else {
			$designation_name = '--';
		}
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$department_name = $department[0]->department_name;
		} else {
			$department_name = '--';
		}
		$data = array(
			'first_name' 		=> 	$user[0]->first_name,
			'last_name' 		=> 	$user[0]->last_name,
			'employee_id' 		=> 	$user[0]->employee_id,
			'user_id' 			=> 	$user[0]->user_id,
			'department_name' 	=> 	$department_name,
			'designation_name' 	=> 	$designation_name,
			'date_of_joining' 	=> 	$user[0]->date_of_joining,
			'profile_picture' 	=> 	$user[0]->profile_picture,
			'gender' 			=> 	$user[0]->gender,
			'wages_type' 		=> 	$user[0]->wages_type,
			'basic_salary' 		=> 	$user[0]->basic_salary,
			'daily_wages' 		=> 	$user[0]->daily_wages,
		);
		if (!empty($session)) {
			$this->load->view('admin/payroll/dialog_templates', $data);
		} else {
			redirect('admin/');
		}
	}


	// pay monthly > create payslip
	public function pay_salary()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('employee_id');

		$user = $this->Xin_model->read_user_info($id);
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($designation)) {
			$designation_id = $designation[0]->designation_id;
		} else {
			$designation_id = 1;
		}
		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$department_id = $department[0]->department_id;
		} else {
			$department_id = 1;
		}
		$claim_info = $this->Employees_model->read_employee_claim_information($user[0]->user_id);

		$claim_amount = 0;
		if ($claim_info) {
			foreach ($claim_info as $claim) {
				$claim_amount += $claim->amount;
			}
		}

		$final_data = 	$data = array(
			'user_id' 	=> 	$user[0]->user_id,
			'pay_date' 	=> 	$this->input->get('pay_date')
		);

		$data = array(
			'department_id' 	=> 	$department_id,
			'designation_id' 	=> 	$designation_id,
			'company_id' 		=> 	$user[0]->company_id,
			'location_id' 		=> 	$user[0]->location_id,
			'user_id' 			=> 	$user[0]->user_id,
			'dob' 				=> 	$user[0]->date_of_birth,
			'wages_type' 		=> 	$user[0]->wages_type,
			'basic_salary' 		=> 	$user[0]->basic_salary,
			'daily_wages' 		=> 	$user[0]->daily_wages,
			'office_shift_id' 	=> 	$user[0]->office_shift_id,
			'final_calculation' =>	$this->contribution_calculation($final_data),
		);
		if (!empty($session)) {
			$this->load->view('admin/payroll/dialog_make_payment', $data);
		} 
		else {
			redirect('admin/');
		}
	}

	// check other payment
	public function check_payment()
	{
		$pay_date 	= 	$this->input->get('pay_date');
		$user_id	=	$this->input->get('user_id');
		$payslip 	= 	$this->Employees_model->get_single_employees_payslip($pay_date, $user_id);
		$system 	= 	$this->Xin_model->read_setting_info(1);
		$check		=	$this->input->get('check');

		$role_resources_ids = $this->Xin_model->user_role_resource();
		$balance_amount = $this->uncheck_payment($this->input->get());
		$final_contribution_amount = $this->contribution_calculation($this->input->get());
		
		foreach ($payslip->result() as $r) {
			$exit_employee = $this->Employees_model->get_employee_exit($r->user_id);

			if (count($exit_employee) > 0) {
				$e_date = date('Y-m-d', strtotime($exit_employee[0]->exit_date));
				$exit_date = date('m-Y', strtotime($e_date));
				if ($exit_date >= $pay_date) {
					$emp_name = $r->first_name . ' ' . $r->last_name;
					// office shift
					$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);
	
					//overtime request
					$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $pay_date);
					$re_hrs_old_int1 = 0;
					$re_hrs_old_seconds = 0;
					$re_pcount = 0;
					foreach ($overtime_count as $overtime_hr) {
						$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
						$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
						$re_interval_late = $request_clock_in->diff($request_clock_out);
						$re_hours_r  = $re_interval_late->format('%h');
						$re_minutes_r = $re_interval_late->format('%i');
						$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';
	
						$re_str_time = $re_total_time;
	
						$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);
	
						sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$re_hrs_old_int1 += $re_hrs_old_seconds;
	
						$re_pcount = gmdate("H", $re_hrs_old_int1);
					}
	
					$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
					$hrs_old_int1 = 0;
					$pcount = 0;
					foreach ($result->result() as $hour_work) {
						$clock_in =  new DateTime($hour_work->clock_in);
						$clock_out =  new DateTime($hour_work->clock_out);
						$interval_late = $clock_in->diff($clock_out);
						$hours_r  = $interval_late->format('%h');
						$minutes_r = $interval_late->format('%i');
						$total_time = $hours_r . ":" . $minutes_r . ":" . '00';
	
						$str_time = $total_time;
	
						$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
	
						sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$hrs_old_int1 += $hrs_old_seconds;
	
						$pcount = gmdate("H", $hrs_old_int1);
					}
					$pcount = $pcount + $re_pcount;
	
					// get company
					$company = $this->Xin_model->read_company_info($r->company_id);
					if (!is_null($company)) {
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';
					}
	
					/**
					 * Local Variable
					 */
					$g_ordinary_wage = 0;
					$g_additional_wage = 0;
					$g_shg = 0;
					$g_sdl = 0;
	
					// 1: salary type
					if ($r->wages_type == 1) {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					} else if ($r->wages_type == 2) {
						$wages_type = $this->lang->line('xin_employee_daily_wages');
						if ($pcount > 0) {
							$basic_salary = $pcount * $r->basic_salary;
						} else {
							$basic_salary = $pcount;
						}
						$p_class = 'emo_hourly_pay';
						$view_p_class = 'hourlywages_template_modal';
					} else {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					}
	
	
					// check if any payment one 
					$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
					$check_payment = $check && !empty($check[0]->check_id) ? explode(',', $check[0]->check_id) : [];
					
					
					$database_cpf_employee = 0;
					$advance_amount	= 0;
					if($check){
						foreach($check as $c){
							$database_cpf_employee += $c->cpf_employee_amount;
							if($check[0]->is_advance == 1){
								$advance_amount += $check[0]->advance_amount;
							}
						}
					}
					$database_cpf_employee = $final_contribution_amount['cpf_employee'] - $database_cpf_employee;
	
					if (in_array('chk_gross_salary', $check_payment) || !in_array('chk_gross_salary', $this->input->get('check_id'))) {
						$basic_salary = 0;
					}else if($check && $check[0]->is_advance == 1){
						$basic_salary = $basic_salary - $advance_amount; 
					}
					
					// employee deduction
					$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
					$pa_month = date('m-Y', strtotime('01-' . $pay_date));
					$deduction_amount = 0;
					if (!in_array('chk_total_employee_deduction', $check_payment) && in_array('chk_total_employee_deduction', $this->input->get('check_id'))) {
						if ($employee_deduction) {
							foreach ($employee_deduction as $deduction) {
								if ($deduction->type_id == 1) {
									$deduction_amount +=  $deduction->amount;
								}
								if ($deduction->type_id == 2) {
									$from_month_year = date('m-Y', strtotime($deduction->from_date));
									$to_month_year = date('d-m-Y', strtotime($deduction->to_date));
	
	
									if ($from_month_year != "" && $to_month_year != "") {
										if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
											$deduction_amount +=  $deduction->amount;
										}
									}
								}
							}
						}
					}
	
	
					//3: Gross rate of pay (unpaid leave deduction)
					$holidays_count = 0;
					$no_of_working_days = 0;
					$month_start_date = new DateTime('01-' . $pay_date);
					$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
					$month_end_date->modify('+1 day');
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($month_start_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$period_day = $p->format('l');
						$period_date = $p->format('Y-m-d');
	
						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
						if ($is_holiday) {
							$holidays_count += 1;
						}
	
						//working days excluding holidays based on office shift
						if ($period_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_working_days += 1;
							}
						}
					}
	
					//unpaid leave
					$unpaid_leave_amount = 0;
					$leaves_taken_count = 0;
					$leave_period = array();
					$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
						
					// if (!in_array('chk_leave_deductions', $check_payment) && in_array('chk_leave_deductions', $this->input->get('check_id'))) {
						if ($unpaid_leaves) {
							foreach ($unpaid_leaves as $k => $l) {
								$pay_date_month = new DateTime('01-' . $pay_date);
								$l_from_date = new DateTime($l->from_date);
								$l_to_date = new DateTime($l->to_date);
	
								if ($l_from_date->format('m') == $l_to_date->format('m')) {
									$start_date = $l_from_date;
									$end_date = $l_to_date;
								} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
									$start_date = $l_from_date;
									$end_date = new DateTime($start_date->format('Y-m-t'));
								} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
									$start_date = $pay_date_month;
									$end_date = $l_to_date;
								}
								$end_date->modify('+1 day');
								$interval = new DateInterval('P1D');
								$period = new DatePeriod($start_date, $interval, $end_date);
								foreach ($period as $d) {
									$p_day = $d->format('l');
									if ($p_day == 'Monday') {
										if ($office_shift[0]->monday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Tuesday') {
										if ($office_shift[0]->tuesday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Wednesday') {
										if ($office_shift[0]->wednesday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Thursday') {
										if ($office_shift[0]->thursday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Friday') {
										if ($office_shift[0]->friday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Saturday') {
										if ($office_shift[0]->saturday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Sunday') {
										if ($office_shift[0]->sunday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									}
								}
								$leave_period[$k]['is_half'] = $l->is_half_day;
							}
						}
					// }
					
					// if joining date and pay date same then this logic work
					$month_date_join = date('m-Y', strtotime($r->date_of_joining));
					$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
					$month_last_date = date('m-Y', strtotime($lastday));
	
					$first_date =  new DateTime($r->date_of_joining);
					$no_of_days_worked = 0;
					$same_month_holidays_count = 0;
					if ($month_date_join == $pay_date) {
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($first_date, $interval, $month_end_date);
						foreach ($period as $p) {
							$p_day = $p->format('l');
							$m_p_date = $p->format('Y-m-d');
	
							//holidays in a month
							$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
							if ($is_holiday) {
								$same_month_holidays_count += 1;
							}
	
							//working days excluding holidays based on office shift
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$no_of_days_worked += 1;
								}
							}
						}
						// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
						$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
					} else {
						// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
						$no_of_days_worked = $no_of_working_days -  $holidays_count;
					}
	
	
					if ($month_date_join == $pay_date){
						$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
					}else{
						$show_main_salary = $basic_salary;
					}
				
					$g_ordinary_wage += $show_main_salary;
					$g_shg += $show_main_salary;
					$g_sdl += $show_main_salary;
				
					$g_ordinary_wage -= $deduction_amount;
					$g_shg -= $deduction_amount;
					$g_sdl -= $deduction_amount;
	
					// 3: all loan/deductions
					$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
					$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
					$loan_de_amount = 0;
					if (!in_array('chk_loan_de_amount', $check_payment) && in_array('chk_loan_de_amount', $this->input->get('check_id'))) {
						if ($count_loan_deduction > 0) {
							foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
									} else {
										$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
									}
								} else {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
								}
								$loan_de_amount += $er_loan;
							}
						} else {
							$loan_de_amount = 0;
						}
						$loan_de_amount = round($loan_de_amount, 2);
					}
	
					// 2: all allowances
					$allowance_amount = 0;
					$gross_allowance_amount = 0;
					if (!in_array('chk_total_allowances', $check_payment) && in_array('chk_total_allowances', $this->input->get('check_id'))) {
						$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
						if ($salary_allowances) {
							foreach ($salary_allowances as $sa) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$eallowance_amount = $sa->allowance_amount / 2;
									} else {
										$eallowance_amount = $sa->allowance_amount;
									}
								} else {
									$eallowance_amount = $sa->allowance_amount;
								}
	
	
								if (!empty($sa->salary_month)) {
									$g_additional_wage += $eallowance_amount;
								} else {
									if ($month_date_join == $pay_date){
										$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
									}
									$g_ordinary_wage += $eallowance_amount;
									if ($sa->id == 2) {
										$gross_allowance_amount = $eallowance_amount;
									}
								}
	
								if ($sa->sdl == 1) {
									$g_sdl += $eallowance_amount;
								}
								if ($sa->shg == 1) {
									$g_shg += $eallowance_amount;
								}
	
								$allowance_amount += $eallowance_amount;
							}
						}
						$gross_allowance_amount = $allowance_amount;
					}
					
	
	
	
					// commissions
					$commissions_amount = 0;
					if ( !in_array('chk_total_commissions', $check_payment) && in_array('chk_total_commissions', $this->input->get('check_id'))) {
						$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
						if ($commissions) {
							foreach ($commissions as $c) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$ecommissions_amount = $c->commission_amount / 2;
									} else {
										$ecommissions_amount = $c->commission_amount;
									}
								} else {
									$ecommissions_amount = $c->commission_amount;
								}
	
								if ($c->commission_type == 9) {
									$g_ordinary_wage += $ecommissions_amount;
								} elseif ($c->commission_type == 10) {
									$g_additional_wage += $ecommissions_amount;
								}
	
								if ($c->sdl == 1) {
									$g_sdl += $ecommissions_amount;
								}
								if ($c->shg == 1) {
									$g_shg += $ecommissions_amount;
								}
	
								$commissions_amount += $ecommissions_amount;
							}
						}
					}
	
					//share options
					$share_options_amount = 0;
					if (!in_array('chk_total_share', $check_payment) && in_array('chk_total_share', $this->input->get('check_id'))) {
						$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
						if ($share_options) {
							$eebr_amount = 0;
							$eris_amount = 0;
							foreach ($share_options as $s) {
								$scheme = $s->so_scheme;
								if ($scheme == 1) {
									$price_doe = $s->price_date_of_excercise;
									$price_ex = $s->excercise_price;
									$no_shares = $s->no_of_shares;
									$amount = ($price_doe - $price_ex) * $no_shares;
									$eebr_amount += $amount;
								} else {
									$price_doe = $s->price_date_of_excercise;
									$price_dog = $s->price_date_of_grant;
									$price_ex = $s->excercise_price;
									$no_shares = $s->no_of_shares;
	
									$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
									$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
									$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
								}
							}
							$share_options_amount = round($eebr_amount + $eris_amount, 2);
							$g_additional_wage += $share_options_amount;
							$g_sdl += $share_options_amount;
							$g_shg += $share_options_amount;
						}
					}
	
					// otherpayments
					$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
					$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
					$other_payments_amount = 0;
					if (!in_array('chk_total_other_payments', $check_payment) && in_array('chk_total_other_payments', $this->input->get('check_id'))) {
						if ($count_other_payments > 0) {
							foreach ($other_payments->result() as $sl_other_payments) {
								if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
									if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$epayments_amount = $sl_other_payments->payments_amount / 2;
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
	
										if ($sl_other_payments->cpf_applicable == 1) {
											$g_additional_wage += $epayments_amount;
											$g_shg += $epayments_amount;
											$g_sdl += $epayments_amount;
										}
	
										$other_payments_amount += $epayments_amount;
									}
								} else {
									$first_date = new DateTime($sl_other_payments->date);
									if ($first_date->format('m-Y') == $pay_date) {
										$first_date =  new DateTime($sl_other_payments->date);
									} else {
										$first_date = new DateTime('01-' . $pay_date);
									}
	
									$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));
	
									if (!empty($sl_other_payments->end_date)) {
										$last_date = new DateTime($sl_other_payments->end_date);
										if ($last_date->format('m-Y') == $pay_date) {
											$last_date = new DateTime($sl_other_payments->end_date);
										}else if($last_date->format('m-Y') >= $pay_date){
											$last_date = $month_end_date_for_other;
										} else {
											$last_date = '';
										}
									} else {
										$last_date = $month_end_date_for_other;
									}
									if(!empty($last_date)){
										$last_date->modify('+1 day');
										$final_last_day = new DateTime($last_date->format('d-m-Y'));
										if ($final_last_day->format('m-Y') >= $pay_date) {
											if ($system[0]->is_half_monthly == 1) {
												if ($system[0]->half_deduct_month == 2) {
													$epayments_amount = $sl_other_payments->payments_amount / 2;
												} else {
													$epayments_amount = $sl_other_payments->payments_amount;
												}
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
	
	
											// it for no of working day
											$no_of_days_worked_for_other_payment = 0;
											$same_month_holidays_count_for_other_payment = 0;
											$interval = new DateInterval('P1D');
											$period = new DatePeriod($first_date, $interval, $last_date);
											foreach ($period as $p) {
												$p_day = $p->format('l');
												$p_date = $p->format('Y-m-d');
	
												//holidays in a month
	
												$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
												if ($is_holiday) {
													$same_month_holidays_count_for_other_payment += 1;
												}
	
												//working days excluding holidays based on office shift
												if ($p_day == 'Monday') {
													if ($office_shift[0]->monday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Tuesday') {
													if ($office_shift[0]->tuesday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Wednesday') {
													if ($office_shift[0]->wednesday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Thursday') {
													if ($office_shift[0]->thursday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Friday') {
													if ($office_shift[0]->friday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Saturday') {
													if ($office_shift[0]->saturday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Sunday') {
													if ($office_shift[0]->sunday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												}
											}
	
											$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
											$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);
											
	
	
											if ($sl_other_payments->cpf_applicable == 1) {
												$g_additional_wage += $epayments_amount;
												$g_shg += $epayments_amount;
												$g_sdl += $epayments_amount;
											}
											$other_payments_amount += $epayments_amount;
										}
									}
								}
							}
						} else {
							$other_payments_amount = 0;
						}
					}
					
	
					$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
					// if (!in_array('chk_leave_deductions', $check_payment) && in_array('chk_leave_deductions', $this->input->get('check_id'))) {
						if ($unpaid_leaves) {
							$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
						}
					// }
	
					// $g_ordinary_wage = $gross_pay;
					$g_shg = $gross_pay;
					$g_sdl = $gross_pay;
	
					$g_ordinary_wage -= $unpaid_leave_amount;
	
	
					// other benefit
					$other_benefit_mount = 0;
					if (!in_array('chk_total_employee_deduction', $check_payment) && in_array('chk_total_employee_deduction', $this->input->get('check_id'))) {
						$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
						foreach ($other_benefit_list->result() as $benefit_list) {
							$other_benefit_mount += $benefit_list->other_benefit_cost;
						}
					}
	
	
					// statutory_deductions
					$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
					$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
					$statutory_deductions_amount = 0;
					if ( !in_array('chk_total_statutory_deductions', $check_payment) || in_array('chk_total_statutory_deductions', $this->input->get('check_id'))) {
						
						if ($count_statutory_deductions > 0) {
							foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
								if ($system[0]->statutory_fixed != 'yes') {
									$sta_salary = $gross_pay;
									$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$single_sd = $st_amount / 2;
										} else {
											$single_sd = $st_amount;
										}
									} else {
										$single_sd = $st_amount;
									}
									$statutory_deductions_amount += $single_sd;
								} else {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
										} else {
											$single_sd = $sl_salary_statutory_deductions->deduction_amount;
										}
									} else {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount;
									}
									$statutory_deductions_amount += $single_sd;
								}
							}
						} else {
							$statutory_deductions_amount = 0;
						}
					}
	
					// overtime
					$overtime_amount = 0;
					if (!in_array('chk_total_overtime', $check_payment) && in_array('chk_total_overtime', $this->input->get('check_id'))) {
						$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
						if ($overtime) {
							$ot_hrs = 0;
							$ot_mins = 0;
							foreach ($overtime as $ot) {
								$total_hours = explode(':', $ot->total_hours);
								$ot_hrs += $total_hours[0];
								$ot_mins += $total_hours[1];
							}
							if ($ot_mins > 0) {
								$ot_hrs += round($ot_mins / 60, 2);
							}
	
							//overtime rate
							$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
							if ($overtime_rate) {
								$rate = $overtime_rate->overtime_pay_rate;
							} else {
								$week_hours = 44;
								$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
								$rate = $rate * 1.5;
							}
	
							if ($ot_hrs > 0) {
								$overtime_amount = round($ot_hrs * $rate, 2);
							}
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$overtime_amount = $overtime_amount / 2;
								}
							}
							$g_ordinary_wage += $overtime_amount;
							$g_sdl += $overtime_amount;
						}
					}
	
					// make payment
					if ($system[0]->is_half_monthly == 1) {
						$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($r->user_id, $pay_date);
						$payment_last = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($r->user_id, $pay_date);
						if ($payment_check->num_rows() > 1) {
	
							//foreach($payment_last as $payment_half_last){
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
							//$mpay = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_make_payment').'"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '" data-company_id="'.$this->input->get("company_id").'"><span class="fa fas fa-money"></span></button></span>';
	
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}
	
							$delete = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code><br>' . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $payment_last[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span><code>' . $this->lang->line('xin_title_second_half') . '</code>';
							//}
							//detail link
							$detail = '';
						} else if ($payment_check->num_rows() > 0) {
	
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
	
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
	
							$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}
							$delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
							$detail = '';
						} else {
	
							$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
							$delete = '';
							//detail link
							$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						}
						//detail link
						//$detail = '';
					} else {
						$payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
						if ($payment_check->num_rows() > 0) {
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
							if ($make_payment[0]->status == 0) {
								$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
							} else if ($make_payment[0]->status == 3) {
								$status = '<span class="label label-warning">' . "Partially" . '</span>';
							}
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}
						} else {
	
							$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '">
								<button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '">
									<span class="fa fas fa-money"></span>
								</button>
							</span>';
							$delete = '';
						}
						//detail link
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					}
	
	
					// employee accommodations
					$employee_accommodations = 0;
					if (!in_array('chk_total_employee_deduction', $check_payment) && in_array('chk_total_employee_deduction', $this->input->get('check_id'))){
						$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
						foreach ($get_employee_accommodations as $get_employee_accommodation) {
							$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
							$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
							if ($period_from == $pay_date || $period_to == $pay_date) {
								if (!empty($get_employee_accommodation->rent_paid)) {
									$employee_accommodations += $get_employee_accommodation->rent_paid;
								}
							}
						}
					}
	
					// employee claims
					$claim_amount = 0;
					if (!in_array('chk_employee_claim', $check_payment) && in_array('chk_employee_claim', $this->input->get('check_id'))){
						$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
						foreach ($get_employee_claims->result() as $claims) {
							$date 	= 	date('m-Y', strtotime($claims->date));
							if ($date == $pay_date) {
								$claim_amount += $claims->amount;
							}
						}
					}
	
					$total_earning = $show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
					$total_deduction = $loan_de_amount + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
					$total_net_salary = ($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;
	
	
					// cpf calculation
					$emp_dob = $r->date_of_birth;
					$dob = new DateTime($emp_dob);
	
					$today = new DateTime('01-' . $pay_date);
					$age = $dob->diff($today);
					$age_year = $age->y;
					$age_month = $age->m;
	
					$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
					$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
					$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;
	
					if ($age_month > 0) {
						$age_year = $age_year + 1;
					}
	
					$cpf_employee 	= 	0;
					$cpf_employer	=	0;
					$total_cpf		= 	0;
					$cpf_total		=	0;
					$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
					if ($im_status) {
						$immigration_id = $im_status->immigration_id;
						if ($immigration_id == 2) {
							$issue_date = $im_status->issue_date;
							$i_date = new DateTime($issue_date);
							$today = new DateTime();
							$pr_age = $i_date->diff($today);
							$pr_age_year = $pr_age->y;
							$pr_age_month = $pr_age->m;
						}
	
						if ($immigration_id == 1 || $immigration_id == 2) {
	
							$ordinary_wage = $g_ordinary_wage;
							if ($ordinary_wage > $ordinary_wage_cap) {
								$ow = $ordinary_wage_cap;
							} else {
								$ow = $ordinary_wage;
							}
	
							//additional wage
							$additional_wage = $g_additional_wage;
							$aw = $g_additional_wage;
							$tw = $ow + $additional_wage;
							if ($im_status->issue_date != "") {
								if ($pr_age_year == 1) {
	
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(4 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 666) {
												$total_cpf = 666;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(4 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 666) {
												$total_cpf = 666;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(3.5 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(3.5 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
								if ($pr_age_year == 2) {
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(9 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.45 * ($tw - 500));
											$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
											$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
											if ($count_total_cpf > 1776) {
												$total_cpf = 1776;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1110) {
												$cpf_employee = 1110;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(6 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.375 * ($tw - 500));
											$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
											$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
											if ($count_total_cpf > 1369) {
												$total_cpf = 1369;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 925) {
												$cpf_employee = 925;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(3.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.225 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
											$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
											if ($count_total_cpf > 814) {
												$total_cpf = 814;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 555) {
												$cpf_employee = 555;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(3.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
											$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
								if ($pr_age_year == 3 || $pr_age_year > 3) {
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(17 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.6 * ($tw - 500));
											$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
											$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
											if ($count_total_cpf > 2738) {
												$total_cpf = 2738;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1480) {
												$cpf_employee = 1480;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = $total_cpf - $cpf_employee;
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(15.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.51 * ($tw - 500));
											$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
											$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
											if ($count_total_cpf > 2405) {
												$total_cpf = 2405;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1258) {
												$cpf_employee = 1258;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(12 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.345 * ($tw - 500));
											$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
											$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
	
											if ($count_total_cpf > 1739) {
												$total_cpf = 1739;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 851) {
												$cpf_employee = 851;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65 && $age_year <= 70) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(9 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.225 * ($tw - 500));
											$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
											$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
	
											if ($count_total_cpf > 1221) {
												$total_cpf = 1221;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 555) {
												$cpf_employee = 555;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 70) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(7.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
											$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
	
											if ($count_total_cpf > 925) {
												$total_cpf = 925;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
							}
	
	
	
							if ($immigration_id == 1) {
	
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(17 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.6 * ($tw - 500));
										$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
										$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
										if ($count_total_cpf > 2738) {
											$total_cpf = 2738;
										} else {
											$total_cpf = $count_total_cpf;
										}
				
										
										if ($count_cpf_employee > 1480) {
											$cpf_employee = 1480;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(15.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.51 * ($tw - 500));
										$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
				
										$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
										if ($count_total_cpf > 2405) {
											$total_cpf = 2405;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1258) {
											$cpf_employee = 1258;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(12 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.345 * ($tw - 500));
										$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
										$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
				
										if ($count_total_cpf > 1739) {
											$total_cpf = 1739;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 851) {
											$cpf_employee = 851;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65 && $age_year <= 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
				
										if ($count_total_cpf > 1221) {
											$total_cpf = 1221;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(7.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
				
										if ($count_total_cpf > 925) {
											$total_cpf = 925;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
							
							if ($check) {
								$cpf_employee = $database_cpf_employee;
							}
							
							$total_net_salary = $total_net_salary - $cpf_employee;
							$cpf_total = $cpf_employee + $cpf_employer;
	
							$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
							$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
							$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
						}
					}
	
	
					// $shg_fund_deduction_amount = 0;
					// //Other Fund Contributions
					// $employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);
					// if ($employee_contributions && $g_shg > 0) {
					// 	$gross_s = $g_shg;
					// 	$contribution_id = $employee_contributions->contribution_id;
					// 	$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					// 	$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					// 	$shg_fund_name = $contribution_type[0]->contribution;
					// 	$shg_fund_deduction_amount += $contribution_amount;
					// 	$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
					// }
					// $ashg_fund_deduction_amount = 0;
	
					// $employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
					// if ($employee_ashg_contributions  && $g_shg > 0) {
					// 	$gross_s = $g_shg;
					// 	$contribution_id = $employee_ashg_contributions->contribution_id;
					// 	$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					// 	$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					// 	$ashg_fund_name = $contribution_type[0]->contribution;
	
					// 	$ashg_fund_deduction_amount += $contribution_amount;
					// 	$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
					// }
	
					// echo $total_net_salary;
	
					// $sdl_total_amount = 0;
					// if ($g_sdl > 1 && $g_sdl <= 800) {
					// 	$sdl_total_amount = 2;
					// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
					// 	$sdl_amount = (0.25 * $g_sdl) / 100;
					// 	$sdl_total_amount = $sdl_amount;
					// } elseif ($g_sdl > 4500) {
					// 	$sdl_total_amount = 11.25;
					// }
	
	
					$net_salary = number_format((float)$total_net_salary, 2, '.', '');
					$basic_salary = number_format((float)$basic_salary, 2, '.', '');
					if ($basic_salary == 0 || $basic_salary == '') {
						$fmpay = '';
					} else {
						$fmpay = $mpay;
					}
	
	
					$company_info = $this->Company_model->read_company_information($r->company_id);
					if (!is_null($company_info)) {
						$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
						$net_salary = $net_salary;
						//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
					} else {
						//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
						$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary, $r->user_id);
	
						$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary, $r->user_id);
	
						//$net_salary = $this->Xin_model->currency_sign($net_salary);	
						//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
						$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount, $r->user_id);
					}
	
					$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';
	
					//action link
					$act = $detail . $fmpay . $delete;
					// $act = $fmpay . $delete;
	
					if ($r->wages_type == 1) {
						if ($system[0]->is_half_monthly == 1) {
							$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
						} else {
							$emp_payroll_wage = $wages_type;
						}
					} else {
						$emp_payroll_wage = $wages_type;
					}
	
					$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
					$p = $payslips->result();
					$balance = $this->Xin_model->currency_sign($p[0]->balance_amount ?? 0, $r->user_id);
					$mode_of_payment = $r->payment_mode ?? '';
	
					
					 
					if($balance_amount > 0)
						 $calculate_balance_amount = $balance_amount -  ($database_cpf_employee - $cpf_employee);
					else
						$calculate_balance_amount = 0;
	
					// $cpf =  $final_contribution_amount['cpf_employee'] - ($final_contribution_amount['cpf_employee'] - $cpf_employee);
					
					if($check && $check[0]->is_advance != 1){
						$net_salary = $net_salary > 0 ? round($net_salary,2) : 0;
					}else{
						$net_salary = round($net_salary - $final_contribution_amount['contribution'],2);
					}
	
					$data = array(
						'total_cpf_employee'	=> 	$cpf_employee,
						// 'total_cpf_employee'	=> 	$cpf,
						'total_cpf_employer'	=>	$cpf_employer,
						'cpf_total'				=> 	$cpf_total,
						'net_salary'			=>	$net_salary,
						'contribution'			=>	$final_contribution_amount['contribution'],
						'total_deduction'		=>	$total_deduction,
						'balance'				=>	round($calculate_balance_amount,2),
						'unpaid_leave_amount'	=>	$unpaid_leave_amount,
						'statutory_deductions'	=>	$statutory_deductions_amount,
						$balance_amount
					);
				}
			} else {
				$emp_name = $r->first_name . ' ' . $r->last_name;
				// office shift
				$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);

				//overtime request
				$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $pay_date);
				$re_hrs_old_int1 = 0;
				$re_hrs_old_seconds = 0;
				$re_pcount = 0;
				foreach ($overtime_count as $overtime_hr) {
					$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
					$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
					$re_interval_late = $request_clock_in->diff($request_clock_out);
					$re_hours_r  = $re_interval_late->format('%h');
					$re_minutes_r = $re_interval_late->format('%i');
					$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';

					$re_str_time = $re_total_time;

					$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);

					sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$re_hrs_old_int1 += $re_hrs_old_seconds;

					$re_pcount = gmdate("H", $re_hrs_old_int1);
				}

				$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
				$hrs_old_int1 = 0;
				$pcount = 0;
				foreach ($result->result() as $hour_work) {
					$clock_in =  new DateTime($hour_work->clock_in);
					$clock_out =  new DateTime($hour_work->clock_out);
					$interval_late = $clock_in->diff($clock_out);
					$hours_r  = $interval_late->format('%h');
					$minutes_r = $interval_late->format('%i');
					$total_time = $hours_r . ":" . $minutes_r . ":" . '00';

					$str_time = $total_time;

					$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

					sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$hrs_old_int1 += $hrs_old_seconds;

					$pcount = gmdate("H", $hrs_old_int1);
				}
				$pcount = $pcount + $re_pcount;

				// get company
				$company = $this->Xin_model->read_company_info($r->company_id);
				if (!is_null($company)) {
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';
				}

				/**
				 * Local Variable
				 */
				$g_ordinary_wage = 0;
				$g_additional_wage = 0;
				$g_shg = 0;
				$g_sdl = 0;

				// 1: salary type
				if ($r->wages_type == 1) {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				} else if ($r->wages_type == 2) {
					$wages_type = $this->lang->line('xin_employee_daily_wages');
					if ($pcount > 0) {
						$basic_salary = $pcount * $r->basic_salary;
					} else {
						$basic_salary = $pcount;
					}
					$p_class = 'emo_hourly_pay';
					$view_p_class = 'hourlywages_template_modal';
				} else {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				}


				// check if any payment one 
				$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
				$check_payment = $check && !empty($check[0]->check_id) ? explode(',', $check[0]->check_id) : [];
				
				
				$database_cpf_employee = 0;
				$advance_amount	= 0;
				if($check){
					foreach($check as $c){
						$database_cpf_employee += $c->cpf_employee_amount;
						if($check[0]->is_advance == 1){
							$advance_amount += $check[0]->advance_amount;
						}
					}
				}
				$database_cpf_employee = $final_contribution_amount['cpf_employee'] - $database_cpf_employee;

				if (in_array('chk_gross_salary', $check_payment) || !in_array('chk_gross_salary', $this->input->get('check_id'))) {
					$basic_salary = 0;
				}else if($check && $check[0]->is_advance == 1){
					$basic_salary = $basic_salary - $advance_amount; 
				}
				
				// employee deduction
				$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
				$pa_month = date('m-Y', strtotime('01-' . $pay_date));
				$deduction_amount = 0;
				if (!in_array('chk_total_employee_deduction', $check_payment) && in_array('chk_total_employee_deduction', $this->input->get('check_id'))) {
					if ($employee_deduction) {
						foreach ($employee_deduction as $deduction) {
							if ($deduction->type_id == 1) {
								$deduction_amount +=  $deduction->amount;
							}
							if ($deduction->type_id == 2) {
								$from_month_year = date('m-Y', strtotime($deduction->from_date));
								$to_month_year = date('d-m-Y', strtotime($deduction->to_date));


								if ($from_month_year != "" && $to_month_year != "") {
									if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
										$deduction_amount +=  $deduction->amount;
									}
								}
							}
						}
					}
				}


				//3: Gross rate of pay (unpaid leave deduction)
				$holidays_count = 0;
				$no_of_working_days = 0;
				$month_start_date = new DateTime('01-' . $pay_date);
				$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
				$month_end_date->modify('+1 day');
				$interval = new DateInterval('P1D');
				$period = new DatePeriod($month_start_date, $interval, $month_end_date);
				foreach ($period as $p) {
					$period_day = $p->format('l');
					$period_date = $p->format('Y-m-d');

					//holidays in a month
					$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
					if ($is_holiday) {
						$holidays_count += 1;
					}

					//working days excluding holidays based on office shift
					if ($period_day == 'Monday') {
						if ($office_shift[0]->monday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Tuesday') {
						if ($office_shift[0]->tuesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Wednesday') {
						if ($office_shift[0]->wednesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Thursday') {
						if ($office_shift[0]->thursday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Friday') {
						if ($office_shift[0]->friday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Saturday') {
						if ($office_shift[0]->saturday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Sunday') {
						if ($office_shift[0]->sunday_in_time != '') {
							$no_of_working_days += 1;
						}
					}
				}

				//unpaid leave
				$unpaid_leave_amount = 0;
				$leaves_taken_count = 0;
				$leave_period = array();
				$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
					
				// if (!in_array('chk_leave_deductions', $check_payment) && in_array('chk_leave_deductions', $this->input->get('check_id'))) {
					if ($unpaid_leaves) {
						foreach ($unpaid_leaves as $k => $l) {
							$pay_date_month = new DateTime('01-' . $pay_date);
							$l_from_date = new DateTime($l->from_date);
							$l_to_date = new DateTime($l->to_date);

							if ($l_from_date->format('m') == $l_to_date->format('m')) {
								$start_date = $l_from_date;
								$end_date = $l_to_date;
							} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $l_from_date;
								$end_date = new DateTime($start_date->format('Y-m-t'));
							} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $pay_date_month;
								$end_date = $l_to_date;
							}
							$end_date->modify('+1 day');
							$interval = new DateInterval('P1D');
							$period = new DatePeriod($start_date, $interval, $end_date);
							foreach ($period as $d) {
								$p_day = $d->format('l');
								if ($p_day == 'Monday') {
									if ($office_shift[0]->monday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Tuesday') {
									if ($office_shift[0]->tuesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Wednesday') {
									if ($office_shift[0]->wednesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Thursday') {
									if ($office_shift[0]->thursday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Friday') {
									if ($office_shift[0]->friday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Saturday') {
									if ($office_shift[0]->saturday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Sunday') {
									if ($office_shift[0]->sunday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								}
							}
							$leave_period[$k]['is_half'] = $l->is_half_day;
						}
					}
				// }
				
				// if joining date and pay date same then this logic work
				$month_date_join = date('m-Y', strtotime($r->date_of_joining));
				$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
				$month_last_date = date('m-Y', strtotime($lastday));

				$first_date =  new DateTime($r->date_of_joining);
				$no_of_days_worked = 0;
				$same_month_holidays_count = 0;
				if ($month_date_join == $pay_date) {
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($first_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$p_day = $p->format('l');
						$m_p_date = $p->format('Y-m-d');

						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
						if ($is_holiday) {
							$same_month_holidays_count += 1;
						}

						//working days excluding holidays based on office shift
						if ($p_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_days_worked += 1;
							}
						}
					}
					// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
					$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
				} else {
					// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
					$no_of_days_worked = $no_of_working_days -  $holidays_count;
				}


				if ($month_date_join == $pay_date){
					$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
				}else{
					$show_main_salary = $basic_salary;
				}
			
				$g_ordinary_wage += $show_main_salary;
				$g_shg += $show_main_salary;
				$g_sdl += $show_main_salary;
			
				$g_ordinary_wage -= $deduction_amount;
				$g_shg -= $deduction_amount;
				$g_sdl -= $deduction_amount;

				// 3: all loan/deductions
				$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
				$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
				$loan_de_amount = 0;
				if (!in_array('chk_loan_de_amount', $check_payment) && in_array('chk_loan_de_amount', $this->input->get('check_id'))) {
					if ($count_loan_deduction > 0) {
						foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
								} else {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
								}
							} else {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
							}
							$loan_de_amount += $er_loan;
						}
					} else {
						$loan_de_amount = 0;
					}
					$loan_de_amount = round($loan_de_amount, 2);
				}

				// 2: all allowances
				$allowance_amount = 0;
				$gross_allowance_amount = 0;
				if (!in_array('chk_total_allowances', $check_payment) && in_array('chk_total_allowances', $this->input->get('check_id'))) {
					$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
					if ($salary_allowances) {
						foreach ($salary_allowances as $sa) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$eallowance_amount = $sa->allowance_amount / 2;
								} else {
									$eallowance_amount = $sa->allowance_amount;
								}
							} else {
								$eallowance_amount = $sa->allowance_amount;
							}


							if (!empty($sa->salary_month)) {
								$g_additional_wage += $eallowance_amount;
							} else {
								if ($month_date_join == $pay_date){
									$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
								}
								$g_ordinary_wage += $eallowance_amount;
								if ($sa->id == 2) {
									$gross_allowance_amount = $eallowance_amount;
								}
							}

							if ($sa->sdl == 1) {
								$g_sdl += $eallowance_amount;
							}
							if ($sa->shg == 1) {
								$g_shg += $eallowance_amount;
							}

							$allowance_amount += $eallowance_amount;
						}
					}
					$gross_allowance_amount = $allowance_amount;
				}
				



				// commissions
				$commissions_amount = 0;
				if ( !in_array('chk_total_commissions', $check_payment) && in_array('chk_total_commissions', $this->input->get('check_id'))) {
					$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
					if ($commissions) {
						foreach ($commissions as $c) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$ecommissions_amount = $c->commission_amount / 2;
								} else {
									$ecommissions_amount = $c->commission_amount;
								}
							} else {
								$ecommissions_amount = $c->commission_amount;
							}

							if ($c->commission_type == 9) {
								$g_ordinary_wage += $ecommissions_amount;
							} elseif ($c->commission_type == 10) {
								$g_additional_wage += $ecommissions_amount;
							}

							if ($c->sdl == 1) {
								$g_sdl += $ecommissions_amount;
							}
							if ($c->shg == 1) {
								$g_shg += $ecommissions_amount;
							}

							$commissions_amount += $ecommissions_amount;
						}
					}
				}

				//share options
				$share_options_amount = 0;
				if (!in_array('chk_total_share', $check_payment) && in_array('chk_total_share', $this->input->get('check_id'))) {
					$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
					if ($share_options) {
						$eebr_amount = 0;
						$eris_amount = 0;
						foreach ($share_options as $s) {
							$scheme = $s->so_scheme;
							if ($scheme == 1) {
								$price_doe = $s->price_date_of_excercise;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
								$amount = ($price_doe - $price_ex) * $no_shares;
								$eebr_amount += $amount;
							} else {
								$price_doe = $s->price_date_of_excercise;
								$price_dog = $s->price_date_of_grant;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;

								$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
								$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
								$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
							}
						}
						$share_options_amount = round($eebr_amount + $eris_amount, 2);
						$g_additional_wage += $share_options_amount;
						$g_sdl += $share_options_amount;
						$g_shg += $share_options_amount;
					}
				}

				// otherpayments
				$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
				$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
				$other_payments_amount = 0;
				if (!in_array('chk_total_other_payments', $check_payment) && in_array('chk_total_other_payments', $this->input->get('check_id'))) {
					if ($count_other_payments > 0) {
						foreach ($other_payments->result() as $sl_other_payments) {
							if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
								if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$epayments_amount = $sl_other_payments->payments_amount / 2;
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}

									if ($sl_other_payments->cpf_applicable == 1) {
										$g_additional_wage += $epayments_amount;
										$g_shg += $epayments_amount;
										$g_sdl += $epayments_amount;
									}

									$other_payments_amount += $epayments_amount;
								}
							} else {
								$first_date = new DateTime($sl_other_payments->date);
								if ($first_date->format('m-Y') == $pay_date) {
									$first_date =  new DateTime($sl_other_payments->date);
								} else {
									$first_date = new DateTime('01-' . $pay_date);
								}

								$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));

								if (!empty($sl_other_payments->end_date)) {
									$last_date = new DateTime($sl_other_payments->end_date);
									if ($last_date->format('m-Y') == $pay_date) {
										$last_date = new DateTime($sl_other_payments->end_date);
									}else if($last_date->format('m-Y') >= $pay_date){
										$last_date = $month_end_date_for_other;
									} else {
										$last_date = '';
									}
								} else {
									$last_date = $month_end_date_for_other;
								}
								if(!empty($last_date)){
									$last_date->modify('+1 day');
									$final_last_day = new DateTime($last_date->format('d-m-Y'));
									if ($final_last_day->format('m-Y') >= $pay_date) {
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$epayments_amount = $sl_other_payments->payments_amount / 2;
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}


										// it for no of working day
										$no_of_days_worked_for_other_payment = 0;
										$same_month_holidays_count_for_other_payment = 0;
										$interval = new DateInterval('P1D');
										$period = new DatePeriod($first_date, $interval, $last_date);
										foreach ($period as $p) {
											$p_day = $p->format('l');
											$p_date = $p->format('Y-m-d');

											//holidays in a month

											$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
											if ($is_holiday) {
												$same_month_holidays_count_for_other_payment += 1;
											}

											//working days excluding holidays based on office shift
											if ($p_day == 'Monday') {
												if ($office_shift[0]->monday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Tuesday') {
												if ($office_shift[0]->tuesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Wednesday') {
												if ($office_shift[0]->wednesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Thursday') {
												if ($office_shift[0]->thursday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Friday') {
												if ($office_shift[0]->friday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Saturday') {
												if ($office_shift[0]->saturday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Sunday') {
												if ($office_shift[0]->sunday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											}
										}

										$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
										$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);
										


										if ($sl_other_payments->cpf_applicable == 1) {
											$g_additional_wage += $epayments_amount;
											$g_shg += $epayments_amount;
											$g_sdl += $epayments_amount;
										}
										$other_payments_amount += $epayments_amount;
									}
								}
							}
						}
					} else {
						$other_payments_amount = 0;
					}
				}
				

				$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
				// if (!in_array('chk_leave_deductions', $check_payment) && in_array('chk_leave_deductions', $this->input->get('check_id'))) {
					if ($unpaid_leaves) {
						$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
					}
				// }

				// $g_ordinary_wage = $gross_pay;
				$g_shg = $gross_pay;
				$g_sdl = $gross_pay;

				$g_ordinary_wage -= $unpaid_leave_amount;


				// other benefit
				$other_benefit_mount = 0;
				if (!in_array('chk_total_employee_deduction', $check_payment) && in_array('chk_total_employee_deduction', $this->input->get('check_id'))) {
					$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
					foreach ($other_benefit_list->result() as $benefit_list) {
						$other_benefit_mount += $benefit_list->other_benefit_cost;
					}
				}


				// statutory_deductions
				$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
				$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
				$statutory_deductions_amount = 0;
				if ( !in_array('chk_total_statutory_deductions', $check_payment) || in_array('chk_total_statutory_deductions', $this->input->get('check_id'))) {
					
					if ($count_statutory_deductions > 0) {
						foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
							if ($system[0]->statutory_fixed != 'yes') {
								$sta_salary = $gross_pay;
								$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $st_amount / 2;
									} else {
										$single_sd = $st_amount;
									}
								} else {
									$single_sd = $st_amount;
								}
								$statutory_deductions_amount += $single_sd;
							} else {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
									} else {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount;
									}
								} else {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount;
								}
								$statutory_deductions_amount += $single_sd;
							}
						}
					} else {
						$statutory_deductions_amount = 0;
					}
				}

				// overtime
				$overtime_amount = 0;
				if (!in_array('chk_total_overtime', $check_payment) && in_array('chk_total_overtime', $this->input->get('check_id'))) {
					$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
					if ($overtime) {
						$ot_hrs = 0;
						$ot_mins = 0;
						foreach ($overtime as $ot) {
							$total_hours = explode(':', $ot->total_hours);
							$ot_hrs += $total_hours[0];
							$ot_mins += $total_hours[1];
						}
						if ($ot_mins > 0) {
							$ot_hrs += round($ot_mins / 60, 2);
						}

						//overtime rate
						$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
						if ($overtime_rate) {
							$rate = $overtime_rate->overtime_pay_rate;
						} else {
							$week_hours = 44;
							$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
							$rate = $rate * 1.5;
						}

						if ($ot_hrs > 0) {
							$overtime_amount = round($ot_hrs * $rate, 2);
						}
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$overtime_amount = $overtime_amount / 2;
							}
						}
						$g_ordinary_wage += $overtime_amount;
						$g_sdl += $overtime_amount;
					}
				}

				// make payment
				if ($system[0]->is_half_monthly == 1) {
					$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($r->user_id, $pay_date);
					$payment_last = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($r->user_id, $pay_date);
					if ($payment_check->num_rows() > 1) {

						//foreach($payment_last as $payment_half_last){
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
						//$mpay = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_make_payment').'"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '" data-company_id="'.$this->input->get("company_id").'"><span class="fa fas fa-money"></span></button></span>';

						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}

						$delete = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code><br>' . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $payment_last[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span><code>' . $this->lang->line('xin_title_second_half') . '</code>';
						//}
						//detail link
						$detail = '';
					} else if ($payment_check->num_rows() > 0) {

						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';

						$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
						$delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
						$detail = '';
					} else {

						$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
						$delete = '';
						//detail link
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					}
					//detail link
					//$detail = '';
				} else {
					$payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
					if ($payment_check->num_rows() > 0) {
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						if ($make_payment[0]->status == 0) {
							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
						} else if ($make_payment[0]->status == 3) {
							$status = '<span class="label label-warning">' . "Partially" . '</span>';
						}
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
					} else {

						$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '">
							<button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '">
								<span class="fa fas fa-money"></span>
							</button>
						</span>';
						$delete = '';
					}
					//detail link
					$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
				}


				// employee accommodations
				$employee_accommodations = 0;
				if (!in_array('chk_total_employee_deduction', $check_payment) && in_array('chk_total_employee_deduction', $this->input->get('check_id'))){
					$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
					foreach ($get_employee_accommodations as $get_employee_accommodation) {
						$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
						$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
						if ($period_from == $pay_date || $period_to == $pay_date) {
							if (!empty($get_employee_accommodation->rent_paid)) {
								$employee_accommodations += $get_employee_accommodation->rent_paid;
							}
						}
					}
				}

				// employee claims
				$claim_amount = 0;
				if (!in_array('chk_employee_claim', $check_payment) && in_array('chk_employee_claim', $this->input->get('check_id'))){
					$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
					foreach ($get_employee_claims->result() as $claims) {
						$date 	= 	date('m-Y', strtotime($claims->date));
						if ($date == $pay_date) {
							$claim_amount += $claims->amount;
						}
					}
				}

				$total_earning = $show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
				$total_deduction = $loan_de_amount + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
				$total_net_salary = ($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;


				// cpf calculation
				$emp_dob = $r->date_of_birth;
				$dob = new DateTime($emp_dob);

				$today = new DateTime('01-' . $pay_date);
				$age = $dob->diff($today);
				$age_year = $age->y;
				$age_month = $age->m;

				$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
				$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
				$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;

				if ($age_month > 0) {
					$age_year = $age_year + 1;
				}

				$cpf_employee 	= 	0;
				$cpf_employer	=	0;
				$total_cpf		= 	0;
				$cpf_total		=	0;
				$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
				if ($im_status) {
					$immigration_id = $im_status->immigration_id;
					if ($immigration_id == 2) {
						$issue_date = $im_status->issue_date;
						$i_date = new DateTime($issue_date);
						$today = new DateTime();
						$pr_age = $i_date->diff($today);
						$pr_age_year = $pr_age->y;
						$pr_age_month = $pr_age->m;
					}

					if ($immigration_id == 1 || $immigration_id == 2) {

						$ordinary_wage = $g_ordinary_wage;
						if ($ordinary_wage > $ordinary_wage_cap) {
							$ow = $ordinary_wage_cap;
						} else {
							$ow = $ordinary_wage;
						}

						//additional wage
						$additional_wage = $g_additional_wage;
						$aw = $g_additional_wage;
						$tw = $ow + $additional_wage;
						if ($im_status->issue_date != "") {
							if ($pr_age_year == 1) {

								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(4 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 666) {
											$total_cpf = 666;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(4 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 666) {
											$total_cpf = 666;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(3.5 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(3.5 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
							if ($pr_age_year == 2) {
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.45 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
										$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
										if ($count_total_cpf > 1776) {
											$total_cpf = 1776;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1110) {
											$cpf_employee = 1110;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(6 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.375 * ($tw - 500));
										$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
										$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
										if ($count_total_cpf > 1369) {
											$total_cpf = 1369;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 925) {
											$cpf_employee = 925;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(3.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
										if ($count_total_cpf > 814) {
											$total_cpf = 814;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(3.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
							if ($pr_age_year == 3 || $pr_age_year > 3) {
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(17 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.6 * ($tw - 500));
										$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
										$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
										if ($count_total_cpf > 2738) {
											$total_cpf = 2738;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1480) {
											$cpf_employee = 1480;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = $total_cpf - $cpf_employee;
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(15.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.51 * ($tw - 500));
										$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
										$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
										if ($count_total_cpf > 2405) {
											$total_cpf = 2405;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1258) {
											$cpf_employee = 1258;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(12 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.345 * ($tw - 500));
										$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
										$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;

										if ($count_total_cpf > 1739) {
											$total_cpf = 1739;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 851) {
											$cpf_employee = 851;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65 && $age_year <= 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;

										if ($count_total_cpf > 1221) {
											$total_cpf = 1221;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(7.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;

										if ($count_total_cpf > 925) {
											$total_cpf = 925;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
						}



						if ($immigration_id == 1) {

							if ($age_year <= 55) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(17 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.6 * ($tw - 500));
									$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
									$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
									if ($count_total_cpf > 2738) {
										$total_cpf = 2738;
									} else {
										$total_cpf = $count_total_cpf;
									}
			
									
									if ($count_cpf_employee > 1480) {
										$cpf_employee = 1480;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 55 && $age_year <= 60) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(15.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.51 * ($tw - 500));
									$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
			
									$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
									if ($count_total_cpf > 2405) {
										$total_cpf = 2405;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 1258) {
										$cpf_employee = 1258;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 60 && $age_year <= 65) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(12 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.345 * ($tw - 500));
									$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
									$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
			
									if ($count_total_cpf > 1739) {
										$total_cpf = 1739;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 851) {
										$cpf_employee = 851;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 65 && $age_year <= 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(9 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.225 * ($tw - 500));
									$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
									$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
			
									if ($count_total_cpf > 1221) {
										$total_cpf = 1221;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 555) {
										$cpf_employee = 555;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(7.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.15 * ($tw - 500));
									$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
									$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
			
									if ($count_total_cpf > 925) {
										$total_cpf = 925;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 370) {
										$cpf_employee = 370;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							}
						}
						
						if ($check) {
							$cpf_employee = $database_cpf_employee;
						}
						
						$total_net_salary = $total_net_salary - $cpf_employee;
						$cpf_total = $cpf_employee + $cpf_employer;

						$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
						$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
						$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
					}
				}


				// $shg_fund_deduction_amount = 0;
				// //Other Fund Contributions
				// $employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);
				// if ($employee_contributions && $g_shg > 0) {
				// 	$gross_s = $g_shg;
				// 	$contribution_id = $employee_contributions->contribution_id;
				// 	$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
				// 	$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
				// 	$shg_fund_name = $contribution_type[0]->contribution;
				// 	$shg_fund_deduction_amount += $contribution_amount;
				// 	$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
				// }
				// $ashg_fund_deduction_amount = 0;

				// $employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
				// if ($employee_ashg_contributions  && $g_shg > 0) {
				// 	$gross_s = $g_shg;
				// 	$contribution_id = $employee_ashg_contributions->contribution_id;
				// 	$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
				// 	$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
				// 	$ashg_fund_name = $contribution_type[0]->contribution;

				// 	$ashg_fund_deduction_amount += $contribution_amount;
				// 	$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
				// }

				// echo $total_net_salary;

				// $sdl_total_amount = 0;
				// if ($g_sdl > 1 && $g_sdl <= 800) {
				// 	$sdl_total_amount = 2;
				// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
				// 	$sdl_amount = (0.25 * $g_sdl) / 100;
				// 	$sdl_total_amount = $sdl_amount;
				// } elseif ($g_sdl > 4500) {
				// 	$sdl_total_amount = 11.25;
				// }


				$net_salary = number_format((float)$total_net_salary, 2, '.', '');
				$basic_salary = number_format((float)$basic_salary, 2, '.', '');
				if ($basic_salary == 0 || $basic_salary == '') {
					$fmpay = '';
				} else {
					$fmpay = $mpay;
				}


				$company_info = $this->Company_model->read_company_information($r->company_id);
				if (!is_null($company_info)) {
					$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
					$net_salary = $net_salary;
					//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
				} else {
					//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
					$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary, $r->user_id);

					$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary, $r->user_id);

					//$net_salary = $this->Xin_model->currency_sign($net_salary);	
					//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
					$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount, $r->user_id);
				}

				$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';

				//action link
				$act = $detail . $fmpay . $delete;
				// $act = $fmpay . $delete;

				if ($r->wages_type == 1) {
					if ($system[0]->is_half_monthly == 1) {
						$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
					} else {
						$emp_payroll_wage = $wages_type;
					}
				} else {
					$emp_payroll_wage = $wages_type;
				}

				$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
				$p = $payslips->result();
				$balance = $this->Xin_model->currency_sign($p[0]->balance_amount ?? 0, $r->user_id);
				$mode_of_payment = $r->payment_mode ?? '';

				
				 
				if($balance_amount > 0)
				 	$calculate_balance_amount = $balance_amount -  ($database_cpf_employee - $cpf_employee);
				else
					$calculate_balance_amount = 0;

				// $cpf =  $final_contribution_amount['cpf_employee'] - ($final_contribution_amount['cpf_employee'] - $cpf_employee);
				
				if($check && $check[0]->is_advance != 1){
					$net_salary = $net_salary > 0 ? round($net_salary,2) : 0;
				}else{
					$net_salary = round($net_salary - $final_contribution_amount['contribution'],2);
				}

				$data = array(
					'total_cpf_employee'	=> 	$cpf_employee,
					// 'total_cpf_employee'	=> 	$cpf,
					'total_cpf_employer'	=>	$cpf_employer,
					'cpf_total'				=> 	$cpf_total,
					'net_salary'			=>	$net_salary,
					'contribution'			=>	$final_contribution_amount['contribution'],
					'total_deduction'		=>	$total_deduction,
					'balance'				=>	round($calculate_balance_amount,2),
					'unpaid_leave_amount'	=>	$unpaid_leave_amount,
					'statutory_deductions'	=>	$statutory_deductions_amount,
					$balance_amount
				);
			}
		}

		echo json_encode($data);
		exit;
	}

	// for uncheck
	public function uncheck_payment($data)
	{
		$pay_date 	= 	$data['pay_date'];
		$user_id	=	$data['user_id'];
		$payslip 	= 	$this->Employees_model->get_single_employees_payslip($pay_date, $user_id);
		$system 	= 	$this->Xin_model->read_setting_info(1);
		$uncheck_id		=	$data['uncheck_id'] ?? [];

		$role_resources_ids = $this->Xin_model->user_role_resource();
		foreach ($payslip->result() as $r) {
			$exit_employee = $this->Employees_model->get_employee_exit($r->user_id);

			if (count($exit_employee) > 0) {
				$e_date = date('Y-m-d', strtotime($exit_employee[0]->exit_date));
				$exit_date = date('m-Y', strtotime($e_date));
				if ($exit_date >= $pay_date) {
					$emp_name = $r->first_name . ' ' . $r->last_name;
					// office shift
					$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);
	
					//overtime request
					$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $pay_date);
					$re_hrs_old_int1 = 0;
					$re_hrs_old_seconds = 0;
					$re_pcount = 0;
					foreach ($overtime_count as $overtime_hr) {
						$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
						$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
						$re_interval_late = $request_clock_in->diff($request_clock_out);
						$re_hours_r  = $re_interval_late->format('%h');
						$re_minutes_r = $re_interval_late->format('%i');
						$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';
	
						$re_str_time = $re_total_time;
	
						$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);
	
						sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$re_hrs_old_int1 += $re_hrs_old_seconds;
	
						$re_pcount = gmdate("H", $re_hrs_old_int1);
					}
	
					$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
					$hrs_old_int1 = 0;
					$pcount = 0;
					foreach ($result->result() as $hour_work) {
						$clock_in =  new DateTime($hour_work->clock_in);
						$clock_out =  new DateTime($hour_work->clock_out);
						$interval_late = $clock_in->diff($clock_out);
						$hours_r  = $interval_late->format('%h');
						$minutes_r = $interval_late->format('%i');
						$total_time = $hours_r . ":" . $minutes_r . ":" . '00';
	
						$str_time = $total_time;
	
						$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
	
						sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$hrs_old_int1 += $hrs_old_seconds;
	
						$pcount = gmdate("H", $hrs_old_int1);
					}
					$pcount = $pcount + $re_pcount;
	
					// get company
					$company = $this->Xin_model->read_company_info($r->company_id);
					if (!is_null($company)) {
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';
					}
	
					/**
					 * Local Variable
					 */
					$g_ordinary_wage = 0;
					$g_additional_wage = 0;
					$g_shg = 0;
					$g_sdl = 0;
	
					// 1: salary type
					if ($r->wages_type == 1) {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					} else if ($r->wages_type == 2) {
						$wages_type = $this->lang->line('xin_employee_daily_wages');
						if ($pcount > 0) {
							$basic_salary = $pcount * $r->basic_salary;
						} else {
							$basic_salary = $pcount;
						}
						$p_class = 'emo_hourly_pay';
						$view_p_class = 'hourlywages_template_modal';
					} else {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					}
	
					$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
					$check_payment = $check && !empty($check[0]->check_id) ? explode(',', $check[0]->check_id) : [];
	
					$advance_amount = 0;
					if ($check) {
						foreach ($check as $c) {
							if($check[0]->is_advance == 1){
								$advance_amount += $check[0]->advance_amount;
							}
						}
					}
	
					if (!in_array('chk_gross_salary', $uncheck_id)) {
						$basic_salary = 0;
					}else if($check && $check[0]->is_advance == 1){
						$basic_salary = $basic_salary - $advance_amount; 
					}
					
					// employee deduction
					$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
					$pa_month = date('m-Y', strtotime('01-' . $pay_date));
					$deduction_amount = 0;
					if (in_array('chk_total_employee_deduction', $uncheck_id)) {
						if ($employee_deduction) {
							foreach ($employee_deduction as $deduction) {
								if ($deduction->type_id == 1) {
									$deduction_amount +=  $deduction->amount;
								}
								if ($deduction->type_id == 2) {
									$from_month_year = date('m-Y', strtotime($deduction->from_date));
									$to_month_year = date('d-m-Y', strtotime($deduction->to_date));
	
	
									if ($from_month_year != "" && $to_month_year != "") {
										if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
											$deduction_amount +=  $deduction->amount;
										}
									}
								}
							}
						}
					}
	
	
	
	
					//3: Gross rate of pay (unpaid leave deduction)
					$holidays_count = 0;
					$no_of_working_days = 0;
					$month_start_date = new DateTime('01-' . $pay_date);
					$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
					$month_end_date->modify('+1 day');
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($month_start_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$period_day = $p->format('l');
						$period_date = $p->format('Y-m-d');
	
						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
						if ($is_holiday) {
							$holidays_count += 1;
						}
	
						//working days excluding holidays based on office shift
						if ($period_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_working_days += 1;
							}
						}
					}
	
					//unpaid leave
					$unpaid_leave_amount = 0;
					$leaves_taken_count = 0;
					$leave_period = array();
					$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
					// if (in_array('chk_leave_deductions', $uncheck_id)) {
						if ($unpaid_leaves) {
							foreach ($unpaid_leaves as $k => $l) {
								$pay_date_month = new DateTime('01-' . $pay_date);
								$l_from_date = new DateTime($l->from_date);
								$l_to_date = new DateTime($l->to_date);
	
								if ($l_from_date->format('m') == $l_to_date->format('m')) {
									$start_date = $l_from_date;
									$end_date = $l_to_date;
								} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
									$start_date = $l_from_date;
									$end_date = new DateTime($start_date->format('Y-m-t'));
								} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
									$start_date = $pay_date_month;
									$end_date = $l_to_date;
								}
								$end_date->modify('+1 day');
								$interval = new DateInterval('P1D');
								$period = new DatePeriod($start_date, $interval, $end_date);
								foreach ($period as $d) {
									$p_day = $d->format('l');
									if ($p_day == 'Monday') {
										if ($office_shift[0]->monday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Tuesday') {
										if ($office_shift[0]->tuesday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Wednesday') {
										if ($office_shift[0]->wednesday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Thursday') {
										if ($office_shift[0]->thursday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Friday') {
										if ($office_shift[0]->friday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Saturday') {
										if ($office_shift[0]->saturday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Sunday') {
										if ($office_shift[0]->sunday_in_time != '') {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									}
								}
								$leave_period[$k]['is_half'] = $l->is_half_day;
							}
						}
					// }
						
	
					// if joining date and pay date same then this logic work
					$month_date_join = date('m-Y', strtotime($r->date_of_joining));
					$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
					$month_last_date = date('m-Y', strtotime($lastday));
	
					$first_date =  new DateTime($r->date_of_joining);
					$no_of_days_worked = 0;
					$same_month_holidays_count = 0;
					if ($month_date_join == $pay_date) {
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($first_date, $interval, $month_end_date);
						foreach ($period as $p) {
							$p_day = $p->format('l');
							$m_p_date = $p->format('Y-m-d');
	
							//holidays in a month
							$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
							if ($is_holiday) {
								$same_month_holidays_count += 1;
							}
	
							//working days excluding holidays based on office shift
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$no_of_days_worked += 1;
								}
							}
						}
	
						// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
						$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
					} else {
						// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
						$no_of_days_worked = $no_of_working_days -  $holidays_count;
					}
					// echo $holidays_count;
	
					if ($month_date_join == $pay_date){
						$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
					}else{
						$show_main_salary = $basic_salary;
					}
				
					$g_ordinary_wage += $show_main_salary;
					$g_shg += $show_main_salary;
					$g_sdl += $show_main_salary;
				
					$g_ordinary_wage -= $deduction_amount;
					$g_shg -= $deduction_amount;
					$g_sdl -= $deduction_amount;
	
	
					
					// 3: all loan/deductions
					$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
					$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
					$loan_de_amount = 0;
					if (in_array('chk_loan_de_amount', $uncheck_id)) {
						if ($count_loan_deduction > 0) {
							foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
									} else {
										$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
									}
								} else {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
								}
								$loan_de_amount += $er_loan;
							}
						} else {
							$loan_de_amount = 0;
						}
						$loan_de_amount = round($loan_de_amount, 2);
					}
	
					// 2: all allowances
					$allowance_amount = 0;
					$gross_allowance_amount = 0;
					if (in_array('chk_total_allowances', $uncheck_id)) {
						$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
						if ($salary_allowances) {
							foreach ($salary_allowances as $sa) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$eallowance_amount = $sa->allowance_amount / 2;
									} else {
										$eallowance_amount = $sa->allowance_amount;
									}
								} else {
									$eallowance_amount = $sa->allowance_amount;
								}
	
	
								if (!empty($sa->salary_month)) {
									$g_additional_wage += $eallowance_amount;
								} else {
									if ($month_date_join == $pay_date){
										$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
									}
									$g_ordinary_wage += $eallowance_amount;
									if ($sa->id == 2) {
										$gross_allowance_amount = $eallowance_amount;
									}
								}
	
								if ($sa->sdl == 1) {
									$g_sdl += $eallowance_amount;
								}
								if ($sa->shg == 1) {
									$g_shg += $eallowance_amount;
								}
	
								$allowance_amount += $eallowance_amount;
							}
						}
						$gross_allowance_amount = $allowance_amount;
					}
	
	
					// commissions
					$commissions_amount = 0;
					if (in_array('chk_total_commissions', $uncheck_id)) {
						$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
						if ($commissions) {
							foreach ($commissions as $c) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$ecommissions_amount = $c->commission_amount / 2;
									} else {
										$ecommissions_amount = $c->commission_amount;
									}
								} else {
									$ecommissions_amount = $c->commission_amount;
								}
	
								if ($c->commission_type == 9) {
									$g_ordinary_wage += $ecommissions_amount;
								} elseif ($c->commission_type == 10) {
									$g_additional_wage += $ecommissions_amount;
								}
	
								if ($c->sdl == 1) {
									$g_sdl += $ecommissions_amount;
								}
								if ($c->shg == 1) {
									$g_shg += $ecommissions_amount;
								}
	
								$commissions_amount += $ecommissions_amount;
							}
						}
					}
	
					//share options
					$share_options_amount = 0;
					if (in_array('chk_total_share', $uncheck_id)) {
						$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
						if ($share_options) {
							$eebr_amount = 0;
							$eris_amount = 0;
							foreach ($share_options as $s) {
								$scheme = $s->so_scheme;
								if ($scheme == 1) {
									$price_doe = $s->price_date_of_excercise;
									$price_ex = $s->excercise_price;
									$no_shares = $s->no_of_shares;
									$amount = ($price_doe - $price_ex) * $no_shares;
									$eebr_amount += $amount;
								} else {
									$price_doe = $s->price_date_of_excercise;
									$price_dog = $s->price_date_of_grant;
									$price_ex = $s->excercise_price;
									$no_shares = $s->no_of_shares;
	
									$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
									$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
									$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
								}
							}
							$share_options_amount = round($eebr_amount + $eris_amount, 2);
							$g_additional_wage += $share_options_amount;
							$g_sdl += $share_options_amount;
							$g_shg += $share_options_amount;
						}
					}
	
	
					// otherpayments
					$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
					$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
					$other_payments_amount = 0;
					if (in_array('chk_total_other_payments', $uncheck_id)) {
						if ($count_other_payments > 0) {
							foreach ($other_payments->result() as $sl_other_payments) {
								if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
									if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$epayments_amount = $sl_other_payments->payments_amount / 2;
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
	
										if ($sl_other_payments->cpf_applicable == 1) {
											$g_additional_wage += $epayments_amount;
											$g_shg += $epayments_amount;
											$g_sdl += $epayments_amount;
										}
	
										$other_payments_amount += $epayments_amount;
									}
								} else {
									$first_date = new DateTime($sl_other_payments->date);
									if ($first_date->format('m-Y') == $pay_date) {
										$first_date =  new DateTime($sl_other_payments->date);
									} else {
										$first_date = new DateTime('01-' . $pay_date);
									}
	
									$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));
	
									if (!empty($sl_other_payments->end_date)) {
										$last_date = new DateTime($sl_other_payments->end_date);
										if ($last_date->format('m-Y') == $pay_date) {
											$last_date = new DateTime($sl_other_payments->end_date);
										}else if($last_date->format('m-Y') >= $pay_date){
												$last_date = $month_end_date_for_other;
										} else {
											$last_date = '';
										}
									} else {
										$last_date = $month_end_date_for_other;
									}
									if(!empty($last_date)){
										$last_date->modify('+1 day');
										$final_last_day = new DateTime($last_date->format('d-m-Y'));
										if ($final_last_day->format('m-Y') >= $pay_date) {
											if ($system[0]->is_half_monthly == 1) {
												if ($system[0]->half_deduct_month == 2) {
													$epayments_amount = $sl_other_payments->payments_amount / 2;
												} else {
													$epayments_amount = $sl_other_payments->payments_amount;
												}
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
	
	
											// it for no of working day
											$no_of_days_worked_for_other_payment = 0;
											$same_month_holidays_count_for_other_payment = 0;
											$interval = new DateInterval('P1D');
											$period = new DatePeriod($first_date, $interval, $last_date);
											foreach ($period as $p) {
												$p_day = $p->format('l');
												$p_date = $p->format('Y-m-d');
	
												//holidays in a month
	
												$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
												if ($is_holiday) {
													$same_month_holidays_count_for_other_payment += 1;
												}
	
												//working days excluding holidays based on office shift
												if ($p_day == 'Monday') {
													if ($office_shift[0]->monday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Tuesday') {
													if ($office_shift[0]->tuesday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Wednesday') {
													if ($office_shift[0]->wednesday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Thursday') {
													if ($office_shift[0]->thursday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Friday') {
													if ($office_shift[0]->friday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Saturday') {
													if ($office_shift[0]->saturday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Sunday') {
													if ($office_shift[0]->sunday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												}
											}
	
											$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
											$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);
											
	
	
											if ($sl_other_payments->cpf_applicable == 1) {
												$g_additional_wage += $epayments_amount;
												$g_shg += $epayments_amount;
												$g_sdl += $epayments_amount;
											}
											$other_payments_amount += $epayments_amount;
										}
									}
								}
							}
						} else {
							$other_payments_amount = 0;
						}
					}
	
					
					$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
					// if (in_array('chk_leave_deductions', $uncheck_id)) {
						if ($unpaid_leaves) {
							$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
					   }
					// }
	
					
				
					// $g_ordinary_wage = $gross_pay;
					$g_shg = $gross_pay;
					$g_sdl = $gross_pay;
	
					$g_ordinary_wage -= $unpaid_leave_amount;
	
					
				
					
					// other benefit
					$other_benefit_mount = 0;
					if (in_array('chk_total_employee_deduction', $uncheck_id)) {
						$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
						foreach ($other_benefit_list->result() as $benefit_list) {
							$other_benefit_mount += $benefit_list->other_benefit_cost;
						}
					}
	
	
					// statutory_deductions
					$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
					$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
					$statutory_deductions_amount = 0;
					// if (in_array('chk_total_statutory_deductions', $uncheck_id) && (!in_array('chk_gross_salary', $uncheck_id) || !in_array('chk_total_other_payments', $uncheck_id) || !in_array('chk_total_allowances', $uncheck_id))) {
					if ((in_array('chk_total_statutory_deductions', $uncheck_id) && (!in_array('chk_total_allowances', $uncheck_id) && !in_array('chk_total_other_payments', $uncheck_id) && !in_array('chk_gross_salary', $uncheck_id)) ) 
					|| 
					(!in_array('chk_total_statutory_deductions', $uncheck_id) && (in_array('chk_total_allowances', $uncheck_id) || in_array('chk_total_other_payments', $uncheck_id) || in_array('chk_gross_salary', $uncheck_id))  )) {
						if ($count_statutory_deductions > 0) {
							foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
								if ($system[0]->statutory_fixed != 'yes') {
									$sta_salary = $gross_pay;
									$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$single_sd = $st_amount / 2;
										} else {
											$single_sd = $st_amount;
										}
									} else {
										$single_sd = $st_amount;
									}
									$statutory_deductions_amount += $single_sd;
								} else {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
										} else {
											$single_sd = $sl_salary_statutory_deductions->deduction_amount;
										}
									} else {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount;
									}
									$statutory_deductions_amount += $single_sd;
								}
							}
						} else {
							$statutory_deductions_amount = 0;
						}
					}
					
					// overtime
					$overtime_amount = 0;
					if (in_array('chk_total_overtime', $uncheck_id)) {
						$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
						if ($overtime) {
							$ot_hrs = 0;
							$ot_mins = 0;
							foreach ($overtime as $ot) {
								$total_hours = explode(':', $ot->total_hours);
								$ot_hrs += $total_hours[0];
								$ot_mins += $total_hours[1];
							}
							if ($ot_mins > 0) {
								$ot_hrs += round($ot_mins / 60, 2);
							}
	
							//overtime rate
							$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
							if ($overtime_rate) {
								$rate = $overtime_rate->overtime_pay_rate;
							} else {
								$week_hours = 44;
								$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
								$rate = $rate * 1.5;
							}
	
							if ($ot_hrs > 0) {
								$overtime_amount = round($ot_hrs * $rate, 2);
							}
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$overtime_amount = $overtime_amount / 2;
								}
							}
							$g_ordinary_wage += $overtime_amount;
							$g_sdl += $overtime_amount;
						}
					}
	
					// make payment
					if ($system[0]->is_half_monthly == 1) {
						$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($r->user_id, $pay_date);
						$payment_last = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($r->user_id, $pay_date);
						if ($payment_check->num_rows() > 1) {
	
							//foreach($payment_last as $payment_half_last){
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
							//$mpay = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_make_payment').'"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '" data-company_id="'.$this->input->get("company_id").'"><span class="fa fas fa-money"></span></button></span>';
	
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}
	
							$delete = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code><br>' . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $payment_last[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span><code>' . $this->lang->line('xin_title_second_half') . '</code>';
							//}
							//detail link
							$detail = '';
						} else if ($payment_check->num_rows() > 0) {
	
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
	
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
	
							$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}
							$delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
							$detail = '';
						} else {
	
							$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
							$delete = '';
							//detail link
							$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						}
						//detail link
						//$detail = '';
					} else {
						$payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
						if ($payment_check->num_rows() > 0) {
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
							if ($make_payment[0]->status == 0) {
								$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
							} else if ($make_payment[0]->status == 3) {
								$status = '<span class="label label-warning">' . "Partially" . '</span>';
							}
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}
						} else {
	
							$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '">
								<button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '">
									<span class="fa fas fa-money"></span>
								</button>
							</span>';
							$delete = '';
						}
						//detail link
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					}
	
	
					// employee accommodations
					$employee_accommodations = 0;
					if (in_array('chk_total_employee_deduction', $uncheck_id)){
						$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
						foreach ($get_employee_accommodations as $get_employee_accommodation) {
							$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
							$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
							if ($period_from == $pay_date || $period_to == $pay_date) {
								if (!empty($get_employee_accommodation->rent_paid)) {
									$employee_accommodations += $get_employee_accommodation->rent_paid;
								}
							}
						}
					}
	
	
					// employee claims
					$claim_amount = 0;
					if (in_array('chk_employee_claim', $uncheck_id)){
						$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
						foreach ($get_employee_claims->result() as $claims) {
							$date 	= 	date('m-Y', strtotime($claims->date));
							if ($date == $pay_date) {
								$claim_amount += $claims->amount;
							}
						}
					}
					
	
					$total_earning 		= 	$show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
					$total_deduction 	= 	$loan_de_amount + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
					$total_net_salary 	= 	($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;
	
					
					// cpf calculation
					$emp_dob = $r->date_of_birth;
					$dob = new DateTime($emp_dob);
	
					$today = new DateTime('01-' . $pay_date);
					$age = $dob->diff($today);
					$age_year = $age->y;
					$age_month = $age->m;
	
					$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
					$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
					$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;
	
					if ($age_month > 0) {
						$age_year = $age_year + 1;
					}
	
					// $cpf_employee 	= 	0;
					// $cpf_employer	=	0;
	
					// $im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
					// if ($im_status) {
					// 	$immigration_id = $im_status->immigration_id;
					// 	if ($immigration_id == 2) {
					// 		$issue_date = $im_status->issue_date;
					// 		$i_date = new DateTime($issue_date);
					// 		$today = new DateTime();
					// 		$pr_age = $i_date->diff($today);
					// 		$pr_age_year = $pr_age->y;
					// 		$pr_age_month = $pr_age->m;
					// 	}
	
					// 	if ($immigration_id == 1 || $immigration_id == 2) {
	
					// 		$ordinary_wage = $g_ordinary_wage;
					// 		if ($ordinary_wage > $ordinary_wage_cap) {
					// 			$ow = $ordinary_wage_cap;
					// 		} else {
					// 			$ow = $ordinary_wage;
					// 		}
	
					// 		//additional wage
					// 		$additional_wage = $g_additional_wage;
					// 		$aw = $g_additional_wage;
					// 		$tw = $ow + $additional_wage;
					// 		if ($im_status->issue_date != "") {
					// 			if ($pr_age_year == 1) {
	
					// 				if ($age_year <= 55) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw < 500) {
					// 						$cpf_employer = round(4 / 100 * $tw);
					// 						$cpf_employee = 0;
					// 					} else if ($tw > 500 && $tw < 750) {
					// 						$cpf_employee = floor(0.15 * ($tw - 500));
					// 						$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
					// 						$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
					// 						if ($count_cpf_employee > 370) {
					// 							$cpf_employee = 370;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						if ($count_total_cpf > 666) {
					// 							$total_cpf = 666;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				} else if ($age_year > 55 && $age_year <= 60) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw < 500) {
					// 						$cpf_employer = round(4 / 100 * $tw);
					// 						$cpf_employee = 0;
					// 					} else if ($tw > 500 && $tw < 750) {
					// 						$cpf_employee = floor(0.15 * ($tw - 500));
					// 						$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
					// 						$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
					// 						if ($count_cpf_employee > 370) {
					// 							$cpf_employee = 370;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						if ($count_total_cpf > 666) {
					// 							$total_cpf = 666;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				} else if ($age_year > 60 && $age_year <= 65) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw < 500) {
					// 						$cpf_employer = round(3.5 / 100 * $tw);
					// 						$cpf_employee = 0;
					// 					} else if ($tw > 500 && $tw < 750) {
					// 						$cpf_employee = floor(0.15 * ($tw - 500));
					// 						$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
					// 						$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
					// 						if ($count_cpf_employee > 370) {
					// 							$cpf_employee = 370;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						if ($count_total_cpf > 629) {
					// 							$total_cpf = 629;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				} else if ($age_year > 65) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw < 500) {
					// 						$cpf_employer = round(3.5 / 100 * $tw);
					// 						$cpf_employee = 0;
					// 					} else if ($tw > 500 && $tw < 750) {
					// 						$cpf_employee = floor(0.15 * ($tw - 500));
					// 						$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
					// 						$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
					// 						if ($count_cpf_employee > 370) {
					// 							$cpf_employee = 370;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						if ($count_total_cpf > 629) {
					// 							$total_cpf = 629;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				}
					// 			}
					// 			if ($pr_age_year == 2) {
					// 				if ($age_year <= 55) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw <= 500) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = round(9 / 100 * $tw);
					// 					} else if ($tw > 500 && $tw <= 750) {
					// 						$cpf_employee = floor(0.45 * ($tw - 500));
					// 						$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
					// 						$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
					// 						if ($count_total_cpf > 1776) {
					// 							$total_cpf = 1776;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						if ($count_cpf_employee > 1110) {
					// 							$cpf_employee = 1110;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				} else if ($age_year > 55 && $age_year <= 60) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw <= 500) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = round(6 / 100 * $tw);
					// 					} else if ($tw > 500 && $tw <= 750) {
					// 						$cpf_employee = floor(0.375 * ($tw - 500));
					// 						$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
					// 						$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
					// 						if ($count_total_cpf > 1369) {
					// 							$total_cpf = 1369;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						if ($count_cpf_employee > 925) {
					// 							$cpf_employee = 925;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				} else if ($age_year > 60 && $age_year <= 65) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw <= 500) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = round(3.5 / 100 * $tw);
					// 					} else if ($tw > 500 && $tw <= 750) {
					// 						$cpf_employee = floor(0.225 * ($tw - 500));
					// 						$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
					// 						$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
					// 						if ($count_total_cpf > 814) {
					// 							$total_cpf = 814;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						if ($count_cpf_employee > 555) {
					// 							$cpf_employee = 555;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				} else if ($age_year > 65) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw <= 500) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = round(3.5 / 100 * $tw);
					// 					} else if ($tw > 500 && $tw <= 750) {
					// 						$cpf_employee = floor(0.15 * ($tw - 500));
					// 						$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
					// 						$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
					// 						if ($count_total_cpf > 629) {
					// 							$total_cpf = 629;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						if ($count_cpf_employee > 370) {
					// 							$cpf_employee = 370;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				}
					// 			}
					// 			if ($pr_age_year == 3 || $pr_age_year > 3) {
					// 				if ($age_year <= 55) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw <= 500) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = round(17 / 100 * $tw);
					// 					} else if ($tw > 500 && $tw <= 750) {
					// 						$cpf_employee = floor(0.6 * ($tw - 500));
					// 						$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
					// 						$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
					// 						if ($count_total_cpf > 2738) {
					// 							$total_cpf = 2738;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						if ($count_cpf_employee > 1480) {
					// 							$cpf_employee = 1480;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						$cpf_employer = $total_cpf - $cpf_employee;
					// 					}
					// 				} else if ($age_year > 55 && $age_year <= 60) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw <= 500) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = round(15.5 / 100 * $tw);
					// 					} else if ($tw > 500 && $tw <= 750) {
					// 						$cpf_employee = floor(0.51 * ($tw - 500));
					// 						$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
					// 						$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
					// 						if ($count_total_cpf > 2405) {
					// 							$total_cpf = 2405;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						if ($count_cpf_employee > 1258) {
					// 							$cpf_employee = 1258;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				} else if ($age_year > 60 && $age_year <= 65) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw <= 500) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = round(12 / 100 * $tw);
					// 					} else if ($tw > 500 && $tw <= 750) {
					// 						$cpf_employee = floor(0.345 * ($tw - 500));
					// 						$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
					// 						$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
	
					// 						if ($count_total_cpf > 1739) {
					// 							$total_cpf = 1739;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						if ($count_cpf_employee > 851) {
					// 							$cpf_employee = 851;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				} else if ($age_year > 65 && $age_year <= 70) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw <= 500) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = round(9 / 100 * $tw);
					// 					} else if ($tw > 500 && $tw <= 750) {
					// 						$cpf_employee = floor(0.225 * ($tw - 500));
					// 						$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
					// 						$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
	
					// 						if ($count_total_cpf > 1221) {
					// 							$total_cpf = 1221;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						if ($count_cpf_employee > 555) {
					// 							$cpf_employee = 555;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				} else if ($age_year > 70) {
					// 					if ($tw < 50) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = 0;
					// 					} else if ($tw > 50 && $tw <= 500) {
					// 						$cpf_employee = 0;
					// 						$cpf_employer = round(7.5 / 100 * $tw);
					// 					} else if ($tw > 500 && $tw <= 750) {
					// 						$cpf_employee = floor(0.15 * ($tw - 500));
					// 						$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					} else if ($tw > 750) {
					// 						$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
					// 						$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
	
					// 						if ($count_total_cpf > 925) {
					// 							$total_cpf = 925;
					// 						} else {
					// 							$total_cpf = $count_total_cpf;
					// 						}
					// 						if ($count_cpf_employee > 370) {
					// 							$cpf_employee = 370;
					// 						} else {
					// 							$cpf_employee = floor($count_cpf_employee);
					// 						}
					// 						$cpf_employer = round($total_cpf - $cpf_employee);
					// 					}
					// 				}
					// 			}
					// 		}
	
	
	
					// 		if ($immigration_id == 1) {
	
					// 			if ($age_year <= 55) {
					// 				if ($tw < 50) {
					// 					$cpf_employee = 0;
					// 					$cpf_employer = 0;
					// 				} else if ($tw > 50 && $tw <= 500) {
					// 					$cpf_employee = 0;
					// 					$cpf_employer = round(17 / 100 * $tw);
					// 				} else if ($tw > 500 && $tw <= 750) {
					// 					$cpf_employee = floor(0.6 * ($tw - 500));
					// 					$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
					// 					$cpf_employer = round($total_cpf - $cpf_employee);
					// 				} else if ($tw > 750) {
					// 					$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
					// 					$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
					// 					if ($count_total_cpf > 2738) {
					// 						$count_total_cpf = 2738;
					// 					} else {
					// 						$total_cpf = $count_total_cpf;
					// 					}
	
	
					// 					if ($count_cpf_employee > 1480) {
					// 						$cpf_employee = 1480;
					// 					} else {
					// 						$cpf_employee = floor($count_cpf_employee);
					// 					}
					// 					$cpf_employer = round($total_cpf - $cpf_employee);
					// 				}
					// 			} else if ($age_year > 55 && $age_year <= 60) {
					// 				if ($tw < 50) {
					// 					$cpf_employee = 0;
					// 					$cpf_employer = 0;
					// 				} else if ($tw > 50 && $tw <= 500) {
					// 					$cpf_employee = 0;
					// 					$cpf_employer = round(15.5 / 100 * $tw);
					// 				} else if ($tw > 500 && $tw <= 750) {
					// 					$cpf_employee = floor(0.51 * ($tw - 500));
					// 					$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
					// 					$cpf_employer = round($total_cpf - $cpf_employee);
					// 				} else if ($tw > 750) {
					// 					$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
	
					// 					$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
					// 					if ($count_total_cpf > 2405) {
					// 						$count_total_cpf = 2405;
					// 					} else {
					// 						$total_cpf = $count_total_cpf;
					// 					}
					// 					if ($count_cpf_employee > 1258) {
					// 						$cpf_employee = 1258;
					// 					} else {
					// 						$cpf_employee = floor($count_cpf_employee);
					// 					}
					// 					$cpf_employer = round($total_cpf - $cpf_employee);
					// 				}
					// 			} else if ($age_year > 60 && $age_year <= 65) {
					// 				if ($tw < 50) {
					// 					$cpf_employee = 0;
					// 					$cpf_employer = 0;
					// 				} else if ($tw > 50 && $tw <= 500) {
					// 					$cpf_employee = 0;
					// 					$cpf_employer = round(12 / 100 * $tw);
					// 				} else if ($tw > 500 && $tw <= 750) {
					// 					$cpf_employee = floor(0.345 * ($tw - 500));
					// 					$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
					// 					$cpf_employer = round($total_cpf - $cpf_employee);
					// 				} else if ($tw > 750) {
					// 					$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
					// 					$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
	
					// 					if ($count_total_cpf > 1739) {
					// 						$total_cpf = 1739;
					// 					} else {
					// 						$total_cpf = $count_total_cpf;
					// 					}
					// 					if ($count_cpf_employee > 851) {
					// 						$cpf_employee = 851;
					// 					} else {
					// 						$cpf_employee = floor($count_cpf_employee);
					// 					}
					// 					$cpf_employer = round($total_cpf - $cpf_employee);
					// 				}
					// 			} else if ($age_year > 65 && $age_year <= 70) {
					// 				if ($tw < 50) {
					// 					$cpf_employee = 0;
					// 					$cpf_employer = 0;
					// 				} else if ($tw > 50 && $tw <= 500) {
					// 					$cpf_employee = 0;
					// 					$cpf_employer = round(9 / 100 * $tw);
					// 				} else if ($tw > 500 && $tw <= 750) {
					// 					$cpf_employee = floor(0.225 * ($tw - 500));
					// 					$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
					// 					$cpf_employer = round($total_cpf - $cpf_employee);
					// 				} else if ($tw > 750) {
					// 					$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
					// 					$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
	
					// 					if ($count_total_cpf > 1221) {
					// 						$total_cpf = 1221;
					// 					} else {
					// 						$total_cpf = $count_total_cpf;
					// 					}
					// 					if ($count_cpf_employee > 555) {
					// 						$cpf_employee = 555;
					// 					} else {
					// 						$cpf_employee = floor($count_cpf_employee);
					// 					}
					// 					$cpf_employer = round($total_cpf - $cpf_employee);
					// 				}
					// 			} else if ($age_year > 70) {
					// 				if ($tw < 50) {
					// 					$cpf_employee = 0;
					// 					$cpf_employer = 0;
					// 				} else if ($tw > 50 && $tw <= 500) {
					// 					$cpf_employee = 0;
					// 					$cpf_employer = round(7.5 / 100 * $tw);
					// 				} else if ($tw > 500 && $tw <= 750) {
					// 					$cpf_employee = floor(0.15 * ($tw - 500));
					// 					$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
					// 					$cpf_employer = round($total_cpf - $cpf_employee);
					// 				} else if ($tw > 750) {
					// 					$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
					// 					$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
	
					// 					if ($count_total_cpf > 925) {
					// 						$total_cpf = 925;
					// 					} else {
					// 						$total_cpf = $count_total_cpf;
					// 					}
					// 					if ($count_cpf_employee > 370) {
					// 						$cpf_employee = 370;
					// 					} else {
					// 						$cpf_employee = floor($count_cpf_employee);
					// 					}
					// 					$cpf_employer = round($total_cpf - $cpf_employee);
					// 				}
					// 			}
					// 		}
	
					// 		$total_net_salary = $total_net_salary - $cpf_employee;
					// 		$cpf_total = $cpf_employee + $cpf_employer;
	
					// 		$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
					// 		$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
					// 		$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
					// 	}
					// }
	
	
					// $shg_fund_deduction_amount = 0;
					// //Other Fund Contributions
					// $employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);
					// if ($employee_contributions && $g_shg > 0) {
					// 	$gross_s = $g_shg;
					// 	$contribution_id = $employee_contributions->contribution_id;
					// 	$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					// 	$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					// 	$shg_fund_name = $contribution_type[0]->contribution;
					// 	$shg_fund_deduction_amount += $contribution_amount;
					// 	$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
					// }
					// $ashg_fund_deduction_amount = 0;
					// $employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
					// if ($employee_ashg_contributions  && $g_shg > 0) {
					// 	$gross_s = $g_shg;
					// 	$contribution_id = $employee_ashg_contributions->contribution_id;
					// 	$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					// 	$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					// 	$ashg_fund_name = $contribution_type[0]->contribution;
	
					// 	$ashg_fund_deduction_amount += $contribution_amount;
					// 	$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
					// }
	
					// echo $total_net_salary;
	
					// $sdl_total_amount = 0;
					// if ($g_sdl > 1 && $g_sdl <= 800) {
					// 	$sdl_total_amount = 2;
					// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
					// 	$sdl_amount = (0.25 * $g_sdl) / 100;
					// 	$sdl_total_amount = $sdl_amount;
					// } elseif ($g_sdl > 4500) {
					// 	$sdl_total_amount = 11.25;
					// }
	
	
					$net_salary = number_format((float)$total_net_salary, 2, '.', '');
					$basic_salary = number_format((float)$basic_salary, 2, '.', '');
					// if ($basic_salary == 0 || $basic_salary == '') {
					// 	$fmpay = '';
					// } else {
					// 	$fmpay = $mpay;
					// }
	
	
					// $company_info = $this->Company_model->read_company_information($r->company_id);
					// if (!is_null($company_info)) {
					// 	$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
					// 	$net_salary = $net_salary;
					// 	//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
					// } else {
					// 	//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
					// 	$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary, $r->user_id);
	
					// 	$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary, $r->user_id);
	
					// 	//$net_salary = $this->Xin_model->currency_sign($net_salary);	
					// 	//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
					// 	$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount, $r->user_id);
					// }
	
					// $iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';
	
					//action link
					// $act = $detail . $fmpay . $delete;
					// $act = $fmpay . $delete;
	
					if ($r->wages_type == 1) {
						if ($system[0]->is_half_monthly == 1) {
							$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
						} else {
							$emp_payroll_wage = $wages_type;
						}
					} else {
						$emp_payroll_wage = $wages_type;
					}
	
					$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
					$p = $payslips->result();
					$balance = $this->Xin_model->currency_sign($p[0]->balance_amount ?? 0, $r->user_id);
					$mode_of_payment = $r->payment_mode ?? '';
	
					
					
					return $net_salary;
					// $data = array(
					// 	$act,
					// 	$iemp_name,
					// 	$emp_payroll_wage,
					// 	$basic_salary,
					// 	'total_cpf_employee'	=> 	$cpf_employee,
					// 	'total_cpf_employer'	=>	$cpf_employer,
					// 	'cpf_total'				=> 	$cpf_total,
					// 	'net_salary'			=>	$net_salary,
					// 	'contribution'			=>	$contribution,
					// 	'total_deduction'		=>	$total_deduction,
					// 	$balance,
					// 	$mode_of_payment,
					// 	$status
					// );
				}
			} else {
				$emp_name = $r->first_name . ' ' . $r->last_name;
				// office shift
				$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);

				//overtime request
				$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $pay_date);
				$re_hrs_old_int1 = 0;
				$re_hrs_old_seconds = 0;
				$re_pcount = 0;
				foreach ($overtime_count as $overtime_hr) {
					$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
					$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
					$re_interval_late = $request_clock_in->diff($request_clock_out);
					$re_hours_r  = $re_interval_late->format('%h');
					$re_minutes_r = $re_interval_late->format('%i');
					$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';

					$re_str_time = $re_total_time;

					$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);

					sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$re_hrs_old_int1 += $re_hrs_old_seconds;

					$re_pcount = gmdate("H", $re_hrs_old_int1);
				}

				$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
				$hrs_old_int1 = 0;
				$pcount = 0;
				foreach ($result->result() as $hour_work) {
					$clock_in =  new DateTime($hour_work->clock_in);
					$clock_out =  new DateTime($hour_work->clock_out);
					$interval_late = $clock_in->diff($clock_out);
					$hours_r  = $interval_late->format('%h');
					$minutes_r = $interval_late->format('%i');
					$total_time = $hours_r . ":" . $minutes_r . ":" . '00';

					$str_time = $total_time;

					$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

					sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$hrs_old_int1 += $hrs_old_seconds;

					$pcount = gmdate("H", $hrs_old_int1);
				}
				$pcount = $pcount + $re_pcount;

				// get company
				$company = $this->Xin_model->read_company_info($r->company_id);
				if (!is_null($company)) {
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';
				}

				/**
				 * Local Variable
				 */
				$g_ordinary_wage = 0;
				$g_additional_wage = 0;
				$g_shg = 0;
				$g_sdl = 0;

				// 1: salary type
				if ($r->wages_type == 1) {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				} else if ($r->wages_type == 2) {
					$wages_type = $this->lang->line('xin_employee_daily_wages');
					if ($pcount > 0) {
						$basic_salary = $pcount * $r->basic_salary;
					} else {
						$basic_salary = $pcount;
					}
					$p_class = 'emo_hourly_pay';
					$view_p_class = 'hourlywages_template_modal';
				} else {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				}

				$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
				$check_payment = $check && !empty($check[0]->check_id) ? explode(',', $check[0]->check_id) : [];

				$advance_amount = 0;
				if ($check) {
					foreach ($check as $c) {
						if($check[0]->is_advance == 1){
							$advance_amount += $check[0]->advance_amount;
						}
					}
				}

				if (!in_array('chk_gross_salary', $uncheck_id)) {
					$basic_salary = 0;
				}else if($check && $check[0]->is_advance == 1){
					$basic_salary = $basic_salary - $advance_amount; 
				}
				
				// employee deduction
				$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
				$pa_month = date('m-Y', strtotime('01-' . $pay_date));
				$deduction_amount = 0;
				if (in_array('chk_total_employee_deduction', $uncheck_id)) {
					if ($employee_deduction) {
						foreach ($employee_deduction as $deduction) {
							if ($deduction->type_id == 1) {
								$deduction_amount +=  $deduction->amount;
							}
							if ($deduction->type_id == 2) {
								$from_month_year = date('m-Y', strtotime($deduction->from_date));
								$to_month_year = date('d-m-Y', strtotime($deduction->to_date));


								if ($from_month_year != "" && $to_month_year != "") {
									if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
										$deduction_amount +=  $deduction->amount;
									}
								}
							}
						}
					}
				}




				//3: Gross rate of pay (unpaid leave deduction)
				$holidays_count = 0;
				$no_of_working_days = 0;
				$month_start_date = new DateTime('01-' . $pay_date);
				$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
				$month_end_date->modify('+1 day');
				$interval = new DateInterval('P1D');
				$period = new DatePeriod($month_start_date, $interval, $month_end_date);
				foreach ($period as $p) {
					$period_day = $p->format('l');
					$period_date = $p->format('Y-m-d');

					//holidays in a month
					$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
					if ($is_holiday) {
						$holidays_count += 1;
					}

					//working days excluding holidays based on office shift
					if ($period_day == 'Monday') {
						if ($office_shift[0]->monday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Tuesday') {
						if ($office_shift[0]->tuesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Wednesday') {
						if ($office_shift[0]->wednesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Thursday') {
						if ($office_shift[0]->thursday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Friday') {
						if ($office_shift[0]->friday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Saturday') {
						if ($office_shift[0]->saturday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Sunday') {
						if ($office_shift[0]->sunday_in_time != '') {
							$no_of_working_days += 1;
						}
					}
				}

				//unpaid leave
				$unpaid_leave_amount = 0;
				$leaves_taken_count = 0;
				$leave_period = array();
				$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
				// if (in_array('chk_leave_deductions', $uncheck_id)) {
					if ($unpaid_leaves) {
						foreach ($unpaid_leaves as $k => $l) {
							$pay_date_month = new DateTime('01-' . $pay_date);
							$l_from_date = new DateTime($l->from_date);
							$l_to_date = new DateTime($l->to_date);

							if ($l_from_date->format('m') == $l_to_date->format('m')) {
								$start_date = $l_from_date;
								$end_date = $l_to_date;
							} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $l_from_date;
								$end_date = new DateTime($start_date->format('Y-m-t'));
							} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $pay_date_month;
								$end_date = $l_to_date;
							}
							$end_date->modify('+1 day');
							$interval = new DateInterval('P1D');
							$period = new DatePeriod($start_date, $interval, $end_date);
							foreach ($period as $d) {
								$p_day = $d->format('l');
								if ($p_day == 'Monday') {
									if ($office_shift[0]->monday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Tuesday') {
									if ($office_shift[0]->tuesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Wednesday') {
									if ($office_shift[0]->wednesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Thursday') {
									if ($office_shift[0]->thursday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Friday') {
									if ($office_shift[0]->friday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Saturday') {
									if ($office_shift[0]->saturday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Sunday') {
									if ($office_shift[0]->sunday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								}
							}
							$leave_period[$k]['is_half'] = $l->is_half_day;
						}
					}
				// }
					

				// if joining date and pay date same then this logic work
				$month_date_join = date('m-Y', strtotime($r->date_of_joining));
				$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
				$month_last_date = date('m-Y', strtotime($lastday));

				$first_date =  new DateTime($r->date_of_joining);
				$no_of_days_worked = 0;
				$same_month_holidays_count = 0;
				if ($month_date_join == $pay_date) {
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($first_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$p_day = $p->format('l');
						$m_p_date = $p->format('Y-m-d');

						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
						if ($is_holiday) {
							$same_month_holidays_count += 1;
						}

						//working days excluding holidays based on office shift
						if ($p_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_days_worked += 1;
							}
						}
					}

					// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
					$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
				} else {
					// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
					$no_of_days_worked = $no_of_working_days -  $holidays_count;
				}
				// echo $holidays_count;

				if ($month_date_join == $pay_date){
					$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
				}else{
					$show_main_salary = $basic_salary;
				}
			
				$g_ordinary_wage += $show_main_salary;
				$g_shg += $show_main_salary;
				$g_sdl += $show_main_salary;
			
				$g_ordinary_wage -= $deduction_amount;
				$g_shg -= $deduction_amount;
				$g_sdl -= $deduction_amount;


				
				// 3: all loan/deductions
				$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
				$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
				$loan_de_amount = 0;
				if (in_array('chk_loan_de_amount', $uncheck_id)) {
					if ($count_loan_deduction > 0) {
						foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
								} else {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
								}
							} else {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
							}
							$loan_de_amount += $er_loan;
						}
					} else {
						$loan_de_amount = 0;
					}
					$loan_de_amount = round($loan_de_amount, 2);
				}

				// 2: all allowances
				$allowance_amount = 0;
				$gross_allowance_amount = 0;
				if (in_array('chk_total_allowances', $uncheck_id)) {
					$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
					if ($salary_allowances) {
						foreach ($salary_allowances as $sa) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$eallowance_amount = $sa->allowance_amount / 2;
								} else {
									$eallowance_amount = $sa->allowance_amount;
								}
							} else {
								$eallowance_amount = $sa->allowance_amount;
							}


							if (!empty($sa->salary_month)) {
								$g_additional_wage += $eallowance_amount;
							} else {
								if ($month_date_join == $pay_date){
									$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
								}
								$g_ordinary_wage += $eallowance_amount;
								if ($sa->id == 2) {
									$gross_allowance_amount = $eallowance_amount;
								}
							}

							if ($sa->sdl == 1) {
								$g_sdl += $eallowance_amount;
							}
							if ($sa->shg == 1) {
								$g_shg += $eallowance_amount;
							}

							$allowance_amount += $eallowance_amount;
						}
					}
					$gross_allowance_amount = $allowance_amount;
				}


				// commissions
				$commissions_amount = 0;
				if (in_array('chk_total_commissions', $uncheck_id)) {
					$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
					if ($commissions) {
						foreach ($commissions as $c) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$ecommissions_amount = $c->commission_amount / 2;
								} else {
									$ecommissions_amount = $c->commission_amount;
								}
							} else {
								$ecommissions_amount = $c->commission_amount;
							}

							if ($c->commission_type == 9) {
								$g_ordinary_wage += $ecommissions_amount;
							} elseif ($c->commission_type == 10) {
								$g_additional_wage += $ecommissions_amount;
							}

							if ($c->sdl == 1) {
								$g_sdl += $ecommissions_amount;
							}
							if ($c->shg == 1) {
								$g_shg += $ecommissions_amount;
							}

							$commissions_amount += $ecommissions_amount;
						}
					}
				}

				//share options
				$share_options_amount = 0;
				if (in_array('chk_total_share', $uncheck_id)) {
					$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
					if ($share_options) {
						$eebr_amount = 0;
						$eris_amount = 0;
						foreach ($share_options as $s) {
							$scheme = $s->so_scheme;
							if ($scheme == 1) {
								$price_doe = $s->price_date_of_excercise;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
								$amount = ($price_doe - $price_ex) * $no_shares;
								$eebr_amount += $amount;
							} else {
								$price_doe = $s->price_date_of_excercise;
								$price_dog = $s->price_date_of_grant;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;

								$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
								$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
								$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
							}
						}
						$share_options_amount = round($eebr_amount + $eris_amount, 2);
						$g_additional_wage += $share_options_amount;
						$g_sdl += $share_options_amount;
						$g_shg += $share_options_amount;
					}
				}


				// otherpayments
				$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
				$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
				$other_payments_amount = 0;
				if (in_array('chk_total_other_payments', $uncheck_id)) {
					if ($count_other_payments > 0) {
						foreach ($other_payments->result() as $sl_other_payments) {
							if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
								if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$epayments_amount = $sl_other_payments->payments_amount / 2;
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}

									if ($sl_other_payments->cpf_applicable == 1) {
										$g_additional_wage += $epayments_amount;
										$g_shg += $epayments_amount;
										$g_sdl += $epayments_amount;
									}

									$other_payments_amount += $epayments_amount;
								}
							} else {
								$first_date = new DateTime($sl_other_payments->date);
								if ($first_date->format('m-Y') == $pay_date) {
									$first_date =  new DateTime($sl_other_payments->date);
								} else {
									$first_date = new DateTime('01-' . $pay_date);
								}

								$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));

								if (!empty($sl_other_payments->end_date)) {
									$last_date = new DateTime($sl_other_payments->end_date);
									if ($last_date->format('m-Y') == $pay_date) {
										$last_date = new DateTime($sl_other_payments->end_date);
									}else if($last_date->format('m-Y') >= $pay_date){
											$last_date = $month_end_date_for_other;
									} else {
										$last_date = '';
									}
								} else {
									$last_date = $month_end_date_for_other;
								}
								if(!empty($last_date)){
									$last_date->modify('+1 day');
									$final_last_day = new DateTime($last_date->format('d-m-Y'));
									if ($final_last_day->format('m-Y') >= $pay_date) {
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$epayments_amount = $sl_other_payments->payments_amount / 2;
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}


										// it for no of working day
										$no_of_days_worked_for_other_payment = 0;
										$same_month_holidays_count_for_other_payment = 0;
										$interval = new DateInterval('P1D');
										$period = new DatePeriod($first_date, $interval, $last_date);
										foreach ($period as $p) {
											$p_day = $p->format('l');
											$p_date = $p->format('Y-m-d');

											//holidays in a month

											$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
											if ($is_holiday) {
												$same_month_holidays_count_for_other_payment += 1;
											}

											//working days excluding holidays based on office shift
											if ($p_day == 'Monday') {
												if ($office_shift[0]->monday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Tuesday') {
												if ($office_shift[0]->tuesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Wednesday') {
												if ($office_shift[0]->wednesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Thursday') {
												if ($office_shift[0]->thursday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Friday') {
												if ($office_shift[0]->friday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Saturday') {
												if ($office_shift[0]->saturday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Sunday') {
												if ($office_shift[0]->sunday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											}
										}

										$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
										$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);
										


										if ($sl_other_payments->cpf_applicable == 1) {
											$g_additional_wage += $epayments_amount;
											$g_shg += $epayments_amount;
											$g_sdl += $epayments_amount;
										}
										$other_payments_amount += $epayments_amount;
									}
								}
							}
						}
					} else {
						$other_payments_amount = 0;
					}
				}

				
				$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
				// if (in_array('chk_leave_deductions', $uncheck_id)) {
					if ($unpaid_leaves) {
						$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
				   }
				// }

				
			
				// $g_ordinary_wage = $gross_pay;
				$g_shg = $gross_pay;
				$g_sdl = $gross_pay;

				$g_ordinary_wage -= $unpaid_leave_amount;

				
			
				
				// other benefit
				$other_benefit_mount = 0;
				if (in_array('chk_total_employee_deduction', $uncheck_id)) {
					$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
					foreach ($other_benefit_list->result() as $benefit_list) {
						$other_benefit_mount += $benefit_list->other_benefit_cost;
					}
				}


				// statutory_deductions
				$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
				$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
				$statutory_deductions_amount = 0;
				// if (in_array('chk_total_statutory_deductions', $uncheck_id) && (!in_array('chk_gross_salary', $uncheck_id) || !in_array('chk_total_other_payments', $uncheck_id) || !in_array('chk_total_allowances', $uncheck_id))) {
				if ((in_array('chk_total_statutory_deductions', $uncheck_id) && (!in_array('chk_total_allowances', $uncheck_id) && !in_array('chk_total_other_payments', $uncheck_id) && !in_array('chk_gross_salary', $uncheck_id)) ) 
				|| 
				(!in_array('chk_total_statutory_deductions', $uncheck_id) && (in_array('chk_total_allowances', $uncheck_id) || in_array('chk_total_other_payments', $uncheck_id) || in_array('chk_gross_salary', $uncheck_id))  )) {
					if ($count_statutory_deductions > 0) {
						foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
							if ($system[0]->statutory_fixed != 'yes') {
								$sta_salary = $gross_pay;
								$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $st_amount / 2;
									} else {
										$single_sd = $st_amount;
									}
								} else {
									$single_sd = $st_amount;
								}
								$statutory_deductions_amount += $single_sd;
							} else {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
									} else {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount;
									}
								} else {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount;
								}
								$statutory_deductions_amount += $single_sd;
							}
						}
					} else {
						$statutory_deductions_amount = 0;
					}
				}
				
				// overtime
				$overtime_amount = 0;
				if (in_array('chk_total_overtime', $uncheck_id)) {
					$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
					if ($overtime) {
						$ot_hrs = 0;
						$ot_mins = 0;
						foreach ($overtime as $ot) {
							$total_hours = explode(':', $ot->total_hours);
							$ot_hrs += $total_hours[0];
							$ot_mins += $total_hours[1];
						}
						if ($ot_mins > 0) {
							$ot_hrs += round($ot_mins / 60, 2);
						}

						//overtime rate
						$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
						if ($overtime_rate) {
							$rate = $overtime_rate->overtime_pay_rate;
						} else {
							$week_hours = 44;
							$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
							$rate = $rate * 1.5;
						}

						if ($ot_hrs > 0) {
							$overtime_amount = round($ot_hrs * $rate, 2);
						}
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$overtime_amount = $overtime_amount / 2;
							}
						}
						$g_ordinary_wage += $overtime_amount;
						$g_sdl += $overtime_amount;
					}
				}

				// make payment
				if ($system[0]->is_half_monthly == 1) {
					$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($r->user_id, $pay_date);
					$payment_last = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($r->user_id, $pay_date);
					if ($payment_check->num_rows() > 1) {

						//foreach($payment_last as $payment_half_last){
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
						//$mpay = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_make_payment').'"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '" data-company_id="'.$this->input->get("company_id").'"><span class="fa fas fa-money"></span></button></span>';

						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}

						$delete = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code><br>' . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $payment_last[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span><code>' . $this->lang->line('xin_title_second_half') . '</code>';
						//}
						//detail link
						$detail = '';
					} else if ($payment_check->num_rows() > 0) {

						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';

						$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
						$delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
						$detail = '';
					} else {

						$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
						$delete = '';
						//detail link
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					}
					//detail link
					//$detail = '';
				} else {
					$payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
					if ($payment_check->num_rows() > 0) {
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						if ($make_payment[0]->status == 0) {
							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
						} else if ($make_payment[0]->status == 3) {
							$status = '<span class="label label-warning">' . "Partially" . '</span>';
						}
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
					} else {

						$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '">
							<button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '">
								<span class="fa fas fa-money"></span>
							</button>
						</span>';
						$delete = '';
					}
					//detail link
					$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
				}


				// employee accommodations
				$employee_accommodations = 0;
				if (in_array('chk_total_employee_deduction', $uncheck_id)){
					$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
					foreach ($get_employee_accommodations as $get_employee_accommodation) {
						$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
						$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
						if ($period_from == $pay_date || $period_to == $pay_date) {
							if (!empty($get_employee_accommodation->rent_paid)) {
								$employee_accommodations += $get_employee_accommodation->rent_paid;
							}
						}
					}
				}


				// employee claims
				$claim_amount = 0;
				if (in_array('chk_employee_claim', $uncheck_id)){
					$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
					foreach ($get_employee_claims->result() as $claims) {
						$date 	= 	date('m-Y', strtotime($claims->date));
						if ($date == $pay_date) {
							$claim_amount += $claims->amount;
						}
					}
				}
				

				$total_earning 		= 	$show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
				$total_deduction 	= 	$loan_de_amount + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
				$total_net_salary 	= 	($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;

				
				// cpf calculation
				$emp_dob = $r->date_of_birth;
				$dob = new DateTime($emp_dob);

				$today = new DateTime('01-' . $pay_date);
				$age = $dob->diff($today);
				$age_year = $age->y;
				$age_month = $age->m;

				$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
				$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
				$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;

				if ($age_month > 0) {
					$age_year = $age_year + 1;
				}

				// $cpf_employee 	= 	0;
				// $cpf_employer	=	0;

				// $im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
				// if ($im_status) {
				// 	$immigration_id = $im_status->immigration_id;
				// 	if ($immigration_id == 2) {
				// 		$issue_date = $im_status->issue_date;
				// 		$i_date = new DateTime($issue_date);
				// 		$today = new DateTime();
				// 		$pr_age = $i_date->diff($today);
				// 		$pr_age_year = $pr_age->y;
				// 		$pr_age_month = $pr_age->m;
				// 	}

				// 	if ($immigration_id == 1 || $immigration_id == 2) {

				// 		$ordinary_wage = $g_ordinary_wage;
				// 		if ($ordinary_wage > $ordinary_wage_cap) {
				// 			$ow = $ordinary_wage_cap;
				// 		} else {
				// 			$ow = $ordinary_wage;
				// 		}

				// 		//additional wage
				// 		$additional_wage = $g_additional_wage;
				// 		$aw = $g_additional_wage;
				// 		$tw = $ow + $additional_wage;
				// 		if ($im_status->issue_date != "") {
				// 			if ($pr_age_year == 1) {

				// 				if ($age_year <= 55) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw < 500) {
				// 						$cpf_employer = round(4 / 100 * $tw);
				// 						$cpf_employee = 0;
				// 					} else if ($tw > 500 && $tw < 750) {
				// 						$cpf_employee = floor(0.15 * ($tw - 500));
				// 						$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
				// 						$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

				// 						if ($count_cpf_employee > 370) {
				// 							$cpf_employee = 370;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						if ($count_total_cpf > 666) {
				// 							$total_cpf = 666;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				} else if ($age_year > 55 && $age_year <= 60) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw < 500) {
				// 						$cpf_employer = round(4 / 100 * $tw);
				// 						$cpf_employee = 0;
				// 					} else if ($tw > 500 && $tw < 750) {
				// 						$cpf_employee = floor(0.15 * ($tw - 500));
				// 						$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
				// 						$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

				// 						if ($count_cpf_employee > 370) {
				// 							$cpf_employee = 370;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						if ($count_total_cpf > 666) {
				// 							$total_cpf = 666;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				} else if ($age_year > 60 && $age_year <= 65) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw < 500) {
				// 						$cpf_employer = round(3.5 / 100 * $tw);
				// 						$cpf_employee = 0;
				// 					} else if ($tw > 500 && $tw < 750) {
				// 						$cpf_employee = floor(0.15 * ($tw - 500));
				// 						$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
				// 						$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

				// 						if ($count_cpf_employee > 370) {
				// 							$cpf_employee = 370;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						if ($count_total_cpf > 629) {
				// 							$total_cpf = 629;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				} else if ($age_year > 65) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw < 500) {
				// 						$cpf_employer = round(3.5 / 100 * $tw);
				// 						$cpf_employee = 0;
				// 					} else if ($tw > 500 && $tw < 750) {
				// 						$cpf_employee = floor(0.15 * ($tw - 500));
				// 						$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
				// 						$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

				// 						if ($count_cpf_employee > 370) {
				// 							$cpf_employee = 370;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						if ($count_total_cpf > 629) {
				// 							$total_cpf = 629;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				}
				// 			}
				// 			if ($pr_age_year == 2) {
				// 				if ($age_year <= 55) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw <= 500) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = round(9 / 100 * $tw);
				// 					} else if ($tw > 500 && $tw <= 750) {
				// 						$cpf_employee = floor(0.45 * ($tw - 500));
				// 						$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
				// 						$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
				// 						if ($count_total_cpf > 1776) {
				// 							$total_cpf = 1776;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						if ($count_cpf_employee > 1110) {
				// 							$cpf_employee = 1110;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				} else if ($age_year > 55 && $age_year <= 60) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw <= 500) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = round(6 / 100 * $tw);
				// 					} else if ($tw > 500 && $tw <= 750) {
				// 						$cpf_employee = floor(0.375 * ($tw - 500));
				// 						$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
				// 						$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
				// 						if ($count_total_cpf > 1369) {
				// 							$total_cpf = 1369;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						if ($count_cpf_employee > 925) {
				// 							$cpf_employee = 925;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				} else if ($age_year > 60 && $age_year <= 65) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw <= 500) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = round(3.5 / 100 * $tw);
				// 					} else if ($tw > 500 && $tw <= 750) {
				// 						$cpf_employee = floor(0.225 * ($tw - 500));
				// 						$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
				// 						$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
				// 						if ($count_total_cpf > 814) {
				// 							$total_cpf = 814;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						if ($count_cpf_employee > 555) {
				// 							$cpf_employee = 555;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				} else if ($age_year > 65) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw <= 500) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = round(3.5 / 100 * $tw);
				// 					} else if ($tw > 500 && $tw <= 750) {
				// 						$cpf_employee = floor(0.15 * ($tw - 500));
				// 						$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
				// 						$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
				// 						if ($count_total_cpf > 629) {
				// 							$total_cpf = 629;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						if ($count_cpf_employee > 370) {
				// 							$cpf_employee = 370;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				}
				// 			}
				// 			if ($pr_age_year == 3 || $pr_age_year > 3) {
				// 				if ($age_year <= 55) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw <= 500) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = round(17 / 100 * $tw);
				// 					} else if ($tw > 500 && $tw <= 750) {
				// 						$cpf_employee = floor(0.6 * ($tw - 500));
				// 						$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
				// 						$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
				// 						if ($count_total_cpf > 2738) {
				// 							$total_cpf = 2738;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						if ($count_cpf_employee > 1480) {
				// 							$cpf_employee = 1480;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						$cpf_employer = $total_cpf - $cpf_employee;
				// 					}
				// 				} else if ($age_year > 55 && $age_year <= 60) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw <= 500) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = round(15.5 / 100 * $tw);
				// 					} else if ($tw > 500 && $tw <= 750) {
				// 						$cpf_employee = floor(0.51 * ($tw - 500));
				// 						$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
				// 						$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
				// 						if ($count_total_cpf > 2405) {
				// 							$total_cpf = 2405;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						if ($count_cpf_employee > 1258) {
				// 							$cpf_employee = 1258;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				} else if ($age_year > 60 && $age_year <= 65) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw <= 500) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = round(12 / 100 * $tw);
				// 					} else if ($tw > 500 && $tw <= 750) {
				// 						$cpf_employee = floor(0.345 * ($tw - 500));
				// 						$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
				// 						$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;

				// 						if ($count_total_cpf > 1739) {
				// 							$total_cpf = 1739;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						if ($count_cpf_employee > 851) {
				// 							$cpf_employee = 851;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				} else if ($age_year > 65 && $age_year <= 70) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw <= 500) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = round(9 / 100 * $tw);
				// 					} else if ($tw > 500 && $tw <= 750) {
				// 						$cpf_employee = floor(0.225 * ($tw - 500));
				// 						$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
				// 						$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;

				// 						if ($count_total_cpf > 1221) {
				// 							$total_cpf = 1221;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						if ($count_cpf_employee > 555) {
				// 							$cpf_employee = 555;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				} else if ($age_year > 70) {
				// 					if ($tw < 50) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = 0;
				// 					} else if ($tw > 50 && $tw <= 500) {
				// 						$cpf_employee = 0;
				// 						$cpf_employer = round(7.5 / 100 * $tw);
				// 					} else if ($tw > 500 && $tw <= 750) {
				// 						$cpf_employee = floor(0.15 * ($tw - 500));
				// 						$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					} else if ($tw > 750) {
				// 						$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
				// 						$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;

				// 						if ($count_total_cpf > 925) {
				// 							$total_cpf = 925;
				// 						} else {
				// 							$total_cpf = $count_total_cpf;
				// 						}
				// 						if ($count_cpf_employee > 370) {
				// 							$cpf_employee = 370;
				// 						} else {
				// 							$cpf_employee = floor($count_cpf_employee);
				// 						}
				// 						$cpf_employer = round($total_cpf - $cpf_employee);
				// 					}
				// 				}
				// 			}
				// 		}



				// 		if ($immigration_id == 1) {

				// 			if ($age_year <= 55) {
				// 				if ($tw < 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(17 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.6 * ($tw - 500));
				// 					$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
				// 					$cpf_employer = round($total_cpf - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
				// 					$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
				// 					if ($count_total_cpf > 2738) {
				// 						$count_total_cpf = 2738;
				// 					} else {
				// 						$total_cpf = $count_total_cpf;
				// 					}


				// 					if ($count_cpf_employee > 1480) {
				// 						$cpf_employee = 1480;
				// 					} else {
				// 						$cpf_employee = floor($count_cpf_employee);
				// 					}
				// 					$cpf_employer = round($total_cpf - $cpf_employee);
				// 				}
				// 			} else if ($age_year > 55 && $age_year <= 60) {
				// 				if ($tw < 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(15.5 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.51 * ($tw - 500));
				// 					$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
				// 					$cpf_employer = round($total_cpf - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;

				// 					$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
				// 					if ($count_total_cpf > 2405) {
				// 						$count_total_cpf = 2405;
				// 					} else {
				// 						$total_cpf = $count_total_cpf;
				// 					}
				// 					if ($count_cpf_employee > 1258) {
				// 						$cpf_employee = 1258;
				// 					} else {
				// 						$cpf_employee = floor($count_cpf_employee);
				// 					}
				// 					$cpf_employer = round($total_cpf - $cpf_employee);
				// 				}
				// 			} else if ($age_year > 60 && $age_year <= 65) {
				// 				if ($tw < 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(12 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.345 * ($tw - 500));
				// 					$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
				// 					$cpf_employer = round($total_cpf - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
				// 					$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;

				// 					if ($count_total_cpf > 1739) {
				// 						$total_cpf = 1739;
				// 					} else {
				// 						$total_cpf = $count_total_cpf;
				// 					}
				// 					if ($count_cpf_employee > 851) {
				// 						$cpf_employee = 851;
				// 					} else {
				// 						$cpf_employee = floor($count_cpf_employee);
				// 					}
				// 					$cpf_employer = round($total_cpf - $cpf_employee);
				// 				}
				// 			} else if ($age_year > 65 && $age_year <= 70) {
				// 				if ($tw < 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(9 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.225 * ($tw - 500));
				// 					$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
				// 					$cpf_employer = round($total_cpf - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
				// 					$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;

				// 					if ($count_total_cpf > 1221) {
				// 						$total_cpf = 1221;
				// 					} else {
				// 						$total_cpf = $count_total_cpf;
				// 					}
				// 					if ($count_cpf_employee > 555) {
				// 						$cpf_employee = 555;
				// 					} else {
				// 						$cpf_employee = floor($count_cpf_employee);
				// 					}
				// 					$cpf_employer = round($total_cpf - $cpf_employee);
				// 				}
				// 			} else if ($age_year > 70) {
				// 				if ($tw < 50) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = 0;
				// 				} else if ($tw > 50 && $tw <= 500) {
				// 					$cpf_employee = 0;
				// 					$cpf_employer = round(7.5 / 100 * $tw);
				// 				} else if ($tw > 500 && $tw <= 750) {
				// 					$cpf_employee = floor(0.15 * ($tw - 500));
				// 					$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
				// 					$cpf_employer = round($total_cpf - $cpf_employee);
				// 				} else if ($tw > 750) {
				// 					$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
				// 					$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;

				// 					if ($count_total_cpf > 925) {
				// 						$total_cpf = 925;
				// 					} else {
				// 						$total_cpf = $count_total_cpf;
				// 					}
				// 					if ($count_cpf_employee > 370) {
				// 						$cpf_employee = 370;
				// 					} else {
				// 						$cpf_employee = floor($count_cpf_employee);
				// 					}
				// 					$cpf_employer = round($total_cpf - $cpf_employee);
				// 				}
				// 			}
				// 		}

				// 		$total_net_salary = $total_net_salary - $cpf_employee;
				// 		$cpf_total = $cpf_employee + $cpf_employer;

				// 		$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
				// 		$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
				// 		$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
				// 	}
				// }


				// $shg_fund_deduction_amount = 0;
				// //Other Fund Contributions
				// $employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);
				// if ($employee_contributions && $g_shg > 0) {
				// 	$gross_s = $g_shg;
				// 	$contribution_id = $employee_contributions->contribution_id;
				// 	$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
				// 	$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
				// 	$shg_fund_name = $contribution_type[0]->contribution;
				// 	$shg_fund_deduction_amount += $contribution_amount;
				// 	$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
				// }
				// $ashg_fund_deduction_amount = 0;
				// $employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
				// if ($employee_ashg_contributions  && $g_shg > 0) {
				// 	$gross_s = $g_shg;
				// 	$contribution_id = $employee_ashg_contributions->contribution_id;
				// 	$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
				// 	$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
				// 	$ashg_fund_name = $contribution_type[0]->contribution;

				// 	$ashg_fund_deduction_amount += $contribution_amount;
				// 	$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
				// }

				// echo $total_net_salary;

				// $sdl_total_amount = 0;
				// if ($g_sdl > 1 && $g_sdl <= 800) {
				// 	$sdl_total_amount = 2;
				// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
				// 	$sdl_amount = (0.25 * $g_sdl) / 100;
				// 	$sdl_total_amount = $sdl_amount;
				// } elseif ($g_sdl > 4500) {
				// 	$sdl_total_amount = 11.25;
				// }


				$net_salary = number_format((float)$total_net_salary, 2, '.', '');
				$basic_salary = number_format((float)$basic_salary, 2, '.', '');
				// if ($basic_salary == 0 || $basic_salary == '') {
				// 	$fmpay = '';
				// } else {
				// 	$fmpay = $mpay;
				// }


				// $company_info = $this->Company_model->read_company_information($r->company_id);
				// if (!is_null($company_info)) {
				// 	$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
				// 	$net_salary = $net_salary;
				// 	//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
				// } else {
				// 	//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
				// 	$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary, $r->user_id);

				// 	$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary, $r->user_id);

				// 	//$net_salary = $this->Xin_model->currency_sign($net_salary);	
				// 	//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
				// 	$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount, $r->user_id);
				// }

				// $iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';

				//action link
				// $act = $detail . $fmpay . $delete;
				// $act = $fmpay . $delete;

				if ($r->wages_type == 1) {
					if ($system[0]->is_half_monthly == 1) {
						$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
					} else {
						$emp_payroll_wage = $wages_type;
					}
				} else {
					$emp_payroll_wage = $wages_type;
				}

				$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
				$p = $payslips->result();
				$balance = $this->Xin_model->currency_sign($p[0]->balance_amount ?? 0, $r->user_id);
				$mode_of_payment = $r->payment_mode ?? '';

				
				
				return $net_salary;
				// $data = array(
				// 	$act,
				// 	$iemp_name,
				// 	$emp_payroll_wage,
				// 	$basic_salary,
				// 	'total_cpf_employee'	=> 	$cpf_employee,
				// 	'total_cpf_employer'	=>	$cpf_employer,
				// 	'cpf_total'				=> 	$cpf_total,
				// 	'net_salary'			=>	$net_salary,
				// 	'contribution'			=>	$contribution,
				// 	'total_deduction'		=>	$total_deduction,
				// 	$balance,
				// 	$mode_of_payment,
				// 	$status
				// );
			}
		}
	}

	// for contrubution calculation
	public function contribution_calculation($data)
	{
		$pay_date 	= 	$data['pay_date'];
		$user_id	=	$data['user_id'];
		$payslip 	= 	$this->Employees_model->get_single_employees_payslip($pay_date, $user_id);
		$system 	= 	$this->Xin_model->read_setting_info(1);

		$role_resources_ids = $this->Xin_model->user_role_resource();
		$system = $this->Xin_model->read_setting_info(1);
		$data = array();
		foreach ($payslip->result() as $r) {
			$exit_employee = $this->Employees_model->get_employee_exit($r->user_id);

			if (count($exit_employee) > 0) {
				$e_date = date('Y-m-d', strtotime($exit_employee[0]->exit_date));
				$exit_date = date('m-Y', strtotime($e_date));
				if ($exit_date >= $pay_date){
					$emp_name = $r->first_name . ' ' . $r->last_name;
					// office shift
					$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);
	
					//overtime request
					$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $this->input->get('month_year'));
					$re_hrs_old_int1 = 0;
					$re_hrs_old_seconds = 0;
					$re_pcount = 0;
					foreach ($overtime_count as $overtime_hr) {
						$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
						$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
						$re_interval_late = $request_clock_in->diff($request_clock_out);
						$re_hours_r  = $re_interval_late->format('%h');
						$re_minutes_r = $re_interval_late->format('%i');
						$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';
	
						$re_str_time = $re_total_time;
	
						$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);
	
						sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$re_hrs_old_int1 += $re_hrs_old_seconds;
	
						$re_pcount = gmdate("H", $re_hrs_old_int1);
					}
	
					$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
					$hrs_old_int1 = 0;
					$pcount = 0;
					foreach ($result->result() as $hour_work) {
						$clock_in =  new DateTime($hour_work->clock_in);
						$clock_out =  new DateTime($hour_work->clock_out);
						$interval_late = $clock_in->diff($clock_out);
						$hours_r  = $interval_late->format('%h');
						$minutes_r = $interval_late->format('%i');
						$total_time = $hours_r . ":" . $minutes_r . ":" . '00';
	
						$str_time = $total_time;
	
						$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
	
						sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$hrs_old_int1 += $hrs_old_seconds;
	
						$pcount = gmdate("H", $hrs_old_int1);
					}
					$pcount = $pcount + $re_pcount;
	
					// get company
					$company = $this->Xin_model->read_company_info($r->company_id);
					if (!is_null($company)) {
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';
					}
	
					/**
					 * Local Variable
					 */
					$g_ordinary_wage = 0;
					$g_additional_wage = 0;
					$g_shg = 0;
					$g_sdl = 0;
	
					// 1: salary type
					if ($r->wages_type == 1) {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					} else if ($r->wages_type == 2) {
						$wages_type = $this->lang->line('xin_employee_daily_wages');
						if ($pcount > 0) {
							$basic_salary = $pcount * $r->basic_salary;
						} else {
							$basic_salary = $pcount;
						}
						$p_class = 'emo_hourly_pay';
						$view_p_class = 'hourlywages_template_modal';
					} else {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					}
	
					
					// employee deduction
					$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
					$pa_month = date('m-Y', strtotime('01-' . $pay_date));
					$deduction_amount = 0;
					if ($employee_deduction) {
						foreach ($employee_deduction as $deduction) {
							if ($deduction->type_id == 1) {
								$deduction_amount +=  $deduction->amount;
							}
							if ($deduction->type_id == 2) {
								$from_month_year = date('m-Y', strtotime($deduction->from_date));
								$to_month_year = date('d-m-Y', strtotime($deduction->to_date));
	
	
								if ($from_month_year != "" && $to_month_year != "") {
									if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
										$deduction_amount +=  $deduction->amount;
									}
								}
							}
						}
					}
	
	
	
					//3: Gross rate of pay (unpaid leave deduction)
					$holidays_count = 0;
					$no_of_working_days = 0;
					$month_start_date = new DateTime('01-' . $pay_date);
					$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
					$month_end_date->modify('+1 day');
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($month_start_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$period_day = $p->format('l');
						$period_date = $p->format('Y-m-d');
	
						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
						if ($is_holiday) {
							$holidays_count += 1;
						}
	
						//working days excluding holidays based on office shift
						if ($period_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_working_days += 1;
							}
						}
					}
	
					//unpaid leave
					$unpaid_leave_amount = 0;
					$leaves_taken_count = 0;
					$leave_period = array();
					$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
					if ($unpaid_leaves) {
						foreach ($unpaid_leaves as $k => $l) {
							$pay_date_month = new DateTime('01-' . $pay_date);
							$l_from_date = new DateTime($l->from_date);
							$l_to_date = new DateTime($l->to_date);
	
							if ($l_from_date->format('m') == $l_to_date->format('m')) {
								$start_date = $l_from_date;
								$end_date = $l_to_date;
							} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $l_from_date;
								$end_date = new DateTime($start_date->format('Y-m-t'));
							} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $pay_date_month;
								$end_date = $l_to_date;
							}
							$end_date->modify('+1 day');
							$interval = new DateInterval('P1D');
							$period = new DatePeriod($start_date, $interval, $end_date);
							foreach ($period as $d) {
								$p_day = $d->format('l');
								if ($p_day == 'Monday') {
									if ($office_shift[0]->monday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Tuesday') {
									if ($office_shift[0]->tuesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Wednesday') {
									if ($office_shift[0]->wednesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Thursday') {
									if ($office_shift[0]->thursday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Friday') {
									if ($office_shift[0]->friday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Saturday') {
									if ($office_shift[0]->saturday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Sunday') {
									if ($office_shift[0]->sunday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								}
							}
							$leave_period[$k]['is_half'] = $l->is_half_day;
						}
					}
	
					// if joining date and pay date same then this logic work
					$month_date_join = date('m-Y', strtotime($r->date_of_joining));
					$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
					$month_last_date = date('m-Y', strtotime($lastday));
	
					$first_date =  new DateTime($r->date_of_joining);
					$no_of_days_worked = 0;
					$same_month_holidays_count = 0;
					if ($month_date_join == $pay_date) {
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($first_date, $interval, $month_end_date);
						foreach ($period as $p) {
							$p_day = $p->format('l');
							$m_p_date = $p->format('Y-m-d');
	
							//holidays in a month
							$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
							if ($is_holiday) {
								$same_month_holidays_count += 1;
							}
	
							//working days excluding holidays based on office shift
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$no_of_days_worked += 1;
								}
							}
						}
	
						// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
						$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
					} else {
						// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
						$no_of_days_worked = $no_of_working_days -  $holidays_count;
					}
	
	
					if ($month_date_join == $pay_date){
						$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
					}else{
						$show_main_salary = $basic_salary;
					}
				
					$g_ordinary_wage += $show_main_salary;
					$g_shg += $show_main_salary;
					$g_sdl += $show_main_salary;
				
					$g_ordinary_wage -= $deduction_amount;
					$g_shg -= $deduction_amount;
					$g_sdl -= $deduction_amount;
	
	
					// 3: all loan/deductions
					$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
					$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
					$loan_de_amount = 0;
					if ($count_loan_deduction > 0) {
						foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
								} else {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
								}
							} else {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
							}
							$loan_de_amount += $er_loan;
						}
					} else {
						$loan_de_amount = 0;
					}
					$loan_de_amount = round($loan_de_amount, 2);
	
					// 2: all allowances
					$allowance_amount = 0;
					$gross_allowance_amount = 0;
					$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
					if ($salary_allowances) {
						foreach ($salary_allowances as $sa) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$eallowance_amount = $sa->allowance_amount / 2;
								} else {
									$eallowance_amount = $sa->allowance_amount;
								}
							} else {
								$eallowance_amount = $sa->allowance_amount;
							}
	
	
							if (!empty($sa->salary_month)) {
								$g_additional_wage += $eallowance_amount;
							} else {
								if ($month_date_join == $pay_date){
									$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
								}
								$g_ordinary_wage += $eallowance_amount;
								if ($sa->id == 2) {
									$gross_allowance_amount = $eallowance_amount;
								}
							}
	
							if ($sa->sdl == 1) {
								$g_sdl += $eallowance_amount;
							}
							if ($sa->shg == 1) {
								$g_shg += $eallowance_amount;
							}
	
							$allowance_amount += $eallowance_amount;
						}
					}
					$gross_allowance_amount = $allowance_amount;
	
	
	
	
					// commissions
					$commissions_amount = 0;
					$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
					if ($commissions) {
						foreach ($commissions as $c) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$ecommissions_amount = $c->commission_amount / 2;
								} else {
									$ecommissions_amount = $c->commission_amount;
								}
							} else {
								$ecommissions_amount = $c->commission_amount;
							}
	
							if ($c->commission_type == 9) {
								$g_ordinary_wage += $ecommissions_amount;
							} elseif ($c->commission_type == 10) {
								$g_additional_wage += $ecommissions_amount;
							}
	
							if ($c->sdl == 1) {
								$g_sdl += $ecommissions_amount;
							}
							if ($c->shg == 1) {
								$g_shg += $ecommissions_amount;
							}
	
							$commissions_amount += $ecommissions_amount;
						}
					}
	
					//share options
					$share_options_amount = 0;
					$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
					if ($share_options) {
						$eebr_amount = 0;
						$eris_amount = 0;
						foreach ($share_options as $s) {
							$scheme = $s->so_scheme;
							if ($scheme == 1) {
								$price_doe = $s->price_date_of_excercise;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
								$amount = ($price_doe - $price_ex) * $no_shares;
								$eebr_amount += $amount;
							} else {
								$price_doe = $s->price_date_of_excercise;
								$price_dog = $s->price_date_of_grant;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
	
								$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
								$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
								$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
							}
						}
						$share_options_amount = round($eebr_amount + $eris_amount, 2);
						$g_additional_wage += $share_options_amount;
						$g_sdl += $share_options_amount;
						$g_shg += $share_options_amount;
					}
	
					// otherpayments
					$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
					$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
					$other_payments_amount = 0;
					if ($count_other_payments > 0) {
						foreach ($other_payments->result() as $sl_other_payments) {
							if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
								if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$epayments_amount = $sl_other_payments->payments_amount / 2;
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}
	
									if ($sl_other_payments->cpf_applicable == 1) {
										$g_additional_wage += $epayments_amount;
										$g_shg += $epayments_amount;
										$g_sdl += $epayments_amount;
									}
	
									$other_payments_amount += $epayments_amount;
								}
							} else {
								$first_date = new DateTime($sl_other_payments->date);
								if ($first_date->format('m-Y') == $pay_date) {
									$first_date =  new DateTime($sl_other_payments->date);
								} else {
									$first_date = new DateTime('01-' . $pay_date);
								}
	
								$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));
	
								if (!empty($sl_other_payments->end_date)) {
									$last_date = new DateTime($sl_other_payments->end_date);
									if ($last_date->format('m-Y') == $pay_date) {
										$last_date = new DateTime($sl_other_payments->end_date);
									}else if($last_date->format('m-Y') >= $pay_date){
										$last_date = $month_end_date_for_other;
									} else {
										$last_date = '';
									}
								} else {
									$last_date = $month_end_date_for_other;
								}
								if(!empty($last_date)){
									$last_date->modify('+1 day');
									$final_last_day = new DateTime($last_date->format('d-m-Y'));
									if ($final_last_day->format('m-Y') >= $pay_date) {
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$epayments_amount = $sl_other_payments->payments_amount / 2;
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
	
	
										// it for no of working day
										$no_of_days_worked_for_other_payment = 0;
										$same_month_holidays_count_for_other_payment = 0;
										$interval = new DateInterval('P1D');
										$period = new DatePeriod($first_date, $interval, $last_date);
										foreach ($period as $p) {
											$p_day = $p->format('l');
											$p_date = $p->format('Y-m-d');
	
											//holidays in a month
	
											$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
											if ($is_holiday) {
												$same_month_holidays_count_for_other_payment += 1;
											}
	
											//working days excluding holidays based on office shift
											if ($p_day == 'Monday') {
												if ($office_shift[0]->monday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Tuesday') {
												if ($office_shift[0]->tuesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Wednesday') {
												if ($office_shift[0]->wednesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Thursday') {
												if ($office_shift[0]->thursday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Friday') {
												if ($office_shift[0]->friday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Saturday') {
												if ($office_shift[0]->saturday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Sunday') {
												if ($office_shift[0]->sunday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											}
										}
	
										$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
										
										$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);
										
	
	
										if ($sl_other_payments->cpf_applicable == 1) {
											$g_additional_wage += $epayments_amount;
											$g_shg += $epayments_amount;
											$g_sdl += $epayments_amount;
										}
										$other_payments_amount += $epayments_amount;
									}
								}
							}
						}
					} else {
						$other_payments_amount = 0;
					}
	
	
					$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
		
					if ($unpaid_leaves) {
						$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
					}
	
					// $g_ordinary_wage = $gross_pay;
					$g_shg = $gross_pay;
					$g_sdl = $gross_pay;
				
	
					$g_ordinary_wage -= $unpaid_leave_amount;
	
	
					// other benefit
					$other_benefit_mount = 0;
					$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
					foreach ($other_benefit_list->result() as $benefit_list) {
						$other_benefit_mount += $benefit_list->other_benefit_cost;
					}
	
	
					// statutory_deductions
					$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
					$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
					$statutory_deductions_amount = 0;
					if ($count_statutory_deductions > 0) {
						foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
							if ($system[0]->statutory_fixed != 'yes') {
								$sta_salary = $gross_pay;
								$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $st_amount / 2;
									} else {
										$single_sd = $st_amount;
									}
								} else {
									$single_sd = $st_amount;
								}
								$statutory_deductions_amount += $single_sd;
							} else {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
									} else {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount;
									}
								} else {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount;
								}
								$statutory_deductions_amount += $single_sd;
							}
						}
					} else {
						$statutory_deductions_amount = 0;
					}
	
					// overtime
					$overtime_amount = 0;
					$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
					if ($overtime) {
						$ot_hrs = 0;
						$ot_mins = 0;
						foreach ($overtime as $ot) {
							$total_hours = explode(':', $ot->total_hours);
							$ot_hrs += $total_hours[0];
							$ot_mins += $total_hours[1];
						}
						if ($ot_mins > 0) {
							$ot_hrs += round($ot_mins / 60, 2);
						}
	
						//overtime rate
						$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
						if ($overtime_rate) {
							$rate = $overtime_rate->overtime_pay_rate;
						} else {
							$week_hours = 44;
							$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
							$rate = $rate * 1.5;
						}
	
						if ($ot_hrs > 0) {
							$overtime_amount = round($ot_hrs * $rate, 2);
						}
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$overtime_amount = $overtime_amount / 2;
							}
						}
						$g_ordinary_wage += $overtime_amount;
						$g_sdl += $overtime_amount;
					}
	
					// make payment
					if ($system[0]->is_half_monthly == 1) {
						$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($r->user_id, $pay_date);
						$payment_last = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($r->user_id, $pay_date);
						if ($payment_check->num_rows() > 1) {
	
							//foreach($payment_last as $payment_half_last){
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
							//$mpay = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_make_payment').'"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '" data-company_id="'.$this->input->get("company_id").'"><span class="fa fas fa-money"></span></button></span>';
	
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}
	
							$delete = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code><br>' . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $payment_last[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span><code>' . $this->lang->line('xin_title_second_half') . '</code>';
							//}
							//detail link
							$detail = '';
						} else if ($payment_check->num_rows() > 0) {
	
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
	
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
	
							$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}
							$delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
							$detail = '';
						} else {
	
							$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
							$delete = '';
							//detail link
							$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						}
						//detail link
						//$detail = '';
					} else {
						$payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
						if ($payment_check->num_rows() > 0) {
							$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
							$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
							if ($make_payment[0]->status == 0) {
								$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
							} else if ($make_payment[0]->status == 3) {
								$status = '<span class="label label-warning">' . "Partially" . '</span>';
							}
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
	
							if (in_array('313', $role_resources_ids)) {
								$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
							} else {
								$delete = '';
							}
						} else {
	
							$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '">
								<button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '">
									<span class="fa fas fa-money"></span>
								</button>
							</span>';
							$delete = '';
						}
						//detail link
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					}
	
	
					// employee accommodations
					$employee_accommodations = 0;
					$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
					foreach ($get_employee_accommodations as $get_employee_accommodation) {
						$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
						$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
						if ($period_from == $pay_date || $period_to == $pay_date) {
							if (!empty($get_employee_accommodation->rent_paid)) {
								$employee_accommodations += $get_employee_accommodation->rent_paid;
							}
						}
					}
	
					// employee claims
					$claim_amount = 0;
					$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
					foreach ($get_employee_claims->result() as $claims) {
						$date 	= 	date('m-Y', strtotime($claims->date));
						if ($date == $pay_date) {
							$claim_amount += $claims->amount;
						}
					}
	
	
					$total_earning = $show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
					$total_deduction = $loan_de_amount + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
					$total_net_salary = ($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;
	
				
					// cpf calculation
					$emp_dob = $r->date_of_birth;
					$dob = new DateTime($emp_dob);
	
					$today = new DateTime('01-' . $pay_date);
					$age = $dob->diff($today);
					$age_year = $age->y;
					$age_month = $age->m;
	
					$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
					$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
					$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;
	
					if ($age_month > 0) {
						$age_year = $age_year + 1;
					}
	
					$cpf_employee 	= 	0;
					$cpf_employer	=	0;
					$total_cpf		=	0;
					$cpf_total		=	0;
					$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
					if ($im_status) {
						$immigration_id = $im_status->immigration_id;
						if ($immigration_id == 2) {
							$issue_date = $im_status->issue_date;
							$i_date = new DateTime($issue_date);
							$today = new DateTime();
							$pr_age = $i_date->diff($today);
							$pr_age_year = $pr_age->y;
							$pr_age_month = $pr_age->m;
						}
	
						if ($immigration_id == 1 || $immigration_id == 2) {
	
							$ordinary_wage = $g_ordinary_wage;
							if ($ordinary_wage > $ordinary_wage_cap) {
								$ow = $ordinary_wage_cap;
							} else {
								$ow = $ordinary_wage;
							}
	
							//additional wage
							$additional_wage = $g_additional_wage;
							$aw = $g_additional_wage;
							$tw = $ow + $additional_wage;
							if ($im_status->issue_date != "") {
								if ($pr_age_year == 1) {
	
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(4 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 666) {
												$total_cpf = 666;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(4 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 666) {
												$total_cpf = 666;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(3.5 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(3.5 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
								if ($pr_age_year == 2) {
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(9 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.45 * ($tw - 500));
											$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
											$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
											if ($count_total_cpf > 1776) {
												$total_cpf = 1776;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1110) {
												$cpf_employee = 1110;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(6 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.375 * ($tw - 500));
											$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
											$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
											if ($count_total_cpf > 1369) {
												$total_cpf = 1369;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 925) {
												$cpf_employee = 925;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(3.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.225 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
											$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
											if ($count_total_cpf > 814) {
												$total_cpf = 814;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 555) {
												$cpf_employee = 555;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(3.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
											$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
								if ($pr_age_year == 3 || $pr_age_year > 3) {
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(17 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.6 * ($tw - 500));
											$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
											$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
											if ($count_total_cpf > 2738) {
												$total_cpf = 2738;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1480) {
												$cpf_employee = 1480;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = $total_cpf - $cpf_employee;
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(15.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.51 * ($tw - 500));
											$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
											$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
											if ($count_total_cpf > 2405) {
												$total_cpf = 2405;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1258) {
												$cpf_employee = 1258;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(12 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.345 * ($tw - 500));
											$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
											$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
	
											if ($count_total_cpf > 1739) {
												$total_cpf = 1739;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 851) {
												$cpf_employee = 851;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65 && $age_year <= 70) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(9 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.225 * ($tw - 500));
											$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
											$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
	
											if ($count_total_cpf > 1221) {
												$total_cpf = 1221;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 555) {
												$cpf_employee = 555;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 70) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(7.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
											$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
	
											if ($count_total_cpf > 925) {
												$total_cpf = 925;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
							}
	
	
	
							if ($immigration_id == 1) {
	
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(17 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.6 * ($tw - 500));
										$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
										$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
										if ($count_total_cpf > 2738) {
											$total_cpf = 2738;
										} else {
											$total_cpf = $count_total_cpf;
										}
				
										
										if ($count_cpf_employee > 1480) {
											$cpf_employee = 1480;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(15.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.51 * ($tw - 500));
										$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
				
										$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
										if ($count_total_cpf > 2405) {
											$total_cpf = 2405;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1258) {
											$cpf_employee = 1258;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(12 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.345 * ($tw - 500));
										$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
										$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
				
										if ($count_total_cpf > 1739) {
											$total_cpf = 1739;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 851) {
											$cpf_employee = 851;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65 && $age_year <= 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
				
										if ($count_total_cpf > 1221) {
											$total_cpf = 1221;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(7.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
				
										if ($count_total_cpf > 925) {
											$total_cpf = 925;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
	
							$total_net_salary = $total_net_salary - $cpf_employee;
							$cpf_total = $cpf_employee + $cpf_employer;
	
							$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
							$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
							$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
						}
					}
	
	
					$shg_fund_deduction_amount = 0;
					//Other Fund Contributions
					$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);
	
					if ($employee_contributions && $g_shg > 0) {
						$gross_s = $g_shg;
						$contribution_id = $employee_contributions->contribution_id;
						$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
						$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
						$shg_fund_name = $contribution_type[0]->contribution;
						$shg_fund_deduction_amount += $contribution_amount;
						$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
					}
					$ashg_fund_deduction_amount = 0;
	
					$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
					if ($employee_ashg_contributions  && $g_shg > 0) {
						$gross_s = $g_shg;
						$contribution_id = $employee_ashg_contributions->contribution_id;
						$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
						$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
						$ashg_fund_name = $contribution_type[0]->contribution;
	
						$ashg_fund_deduction_amount += $contribution_amount;
						$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
					}
	
					// echo $total_net_salary;
	
					// $sdl_total_amount = 0;
					// if ($g_sdl > 1 && $g_sdl <= 800) {
					// 	$sdl_total_amount = 2;
					// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
					// 	$sdl_amount = (0.25 * $g_sdl) / 100;
					// 	$sdl_total_amount = $sdl_amount;
					// } elseif ($g_sdl > 4500) {
					// 	$sdl_total_amount = 11.25;
					// }
	
	
					$net_salary = number_format((float)$total_net_salary, 2, '.', '');
					$basic_salary = number_format((float)$basic_salary, 2, '.', '');
					if ($basic_salary == 0 || $basic_salary == '') {
						$fmpay = '';
					} else {
						$fmpay = $mpay;
					}
	
	
					$company_info = $this->Company_model->read_company_information($r->company_id);
					if (!is_null($company_info)) {
						$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
						$net_salary = $this->Xin_model->company_currency_sign($net_salary, $r->company_id);
						//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
					} else {
						//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
						$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary, $r->user_id);
	
						$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary, $r->user_id);
	
						//$net_salary = $this->Xin_model->currency_sign($net_salary);	
						//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
						$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount, $r->user_id);
					}
	
					$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';
	
					//action link
					$act = $detail . $fmpay . $delete;
					// $act = $fmpay . $delete;
	
					if ($r->wages_type == 1) {
						if ($system[0]->is_half_monthly == 1) {
							$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
						} else {
							$emp_payroll_wage = $wages_type;
						}
					} else {
						$emp_payroll_wage = $wages_type;
					}
	
					$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
					$p = $payslips->result();
					$balance = $this->Xin_model->currency_sign($p[0]->balance_amount ?? 0, $r->user_id);
					$mode_of_payment = $r->payment_mode ?? '';
	
	
					return [
						'contribution' 	=>	$shg_fund_deduction_amount + $ashg_fund_deduction_amount,
						'salary'		=>	$net_salary,
						'cpf_employee'	=>	$cpf_employee,
						'unpaid_leave_amount'	=>	$unpaid_leave_amount
					];
					// $data[] = array(
					// 	$act,
					// 	$iemp_name,
					// 	$emp_payroll_wage,
					// 	// $basic_salary,
					// 	$this->Xin_model->currency_sign($gross_pay, $r->user_id),
					// 	$this->Xin_model->currency_sign($cpf_employee, $r->user_id),
					// 	$net_salary,
					// 	$balance,
					// 	$mode_of_payment,
					// 	$status
					// );
				}
			} else {
				$emp_name = $r->first_name . ' ' . $r->last_name;
				// office shift
				$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);

				//overtime request
				$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $this->input->get('month_year'));
				$re_hrs_old_int1 = 0;
				$re_hrs_old_seconds = 0;
				$re_pcount = 0;
				foreach ($overtime_count as $overtime_hr) {
					$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
					$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
					$re_interval_late = $request_clock_in->diff($request_clock_out);
					$re_hours_r  = $re_interval_late->format('%h');
					$re_minutes_r = $re_interval_late->format('%i');
					$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';

					$re_str_time = $re_total_time;

					$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);

					sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$re_hrs_old_int1 += $re_hrs_old_seconds;

					$re_pcount = gmdate("H", $re_hrs_old_int1);
				}

				$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
				$hrs_old_int1 = 0;
				$pcount = 0;
				foreach ($result->result() as $hour_work) {
					$clock_in =  new DateTime($hour_work->clock_in);
					$clock_out =  new DateTime($hour_work->clock_out);
					$interval_late = $clock_in->diff($clock_out);
					$hours_r  = $interval_late->format('%h');
					$minutes_r = $interval_late->format('%i');
					$total_time = $hours_r . ":" . $minutes_r . ":" . '00';

					$str_time = $total_time;

					$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

					sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$hrs_old_int1 += $hrs_old_seconds;

					$pcount = gmdate("H", $hrs_old_int1);
				}
				$pcount = $pcount + $re_pcount;

				// get company
				$company = $this->Xin_model->read_company_info($r->company_id);
				if (!is_null($company)) {
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';
				}

				/**
				 * Local Variable
				 */
				$g_ordinary_wage = 0;
				$g_additional_wage = 0;
				$g_shg = 0;
				$g_sdl = 0;

				// 1: salary type
				if ($r->wages_type == 1) {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				} else if ($r->wages_type == 2) {
					$wages_type = $this->lang->line('xin_employee_daily_wages');
					if ($pcount > 0) {
						$basic_salary = $pcount * $r->basic_salary;
					} else {
						$basic_salary = $pcount;
					}
					$p_class = 'emo_hourly_pay';
					$view_p_class = 'hourlywages_template_modal';
				} else {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				}

				
				// employee deduction
				$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
				$pa_month = date('m-Y', strtotime('01-' . $pay_date));
				$deduction_amount = 0;
				if ($employee_deduction) {
					foreach ($employee_deduction as $deduction) {
						if ($deduction->type_id == 1) {
							$deduction_amount +=  $deduction->amount;
						}
						if ($deduction->type_id == 2) {
							$from_month_year = date('m-Y', strtotime($deduction->from_date));
							$to_month_year = date('d-m-Y', strtotime($deduction->to_date));


							if ($from_month_year != "" && $to_month_year != "") {
								if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
									$deduction_amount +=  $deduction->amount;
								}
							}
						}
					}
				}



				//3: Gross rate of pay (unpaid leave deduction)
				$holidays_count = 0;
				$no_of_working_days = 0;
				$month_start_date = new DateTime('01-' . $pay_date);
				$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
				$month_end_date->modify('+1 day');
				$interval = new DateInterval('P1D');
				$period = new DatePeriod($month_start_date, $interval, $month_end_date);
				foreach ($period as $p) {
					$period_day = $p->format('l');
					$period_date = $p->format('Y-m-d');

					//holidays in a month
					$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
					if ($is_holiday) {
						$holidays_count += 1;
					}

					//working days excluding holidays based on office shift
					if ($period_day == 'Monday') {
						if ($office_shift[0]->monday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Tuesday') {
						if ($office_shift[0]->tuesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Wednesday') {
						if ($office_shift[0]->wednesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Thursday') {
						if ($office_shift[0]->thursday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Friday') {
						if ($office_shift[0]->friday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Saturday') {
						if ($office_shift[0]->saturday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Sunday') {
						if ($office_shift[0]->sunday_in_time != '') {
							$no_of_working_days += 1;
						}
					}
				}

				//unpaid leave
				$unpaid_leave_amount = 0;
				$leaves_taken_count = 0;
				$leave_period = array();
				$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
				if ($unpaid_leaves) {
					foreach ($unpaid_leaves as $k => $l) {
						$pay_date_month = new DateTime('01-' . $pay_date);
						$l_from_date = new DateTime($l->from_date);
						$l_to_date = new DateTime($l->to_date);

						if ($l_from_date->format('m') == $l_to_date->format('m')) {
							$start_date = $l_from_date;
							$end_date = $l_to_date;
						} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
							$start_date = $l_from_date;
							$end_date = new DateTime($start_date->format('Y-m-t'));
						} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
							$start_date = $pay_date_month;
							$end_date = $l_to_date;
						}
						$end_date->modify('+1 day');
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($start_date, $interval, $end_date);
						foreach ($period as $d) {
							$p_day = $d->format('l');
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							}
						}
						$leave_period[$k]['is_half'] = $l->is_half_day;
					}
				}

				// if joining date and pay date same then this logic work
				$month_date_join = date('m-Y', strtotime($r->date_of_joining));
				$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
				$month_last_date = date('m-Y', strtotime($lastday));

				$first_date =  new DateTime($r->date_of_joining);
				$no_of_days_worked = 0;
				$same_month_holidays_count = 0;
				if ($month_date_join == $pay_date) {
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($first_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$p_day = $p->format('l');
						$m_p_date = $p->format('Y-m-d');

						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
						if ($is_holiday) {
							$same_month_holidays_count += 1;
						}

						//working days excluding holidays based on office shift
						if ($p_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_days_worked += 1;
							}
						}
					}

					// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
					$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
				} else {
					// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
					$no_of_days_worked = $no_of_working_days -  $holidays_count;
				}


				if ($month_date_join == $pay_date){
					$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
				}else{
					$show_main_salary = $basic_salary;
				}
			
				$g_ordinary_wage += $show_main_salary;
				$g_shg += $show_main_salary;
				$g_sdl += $show_main_salary;
			
				$g_ordinary_wage -= $deduction_amount;
				$g_shg -= $deduction_amount;
				$g_sdl -= $deduction_amount;


				// 3: all loan/deductions
				$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
				$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
				$loan_de_amount = 0;
				if ($count_loan_deduction > 0) {
					foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
							} else {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
							}
						} else {
							$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
						}
						$loan_de_amount += $er_loan;
					}
				} else {
					$loan_de_amount = 0;
				}
				$loan_de_amount = round($loan_de_amount, 2);

				// 2: all allowances
				$allowance_amount = 0;
				$gross_allowance_amount = 0;
				$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
				if ($salary_allowances) {
					foreach ($salary_allowances as $sa) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$eallowance_amount = $sa->allowance_amount / 2;
							} else {
								$eallowance_amount = $sa->allowance_amount;
							}
						} else {
							$eallowance_amount = $sa->allowance_amount;
						}


						if (!empty($sa->salary_month)) {
							$g_additional_wage += $eallowance_amount;
						} else {
							if ($month_date_join == $pay_date){
								$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
							}
							$g_ordinary_wage += $eallowance_amount;
							if ($sa->id == 2) {
								$gross_allowance_amount = $eallowance_amount;
							}
						}

						if ($sa->sdl == 1) {
							$g_sdl += $eallowance_amount;
						}
						if ($sa->shg == 1) {
							$g_shg += $eallowance_amount;
						}

						$allowance_amount += $eallowance_amount;
					}
				}
				$gross_allowance_amount = $allowance_amount;




				// commissions
				$commissions_amount = 0;
				$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
				if ($commissions) {
					foreach ($commissions as $c) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$ecommissions_amount = $c->commission_amount / 2;
							} else {
								$ecommissions_amount = $c->commission_amount;
							}
						} else {
							$ecommissions_amount = $c->commission_amount;
						}

						if ($c->commission_type == 9) {
							$g_ordinary_wage += $ecommissions_amount;
						} elseif ($c->commission_type == 10) {
							$g_additional_wage += $ecommissions_amount;
						}

						if ($c->sdl == 1) {
							$g_sdl += $ecommissions_amount;
						}
						if ($c->shg == 1) {
							$g_shg += $ecommissions_amount;
						}

						$commissions_amount += $ecommissions_amount;
					}
				}

				//share options
				$share_options_amount = 0;
				$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
				if ($share_options) {
					$eebr_amount = 0;
					$eris_amount = 0;
					foreach ($share_options as $s) {
						$scheme = $s->so_scheme;
						if ($scheme == 1) {
							$price_doe = $s->price_date_of_excercise;
							$price_ex = $s->excercise_price;
							$no_shares = $s->no_of_shares;
							$amount = ($price_doe - $price_ex) * $no_shares;
							$eebr_amount += $amount;
						} else {
							$price_doe = $s->price_date_of_excercise;
							$price_dog = $s->price_date_of_grant;
							$price_ex = $s->excercise_price;
							$no_shares = $s->no_of_shares;

							$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
							$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
							$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
						}
					}
					$share_options_amount = round($eebr_amount + $eris_amount, 2);
					$g_additional_wage += $share_options_amount;
					$g_sdl += $share_options_amount;
					$g_shg += $share_options_amount;
				}

				// otherpayments
				$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
				$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
				$other_payments_amount = 0;
				if ($count_other_payments > 0) {
					foreach ($other_payments->result() as $sl_other_payments) {
						if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
							if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$epayments_amount = $sl_other_payments->payments_amount / 2;
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}
								} else {
									$epayments_amount = $sl_other_payments->payments_amount;
								}

								if ($sl_other_payments->cpf_applicable == 1) {
									$g_additional_wage += $epayments_amount;
									$g_shg += $epayments_amount;
									$g_sdl += $epayments_amount;
								}

								$other_payments_amount += $epayments_amount;
							}
						} else {
							$first_date = new DateTime($sl_other_payments->date);
							if ($first_date->format('m-Y') == $pay_date) {
								$first_date =  new DateTime($sl_other_payments->date);
							} else {
								$first_date = new DateTime('01-' . $pay_date);
							}

							$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));

							if (!empty($sl_other_payments->end_date)) {
								$last_date = new DateTime($sl_other_payments->end_date);
								if ($last_date->format('m-Y') == $pay_date) {
									$last_date = new DateTime($sl_other_payments->end_date);
								}else if($last_date->format('m-Y') >= $pay_date){
									$last_date = $month_end_date_for_other;
								} else {
									$last_date = '';
								}
							} else {
								$last_date = $month_end_date_for_other;
							}
							if(!empty($last_date)){
								$last_date->modify('+1 day');
								$final_last_day = new DateTime($last_date->format('d-m-Y'));
								if ($final_last_day->format('m-Y') >= $pay_date) {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$epayments_amount = $sl_other_payments->payments_amount / 2;
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}


									// it for no of working day
									$no_of_days_worked_for_other_payment = 0;
									$same_month_holidays_count_for_other_payment = 0;
									$interval = new DateInterval('P1D');
									$period = new DatePeriod($first_date, $interval, $last_date);
									foreach ($period as $p) {
										$p_day = $p->format('l');
										$p_date = $p->format('Y-m-d');

										//holidays in a month

										$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
										if ($is_holiday) {
											$same_month_holidays_count_for_other_payment += 1;
										}

										//working days excluding holidays based on office shift
										if ($p_day == 'Monday') {
											if ($office_shift[0]->monday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Tuesday') {
											if ($office_shift[0]->tuesday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Wednesday') {
											if ($office_shift[0]->wednesday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Thursday') {
											if ($office_shift[0]->thursday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Friday') {
											if ($office_shift[0]->friday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Saturday') {
											if ($office_shift[0]->saturday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Sunday') {
											if ($office_shift[0]->sunday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										}
									}

									$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
									
									$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);
									


									if ($sl_other_payments->cpf_applicable == 1) {
										$g_additional_wage += $epayments_amount;
										$g_shg += $epayments_amount;
										$g_sdl += $epayments_amount;
									}
									$other_payments_amount += $epayments_amount;
								}
							}
						}
					}
				} else {
					$other_payments_amount = 0;
				}


				$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
	
				if ($unpaid_leaves) {
					$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
				}

				// $g_ordinary_wage = $gross_pay;
				$g_shg = $gross_pay;
				$g_sdl = $gross_pay;
			

				$g_ordinary_wage -= $unpaid_leave_amount;


				// other benefit
				$other_benefit_mount = 0;
				$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
				foreach ($other_benefit_list->result() as $benefit_list) {
					$other_benefit_mount += $benefit_list->other_benefit_cost;
				}


				// statutory_deductions
				$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
				$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
				$statutory_deductions_amount = 0;
				if ($count_statutory_deductions > 0) {
					foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
						if ($system[0]->statutory_fixed != 'yes') {
							$sta_salary = $gross_pay;
							$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$single_sd = $st_amount / 2;
								} else {
									$single_sd = $st_amount;
								}
							} else {
								$single_sd = $st_amount;
							}
							$statutory_deductions_amount += $single_sd;
						} else {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
								} else {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount;
								}
							} else {
								$single_sd = $sl_salary_statutory_deductions->deduction_amount;
							}
							$statutory_deductions_amount += $single_sd;
						}
					}
				} else {
					$statutory_deductions_amount = 0;
				}

				// overtime
				$overtime_amount = 0;
				$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
				if ($overtime) {
					$ot_hrs = 0;
					$ot_mins = 0;
					foreach ($overtime as $ot) {
						$total_hours = explode(':', $ot->total_hours);
						$ot_hrs += $total_hours[0];
						$ot_mins += $total_hours[1];
					}
					if ($ot_mins > 0) {
						$ot_hrs += round($ot_mins / 60, 2);
					}

					//overtime rate
					$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
					if ($overtime_rate) {
						$rate = $overtime_rate->overtime_pay_rate;
					} else {
						$week_hours = 44;
						$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
						$rate = $rate * 1.5;
					}

					if ($ot_hrs > 0) {
						$overtime_amount = round($ot_hrs * $rate, 2);
					}
					if ($system[0]->is_half_monthly == 1) {
						if ($system[0]->half_deduct_month == 2) {
							$overtime_amount = $overtime_amount / 2;
						}
					}
					$g_ordinary_wage += $overtime_amount;
					$g_sdl += $overtime_amount;
				}

				// make payment
				if ($system[0]->is_half_monthly == 1) {
					$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($r->user_id, $pay_date);
					$payment_last = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($r->user_id, $pay_date);
					if ($payment_check->num_rows() > 1) {

						//foreach($payment_last as $payment_half_last){
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
						//$mpay = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_payroll_make_payment').'"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".'.$p_class.'" data-employee_id="'. $r->user_id . '" data-payment_date="'. $p_date . '" data-company_id="'.$this->input->get("company_id").'"><span class="fa fas fa-money"></span></button></span>';

						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}

						$delete = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code><br>' . '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $payment_last[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $payment_last[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span><code>' . $this->lang->line('xin_title_second_half') . '</code>';
						//}
						//detail link
						$detail = '';
					} else if ($payment_check->num_rows() > 0) {

						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';

						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';

						$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
						$delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
						$detail = '';
					} else {

						$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
						$delete = '';
						//detail link
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					}
					//detail link
					//$detail = '';
				} else {
					$payment_check = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
					if ($payment_check->num_rows() > 0) {
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

						if ($make_payment[0]->status == 0) {
							$status = '<span class="label label-success">' . $this->lang->line('xin_payroll_paid') . '</span>';
						} else if ($make_payment[0]->status == 3) {
							$status = '<span class="label label-warning">' . "Partially" . '</span>';
						}
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $make_payment[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
					} else {

						$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '">
							<button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '">
								<span class="fa fas fa-money"></span>
							</button>
						</span>';
						$delete = '';
					}
					//detail link
					$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
				}


				// employee accommodations
				$employee_accommodations = 0;
				$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
				foreach ($get_employee_accommodations as $get_employee_accommodation) {
					$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
					$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
					if ($period_from == $pay_date || $period_to == $pay_date) {
						if (!empty($get_employee_accommodation->rent_paid)) {
							$employee_accommodations += $get_employee_accommodation->rent_paid;
						}
					}
				}

				// employee claims
				$claim_amount = 0;
				$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
				foreach ($get_employee_claims->result() as $claims) {
					$date 	= 	date('m-Y', strtotime($claims->date));
					if ($date == $pay_date) {
						$claim_amount += $claims->amount;
					}
				}


				$total_earning = $show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
				$total_deduction = $loan_de_amount + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
				$total_net_salary = ($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;

			
				// cpf calculation
				$emp_dob = $r->date_of_birth;
				$dob = new DateTime($emp_dob);

				$today = new DateTime('01-' . $pay_date);
				$age = $dob->diff($today);
				$age_year = $age->y;
				$age_month = $age->m;

				$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
				$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
				$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;

				if ($age_month > 0) {
					$age_year = $age_year + 1;
				}

				$cpf_employee 	= 	0;
				$cpf_employer	=	0;
				$total_cpf		=	0;
				$cpf_total		=	0;
				$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
				if ($im_status) {
					$immigration_id = $im_status->immigration_id;
					if ($immigration_id == 2) {
						$issue_date = $im_status->issue_date;
						$i_date = new DateTime($issue_date);
						$today = new DateTime();
						$pr_age = $i_date->diff($today);
						$pr_age_year = $pr_age->y;
						$pr_age_month = $pr_age->m;
					}

					if ($immigration_id == 1 || $immigration_id == 2) {

						$ordinary_wage = $g_ordinary_wage;
						if ($ordinary_wage > $ordinary_wage_cap) {
							$ow = $ordinary_wage_cap;
						} else {
							$ow = $ordinary_wage;
						}

						//additional wage
						$additional_wage = $g_additional_wage;
						$aw = $g_additional_wage;
						$tw = $ow + $additional_wage;
						if ($im_status->issue_date != "") {
							if ($pr_age_year == 1) {

								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(4 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 666) {
											$total_cpf = 666;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(4 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 666) {
											$total_cpf = 666;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(3.5 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(3.5 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
							if ($pr_age_year == 2) {
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.45 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
										$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
										if ($count_total_cpf > 1776) {
											$total_cpf = 1776;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1110) {
											$cpf_employee = 1110;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(6 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.375 * ($tw - 500));
										$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
										$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
										if ($count_total_cpf > 1369) {
											$total_cpf = 1369;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 925) {
											$cpf_employee = 925;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(3.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
										if ($count_total_cpf > 814) {
											$total_cpf = 814;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(3.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
							if ($pr_age_year == 3 || $pr_age_year > 3) {
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(17 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.6 * ($tw - 500));
										$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
										$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
										if ($count_total_cpf > 2738) {
											$total_cpf = 2738;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1480) {
											$cpf_employee = 1480;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = $total_cpf - $cpf_employee;
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(15.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.51 * ($tw - 500));
										$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
										$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
										if ($count_total_cpf > 2405) {
											$total_cpf = 2405;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1258) {
											$cpf_employee = 1258;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(12 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.345 * ($tw - 500));
										$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
										$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;

										if ($count_total_cpf > 1739) {
											$total_cpf = 1739;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 851) {
											$cpf_employee = 851;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65 && $age_year <= 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;

										if ($count_total_cpf > 1221) {
											$total_cpf = 1221;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(7.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;

										if ($count_total_cpf > 925) {
											$total_cpf = 925;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
						}



						if ($immigration_id == 1) {

							if ($age_year <= 55) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(17 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.6 * ($tw - 500));
									$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
									$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
									if ($count_total_cpf > 2738) {
										$total_cpf = 2738;
									} else {
										$total_cpf = $count_total_cpf;
									}
			
									
									if ($count_cpf_employee > 1480) {
										$cpf_employee = 1480;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 55 && $age_year <= 60) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(15.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.51 * ($tw - 500));
									$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
			
									$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
									if ($count_total_cpf > 2405) {
										$total_cpf = 2405;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 1258) {
										$cpf_employee = 1258;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 60 && $age_year <= 65) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(12 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.345 * ($tw - 500));
									$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
									$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
			
									if ($count_total_cpf > 1739) {
										$total_cpf = 1739;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 851) {
										$cpf_employee = 851;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 65 && $age_year <= 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(9 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.225 * ($tw - 500));
									$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
									$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
			
									if ($count_total_cpf > 1221) {
										$total_cpf = 1221;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 555) {
										$cpf_employee = 555;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(7.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.15 * ($tw - 500));
									$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
									$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
			
									if ($count_total_cpf > 925) {
										$total_cpf = 925;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 370) {
										$cpf_employee = 370;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							}
						}

						$total_net_salary = $total_net_salary - $cpf_employee;
						$cpf_total = $cpf_employee + $cpf_employer;

						$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
						$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
						$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
					}
				}


				$shg_fund_deduction_amount = 0;
				//Other Fund Contributions
				$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);

				if ($employee_contributions && $g_shg > 0) {
					$gross_s = $g_shg;
					$contribution_id = $employee_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					$shg_fund_name = $contribution_type[0]->contribution;
					$shg_fund_deduction_amount += $contribution_amount;
					$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
				}
				$ashg_fund_deduction_amount = 0;

				$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
				if ($employee_ashg_contributions  && $g_shg > 0) {
					$gross_s = $g_shg;
					$contribution_id = $employee_ashg_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					$ashg_fund_name = $contribution_type[0]->contribution;

					$ashg_fund_deduction_amount += $contribution_amount;
					$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
				}

				// echo $total_net_salary;

				// $sdl_total_amount = 0;
				// if ($g_sdl > 1 && $g_sdl <= 800) {
				// 	$sdl_total_amount = 2;
				// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
				// 	$sdl_amount = (0.25 * $g_sdl) / 100;
				// 	$sdl_total_amount = $sdl_amount;
				// } elseif ($g_sdl > 4500) {
				// 	$sdl_total_amount = 11.25;
				// }


				$net_salary = number_format((float)$total_net_salary, 2, '.', '');
				$basic_salary = number_format((float)$basic_salary, 2, '.', '');
				if ($basic_salary == 0 || $basic_salary == '') {
					$fmpay = '';
				} else {
					$fmpay = $mpay;
				}


				$company_info = $this->Company_model->read_company_information($r->company_id);
				if (!is_null($company_info)) {
					$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
					$net_salary = $this->Xin_model->company_currency_sign($net_salary, $r->company_id);
					//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
				} else {
					//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
					$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary, $r->user_id);

					$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary, $r->user_id);

					//$net_salary = $this->Xin_model->currency_sign($net_salary);	
					//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
					$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount, $r->user_id);
				}

				$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';

				//action link
				$act = $detail . $fmpay . $delete;
				// $act = $fmpay . $delete;

				if ($r->wages_type == 1) {
					if ($system[0]->is_half_monthly == 1) {
						$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
					} else {
						$emp_payroll_wage = $wages_type;
					}
				} else {
					$emp_payroll_wage = $wages_type;
				}

				$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
				$p = $payslips->result();
				$balance = $this->Xin_model->currency_sign($p[0]->balance_amount ?? 0, $r->user_id);
				$mode_of_payment = $r->payment_mode ?? '';


				return [
					'contribution' 	=>	$shg_fund_deduction_amount + $ashg_fund_deduction_amount,
					'salary'		=>	$net_salary,
					'cpf_employee'	=>	$cpf_employee,
					'unpaid_leave_amount'	=>	$unpaid_leave_amount
				];
				// $data[] = array(
				// 	$act,
				// 	$iemp_name,
				// 	$emp_payroll_wage,
				// 	// $basic_salary,
				// 	$this->Xin_model->currency_sign($gross_pay, $r->user_id),
				// 	$this->Xin_model->currency_sign($cpf_employee, $r->user_id),
				// 	$net_salary,
				// 	$balance,
				// 	$mode_of_payment,
				// 	$status
				// );
			}
		}
	}


	// Validate and add info in database > add monthly payment
	public function add_pay_monthly()
	{
		if ($this->input->post('add_type') == 'add_monthly_payment') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			$basic_salary = $this->input->post('gross_salary');

			$system = $this->Xin_model->read_setting_info(1);
			$user = $this->Xin_model->read_user_info($this->input->post('emp_id'));
			// office shift
			$office_shift = $this->Timesheet_model->read_office_shift_information($this->input->post('office_shift_id'));

			if ($system[0]->is_half_monthly == 1) {
				$is_half_monthly_payroll = 1;
			} else {
				$is_half_monthly_payroll = 0;
			}
		

			$jurl = random_string('alnum', 40);

			$basic_salary = 0;
			$gross_salary = 0;
			$leave_deductions = 0;
			$total_allowances = 0;
			$total_loan = 0;
			$total_overtime = 0;
			$total_commissions = 0;
			$total_statutory_deductions = 0;
			$total_employee_deduction = 0;
			$employee_claim = 0;
			$total_other_payments = 0;
			$total_share = 0;
			$additional_allowances = 0;

			if ($this->input->post('chk_gross_salary')) {
				$basic_salary = $this->input->post('gross_salary');
				$gross_salary = $this->input->post('gross_salary');
			}

			// if ($this->input->post('chk_leave_deductions')) {
				$leave_deductions = $this->input->post('leave_deductions');
			// }

			if ($this->input->post('chk_total_allowances')) {
				$total_allowances = $this->input->post('total_allowances');
			}

			if ($this->input->post('chk_total_commissions')) {
				$total_commissions = $this->input->post('total_commissions');
			}

			if ($this->input->post('chk_loan_de_amount')) {
				$total_loan = $this->input->post('total_loan');
			}

			if ($this->input->post('chk_total_overtime')) {
				$total_overtime = $this->input->post('total_overtime');
			}

			if ($this->input->post('chk_total_statutory_deductions') || $this->input->post('chk_gross_salary') || $this->input->post('chk_total_allowances') || $this->input->post('chk_total_other_payments')) {
				$total_statutory_deductions = $this->input->post('total_statutory_deductions');
			}

			if ($this->input->post('chk_total_other_payments')) {
				$total_other_payments = $this->input->post('total_other_payments');
			}

			if ($this->input->post('chk_total_employee_deduction')) {
				$total_employee_deduction = $this->input->post('total_employee_deduction');
			}

			if ($this->input->post('chk_employee_claim')) {
				$employee_claim = $this->input->post('employee_claim');
			}

			if ($this->input->post('chk_total_share')) {
				$total_share = $this->input->post('total_share');
			}

			

			$data = array(
				'employee_id' 			=> 	$this->input->post('emp_id'),
				'department_id' 		=> 	$this->input->post('department_id'),
				'company_id' 			=> 	$this->input->post('company_id'),
				'location_id' 			=> 	$this->input->post('location_id'),
				'designation_id' 		=> 	$this->input->post('designation_id'),
				'salary_month' 			=> 	$this->input->post('pay_date'),
				'basic_salary' 			=> 	$basic_salary,
				'gross_salary' 			=> 	$gross_salary,
				'net_salary' 			=> 	$this->input->post('net_salary'),
				'wages_type' 			=> 	$this->input->post('wages_type'),
				'hours_worked'			=>	$this->input->post('total_working_hour'),
				'is_half_monthly_payroll' 		=> 	$is_half_monthly_payroll,
				'total_commissions' 			=> 	$total_commissions,
				'total_statutory_deductions' 	=> $total_statutory_deductions,
				'total_other_payments' 	=> 	$total_other_payments,
				'total_allowances' 		=> 	$total_allowances,
				'total_loan' 			=> 	$total_loan,
				'total_overtime' 		=> 	$this->input->post('total_overtime_time'),
				'total_overtime_amount' => 	$total_overtime,
				'claim_amount' 			=> 	$employee_claim,
				'cpf_employee_amount' 	=> 	$this->input->post('total_cpf_employee'),
				'cpf_employer_amount' 	=> 	$this->input->post('total_cpf_employer'),
				'leave_deduction' 		=> 	$leave_deductions,
				'contribution_fund' 	=> 	$this->input->post('total_fund_contribution'),
				'share_option_amount' 	=> 	$total_share,
				'additonal_allowance' 	=> 	$additional_allowances,
				'deduction_amount' 		=> 	$total_employee_deduction,
				'balance_amount' 		=> 	$this->input->post('balance_amount'),
				'is_payment' 			=> 	'1',
				'status' 				=> 	$this->input->post('balance_amount') > 0 ? 2 : 1,
				'payslip_type' 			=> 	'full_monthly',
				'payslip_key' 			=> 	$jurl,
				'year_to_date' 			=> 	date('d-m-Y'),
				'created_at' 			=> 	date('d-m-Y h:i:s'),
				'check_id'				=>	$this->input->post('check_id'),
				'payment_mode'			=>	$this->input->post('payment_mode'),
			);
			$result = $this->Payroll_model->add_salary_payslip($data);

			if ($result) {

				$user = $this->Xin_model->read_user_info($this->input->post('emp_id'));

				$basic_salary = $basic_salary;

				$g_ordinary_wage = 0;
				$g_additional_wage = 0;
				$g_shg = 0;
				$g_sdl = 0;

				$g_ordinary_wage += $basic_salary;
				$g_shg += $basic_salary;
				$g_sdl += $basic_salary;

				

				//3: Gross rate of pay (unpaid leave deduction)
				$holidays_count = 0;
				$no_of_working_days = 0;
				$month_start_date = new DateTime('01-' . $this->input->post('pay_date'));
				$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
				$month_end_date->modify('+1 day');
				$interval = new DateInterval('P1D');
				$period = new DatePeriod($month_start_date, $interval, $month_end_date);
				foreach ($period as $p) {
					$p_day = $p->format('l');
					$p_date = $p->format('Y-m-d');

					//holidays in a month
					$is_holiday = $this->Timesheet_model->is_holiday_on_date($this->input->post('company_id'), $p_date);
					if ($is_holiday) {
						$holidays_count += 1;
					}

					//working days excluding holidays based on office shift
					if ($p_day == 'Monday') {
						if ($office_shift[0]->monday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($p_day == 'Tuesday') {
						if ($office_shift[0]->tuesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($p_day == 'Wednesday') {
						if ($office_shift[0]->wednesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($p_day == 'Thursday') {
						if ($office_shift[0]->thursday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($p_day == 'Friday') {
						if ($office_shift[0]->friday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($p_day == 'Saturday') {
						if ($office_shift[0]->saturday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($p_day == 'Sunday') {
						if ($office_shift[0]->sunday_in_time != '') {
							$no_of_working_days += 1;
						}
					}
				}

				//unpaid leave
				$unpaid_leave_amount = 0;
				$leaves_taken_count = 0;
				$leave_period = array();
				$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($this->input->post('emp_id'), $this->input->post('pay_date'));
				if ($unpaid_leaves) {
					foreach ($unpaid_leaves as $k => $l) {
						$pay_date_month = new DateTime('01-' . $this->input->post('pay_date'));
						$l_from_date = new DateTime($l->from_date);
						$l_to_date = new DateTime($l->to_date);

						if ($l_from_date->format('m') == $l_to_date->format('m')) {
							$start_date = $l_from_date;
							$end_date = $l_to_date;
						} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
							$start_date = $l_from_date;
							$end_date = new DateTime($start_date->format('Y-m-t'));
						} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
							$start_date = $pay_date_month;
							$end_date = $l_to_date;
						}
						$end_date->modify('+1 day');
						$interval = new DateInterval('P1D');
						$holiday_array_new = array();

						$period = new DatePeriod($start_date, $interval, $end_date);
						foreach ($period as $d) {
							$p_day = $d->format('l');
							if ($p_day == 'Monday') {

								if ($office_shift[0]->monday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							}
						}
						$leave_period[$k]['is_half'] = $l->is_half_day;
					}
				}
				
				
				$month_date_join = date('m-Y', strtotime($user[0]->date_of_joining));
				$lastday = date('t-m-Y', strtotime("01-" . $p_date));
				$month_last_date = date('m-Y', strtotime($lastday));

				$first_date =  new DateTime($user[0]->date_of_joining);
				$no_of_days_worked = 0;
				$same_month_holidays_count = 0;
				if ($month_date_join == $this->input->post('pay_date')) {
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($first_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$p_day = $p->format('l');
						$p_date = $p->format('Y-m-d');

						//holidays in a month

						$is_holiday = $this->Timesheet_model->is_holiday_on_date($this->input->post('company_id'), $p_date);
						if ($is_holiday) {
							$same_month_holidays_count += 1;
						}

						//working days excluding holidays based on office shift
						if ($p_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_days_worked += 1;
							}
						}
					}

					// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
					$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
				} else {
					// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
					$no_of_days_worked = $no_of_working_days -  $holidays_count;
				}

				
				if ($month_date_join == $this->input->post('pay_date')) {
					$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
				} else {
					$show_main_salary = $basic_salary;
				}

				

				// set allowance
				$allowance_amount = 0;
				$gross_allowance_amount = 0;
				if ($this->input->post('chk_total_allowances')) {
					$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($this->input->post('emp_id'), $this->input->post('pay_date'));
					if ($salary_allowances) {
						foreach ($salary_allowances as $sa) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$eallowance_amount = $sa->allowance_amount / 2;
								} else {
									$eallowance_amount = $sa->allowance_amount;
								}
							} else {
								$eallowance_amount = $sa->allowance_amount;
							}
							if (!empty($sa->salary_month)) {
								$g_additional_wage += $eallowance_amount;
							} else {
								// for no of working day
								if ($month_date_join == $this->input->post('pay_date')) {
									$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
								}
								$g_ordinary_wage += $eallowance_amount;
								if ($sa->id == 2) {
									$gross_allowance_amount = $eallowance_amount;
								}
							}
				
							if ($sa->sdl == 1) {
								$g_sdl += $eallowance_amount;
							}
							if ($sa->shg == 1) {
								$g_shg += $eallowance_amount;
							}
				
							$allowance_amount += $eallowance_amount;

							$allowance_data = array(
								'payslip_id' 		=> 	$result,
								'employee_id' 		=> 	$this->input->post('emp_id'),
								'salary_month' 		=> 	$this->input->post('pay_date'),
								'allowance_title' 	=> 	$sa->allowance_title,
								'allowance_amount' 	=> 	$eallowance_amount,
								'created_at' 		=> 	date('d-m-Y h:i:s')
							);
							$_allowance_data = $this->Payroll_model->add_salary_payslip_allowances($allowance_data);
	
						}
					}
				}
				$gross_allowance_amount = $allowance_amount;


				// employee deduction
				$employee_deduction = $this->Payroll_model->get_deduction_detail($this->input->post('emp_id'), $this->input->post('pay_date'));
				if ($this->input->post('chk_total_employee_deduction')) {
					foreach ($employee_deduction as $deduction) {
						$xin_salary_deduction = array(
							'payslip_id' 		=> 	$result,
							'employee_id' 		=> 	$this->input->post('emp_id'),
							'salary_month' 		=> 	$this->input->post('pay_date'),
							'name' 				=> 	$deduction->deduction_type,
							'amount' 			=> 	$deduction->amount,

						);
						$this->Payroll_model->add_salary_payslip_deduction($xin_salary_deduction);
					}

					$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($this->input->post('emp_id'), $this->input->post('pay_date'));
					foreach ($other_benefit_list->result() as $other_benefit) {
						$xin_salary_deduction = array(
							'payslip_id' 		=> 	$result,
							'employee_id' 		=> 	$this->input->post('emp_id'),
							'salary_month' 		=> 	$this->input->post('pay_date'),
							'name' 				=> 	$other_benefit->other_benefit,
							'amount' 			=> 	$other_benefit->other_benefit_cost,

						);
						$this->Payroll_model->add_salary_payslip_deduction($xin_salary_deduction);
					}

					$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($this->input->post('emp_id'), $this->input->post('pay_date'));

					foreach ($get_employee_accommodations as $get_employee_accommodation) {
						$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
						$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
						if ($period_from == $this->input->post('pay_date') || $period_to == $this->input->post('pay_date')) {
							$xin_salary_deduction = array(
								'payslip_id' 		=> 	$result,
								'employee_id' 		=> 	$this->input->post('emp_id'),
								'salary_month' 		=> 	$this->input->post('pay_date'),
								'name' 				=> 	$get_employee_accommodation->title,
								'amount' 			=> 	$get_employee_accommodation->rent_paid,
	
							);
							$this->Payroll_model->add_salary_payslip_deduction($xin_salary_deduction);
						}
					}

				}


				// commissions
				if ($this->input->post('chk_total_commissions')) {
					$commission_amount = 0;
					$commissions = $this->Employees_model->getEmployeeMonthlyCommission($this->input->post('emp_id'), $this->input->post('pay_date'));
					if ($commissions) {
						foreach ($commissions as $c) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$ecommission_amount = $c->commission_amount / 2;
								} else {
									$ecommission_amount = $c->commission_amount;
								}
							} else {
								$ecommission_amount = $c->commission_amount;
							}

							if ($c->commission_type == 9) {
								$g_ordinary_wage += $ecommission_amount;
							} elseif ($c->commission_type == 10) {
								$g_additional_wage += $ecommission_amount;
							}

							if ($c->sdl == 1) {
								$g_sdl += $ecommission_amount;
							}
							if ($c->shg == 1) {
								$g_shg += $ecommission_amount;
							}

							$commissions_data = array(
								'payslip_id' 		=> 	$result,
								'employee_id' 		=> 	$this->input->post('emp_id'),
								'salary_month' 		=> 	$this->input->post('pay_date'),
								'commission_id' 	=> 	$c->commission_type,
								'commission_amount' => 	$ecommission_amount,
								'created_at' 		=> 	date('d-m-Y h:i:s')
							);
							$this->Payroll_model->add_salary_payslip_commissions($commissions_data);
							$commission_amount += $ecommission_amount;
						}
					}
				}

				//share options
				if ($this->input->post('chk_total_share')) {
					$share_options_amount = 0;
					$share_options = $this->Employees_model->getEmployeeShareOptions($this->input->post('emp_id'), $this->input->post('pay_date'));
					if ($share_options) {
						$eebr_amount = 0;
						$eris_amount = 0;
						foreach ($share_options as $s) {
							$scheme = $s->so_scheme;
							if ($scheme == 1) {
								$price_doe = $s->price_date_of_excercise;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
								$amount = ($price_doe - $price_ex) * $no_shares;
								$eebr_amount += $amount;
							} else {
								$price_doe = $s->price_date_of_excercise;
								$price_dog = $s->price_date_of_grant;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;

								$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
								$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
								$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
							}
						}
						$share_options_amount = round($eebr_amount + $eris_amount, 2);
						$g_additional_wage += $share_options_amount;
						$g_sdl += $share_options_amount;
						$g_shg += $share_options_amount;

						$share_options_data = array(
							'payslip_id' 		=> 	$result,
							'employee_id' 		=> 	$this->input->post('emp_id'),
							'salary_month' 		=> 	$this->input->post('pay_date'),
							'amount' 			=> 	round($share_options_amount, 2)
						);
						$this->Payroll_model->add_salary_payslip_share_options($share_options_data);
					}
				}


				// set other payments
				$other_payments_amount = 0;
				if ($this->input->post('chk_total_other_payments')) {
					$salary_other_payments = $this->Employees_model->read_salary_other_payments($this->input->post('emp_id'));
					$count_other_payment = $this->Employees_model->count_employee_other_payments($this->input->post('emp_id'));
					if ($count_other_payment > 0) {
						foreach ($salary_other_payments as $sl_other_payments) {
							if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
								if ($this->input->post('pay_date') == date('m-Y', strtotime($sl_other_payments->date))) {

									$esl_other_payments = $sl_other_payments->payments_amount;
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$epayments_amount = $esl_other_payments / 2;
										} else {
											$epayments_amount = $esl_other_payments;
										}
									} else {
										$epayments_amount = $esl_other_payments;
									}

									if ($sl_other_payments->cpf_applicable == 1) {
										$g_additional_wage += $epayments_amount;
										$g_shg += $epayments_amount;
										$g_sdl += $epayments_amount;
									}
									$other_payments_amount += $epayments_amount;
									$other_payments_data = array(
										'payslip_id' 		=> 	$result,
										'employee_id' 		=> 	$this->input->post('emp_id'),
										'salary_month' 		=> 	$this->input->post('pay_date'),
										'payments_title' 	=> 	$sl_other_payments->payments_title,
										'payments_amount' 	=> 	$epayments_amount,
										'created_at' 		=> 	date('d-m-Y h:i:s')
									);
									$this->Payroll_model->add_salary_payslip_other_payments($other_payments_data);
								}
							} else {
								$first_date = new DateTime($sl_other_payments->date);
								if ($first_date->format('m-Y') == $this->input->post('pay_date')) {
									$first_date =  new DateTime($sl_other_payments->date);
								} else {
									$first_date = new DateTime('01-' . $this->input->post('pay_date'));
								}
				
								$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));
				
								if (!empty($sl_other_payments->end_date)) {
									$last_date = new DateTime($sl_other_payments->end_date);
									if ($last_date->format('m-Y') == $this->input->post('pay_date')) {
										$last_date = new DateTime($sl_other_payments->end_date);
									} else if ($last_date->format('m-Y') >= $this->input->post('pay_date')) {
										$last_date = $month_end_date_for_other;
									} else {
										$last_date = '';
									}
								} else {
									$last_date = $month_end_date_for_other;
								}
				
								if (!empty($last_date)) {
									$last_date->modify('+1 day');
									$final_last_day = new DateTime($last_date->format('d-m-Y'));
									if ($final_last_day->format('m-Y') >= $this->input->post('pay_date')) {
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$epayments_amount = $sl_other_payments->payments_amount / 2;
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
				
				
										// it for no of working day
										$no_of_days_worked_for_other_payment = 0;
										$same_month_holidays_count_for_other_payment = 0;
										$interval = new DateInterval('P1D');
										$period = new DatePeriod($first_date, $interval, $last_date);
										foreach ($period as $p) {
											$p_day = $p->format('l');
											$p_date = $p->format('Y-m-d');
				
											//holidays in a month
				
											$is_holiday = $this->Timesheet_model->is_holiday_on_date($this->input->post('company_id'), $p_date);
											if ($is_holiday) {
												$same_month_holidays_count_for_other_payment += 1;
											}
				
											//working days excluding holidays based on office shift
											if ($p_day == 'Monday') {
												if ($office_shift[0]->monday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Tuesday') {
												if ($office_shift[0]->tuesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Wednesday') {
												if ($office_shift[0]->wednesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Thursday') {
												if ($office_shift[0]->thursday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Friday') {
												if ($office_shift[0]->friday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Saturday') {
												if ($office_shift[0]->saturday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Sunday') {
												if ($office_shift[0]->sunday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											}
										}
				
										$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
										$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);
				
				
										if ($sl_other_payments->cpf_applicable == 1) {
											$g_additional_wage += $epayments_amount;
											$g_shg += $epayments_amount;
											$g_sdl += $epayments_amount;
										}
										$other_payments_amount += $epayments_amount;
										$other_payments_data = array(
											'payslip_id' 		=> 	$result,
											'employee_id' 		=> 	$this->input->post('emp_id'),
											'salary_month' 		=> 	$this->input->post('pay_date'),
											'payments_title' 	=> 	$sl_other_payments->payments_title,
											'payments_amount' 	=> 	$epayments_amount,
											'created_at' 		=> 	date('d-m-Y h:i:s')
										);
										$this->Payroll_model->add_salary_payslip_other_payments($other_payments_data);
									}
								}
							}
						}
					}
				}


				 $gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
	
				if ($unpaid_leaves) {
					$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
				}
				// unpaid leave
				// if ($this->input->post('chk_leave_deductions')) {
					if ($unpaid_leaves) {
						foreach ($leave_period as $l) {
							$is_half = $l['is_half'];
							$leave_dates = $l['leave_date'];
							$leave_day_pay = round(($basic_salary + $allowance_amount + $other_payments_amount) / $no_of_working_days, 2);
							if ($is_half) {
								$leave_day_pay = $leave_day_pay / 2;
							}
							foreach ($leave_dates as $ld) {
								$unpaid_leave_data = array(
									'payslip_id' 		=> 	$result,
									'employee_id' 		=> 	$this->input->post('emp_id'),
									'salary_month' 		=> 	$this->input->post('pay_date'),
									'leave_date' 		=> 	$ld,
									'leave_amount' 		=> 	$leave_day_pay,
									'is_half' 			=> 	$is_half,
									'total_leave_amount' => $unpaid_leave_amount
								);
								$this->Payroll_model->add_salary_payslip_leave_deduction($unpaid_leave_data);
							}
						}
					}
				// }


				// employee claims
				if ($this->input->post('chk_employee_claim')){
					$get_employee_claims = $this->Employees_model->getEmployeeClaim($this->input->post('emp_id'));
					foreach ($get_employee_claims->result() as $claims) {
						$date 	= 	date('m-Y', strtotime($claims->date));
						if ($date == $this->input->post('pay_date')) {
							$claim_data = array(
								'payslip_id' 		=> 	$result,
								'employee_id' 		=> 	$this->input->post('emp_id'),
								'salary_month' 		=> 	$this->input->post('pay_date'),
								'claim_type' 		=> 	$claims->name,
								'amount' 			=> 	$claims->amount,
								'year'				=>	$claims->claim_year,
								'date'				=>	$claims->date,
								'employee_claim_id'	=>	$claims->claim_id
							);
							$this->Payroll_model->add_salary_payslip_claim($claim_data);
						}
					}
				}
			

				// set statutory_deductions
				if ($this->input->post('chk_total_statutory_deductions') || $this->input->post('chk_gross_salary') || $this->input->post('chk_total_allowances') || $this->input->post('chk_total_other_payments')) {
					$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($this->input->post('emp_id'));
					$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($this->input->post('emp_id'));
					$statutory_deductions_amount = 0;
					if ($count_statutory_deductions > 0) {
						foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
							if ($system[0]->statutory_fixed != 'yes') :
								$sta_salary = $gross_pay;
								$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $st_amount / 2;
									} else {
										$single_sd = $st_amount;
									}
								} else {
									$single_sd = $st_amount;
								}
								$statutory_deductions_amount += $single_sd;
							else :
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $sl_statutory_deductions->deduction_amount / 2;
									} else {
										$single_sd = $sl_statutory_deductions->deduction_amount;
									}
								} else {
									$single_sd = $sl_statutory_deductions->deduction_amount;
								}
								$statutory_deductions_amount += $single_sd;
							endif;


							$statutory_deduction_data = array(
								'payslip_id' 		=> 	$result,
								'employee_id' 		=> 	$this->input->post('emp_id'),
								'salary_month' 		=> 	$this->input->post('pay_date'),
								'deduction_title' 	=> 	$sl_statutory_deductions->deduction_title,
								'deduction_amount' 	=> 	$statutory_deductions_amount,
								'created_at' 		=> 	date('d-m-Y h:i:s')
							);
							$this->Payroll_model->add_salary_payslip_statutory_deductions($statutory_deduction_data);

						}
					}
				}

				// set loan
				if ($this->input->post('chk_loan_de_amount')) {
					$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($this->input->post('emp_id'));
					$count_loan_deduction = $this->Employees_model->count_employee_deductions($this->input->post('emp_id'));
					$loan_de_amount = 0;
					if ($count_loan_deduction > 0) {
						foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
							$esl_salary_loan_deduction = $sl_salary_loan_deduction->loan_deduction_amount;
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$eloan_deduction_amount = $esl_salary_loan_deduction / 2;
								} else {
									$eloan_deduction_amount = $esl_salary_loan_deduction;
								}
							} else {
								$eloan_deduction_amount = $esl_salary_loan_deduction;
							}
							$loan_data = array(
								'payslip_id' 		=> 	$result,
								'employee_id' 		=> 	$this->input->post('emp_id'),
								'salary_month' 		=> 	$this->input->post('pay_date'),
								'loan_title' 		=> 	$sl_salary_loan_deduction->loan_deduction_title,
								'loan_amount' 		=> 	$eloan_deduction_amount,
								'created_at' 		=> 	date('d-m-Y h:i:s')
							);
							$_loan_data = $this->Payroll_model->add_salary_payslip_loan($loan_data);
						}
					}
				}


				// overtime
				$overtime_amount = 0;
				$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($this->input->post('emp_id'), $this->input->post('pay_date'));
				if ($overtime) {
					$ot_days = 0;
					$ot_hrs = 0;
					$ot_mins = 0;
					foreach ($overtime as $ot) {
						$total_hours = explode(':', $ot->total_hours);
						$ot_hrs += $total_hours[0];
						$ot_mins += $total_hours[1];
						$ot_days += 1;
					}
					if ($ot_mins > 0) {
						$ot_hrs += round($ot_mins / 60, 2);
					}

					//overtime rate
					$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($this->input->post('emp_id'));

					if ($overtime_rate) {
						$rate = $overtime_rate->overtime_pay_rate;
					} else {
						$week_hours = 44;

						$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
						$rate = $rate * 1.5;
					}

					if ($ot_hrs > 0) {
						$overtime_amount = round($ot_hrs * $rate, 2);
					}
					if ($system[0]->is_half_monthly == 1) {
						if ($system[0]->half_deduct_month == 2) {
							$overtime_amount = $overtime_amount / 2;
						}
					}

					$overtime_data = array(
						'payslip_id' 			=> 	$result,
						'employee_id' 			=> 	$this->input->post('emp_id'),
						'overtime_salary_month' => 	$this->input->post('pay_date'),
						'overtime_no_of_days' 	=> 	$ot_days,
						'overtime_hours' 		=> 	$ot_hrs,
						'overtime_rate' 		=> 	$rate,
						'total_overtime' 		=> 	$overtime_amount,
						'created_at' 			=> 	date('d-m-Y h:i:s')
					);
					$_overtime_data = $this->Payroll_model->add_salary_payslip_overtime($overtime_data);
				}

				//cpf
				$total_cpf =  $this->input->post('total_cpf');
				if ($total_cpf && $total_cpf > 0) {
					$ow_paid = $this->input->post('ow_paid');
					$cpf_data = [
						'payslip_id' 	=> 	$result,
						'month_year' 	=> 	'01-' . $this->input->post('pay_date'),
						'ow_paid'		=> 	$ow_paid,
						'ow_cpf'		=> 	$this->input->post('ow_cpf'),
						'ow_cpf_employer'	=> $this->input->post('ow_cpf_employer'),
						'ow_cpf_employee'	=> $this->input->post('ow_cpf_employee'),
						'aw_paid'		=> 	$this->input->post('aw_paid'),
						'aw_cpf'		=> 	$this->input->post('aw_cpf'),
						'aw_cpf_employer'	=> $this->input->post('aw_cpf_employer'),
						'aw_cpf_employee'	=> $this->input->post('aw_cpf_employee')
					];

					$cpf_payslip = $this->Cpf_payslip_model->add_cpf_payslip($cpf_data);
				}

				//Other Fund Contributions
				$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($this->input->post('emp_id'));
				if ($employee_contributions) {
					$fund_deduction_amount = 0;
					$gross_s = $g_shg;
					$contribution_id = $employee_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);

					$fund_deduction_amount += $contribution_amount;
					$cdata = array(
						'payslip_id' 			=> 	$result,
						'contribution_id' 		=> 	$contribution_id,
						'contribution_amount' 	=> $contribution_amount
					);
					$this->Contribution_fund_model->setContributionPayslip($cdata);
				}


				//ASHG Fund Contributions
				$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($this->input->post('emp_id'));
				if ($employee_ashg_contributions) {
					$fund_deduction_amount = 0;
					$gross_s = $g_shg;
					$contribution_id = $employee_ashg_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);

					$fund_deduction_amount += $contribution_amount;
					$cdata = array(
						'payslip_id' 			=> 	$result,
						'contribution_id' 		=> 	$contribution_id,
						'contribution_amount' 	=> 	$contribution_amount
					);
					$this->Contribution_fund_model->setContributionPayslip($cdata);
				}

				//sdl
				$sdl = 0;
				if ($g_sdl > 1 && $g_sdl <= 800) {
					$sdl = 2;
				} elseif ($g_sdl > 800 && $g_sdl <= 4500) {
					$sdl_amount = (0.25 * $g_sdl) / 100;
					$sdl = $sdl_amount;
				} elseif ($g_sdl > 4500) {
					$sdl = 11.25;
				}

				$cdata = array(
					'payslip_id'		 	=> 	$result,
					'contribution_id' 		=> 	5,
					'contribution_amount' 	=> 	$sdl
				);
				$this->Contribution_fund_model->setContributionPayslip($cdata);

				$Return['result'] = $this->lang->line('xin_success_payment_paid');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	// payment history
	public function payslip()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		
		$key = $this->uri->segment(5);

		$result = $this->Payroll_model->read_salary_payslip_info_key($key);
		
		if (is_null($result)) {
			redirect('admin/payroll/generate_payslip');
		}
		$p_method = '';
		$payment_method = $this->Xin_model->read_payment_method($result[0]->payment_method);
		if(!is_null($payment_method)){
		  $p_method = $payment_method[0]->method_name;
		} else {
		  $p_method = '--';
		}
		// get addd by > template
		$user = $this->Xin_model->read_user_info($result[0]->employee_id);
		// user full name
		if (!is_null($user)) {
			$first_name = $user[0]->first_name;
			$last_name = $user[0]->last_name;
		} else {
			$first_name = '--';
			$last_name = '--';
		}
		// get designation
		$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($designation)) {
			$designation_name = $designation[0]->designation_name;
		} else {
			$designation_name = '--';
		}

		// department
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$department_name = $department[0]->department_name;
		} else {
			$department_name = '--';
		}

		$payslip_detail = $this->Payroll_model->read_salary_payslip_info_employee_id($result[0]->employee_id,$result[0]->salary_month); 
		
		$payslip_id = [];
		$leave_deduction =	0;
		foreach($payslip_detail as $p){
			 $payslip_id[] = $p->payslip_id;
			 $leave_deduction += $p->leave_deduction;

		}


		$data = array(
			'title' 				=> 	$this->lang->line('xin_payroll_employee_payslip') . ' | ' . $this->Xin_model->site_title(),
			'first_name' 			=> 	$first_name,
			'last_name' 			=> 	$last_name,
			'employee_id' 			=> 	$user[0]->employee_id,
			'euser_id' 				=> 	$user[0]->user_id,
			'id_no' 				=> 	$user[0]->id_no,
			'date_of_birth' 		=> 	$user[0]->date_of_birth,
			'contact_no' 			=> 	$user[0]->contact_no,
			'date_of_joining' 		=> 	$user[0]->date_of_joining,
			'department_name' 		=> 	$department_name,
			'designation_name' 		=> 	$designation_name,
			'date_of_joining' 		=> 	$user[0]->date_of_joining,
			'profile_picture' 		=> 	$user[0]->profile_picture,
			'gender' 				=> 	$user[0]->gender,
			'make_payment_id' 		=> 	$result[0]->payslip_id,
			'wages_type' 			=> 	$result[0]->wages_type,
			'payment_status' 		=> 	($result[0]->status == 0 ? 'Pending' : 'Paid'),
			'payment_date' 			=> 	$result[0]->salary_month,
			'year_to_date' 			=> 	$result[0]->year_to_date,
			'basic_salary' 			=> 	$result[0]->basic_salary,
			'daily_wages' 			=> 	$result[0]->basic_salary,
			'hours_worked' 			=> 	$result[0]->hours_worked,
			'payment_method' 		=> 	$p_method,
			'total_loan' 			=> 	$result[0]->total_loan,
			'total_overtime' 		=> 	$result[0]->total_overtime,
			'total_commissions'	 	=> 	$result[0]->total_commissions,
			'total_statutory_deductions' 	=> 	$result[0]->total_statutory_deductions,
			'total_other_payments' 	=> 	$result[0]->total_other_payments,
			'share_option_amount' 	=> 	$result[0]->share_option_amount,
			'net_salary' 			=> 	$result[0]->net_salary,
			'claim_amount' 			=> 	$result[0]->claim_amount,
			'other_payment' 		=> 	$result[0]->other_payment ?? 0,
			'payslip_key' 			=> 	$result[0]->payslip_key,
			'payslip_type' 			=> 	$result[0]->payslip_type,
			'hours_worked' 			=> 	$result[0]->hours_worked,
			'pay_comments' 			=> 	$result[0]->pay_comments,
			'deduction_amount' 		=> 	$result[0]->deduction_amount,
			'is_payment' 			=> 	$result[0]->is_payment,
			'approval_status' 		=> 	$result[0]->status,
			'gross_salary'			=> 	$result[0]->net_salary,
			'cpf_employee' 			=> 	$result[0]->cpf_employee_amount,
			'cpf_employer' 			=> 	$result[0]->cpf_employer_amount,
			'total_overtime_amount' => 	$result[0]->total_overtime_amount,
			'additional_fund' 		=> 	$result[0]->cpf_employer_amount,
			'additonal_allowance' 	=> 	$result[0]->additonal_allowance,
			'hourly_rate' 			=> 	$result[0]->gross_salary,
			'mapping_data' 			=> 	$this->Payroll_model->get_mapping_data($result[0]->payslip_id),
			'leave_deduction'		=>	$leave_deduction,
			'claims'				=>	$this->Payroll_model->read_make_payment_claims($payslip_id),
			'deductions'			=> 	$this->Payroll_model->read_make_payment_deduction($payslip_id),
			'allowances' 			=> 	$this->Payroll_model->read_make_payment_allowances($payslip_id),
		);

		$data['breadcrumbs'] = $this->lang->line('xin_payroll_employee_payslip');
		$data['path_url'] = 'payslip';
		$role_resources_ids = $this->Xin_model->user_role_resource();

		//Contribution funds
		$contribution = $this->Contribution_fund_model->getContributionPayslip($result[0]->payslip_id);
		if ($contribution) {
			$contribution_fund = array();
			foreach ($contribution as $i => $c) {
				if ($c->contribution_id != 5) {
					$contribution_fund[$i]['contribution_id'] = $c->contribution_id;
					$contribution_fund[$i]['contribution'] = $c->contribution;
					$contribution_fund[$i]['contribution_amount'] = $c->contribution_amount;
				}
			}
			$data['contribution_fund'] = $contribution_fund;
		}


		if (!empty($session)) {
		// 	if ($result[0]->payslip_type == 'hourly') {
		// 		$pay_basic = 0;

		// 		if ($this->input->get('ismobile') == 'true') {
		// 			// print_r($data);exit();
		// 			$data['subview'] = $this->load->view("admin/payroll/payslip_m", $data, TRUE);
		// 			$this->load->view('admin/layout/pms/layout_pms', $data);
		// 		} else {

		// 			$data['subview'] = $this->load->view("admin/payroll/hourly_payslip", $data, TRUE);
		// 			$this->load->view('admin/layout/pms/layout_pms', $data);; //page load

		// 		}
		// 	} else {
				$data['subview'] = $this->load->view("admin/payroll/payslip", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data);; //page load
			// }
		} else {
			redirect('admin/');
		}
	}


	// for bulk payment list
	public function payslip_list_bulk()
	{

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/payroll/generate_payslip", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw 	= intval($this->input->get("draw"));
		$start 	= intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		// date and employee id/company id
		// payment month
		$pay_date = $this->input->get("month_year");

		$role_resources_ids = $this->Xin_model->user_role_resource();
		$user_info = $this->Xin_model->read_user_info($session['user_id']);

		if ($user_info[0]->user_role_id == 1 || in_array('314', $role_resources_ids)) {
			if ($this->input->get("employee_id") == 0 && $this->input->get("company_id") == 0) {
				$payslip = $this->Employees_model->get_employees_payslip($pay_date);
			} else if ($this->input->get("employee_id") == 0 && $this->input->get("company_id") != 0) {
				$payslip = $this->Payroll_model->get_comp_template($this->input->get("company_id"), 0, $pay_date);
			} else if ($this->input->get("employee_id") != 0 && $this->input->get("company_id") != 0) {
				$payslip = $this->Payroll_model->get_employee_comp_template($this->input->get("company_id"), $this->input->get("employee_id"), $pay_date);
			} else {
				$payslip = $this->Employees_model->get_employees_payslip($pay_date);
			}
		} else {
			$payslip = $this->Payroll_model->get_employee_comp_template($user_info[0]->company_id, $session['user_id']);
		}


		$system = $this->Xin_model->read_setting_info(1);
		$data = array();
		foreach ($payslip->result() as $key => $r) {
			$exit_employee = $this->Employees_model->get_employee_exit($r->user_id);

			if (count($exit_employee) > 0) {
				$e_date = date('Y-m-d', strtotime($exit_employee[0]->exit_date));
				$exit_date = date('m-Y', strtotime($e_date));
				if ($exit_date >= $pay_date){
					$emp_name = $r->first_name . ' ' . $r->last_name;
					// office shift
					$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);
	
					//overtime request
					$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $this->input->get('month_year'));
					$re_hrs_old_int1 = 0;
					$re_hrs_old_seconds = 0;
					$re_pcount = 0;
					foreach ($overtime_count as $overtime_hr) {
						$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
						$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
						$re_interval_late = $request_clock_in->diff($request_clock_out);
						$re_hours_r  = $re_interval_late->format('%h');
						$re_minutes_r = $re_interval_late->format('%i');
						$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';
	
						$re_str_time = $re_total_time;
	
						$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);
	
						sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$re_hrs_old_int1 += $re_hrs_old_seconds;
	
						$re_pcount = gmdate("H", $re_hrs_old_int1);
					}
	
					$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
					$hrs_old_int1 = 0;
					$pcount = 0;
					foreach ($result->result() as $hour_work) {
						$clock_in =  new DateTime($hour_work->clock_in);
						$clock_out =  new DateTime($hour_work->clock_out);
						$interval_late = $clock_in->diff($clock_out);
						$hours_r  = $interval_late->format('%h');
						$minutes_r = $interval_late->format('%i');
						$total_time = $hours_r . ":" . $minutes_r . ":" . '00';
	
						$str_time = $total_time;
	
						$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
	
						sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$hrs_old_int1 += $hrs_old_seconds;
	
						$pcount = gmdate("H", $hrs_old_int1);
					}
					$pcount = $pcount + $re_pcount;
	
					// get company
					$company = $this->Xin_model->read_company_info($r->company_id);
					if (!is_null($company)) {
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';
					}
	
					/**
					 * Local Variable
					 */
					$g_ordinary_wage = 0;
					$g_additional_wage = 0;
					$g_shg = 0;
					$g_sdl = 0;
	
					// 1: salary type
					if ($r->wages_type == 1) {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					} else if ($r->wages_type == 2) {
						$wages_type = $this->lang->line('xin_employee_daily_wages');
						if ($pcount > 0) {
							$basic_salary = $pcount * $r->basic_salary;
						} else {
							$basic_salary = $pcount;
						}
						$p_class = 'emo_hourly_pay';
						$view_p_class = 'hourlywages_template_modal';
					} else {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					}
	
					$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
					$check_payment = $check && !empty($check[0]->check_id) ? explode(',', $check[0]->check_id) : [];
	
					$database_cpf_employee = 0;
					$advance_amount	=	0;
					if($check){
						foreach($check as $c){
							$database_cpf_employee += $c->cpf_employee_amount;
							if($check[0]->is_advance == 1){
								$advance_amount += $check[0]->advance_amount;
							}
						}
					}
					$final_contribution_amount = $this->contribution_calculation(['user_id'=>$r->user_id,"pay_date"=>$pay_date]);
	
					$database_cpf_employee = $final_contribution_amount['cpf_employee'] - $database_cpf_employee;
	
	
	
	
					if (in_array('chk_gross_salary', $check_payment)) {
						$basic_salary = 0;
					}else if ($check && $check[0]->is_advance == 1) {
						$basic_salary = $basic_salary - $advance_amount;
					}
	
					
					
	
					// employee deduction
					$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
					$pa_month = date('m-Y', strtotime('01-' . $pay_date));
					$deduction_amount = 0;
					if (!in_array('chk_total_employee_deduction', $check_payment)) {
						if ($employee_deduction) {
							foreach ($employee_deduction as $deduction) {
								if ($deduction->type_id == 1) {
									$deduction_amount +=  $deduction->amount;
								}
								if ($deduction->type_id == 2) {
									$from_month_year = date('m-Y', strtotime($deduction->from_date));
									$to_month_year = date('d-m-Y', strtotime($deduction->to_date));
	
	
									if ($from_month_year != "" && $to_month_year != "") {
										if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
											$deduction_amount +=  $deduction->amount;
										}
									}
								}
							}
						}
					}
	
	
	
					//3: Gross rate of pay (unpaid leave deduction)
					$holidays_count = 0;
					$no_of_working_days = 0;
					$month_start_date = new DateTime('01-' . $pay_date);
					$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
					$month_end_date->modify('+1 day');
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($month_start_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$period_day = $p->format('l');
						$period_date = $p->format('Y-m-d');
	
						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
						if ($is_holiday) {
							$holidays_count += 1;
						}
	
						//working days excluding holidays based on office shift
						if ($period_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_working_days += 1;
							}
						}
					}
	
					//unpaid leave
					$unpaid_leave_amount = 0;
					$leaves_taken_count = 0;
					$leave_period = array();
					$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
					if ($unpaid_leaves) {
						foreach ($unpaid_leaves as $k => $l) {
							$pay_date_month = new DateTime('01-' . $pay_date);
							$l_from_date = new DateTime($l->from_date);
							$l_to_date = new DateTime($l->to_date);
	
							if ($l_from_date->format('m') == $l_to_date->format('m')) {
								$start_date = $l_from_date;
								$end_date = $l_to_date;
							} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $l_from_date;
								$end_date = new DateTime($start_date->format('Y-m-t'));
							} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $pay_date_month;
								$end_date = $l_to_date;
							}
							$end_date->modify('+1 day');
							$interval = new DateInterval('P1D');
							$period = new DatePeriod($start_date, $interval, $end_date);
							foreach ($period as $d) {
								$p_day = $d->format('l');
								if ($p_day == 'Monday') {
									if ($office_shift[0]->monday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Tuesday') {
									if ($office_shift[0]->tuesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Wednesday') {
									if ($office_shift[0]->wednesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Thursday') {
									if ($office_shift[0]->thursday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Friday') {
									if ($office_shift[0]->friday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Saturday') {
									if ($office_shift[0]->saturday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Sunday') {
									if ($office_shift[0]->sunday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								}
							}
							$leave_period[$k]['is_half'] = $l->is_half_day;
						}
					}
	
					// if joining date and pay date same then this logic work
					$month_date_join = date('m-Y', strtotime($r->date_of_joining));
					$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
					$month_last_date = date('m-Y', strtotime($lastday));
	
					$first_date =  new DateTime($r->date_of_joining);
					$no_of_days_worked = 0;
					$same_month_holidays_count = 0;
					if ($month_date_join == $pay_date) {
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($first_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$p_day = $p->format('l');
						$m_p_date = $p->format('Y-m-d');
	
						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
						if ($is_holiday) {
							$same_month_holidays_count += 1;
						}
	
						//working days excluding holidays based on office shift
						if ($p_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_days_worked += 1;
							}
						}
					}
					// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
					$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
					} else {
						// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
						$no_of_days_worked = $no_of_working_days -  $holidays_count;
					}
	
	
					if ($month_date_join == $pay_date){
						$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
					}else{
						$show_main_salary = $basic_salary;
					}
				
					$g_ordinary_wage += $show_main_salary;
					$g_shg += $show_main_salary;
					$g_sdl += $show_main_salary;
				
					$g_ordinary_wage -= $deduction_amount;
					$g_shg -= $deduction_amount;
					$g_sdl -= $deduction_amount;
	
	
					// 3: all loan/deductions
					$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
					$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
					$loan_de_amount = 0;
					if (!in_array('chk_loan_de_amount', $check_payment) ) {
						if ($count_loan_deduction > 0) {
							foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
									} else {
										$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
									}
								} else {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
								}
								$loan_de_amount += $er_loan;
							}
						} else {
							$loan_de_amount = 0;
						}
						$loan_de_amount = round($loan_de_amount, 2);
					}
					
					// 2: all allowances
					$allowance_amount = 0;
					$gross_allowance_amount = 0;
					$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
					if (!in_array('chk_total_allowances', $check_payment)) {
						if ($salary_allowances) {
							foreach ($salary_allowances as $sa) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$eallowance_amount = $sa->allowance_amount / 2;
									} else {
										$eallowance_amount = $sa->allowance_amount;
									}
								} else {
									$eallowance_amount = $sa->allowance_amount;
								}
	
	
								if (!empty($sa->salary_month)) {
									$g_additional_wage += $eallowance_amount;
								} else {
									if ($month_date_join == $pay_date){
										$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
									}
									$g_ordinary_wage += $eallowance_amount;
									if ($sa->id == 2) {
										$gross_allowance_amount = $eallowance_amount;
									}
								}
	
								if ($sa->sdl == 1) {
									$g_sdl += $eallowance_amount;
								}
								if ($sa->shg == 1) {
									$g_shg += $eallowance_amount;
								}
	
								$allowance_amount += $eallowance_amount;
							}
						}
						$gross_allowance_amount = $allowance_amount;
					}
	
	
					// commissions
					$commissions_amount = 0;
					$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
					if (!in_array('chk_total_commissions', $check_payment)) {
						if ($commissions) {
							foreach ($commissions as $c) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$ecommissions_amount = $c->commission_amount / 2;
									} else {
										$ecommissions_amount = $c->commission_amount;
									}
								} else {
									$ecommissions_amount = $c->commission_amount;
								}
	
								if ($c->commission_type == 9) {
									$g_ordinary_wage += $ecommissions_amount;
								} elseif ($c->commission_type == 10) {
									$g_additional_wage += $ecommissions_amount;
								}
	
								if ($c->sdl == 1) {
									$g_sdl += $ecommissions_amount;
								}
								if ($c->shg == 1) {
									$g_shg += $ecommissions_amount;
								}
	
								$commissions_amount += $ecommissions_amount;
							}
						}
					}
	
					//share options
					$share_options_amount = 0;
					if (!in_array('chk_total_share', $check_payment)) {
						$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
						if ($share_options) {
							$eebr_amount = 0;
							$eris_amount = 0;
							foreach ($share_options as $s) {
								$scheme = $s->so_scheme;
								if ($scheme == 1) {
									$price_doe = $s->price_date_of_excercise;
									$price_ex = $s->excercise_price;
									$no_shares = $s->no_of_shares;
									$amount = ($price_doe - $price_ex) * $no_shares;
									$eebr_amount += $amount;
								} else {
									$price_doe = $s->price_date_of_excercise;
									$price_dog = $s->price_date_of_grant;
									$price_ex = $s->excercise_price;
									$no_shares = $s->no_of_shares;
	
									$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
									$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
									$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
								}
							}
							$share_options_amount = round($eebr_amount + $eris_amount, 2);
							$g_additional_wage += $share_options_amount;
							$g_sdl += $share_options_amount;
							$g_shg += $share_options_amount;
						}
					}
	
					
					// otherpayments
					$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
					$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
					$other_payments_amount = 0;
					if (!in_array('chk_total_other_payments', $check_payment)) {
						if ($count_other_payments > 0) {
							foreach ($other_payments->result() as $sl_other_payments) {
								if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
									if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$epayments_amount = $sl_other_payments->payments_amount / 2;
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
	
										if ($sl_other_payments->cpf_applicable == 1) {
											$g_additional_wage += $epayments_amount;
											$g_shg += $epayments_amount;
											$g_sdl += $epayments_amount;
										}
	
										$other_payments_amount += $epayments_amount;
									}
								} else {
									$first_date = new DateTime($sl_other_payments->date);
									if ($first_date->format('m-Y') == $pay_date) {
										$first_date =  new DateTime($sl_other_payments->date);
									} else {
										$first_date = new DateTime('01-' . $pay_date);
									}
	
									$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));
	
									if (!empty($sl_other_payments->end_date)) {
										$last_date = new DateTime($sl_other_payments->end_date);
										if ($last_date->format('m-Y') == $pay_date) {
											$last_date = new DateTime($sl_other_payments->end_date);
										} else if($last_date->format('m-Y') >= $pay_date){
											$last_date = $month_end_date_for_other;
										} else {
											$last_date = '';
										}
									} else {
										$last_date = $month_end_date_for_other;
									}
									if(!empty($last_date)){
										$last_date->modify('+1 day');
										$final_last_day = new DateTime($last_date->format('d-m-Y'));
										if ($final_last_day->format('m-Y') >= $pay_date) {
											if ($system[0]->is_half_monthly == 1) {
												if ($system[0]->half_deduct_month == 2) {
													$epayments_amount = $sl_other_payments->payments_amount / 2;
												} else {
													$epayments_amount = $sl_other_payments->payments_amount;
												}
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
	
	
											// it for no of working day
											$no_of_days_worked_for_other_payment = 0;
											$same_month_holidays_count_for_other_payment = 0;
											$interval = new DateInterval('P1D');
											$period = new DatePeriod($first_date, $interval, $last_date);
											foreach ($period as $p) {
												$p_day = $p->format('l');
												$p_date = $p->format('Y-m-d');
	
												//holidays in a month
	
												$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
												if ($is_holiday) {
													$same_month_holidays_count_for_other_payment += 1;
												}
	
												//working days excluding holidays based on office shift
												if ($p_day == 'Monday') {
													if ($office_shift[0]->monday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Tuesday') {
													if ($office_shift[0]->tuesday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Wednesday') {
													if ($office_shift[0]->wednesday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Thursday') {
													if ($office_shift[0]->thursday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Friday') {
													if ($office_shift[0]->friday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Saturday') {
													if ($office_shift[0]->saturday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												} else if ($p_day == 'Sunday') {
													if ($office_shift[0]->sunday_in_time != '') {
														$no_of_days_worked_for_other_payment += 1;
													}
												}
											}
	
											$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
											$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);
	
	
											if ($sl_other_payments->cpf_applicable == 1) {
												$g_additional_wage += $epayments_amount;
												$g_shg += $epayments_amount;
												$g_sdl += $epayments_amount;
											}
											$other_payments_amount += $epayments_amount;
										}
									}
								}
							}
						} else {
							$other_payments_amount = 0;
						}
					}
	
					$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
		
					if ($unpaid_leaves) {
						$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
					}
					
					// $g_ordinary_wage = $gross_pay;
					$g_shg = $gross_pay;
					$g_sdl = $gross_pay;
	
					$g_ordinary_wage -= $unpaid_leave_amount;
	
					
	
					// other benefit
					$other_benefit_mount = 0;
					if (!in_array('chk_total_other_payments', $check_payment)) {
						$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
						foreach ($other_benefit_list->result() as $benefit_list) {
							$other_benefit_mount += $benefit_list->other_benefit_cost;
						}
					}
	
	
					// statutory_deductions
					$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
					$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
					$statutory_deductions_amount = 0;
					if (!in_array('chk_total_statutory_deductions', $check_payment) || in_array('chk_total_allowances', $check_payment) || in_array('chk_total_other_payments', $check_payment) || in_array('chk_gross_salary', $check_payment)) {
						if ($count_statutory_deductions > 0) {
							foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
								if ($system[0]->statutory_fixed != 'yes') {
									$sta_salary = $gross_pay;
									$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$single_sd = $st_amount / 2;
										} else {
											$single_sd = $st_amount;
										}
									} else {
										$single_sd = $st_amount;
									}
									$statutory_deductions_amount += $single_sd;
								} else {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
										} else {
											$single_sd = $sl_salary_statutory_deductions->deduction_amount;
										}
									} else {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount;
									}
									$statutory_deductions_amount += $single_sd;
								}
							}
						} else {
							$statutory_deductions_amount = 0;
						}
					}
	
					// overtime
					$overtime_amount = 0;
					if (!in_array('chk_total_overtime', $check_payment)) {
						$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
						if ($overtime) {
							$ot_hrs = 0;
							$ot_mins = 0;
							foreach ($overtime as $ot) {
								$total_hours = explode(':', $ot->total_hours);
								$ot_hrs += $total_hours[0];
								$ot_mins += $total_hours[1];
							}
							if ($ot_mins > 0) {
								$ot_hrs += round($ot_mins / 60, 2);
							}
	
							//overtime rate
							$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
							if ($overtime_rate) {
								$rate = $overtime_rate->overtime_pay_rate;
							} else {
								$week_hours = 44;
								$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
								$rate = $rate * 1.5;
							}
	
							if ($ot_hrs > 0) {
								$overtime_amount = round($ot_hrs * $rate, 2);
							}
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$overtime_amount = $overtime_amount / 2;
								}
							}
							$g_ordinary_wage += $overtime_amount;
							$g_sdl += $overtime_amount;
						}
					}
	
					
	
					$payment_check = $this->Payroll_model->check_make_payment_payslip_for_first_payment($r->user_id, $pay_date);
					$payment_check_final = $this->Payroll_model->check_make_payment_payslip_for_final_payment($r->user_id, $pay_date);
					$balance = 0;
					$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
					if($check && $check[0]->balance_amount > 0){
						$balance = $check[0]->balance_amount;
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
					
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
						$status = '<span class="label label-warning">' . "Partially Paid" . '</span>';
	
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
						$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
						
						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
						// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
						$delete  = $delete;
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						
					}else if($check && $check[0]->balance_amount == 0){
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
					
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
						$status = '<span class="label label-success">Paid</span>';
	
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
						
						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
						// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
						$delete  = $delete;
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						
					}else {
							$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
							$delete = '';
							$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
							$balance = $this->Xin_model->currency_sign(0, $r->user_id);
						}
	
					
					// employee accommodations
					$employee_accommodations = 0;
					if (!in_array('chk_total_employee_deduction', $check_payment)) {
						$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
						foreach ($get_employee_accommodations as $get_employee_accommodation) {
							$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
							$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
							if ($period_from == $pay_date || $period_to == $pay_date) {
								if (!empty($get_employee_accommodation->rent_paid)) {
									$employee_accommodations += $get_employee_accommodation->rent_paid;
								}
							}
						}
					}
	
					// employee claims
					$claim_amount = 0;
					if (!in_array('chk_employee_claim', $check_payment)) {
						$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
						foreach ($get_employee_claims->result() as $claims) {
							$date 	= 	date('m-Y', strtotime($claims->date));
							if ($date == $pay_date) {
								$claim_amount += $claims->amount;
							}
						}
					}
	
	
	
					$total_earning = $show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
					$total_deduction = $loan_de_amount + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
					$total_net_salary = ($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;
	
	
					// cpf calculation
					$emp_dob = $r->date_of_birth;
					$dob = new DateTime($emp_dob);
	
					$today = new DateTime('01-' . $pay_date);
					$age = $dob->diff($today);
					$age_year = $age->y;
					$age_month = $age->m;
	
					$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
					$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
					$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;
	
					if ($age_month > 0) {
						$age_year = $age_year + 1;
					}
	
					$cpf_employee 	= 	0;
					$cpf_employer	=	0;
	
					$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
					if ($im_status) {
						$immigration_id = $im_status->immigration_id;
						if ($immigration_id == 2) {
							$issue_date = $im_status->issue_date;
							$i_date = new DateTime($issue_date);
							$today = new DateTime();
							$pr_age = $i_date->diff($today);
							$pr_age_year = $pr_age->y;
							$pr_age_month = $pr_age->m;
						}
	
						if ($immigration_id == 1 || $immigration_id == 2) {
	
							$ordinary_wage = $g_ordinary_wage;
							if ($ordinary_wage > $ordinary_wage_cap) {
								$ow = $ordinary_wage_cap;
							} else {
								$ow = $ordinary_wage;
							}
	
							//additional wage
							$additional_wage = $g_additional_wage;
							$aw = $g_additional_wage;
							$tw = $ow + $additional_wage;
							if ($im_status->issue_date != "") {
								if ($pr_age_year == 1) {
	
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(4 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 666) {
												$total_cpf = 666;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(4 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 666) {
												$total_cpf = 666;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(3.5 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(3.5 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
								if ($pr_age_year == 2) {
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(9 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.45 * ($tw - 500));
											$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
											$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
											if ($count_total_cpf > 1776) {
												$total_cpf = 1776;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1110) {
												$cpf_employee = 1110;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(6 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.375 * ($tw - 500));
											$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
											$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
											if ($count_total_cpf > 1369) {
												$total_cpf = 1369;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 925) {
												$cpf_employee = 925;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(3.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.225 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
											$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
											if ($count_total_cpf > 814) {
												$total_cpf = 814;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 555) {
												$cpf_employee = 555;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(3.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
											$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
								if ($pr_age_year == 3 || $pr_age_year > 3) {
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(17 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.6 * ($tw - 500));
											$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
											$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
											if ($count_total_cpf > 2738) {
												$total_cpf = 2738;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1480) {
												$cpf_employee = 1480;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = $total_cpf - $cpf_employee;
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(15.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.51 * ($tw - 500));
											$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
											$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
											if ($count_total_cpf > 2405) {
												$total_cpf = 2405;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1258) {
												$cpf_employee = 1258;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(12 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.345 * ($tw - 500));
											$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
											$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
	
											if ($count_total_cpf > 1739) {
												$total_cpf = 1739;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 851) {
												$cpf_employee = 851;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65 && $age_year <= 70) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(9 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.225 * ($tw - 500));
											$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
											$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
	
											if ($count_total_cpf > 1221) {
												$total_cpf = 1221;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 555) {
												$cpf_employee = 555;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 70) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(7.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
											$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
	
											if ($count_total_cpf > 925) {
												$total_cpf = 925;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
							}
	
	
	
							if ($immigration_id == 1) {
	
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(17 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.6 * ($tw - 500));
										$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
										$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
										if ($count_total_cpf > 2738) {
											$total_cpf = 2738;
										} else {
											$total_cpf = $count_total_cpf;
										}
				
										
										if ($count_cpf_employee > 1480) {
											$cpf_employee = 1480;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(15.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.51 * ($tw - 500));
										$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
				
										$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
										if ($count_total_cpf > 2405) {
											$total_cpf = 2405;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1258) {
											$cpf_employee = 1258;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(12 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.345 * ($tw - 500));
										$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
										$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
				
										if ($count_total_cpf > 1739) {
											$total_cpf = 1739;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 851) {
											$cpf_employee = 851;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65 && $age_year <= 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
				
										if ($count_total_cpf > 1221) {
											$total_cpf = 1221;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(7.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
				
										if ($count_total_cpf > 925) {
											$total_cpf = 925;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
	
							if($check){
								$cpf_employee = $database_cpf_employee;
							}
	
							$total_net_salary = $total_net_salary - $cpf_employee;
							$cpf_total = $cpf_employee + $cpf_employer;
	
							$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
							$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
							$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
						}
					}
	
	
					$shg_fund_deduction_amount = 0;
					//Other Fund Contributions
					$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);
	
					if ((!$check || $check[0]->is_advance == 1) && $employee_contributions && $g_shg > 0) {
						$gross_s = $g_shg;
						$contribution_id = $employee_contributions->contribution_id;
						$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
						$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
						$shg_fund_name = $contribution_type[0]->contribution;
						$shg_fund_deduction_amount += $contribution_amount;
						$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
					}
					$ashg_fund_deduction_amount = 0;
	
					$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
					if ((!$check || $check[0]->is_advance == 1) && $employee_ashg_contributions  && $g_shg > 0) {
						$gross_s = $g_shg;
						$contribution_id = $employee_ashg_contributions->contribution_id;
						$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
						$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
						$ashg_fund_name = $contribution_type[0]->contribution;
	
						$ashg_fund_deduction_amount += $contribution_amount;
						$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
					}
	
					// echo $total_net_salary;
	
					// $sdl_total_amount = 0;
					// if ($g_sdl > 1 && $g_sdl <= 800) {
					// 	$sdl_total_amount = 2;
					// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
					// 	$sdl_amount = (0.25 * $g_sdl) / 100;
					// 	$sdl_total_amount = $sdl_amount;
					// } elseif ($g_sdl > 4500) {
					// 	$sdl_total_amount = 11.25;
					// }
	
	
					$net_salary = number_format((float)$total_net_salary, 2, '.', '');
					$basic_salary = number_format((float)$basic_salary, 2, '.', '');
					// if ($basic_salary == 0 || $basic_salary == '') {
					// 	$fmpay = '';
					// } else {
						$fmpay = $mpay;
					// }
	
	
					// $company_info = $this->Company_model->read_company_information($r->company_id);
					// if (!is_null($company_info)) {
					// 	$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
					// 	$net_salary = $this->Xin_model->company_currency_sign($net_salary, $r->company_id);
					// 	//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
					// } else {
					// 	//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
					// 	$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary, $r->user_id);
	
					// 	$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary, $r->user_id);
	
					// 	//$net_salary = $this->Xin_model->currency_sign($net_salary);	
					// 	//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
					// 	$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount, $r->user_id);
					// }
	
					$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';
	
					//action link
					$act = $detail . $fmpay . $delete;
					// $act = $fmpay . $delete;
	
					if ($r->wages_type == 1) {
						if ($system[0]->is_half_monthly == 1) {
							$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
						} else {
							$emp_payroll_wage = $wages_type;
						}
					} else {
						$emp_payroll_wage = $wages_type;
					}
	
					$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
					$p = $payslips->result();
					
					$mode_of_payment = $r->payment_mode ?? '';
	
					$main_check = $check && $check[0]->is_advance !=1 && $check[0]->balance_amount == 0 ? 'checked disabled' : ''; 
	
					$field =  '<div class="k-top"><span class="k-icon k-plus" role="presentation"></span><span class="k-checkbox" role="presentation"  style="display:inline-block;"><label><input type="checkbox" name="employee_id[]" class="role-checkbox main_check_box" value="'.$r->user_id.'" '.$main_check.'> <input type="hidden" name="employee_id[]" class="role-checkbox main_check_box" value="0"> </label></span><span class="k-in k-state-focused"></span></div>';
					
	
					$check_gross_salary = in_array('chk_gross_salary', $check_payment) ? "checked disabled" : "";
					$check_allowance_amount = in_array('chk_total_allowances', $check_payment) ? "checked disabled" : "";
					$check_unpaid_leave_amount = in_array('chk_leave_deductions', $check_payment) ? "checked disabled" : "";
					$check_commissions_amount = in_array('chk_total_commissions', $check_payment) ? "checked disabled" : "";
					$check_loan_de_amount = in_array('chk_loan_de_amount', $check_payment) ? "checked disabled" : "";
					$check_overtime_amount = in_array('chk_total_overtime', $check_payment) ? "checked disabled" : "";
					$check_statutory_deductions_amount = in_array('chk_total_statutory_deductions', $check_payment) ? "checked disabled" : "";
					$check_other_payments_amount = in_array('chk_total_other_payments', $check_payment) ? "checked disabled" : "";
					$check_total_deduction = in_array('chk_total_employee_deduction', $check_payment) ? "checked disabled" : "";
					$check_claim_amount = in_array('chk_employee_claim', $check_payment) ? "checked disabled" : "";
					$check_share_options_amount = in_array('chk_total_share', $check_payment) ? "checked disabled" : "";
					
					$data[] = array(
						$field,
						$iemp_name,
						$emp_payroll_wage. '<input type="hidden" name="loop[]" value="'.$key.'"/>',
						'<input type="text" name="gross_salary[]" value="'. $show_main_salary . '" readonly class="form-control"/> <input type="checkbox"  name="chk_gross_salary[]" id="chk_gross_salary" class="header_chk_gross_salary bc_checked_class row-item" '. $check_gross_salary  .'/>',
						'<input type="text" name="total_allowances[]" value="'.$allowance_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_total_allowances[]" id="chk_total_allowances" class="header_chk_total_allowances bc_checked_class row-item" '. $check_allowance_amount  .'/>',
						'<input type="text" name="leave_deductions[]" value="'.$unpaid_leave_amount . '" readonly class="form-control leave_deductions"/> <input type="checkbox"  name="chk_leave_deductions[]" id="chk_leave_deductions" class="header_chk_leave_deductions bc_checked_class row-item" '. $check_unpaid_leave_amount  .'/>',
						'<input type="text" name="total_commissions[]" value="'.$commissions_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_total_commissions[]" id="chk_total_commissions" class="header_chk_total_commissions bc_checked_class row-item" '. $check_commissions_amount  .'/>',
						'<input type="text" name="total_loan[]" value="'.$loan_de_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_loan_de_amount[]" id="chk_loan_de_amount" class="header_chk_loan_de_amount bc_checked_class row-item" '. $check_loan_de_amount  .'/>',		
						'<input type="text" name="total_overtime[]" value="'.$overtime_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_total_overtime[]" id="chk_total_overtime" class="header_chk_total_overtime bc_checked_class row-item" '. $check_overtime_amount  .'/>',
						'<input type="text" name="total_statutory_deductions[]" value="'.$statutory_deductions_amount . '" readonly class="form-control statutory_deductions"/> <input type="checkbox"  name="chk_total_statutory_deductions[]" id="chk_total_statutory_deductions" class="header_chk_total_statutory_deductions bc_checked_class row-item" '. $check_statutory_deductions_amount  .'/>',
						'<input type="text" name="total_other_payments[]" value="'.$other_payments_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_total_other_payments[]" id="chk_total_other_payments" class="header_chk_total_other_payments bc_checked_class row-item" '. $check_other_payments_amount  .'/>',
						'<input type="text" name="total_cpf_employee[]" value="'.$cpf_employee.'" readonly class="form-control total_cpf_employee" />',
						'<input type="text" name="total_cpf_employer[]" value="'.$cpf_employer.'"  readonly class="form-control total_cpf_employer"/>',
						'<input type="text" name="total_cpf[]" value="'.($cpf_employer + $cpf_employee).'"  readonly class="form-control total_cpf"/>',
						'<input type="text" name="total_fund_contribution[]" value="'.($shg_fund_deduction_amount + $ashg_fund_deduction_amount).'"  readonly class="form-control total_fund_contribution"  />',
						'<input type="text" name="total_employee_deduction[]" value="'.$total_deduction . '" readonly class="form-control total_employee_deduction"/> <input type="checkbox"  name="chk_total_employee_deduction[]" id="chk_total_employee_deduction" class="header_chk_total_employee_deduction bc_checked_class row-item" '. $check_total_deduction  .'/>',
						'<input type="text" name="employee_claim[]" value="'.$claim_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_employee_claim[]" id="chk_employee_claim" class="header_chk_employee_claim bc_checked_class row-item" '. $check_claim_amount  .'/>',
						'<input type="text" name="total_share[]" value="'.$share_options_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_total_share[]" id="chk_total_share" class="header_chk_total_share bc_checked_class row-item" '. $check_share_options_amount  .'/>',
						'<input type="text" name="balance_amount[]" class="form-control balance_amount" value="0" readonly style="width: 100px;" >',
						'<input type="text" name="net_salary[]" value="'.$net_salary.'" class="form-control net_salary_s" readonly>',
						'<input type="text" name="payment_amount[]" value="'.$net_salary.'" class="form-control payment_amount_s" readonly>',
						
						// $net_salary,
						$status,
						"",
					);
	
				}
			} else {
				$emp_name = $r->first_name . ' ' . $r->last_name;
				// office shift
				$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);

				//overtime request
				$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $this->input->get('month_year'));
				$re_hrs_old_int1 = 0;
				$re_hrs_old_seconds = 0;
				$re_pcount = 0;
				foreach ($overtime_count as $overtime_hr) {
					$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
					$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
					$re_interval_late = $request_clock_in->diff($request_clock_out);
					$re_hours_r  = $re_interval_late->format('%h');
					$re_minutes_r = $re_interval_late->format('%i');
					$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';

					$re_str_time = $re_total_time;

					$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);

					sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$re_hrs_old_int1 += $re_hrs_old_seconds;

					$re_pcount = gmdate("H", $re_hrs_old_int1);
				}

				$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
				$hrs_old_int1 = 0;
				$pcount = 0;
				foreach ($result->result() as $hour_work) {
					$clock_in =  new DateTime($hour_work->clock_in);
					$clock_out =  new DateTime($hour_work->clock_out);
					$interval_late = $clock_in->diff($clock_out);
					$hours_r  = $interval_late->format('%h');
					$minutes_r = $interval_late->format('%i');
					$total_time = $hours_r . ":" . $minutes_r . ":" . '00';

					$str_time = $total_time;

					$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

					sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$hrs_old_int1 += $hrs_old_seconds;

					$pcount = gmdate("H", $hrs_old_int1);
				}
				$pcount = $pcount + $re_pcount;

				// get company
				$company = $this->Xin_model->read_company_info($r->company_id);
				if (!is_null($company)) {
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';
				}

				/**
				 * Local Variable
				 */
				$g_ordinary_wage = 0;
				$g_additional_wage = 0;
				$g_shg = 0;
				$g_sdl = 0;

				// 1: salary type
				if ($r->wages_type == 1) {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				} else if ($r->wages_type == 2) {
					$wages_type = $this->lang->line('xin_employee_daily_wages');
					if ($pcount > 0) {
						$basic_salary = $pcount * $r->basic_salary;
					} else {
						$basic_salary = $pcount;
					}
					$p_class = 'emo_hourly_pay';
					$view_p_class = 'hourlywages_template_modal';
				} else {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				}

				$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
				$check_payment = $check && !empty($check[0]->check_id) ? explode(',', $check[0]->check_id) : [];

				$database_cpf_employee = 0;
				$advance_amount	=	0;
				if($check){
					foreach($check as $c){
						$database_cpf_employee += $c->cpf_employee_amount;
						if($check[0]->is_advance == 1){
							$advance_amount += $check[0]->advance_amount;
						}
					}
				}
				$final_contribution_amount = $this->contribution_calculation(['user_id'=>$r->user_id,"pay_date"=>$pay_date]);

				$database_cpf_employee = $final_contribution_amount['cpf_employee'] - $database_cpf_employee;




				if (in_array('chk_gross_salary', $check_payment)) {
					$basic_salary = 0;
				}else if ($check && $check[0]->is_advance == 1) {
					$basic_salary = $basic_salary - $advance_amount;
				}

				
				

				// employee deduction
				$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
				$pa_month = date('m-Y', strtotime('01-' . $pay_date));
				$deduction_amount = 0;
				if (!in_array('chk_total_employee_deduction', $check_payment)) {
					if ($employee_deduction) {
						foreach ($employee_deduction as $deduction) {
							if ($deduction->type_id == 1) {
								$deduction_amount +=  $deduction->amount;
							}
							if ($deduction->type_id == 2) {
								$from_month_year = date('m-Y', strtotime($deduction->from_date));
								$to_month_year = date('d-m-Y', strtotime($deduction->to_date));


								if ($from_month_year != "" && $to_month_year != "") {
									if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
										$deduction_amount +=  $deduction->amount;
									}
								}
							}
						}
					}
				}



				//3: Gross rate of pay (unpaid leave deduction)
				$holidays_count = 0;
				$no_of_working_days = 0;
				$month_start_date = new DateTime('01-' . $pay_date);
				$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
				$month_end_date->modify('+1 day');
				$interval = new DateInterval('P1D');
				$period = new DatePeriod($month_start_date, $interval, $month_end_date);
				foreach ($period as $p) {
					$period_day = $p->format('l');
					$period_date = $p->format('Y-m-d');

					//holidays in a month
					$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
					if ($is_holiday) {
						$holidays_count += 1;
					}

					//working days excluding holidays based on office shift
					if ($period_day == 'Monday') {
						if ($office_shift[0]->monday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Tuesday') {
						if ($office_shift[0]->tuesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Wednesday') {
						if ($office_shift[0]->wednesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Thursday') {
						if ($office_shift[0]->thursday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Friday') {
						if ($office_shift[0]->friday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Saturday') {
						if ($office_shift[0]->saturday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Sunday') {
						if ($office_shift[0]->sunday_in_time != '') {
							$no_of_working_days += 1;
						}
					}
				}

				//unpaid leave
				$unpaid_leave_amount = 0;
				$leaves_taken_count = 0;
				$leave_period = array();
				$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
				if ($unpaid_leaves) {
					foreach ($unpaid_leaves as $k => $l) {
						$pay_date_month = new DateTime('01-' . $pay_date);
						$l_from_date = new DateTime($l->from_date);
						$l_to_date = new DateTime($l->to_date);

						if ($l_from_date->format('m') == $l_to_date->format('m')) {
							$start_date = $l_from_date;
							$end_date = $l_to_date;
						} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
							$start_date = $l_from_date;
							$end_date = new DateTime($start_date->format('Y-m-t'));
						} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
							$start_date = $pay_date_month;
							$end_date = $l_to_date;
						}
						$end_date->modify('+1 day');
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($start_date, $interval, $end_date);
						foreach ($period as $d) {
							$p_day = $d->format('l');
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							}
						}
						$leave_period[$k]['is_half'] = $l->is_half_day;
					}
				}

				// if joining date and pay date same then this logic work
				$month_date_join = date('m-Y', strtotime($r->date_of_joining));
				$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
				$month_last_date = date('m-Y', strtotime($lastday));

				$first_date =  new DateTime($r->date_of_joining);
				$no_of_days_worked = 0;
				$same_month_holidays_count = 0;
				if ($month_date_join == $pay_date) {
				$interval = new DateInterval('P1D');
				$period = new DatePeriod($first_date, $interval, $month_end_date);
				foreach ($period as $p) {
					$p_day = $p->format('l');
					$m_p_date = $p->format('Y-m-d');

					//holidays in a month
					$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
					if ($is_holiday) {
						$same_month_holidays_count += 1;
					}

					//working days excluding holidays based on office shift
					if ($p_day == 'Monday') {
						if ($office_shift[0]->monday_in_time != '') {
							$no_of_days_worked += 1;
						}
					} else if ($p_day == 'Tuesday') {
						if ($office_shift[0]->tuesday_in_time != '') {
							$no_of_days_worked += 1;
						}
					} else if ($p_day == 'Wednesday') {
						if ($office_shift[0]->wednesday_in_time != '') {
							$no_of_days_worked += 1;
						}
					} else if ($p_day == 'Thursday') {
						if ($office_shift[0]->thursday_in_time != '') {
							$no_of_days_worked += 1;
						}
					} else if ($p_day == 'Friday') {
						if ($office_shift[0]->friday_in_time != '') {
							$no_of_days_worked += 1;
						}
					} else if ($p_day == 'Saturday') {
						if ($office_shift[0]->saturday_in_time != '') {
							$no_of_days_worked += 1;
						}
					} else if ($p_day == 'Sunday') {
						if ($office_shift[0]->sunday_in_time != '') {
							$no_of_days_worked += 1;
						}
					}
				}
				// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
				$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
				} else {
					// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
					$no_of_days_worked = $no_of_working_days -  $holidays_count;
				}


				if ($month_date_join == $pay_date){
					$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
				}else{
					$show_main_salary = $basic_salary;
				}
			
				$g_ordinary_wage += $show_main_salary;
				$g_shg += $show_main_salary;
				$g_sdl += $show_main_salary;
			
				$g_ordinary_wage -= $deduction_amount;
				$g_shg -= $deduction_amount;
				$g_sdl -= $deduction_amount;


				// 3: all loan/deductions
				$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
				$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
				$loan_de_amount = 0;
				if (!in_array('chk_loan_de_amount', $check_payment) ) {
					if ($count_loan_deduction > 0) {
						foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
								} else {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
								}
							} else {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
							}
							$loan_de_amount += $er_loan;
						}
					} else {
						$loan_de_amount = 0;
					}
					$loan_de_amount = round($loan_de_amount, 2);
				}
				
				// 2: all allowances
				$allowance_amount = 0;
				$gross_allowance_amount = 0;
				$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
				if (!in_array('chk_total_allowances', $check_payment)) {
					if ($salary_allowances) {
						foreach ($salary_allowances as $sa) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$eallowance_amount = $sa->allowance_amount / 2;
								} else {
									$eallowance_amount = $sa->allowance_amount;
								}
							} else {
								$eallowance_amount = $sa->allowance_amount;
							}


							if (!empty($sa->salary_month)) {
								$g_additional_wage += $eallowance_amount;
							} else {
								if ($month_date_join == $pay_date){
									$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
								}
								$g_ordinary_wage += $eallowance_amount;
								if ($sa->id == 2) {
									$gross_allowance_amount = $eallowance_amount;
								}
							}

							if ($sa->sdl == 1) {
								$g_sdl += $eallowance_amount;
							}
							if ($sa->shg == 1) {
								$g_shg += $eallowance_amount;
							}

							$allowance_amount += $eallowance_amount;
						}
					}
					$gross_allowance_amount = $allowance_amount;
				}


				// commissions
				$commissions_amount = 0;
				$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
				if (!in_array('chk_total_commissions', $check_payment)) {
					if ($commissions) {
						foreach ($commissions as $c) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$ecommissions_amount = $c->commission_amount / 2;
								} else {
									$ecommissions_amount = $c->commission_amount;
								}
							} else {
								$ecommissions_amount = $c->commission_amount;
							}

							if ($c->commission_type == 9) {
								$g_ordinary_wage += $ecommissions_amount;
							} elseif ($c->commission_type == 10) {
								$g_additional_wage += $ecommissions_amount;
							}

							if ($c->sdl == 1) {
								$g_sdl += $ecommissions_amount;
							}
							if ($c->shg == 1) {
								$g_shg += $ecommissions_amount;
							}

							$commissions_amount += $ecommissions_amount;
						}
					}
				}

				//share options
				$share_options_amount = 0;
				if (!in_array('chk_total_share', $check_payment)) {
					$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
					if ($share_options) {
						$eebr_amount = 0;
						$eris_amount = 0;
						foreach ($share_options as $s) {
							$scheme = $s->so_scheme;
							if ($scheme == 1) {
								$price_doe = $s->price_date_of_excercise;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
								$amount = ($price_doe - $price_ex) * $no_shares;
								$eebr_amount += $amount;
							} else {
								$price_doe = $s->price_date_of_excercise;
								$price_dog = $s->price_date_of_grant;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;

								$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
								$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
								$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
							}
						}
						$share_options_amount = round($eebr_amount + $eris_amount, 2);
						$g_additional_wage += $share_options_amount;
						$g_sdl += $share_options_amount;
						$g_shg += $share_options_amount;
					}
				}

				
				// otherpayments
				$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
				$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
				$other_payments_amount = 0;
				if (!in_array('chk_total_other_payments', $check_payment)) {
					if ($count_other_payments > 0) {
						foreach ($other_payments->result() as $sl_other_payments) {
							if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
								if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$epayments_amount = $sl_other_payments->payments_amount / 2;
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}

									if ($sl_other_payments->cpf_applicable == 1) {
										$g_additional_wage += $epayments_amount;
										$g_shg += $epayments_amount;
										$g_sdl += $epayments_amount;
									}

									$other_payments_amount += $epayments_amount;
								}
							} else {
								$first_date = new DateTime($sl_other_payments->date);
								if ($first_date->format('m-Y') == $pay_date) {
									$first_date =  new DateTime($sl_other_payments->date);
								} else {
									$first_date = new DateTime('01-' . $pay_date);
								}

								$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));

								if (!empty($sl_other_payments->end_date)) {
									$last_date = new DateTime($sl_other_payments->end_date);
									if ($last_date->format('m-Y') == $pay_date) {
										$last_date = new DateTime($sl_other_payments->end_date);
									} else if($last_date->format('m-Y') >= $pay_date){
										$last_date = $month_end_date_for_other;
									} else {
										$last_date = '';
									}
								} else {
									$last_date = $month_end_date_for_other;
								}
								if(!empty($last_date)){
									$last_date->modify('+1 day');
									$final_last_day = new DateTime($last_date->format('d-m-Y'));
									if ($final_last_day->format('m-Y') >= $pay_date) {
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$epayments_amount = $sl_other_payments->payments_amount / 2;
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}


										// it for no of working day
										$no_of_days_worked_for_other_payment = 0;
										$same_month_holidays_count_for_other_payment = 0;
										$interval = new DateInterval('P1D');
										$period = new DatePeriod($first_date, $interval, $last_date);
										foreach ($period as $p) {
											$p_day = $p->format('l');
											$p_date = $p->format('Y-m-d');

											//holidays in a month

											$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
											if ($is_holiday) {
												$same_month_holidays_count_for_other_payment += 1;
											}

											//working days excluding holidays based on office shift
											if ($p_day == 'Monday') {
												if ($office_shift[0]->monday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Tuesday') {
												if ($office_shift[0]->tuesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Wednesday') {
												if ($office_shift[0]->wednesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Thursday') {
												if ($office_shift[0]->thursday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Friday') {
												if ($office_shift[0]->friday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Saturday') {
												if ($office_shift[0]->saturday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Sunday') {
												if ($office_shift[0]->sunday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											}
										}

										$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
										$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);


										if ($sl_other_payments->cpf_applicable == 1) {
											$g_additional_wage += $epayments_amount;
											$g_shg += $epayments_amount;
											$g_sdl += $epayments_amount;
										}
										$other_payments_amount += $epayments_amount;
									}
								}
							}
						}
					} else {
						$other_payments_amount = 0;
					}
				}

				$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
	
				if ($unpaid_leaves) {
					$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
				}
				
				// $g_ordinary_wage = $gross_pay;
				$g_shg = $gross_pay;
				$g_sdl = $gross_pay;

				$g_ordinary_wage -= $unpaid_leave_amount;

				

				// other benefit
				$other_benefit_mount = 0;
				if (!in_array('chk_total_other_payments', $check_payment)) {
					$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
					foreach ($other_benefit_list->result() as $benefit_list) {
						$other_benefit_mount += $benefit_list->other_benefit_cost;
					}
				}


				// statutory_deductions
				$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
				$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
				$statutory_deductions_amount = 0;
				if (!in_array('chk_total_statutory_deductions', $check_payment) || in_array('chk_total_allowances', $check_payment) || in_array('chk_total_other_payments', $check_payment) || in_array('chk_gross_salary', $check_payment)) {
					if ($count_statutory_deductions > 0) {
						foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
							if ($system[0]->statutory_fixed != 'yes') {
								$sta_salary = $gross_pay;
								$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $st_amount / 2;
									} else {
										$single_sd = $st_amount;
									}
								} else {
									$single_sd = $st_amount;
								}
								$statutory_deductions_amount += $single_sd;
							} else {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
									} else {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount;
									}
								} else {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount;
								}
								$statutory_deductions_amount += $single_sd;
							}
						}
					} else {
						$statutory_deductions_amount = 0;
					}
				}

				// overtime
				$overtime_amount = 0;
				if (!in_array('chk_total_overtime', $check_payment)) {
					$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
					if ($overtime) {
						$ot_hrs = 0;
						$ot_mins = 0;
						foreach ($overtime as $ot) {
							$total_hours = explode(':', $ot->total_hours);
							$ot_hrs += $total_hours[0];
							$ot_mins += $total_hours[1];
						}
						if ($ot_mins > 0) {
							$ot_hrs += round($ot_mins / 60, 2);
						}

						//overtime rate
						$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
						if ($overtime_rate) {
							$rate = $overtime_rate->overtime_pay_rate;
						} else {
							$week_hours = 44;
							$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
							$rate = $rate * 1.5;
						}

						if ($ot_hrs > 0) {
							$overtime_amount = round($ot_hrs * $rate, 2);
						}
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$overtime_amount = $overtime_amount / 2;
							}
						}
						$g_ordinary_wage += $overtime_amount;
						$g_sdl += $overtime_amount;
					}
				}

				

				$payment_check = $this->Payroll_model->check_make_payment_payslip_for_first_payment($r->user_id, $pay_date);
				$payment_check_final = $this->Payroll_model->check_make_payment_payslip_for_final_payment($r->user_id, $pay_date);
				$balance = 0;
				$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
				if($check && $check[0]->balance_amount > 0){
					$balance = $check[0]->balance_amount;
					$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
				
					$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

					$status = '<span class="label label-warning">' . "Partially Paid" . '</span>';

					$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
					$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
					
					if (in_array('313', $role_resources_ids)) {
						$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
					} else {
						$delete = '';
					}
					// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
					$delete  = $delete;
					$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					
				}else if($check && $check[0]->balance_amount == 0){
					$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
				
					$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

					$status = '<span class="label label-success">Paid</span>';

					$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
					
					if (in_array('313', $role_resources_ids)) {
						$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
					} else {
						$delete = '';
					}
					// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
					$delete  = $delete;
					$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					
				}else {
						$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
						$delete = '';
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						$balance = $this->Xin_model->currency_sign(0, $r->user_id);
					}

				
				// employee accommodations
				$employee_accommodations = 0;
				if (!in_array('chk_total_employee_deduction', $check_payment)) {
					$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
					foreach ($get_employee_accommodations as $get_employee_accommodation) {
						$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
						$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
						if ($period_from == $pay_date || $period_to == $pay_date) {
							if (!empty($get_employee_accommodation->rent_paid)) {
								$employee_accommodations += $get_employee_accommodation->rent_paid;
							}
						}
					}
				}

				// employee claims
				$claim_amount = 0;
				if (!in_array('chk_employee_claim', $check_payment)) {
					$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
					foreach ($get_employee_claims->result() as $claims) {
						$date 	= 	date('m-Y', strtotime($claims->date));
						if ($date == $pay_date) {
							$claim_amount += $claims->amount;
						}
					}
				}



				$total_earning = $show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
				$total_deduction = $loan_de_amount + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
				$total_net_salary = ($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;


				// cpf calculation
				$emp_dob = $r->date_of_birth;
				$dob = new DateTime($emp_dob);

				$today = new DateTime('01-' . $pay_date);
				$age = $dob->diff($today);
				$age_year = $age->y;
				$age_month = $age->m;

				$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
				$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
				$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;

				if ($age_month > 0) {
					$age_year = $age_year + 1;
				}

				$cpf_employee 	= 	0;
				$cpf_employer	=	0;

				$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
				if ($im_status) {
					$immigration_id = $im_status->immigration_id;
					if ($immigration_id == 2) {
						$issue_date = $im_status->issue_date;
						$i_date = new DateTime($issue_date);
						$today = new DateTime();
						$pr_age = $i_date->diff($today);
						$pr_age_year = $pr_age->y;
						$pr_age_month = $pr_age->m;
					}

					if ($immigration_id == 1 || $immigration_id == 2) {

						$ordinary_wage = $g_ordinary_wage;
						if ($ordinary_wage > $ordinary_wage_cap) {
							$ow = $ordinary_wage_cap;
						} else {
							$ow = $ordinary_wage;
						}

						//additional wage
						$additional_wage = $g_additional_wage;
						$aw = $g_additional_wage;
						$tw = $ow + $additional_wage;
						if ($im_status->issue_date != "") {
							if ($pr_age_year == 1) {

								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(4 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 666) {
											$total_cpf = 666;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(4 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 666) {
											$total_cpf = 666;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(3.5 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(3.5 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
							if ($pr_age_year == 2) {
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.45 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
										$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
										if ($count_total_cpf > 1776) {
											$total_cpf = 1776;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1110) {
											$cpf_employee = 1110;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(6 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.375 * ($tw - 500));
										$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
										$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
										if ($count_total_cpf > 1369) {
											$total_cpf = 1369;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 925) {
											$cpf_employee = 925;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(3.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
										if ($count_total_cpf > 814) {
											$total_cpf = 814;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(3.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
							if ($pr_age_year == 3 || $pr_age_year > 3) {
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(17 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.6 * ($tw - 500));
										$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
										$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
										if ($count_total_cpf > 2738) {
											$total_cpf = 2738;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1480) {
											$cpf_employee = 1480;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = $total_cpf - $cpf_employee;
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(15.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.51 * ($tw - 500));
										$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
										$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
										if ($count_total_cpf > 2405) {
											$total_cpf = 2405;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1258) {
											$cpf_employee = 1258;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(12 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.345 * ($tw - 500));
										$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
										$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;

										if ($count_total_cpf > 1739) {
											$total_cpf = 1739;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 851) {
											$cpf_employee = 851;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65 && $age_year <= 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;

										if ($count_total_cpf > 1221) {
											$total_cpf = 1221;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(7.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;

										if ($count_total_cpf > 925) {
											$total_cpf = 925;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
						}



						if ($immigration_id == 1) {

							if ($age_year <= 55) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(17 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.6 * ($tw - 500));
									$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
									$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
									if ($count_total_cpf > 2738) {
										$total_cpf = 2738;
									} else {
										$total_cpf = $count_total_cpf;
									}
			
									
									if ($count_cpf_employee > 1480) {
										$cpf_employee = 1480;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 55 && $age_year <= 60) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(15.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.51 * ($tw - 500));
									$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
			
									$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
									if ($count_total_cpf > 2405) {
										$total_cpf = 2405;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 1258) {
										$cpf_employee = 1258;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 60 && $age_year <= 65) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(12 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.345 * ($tw - 500));
									$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
									$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
			
									if ($count_total_cpf > 1739) {
										$total_cpf = 1739;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 851) {
										$cpf_employee = 851;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 65 && $age_year <= 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(9 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.225 * ($tw - 500));
									$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
									$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
			
									if ($count_total_cpf > 1221) {
										$total_cpf = 1221;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 555) {
										$cpf_employee = 555;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(7.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.15 * ($tw - 500));
									$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
									$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
			
									if ($count_total_cpf > 925) {
										$total_cpf = 925;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 370) {
										$cpf_employee = 370;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							}
						}

						if($check){
							$cpf_employee = $database_cpf_employee;
						}

						$total_net_salary = $total_net_salary - $cpf_employee;
						$cpf_total = $cpf_employee + $cpf_employer;

						$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
						$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
						$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
					}
				}


				$shg_fund_deduction_amount = 0;
				//Other Fund Contributions
				$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);

				if ((!$check || $check[0]->is_advance == 1) && $employee_contributions && $g_shg > 0) {
					$gross_s = $g_shg;
					$contribution_id = $employee_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					$shg_fund_name = $contribution_type[0]->contribution;
					$shg_fund_deduction_amount += $contribution_amount;
					$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
				}
				$ashg_fund_deduction_amount = 0;

				$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
				if ((!$check || $check[0]->is_advance == 1) && $employee_ashg_contributions  && $g_shg > 0) {
					$gross_s = $g_shg;
					$contribution_id = $employee_ashg_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					$ashg_fund_name = $contribution_type[0]->contribution;

					$ashg_fund_deduction_amount += $contribution_amount;
					$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
				}

				// echo $total_net_salary;

				// $sdl_total_amount = 0;
				// if ($g_sdl > 1 && $g_sdl <= 800) {
				// 	$sdl_total_amount = 2;
				// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
				// 	$sdl_amount = (0.25 * $g_sdl) / 100;
				// 	$sdl_total_amount = $sdl_amount;
				// } elseif ($g_sdl > 4500) {
				// 	$sdl_total_amount = 11.25;
				// }


				$net_salary = number_format((float)$total_net_salary, 2, '.', '');
				$basic_salary = number_format((float)$basic_salary, 2, '.', '');
				// if ($basic_salary == 0 || $basic_salary == '') {
				// 	$fmpay = '';
				// } else {
					$fmpay = $mpay;
				// }


				// $company_info = $this->Company_model->read_company_information($r->company_id);
				// if (!is_null($company_info)) {
				// 	$basic_salary = $this->Xin_model->company_currency_sign($basic_salary, $r->company_id);
				// 	$net_salary = $this->Xin_model->company_currency_sign($net_salary, $r->company_id);
				// 	//	$cpf_employee = $this->Xin_model->company_currency_sign($cpf_employee,$r->company_id);	
				// } else {
				// 	//$basic_salary = $this->Xin_model->currency_sign($basic_salary);
				// 	$basic_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->basic_salary, $r->user_id);

				// 	$net_salary = $this->Xin_model->currency_sign($payment_check->result()[0]->net_salary, $r->user_id);

				// 	//$net_salary = $this->Xin_model->currency_sign($net_salary);	
				// 	//$cpf_employee = $this->Xin_model->currency_sign($cpf_employee);	
				// 	$cpf_employee = $this->Xin_model->currency_sign($payment_check->result()[0]->cpf_employee_amount, $r->user_id);
				// }

				$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';

				//action link
				$act = $detail . $fmpay . $delete;
				// $act = $fmpay . $delete;

				if ($r->wages_type == 1) {
					if ($system[0]->is_half_monthly == 1) {
						$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
					} else {
						$emp_payroll_wage = $wages_type;
					}
				} else {
					$emp_payroll_wage = $wages_type;
				}

				$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
				$p = $payslips->result();
				
				$mode_of_payment = $r->payment_mode ?? '';

				$main_check = $check && $check[0]->is_advance !=1 && $check[0]->balance_amount == 0 ? 'checked disabled' : ''; 

				$field =  '<div class="k-top"><span class="k-icon k-plus" role="presentation"></span><span class="k-checkbox" role="presentation"  style="display:inline-block;"><label><input type="checkbox" name="employee_id[]" class="role-checkbox main_check_box" value="'.$r->user_id.'" '.$main_check.'> </label></span><span class="k-in k-state-focused"></span></div>';
				

				$check_gross_salary = in_array('chk_gross_salary', $check_payment) ? "checked disabled" : "";
				$check_allowance_amount = in_array('chk_total_allowances', $check_payment) ? "checked disabled" : "";
				$check_unpaid_leave_amount = in_array('chk_leave_deductions', $check_payment) ? "checked disabled" : "";
				$check_commissions_amount = in_array('chk_total_commissions', $check_payment) ? "checked disabled" : "";
				$check_loan_de_amount = in_array('chk_loan_de_amount', $check_payment) ? "checked disabled" : "";
				$check_overtime_amount = in_array('chk_total_overtime', $check_payment) ? "checked disabled" : "";
				$check_statutory_deductions_amount = in_array('chk_total_statutory_deductions', $check_payment) ? "checked disabled" : "";
				$check_other_payments_amount = in_array('chk_total_other_payments', $check_payment) ? "checked disabled" : "";
				$check_total_deduction = in_array('chk_total_employee_deduction', $check_payment) ? "checked disabled" : "";
				$check_claim_amount = in_array('chk_employee_claim', $check_payment) ? "checked disabled" : "";
				$check_share_options_amount = in_array('chk_total_share', $check_payment) ? "checked disabled" : "";
				
				$data[] = array(
					$field,
					$iemp_name,
					$emp_payroll_wage. '<input type="hidden" name="loop[]" value="'.$key.'"/>',
					'<input type="text" name="gross_salary[]" value="'. $show_main_salary . '" readonly class="form-control"/> <input type="checkbox"  name="chk_gross_salary[]" id="chk_gross_salary" class="header_chk_gross_salary bc_checked_class row-item" '. $check_gross_salary  .'/>',
					'<input type="text" name="total_allowances[]" value="'.$allowance_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_total_allowances[]" id="chk_total_allowances" class="header_chk_total_allowances bc_checked_class row-item" '. $check_allowance_amount  .'/>',
					'<input type="text" name="leave_deductions[]" value="'.$unpaid_leave_amount . '" readonly class="form-control leave_deductions"/> <input type="checkbox"  name="chk_leave_deductions[]" id="chk_leave_deductions" class="header_chk_leave_deductions bc_checked_class row-item" '. $check_unpaid_leave_amount  .'/>',
					'<input type="text" name="total_commissions[]" value="'.$commissions_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_total_commissions[]" id="chk_total_commissions" class="header_chk_total_commissions bc_checked_class row-item" '. $check_commissions_amount  .'/>',
					'<input type="text" name="total_loan[]" value="'.$loan_de_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_loan_de_amount[]" id="chk_loan_de_amount" class="header_chk_loan_de_amount bc_checked_class row-item" '. $check_loan_de_amount  .'/>',		
					'<input type="text" name="total_overtime[]" value="'.$overtime_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_total_overtime[]" id="chk_total_overtime" class="header_chk_total_overtime bc_checked_class row-item" '. $check_overtime_amount  .'/>',
					'<input type="text" name="total_statutory_deductions[]" value="'.$statutory_deductions_amount . '" readonly class="form-control statutory_deductions"/> <input type="checkbox"  name="chk_total_statutory_deductions[]" id="chk_total_statutory_deductions" class="header_chk_total_statutory_deductions bc_checked_class row-item" '. $check_statutory_deductions_amount  .'/>',
					'<input type="text" name="total_other_payments[]" value="'.$other_payments_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_total_other_payments[]" id="chk_total_other_payments" class="header_chk_total_other_payments bc_checked_class row-item" '. $check_other_payments_amount  .'/>',
					'<input type="text" name="total_cpf_employee[]" value="'.$cpf_employee.'" readonly class="form-control total_cpf_employee" />',
					'<input type="text" name="total_cpf_employer[]" value="'.$cpf_employer.'"  readonly class="form-control total_cpf_employer"/>',
					'<input type="text" name="total_cpf[]" value="'.($cpf_employer + $cpf_employee).'"  readonly class="form-control total_cpf"/>',
					'<input type="text" name="total_fund_contribution[]" value="'.($shg_fund_deduction_amount + $ashg_fund_deduction_amount).'"  readonly class="form-control total_fund_contribution"  />',
					'<input type="text" name="total_employee_deduction[]" value="'.$total_deduction . '" readonly class="form-control total_employee_deduction"/> <input type="checkbox"  name="chk_total_employee_deduction[]" id="chk_total_employee_deduction" class="header_chk_total_employee_deduction bc_checked_class row-item" '. $check_total_deduction  .'/>',
					'<input type="text" name="employee_claim[]" value="'.$claim_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_employee_claim[]" id="chk_employee_claim" class="header_chk_employee_claim bc_checked_class row-item" '. $check_claim_amount  .'/>',
					'<input type="text" name="total_share[]" value="'.$share_options_amount . '" readonly class="form-control"/> <input type="checkbox"  name="chk_total_share[]" id="chk_total_share" class="header_chk_total_share bc_checked_class row-item" '. $check_share_options_amount  .'/>',
					'<input type="text" name="balance_amount[]" class="form-control balance_amount" value="0" readonly style="width: 100px;" >',
					'<input type="text" name="net_salary[]" value="'.$net_salary.'" class="form-control net_salary_s" readonly>',
					'<input type="text" name="payment_amount[]" value="'.$net_salary.'" class="form-control payment_amount_s" readonly>',
					
					// $net_salary,
					$status,
					"",
				);

			}
		}
		$output = array(
			"draw" 				=> 	$draw,
			"recordsTotal" 		=> 	$payslip->num_rows(),
			"recordsFiltered" 	=> 	$payslip->num_rows(),
			"data" 				=> 	$data
		);
		echo json_encode($output);
		exit();
	}


	// bulk payment
	public function add_pay_bulk(){	
		// print_r($this->input->post('employee_id'));
		// foreach($this->input->post('loop') as $key){
		// 	// echo $key;
		// 	   $employee_id = $this->input->post('employee_id')[$key] ?? 0;
		// 	if(!empty($employee_id)){
		// 		echo $employee_id;
		// 		// echo $this->input->post('total_statutory_deductions')[$key] ?? 0;
		// 	}
		// }
		// exit;
		if(!empty($this->input->post('employee_id'))){
			foreach($this->input->post('loop') as $key => $l){
				$employee_id = $this->input->post('employee_id')[$key] ?? 0;
				if($employee_id){
					$user = $this->Xin_model->read_user_info($employee_id);
					$jurl = random_string('alnum', 40);
					// echo $employee_id;
					$basic_salary = 0;
					$gross_salary = 0;
					$leave_deductions = 0;
					$total_allowances = 0;
					$total_loan = 0;
					$total_overtime = 0;
					$total_commissions = 0;
					$total_statutory_deductions = 0;
					$total_employee_deduction = 0;
					$employee_claim = 0;
					$total_other_payments = 0;
					$total_share = 0;
					$additional_allowances = 0;
					$total_amount = 0;

					// $basic_salary = $user[0]->basic_salary;

					$check_id = explode(',',$this->input->post('check_id'));
					// print_r($check_id);
					if (in_array('chk_gross_salary',$check_id)) {	
						$check_id = array_values(array_diff($check_id, ['chk_gross_salary']));
						$gross_salary = $this->input->post('gross_salary')[$key];
						$basic_salary = $this->input->post('gross_salary')[$key];
					}

					// if ($this->input->post('chk_leave_deductions')) {
						$leave_deductions = $this->input->post('leave_deductions')[$key];
					// }

					if (in_array('chk_total_allowances',$check_id)) {
						$total_allowances = $this->input->post('total_allowances')[$key];
					}

					

					if (in_array('chk_total_commissions',$check_id)) {
						$total_commissions = $this->input->post('total_commissions')[$key];
					}

					if (in_array('chk_loan_de_amount',$check_id)) {
						$total_loan = $this->input->post('total_loan')[$key];
					}

					if (in_array('chk_total_overtime',$check_id)) {
						$total_overtime = $this->input->post('total_overtime')[$key];
					}

					if ( in_array('chk_total_statutory_deductions',$check_id) || in_array('chk_gross_salary',$check_id) || in_array('chk_total_allowances',$check_id) || in_array('chk_total_other_payments',$check_id)) {
						$total_statutory_deductions = $this->input->post('total_statutory_deductions')[$key];
					}

					if (in_array('chk_total_other_payments',$check_id)) {
						$total_other_payments = $this->input->post('total_other_payments')[$key];
					}

					if (in_array('chk_total_employee_deduction',$check_id)) {
						$total_employee_deduction = $this->input->post('total_employee_deduction')[$key];
					}

					if (in_array('chk_employee_claim',$check_id)) {
						$employee_claim = $this->input->post('employee_claim')[$key];
					}

					if (in_array('chk_total_share',$check_id)) {
						$total_share = $this->input->post('total_share')[$key];
					}


					// logic for hourly payment
					//overtime request
					$overtime_count = $this->Overtime_request_model->get_overtime_request_count($employee_id, $this->input->post('pay_date'));
					$re_hrs_old_int1 = 0;
					$re_hrs_old_seconds = 0;
					$re_pcount = 0;
					foreach ($overtime_count as $overtime_hr) {
						$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
						$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
						$re_interval_late = $request_clock_in->diff($request_clock_out);
						$re_hours_r  = $re_interval_late->format('%h');
						$re_minutes_r = $re_interval_late->format('%i');
						$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';

						$re_str_time = $re_total_time;

						$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);

						sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);

						$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

						$re_hrs_old_int1 += $re_hrs_old_seconds;

						$re_pcount = gmdate("H", $re_hrs_old_int1);
					}

					$result = $this->Payroll_model->total_hours_worked($employee_id, $this->input->post('pay_date'));
					$hrs_old_int1 = 0;
					$pcount = 0;
					foreach ($result->result() as $hour_work) {
						$clock_in =  new DateTime($hour_work->clock_in);
						$clock_out =  new DateTime($hour_work->clock_out);
						$interval_late = $clock_in->diff($clock_out);
						$hours_r  = $interval_late->format('%h');
						$minutes_r = $interval_late->format('%i');
						$total_time = $hours_r . ":" . $minutes_r . ":" . '00';

						$str_time = $total_time;

						$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

						sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

						$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

						$hrs_old_int1 += $hrs_old_seconds;

						$pcount = gmdate("H", $hrs_old_int1);
					}
					$pcount = $pcount + $re_pcount;
					// end logic

					// print_r($check_id);
					

					$data = array(
						'employee_id' 			=> 	$employee_id,
						'department_id' 		=> 	$user[0]->department_id,
						'company_id' 			=> 	$user[0]->company_id,
						'location_id' 			=> 	$user[0]->location_id,
						'designation_id' 		=> 	$user[0]->designation_id,
						'salary_month' 			=> 	$this->input->post('pay_date'),
						'basic_salary' 			=> 	$basic_salary,
						'gross_salary' 			=> 	$gross_salary,
						'net_salary' 			=> 	$this->input->post('net_salary')[$key],
						'wages_type' 			=> 	$user[0]->wages_type,
						'hours_worked'			=>	$pcount,
						'is_half_monthly_payroll' 		=> 	0,
						'total_commissions' 			=> 	$total_commissions,
						'total_statutory_deductions' 	=> $total_statutory_deductions,
						'total_other_payments' 	=> 	$total_other_payments,
						'total_allowances' 		=> 	$total_allowances,
						'total_loan' 			=> 	$total_loan,
						'total_overtime' 		=> 	$this->input->post('total_overtime_time') ?? 0,
						'total_overtime_amount' => 	$total_overtime,
						'claim_amount' 			=> 	$employee_claim,
						'cpf_employee_amount' 	=> 	$this->input->post('total_cpf_employee')[$key],
						'cpf_employer_amount' 	=> 	$this->input->post('total_cpf_employer')[$key],
						'leave_deduction' 		=> 	$leave_deductions,
						'contribution_fund' 	=> 	$this->input->post('total_fund_contribution')[$key],
						'share_option_amount' 	=> 	$total_share,
						'additonal_allowance' 	=> 	$additional_allowances,
						'deduction_amount' 		=> 	$total_employee_deduction,
						'balance_amount' 		=> 	$this->input->post('balance_amount')[$key],
						'is_payment' 			=> 	'1',
						'status' 				=> 	$this->input->post('balance_amount')[$key] > 0 ? 2 : 1,
						'payslip_type' 			=> 	'full_monthly',
						'payslip_key' 			=> 	$jurl,
						'year_to_date' 			=> 	date('d-m-Y'),
						'created_at' 			=> 	date('d-m-Y h:i:s'),
						'check_id'				=>	$this->input->post('check_id'),
						'payment_mode'			=>	$this->input->post('payment_mode'),
					);
					// print_r($data);
					$result = $this->Payroll_model->add_salary_payslip($data);

					if ($result) {

						$g_ordinary_wage = 0;
						$g_additional_wage = 0;
						$g_shg = 0;
						$g_sdl = 0;

						$g_ordinary_wage += $basic_salary;
						$g_shg += $basic_salary;
						$g_sdl += $basic_salary;

						$office_shift = $this->Timesheet_model->read_office_shift_information($user[0]->office_shift_id);
						$system = $this->Xin_model->read_setting_info(1);

						//3: Gross rate of pay (unpaid leave deduction)
						$holidays_count = 0;
						$no_of_working_days = 0;
						$month_start_date = new DateTime('01-' . $this->input->post('pay_date'));
						$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
						$month_end_date->modify('+1 day');
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($month_start_date, $interval, $month_end_date);
						foreach ($period as $p) {
							$p_day = $p->format('l');
							$p_date = $p->format('Y-m-d');

							//holidays in a month
							$is_holiday = $this->Timesheet_model->is_holiday_on_date($user[0]->company_id, $p_date);
							if ($is_holiday) {
								$holidays_count += 1;
							}

							//working days excluding holidays based on office shift
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$no_of_working_days += 1;
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$no_of_working_days += 1;
								}
							}
						}

						//unpaid leave
						$unpaid_leave_amount = 0;
						$leaves_taken_count = 0;
						$leave_period = array();
						$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($employee_id, $this->input->post('pay_date'));
						if ($unpaid_leaves) {
							foreach ($unpaid_leaves as $k => $l) {
								$pay_date_month = new DateTime('01-' . $this->input->post('pay_date'));
								$l_from_date = new DateTime($l->from_date);
								$l_to_date = new DateTime($l->to_date);

								if ($l_from_date->format('m') == $l_to_date->format('m')) {
									$start_date = $l_from_date;
									$end_date = $l_to_date;
								} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
									$start_date = $l_from_date;
									$end_date = new DateTime($start_date->format('Y-m-t'));
								} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
									$start_date = $pay_date_month;
									$end_date = $l_to_date;
								}
								$end_date->modify('+1 day');
								$interval = new DateInterval('P1D');
								$holiday_array_new = array();

								$period = new DatePeriod($start_date, $interval, $end_date);
								foreach ($period as $d) {
									$p_day = $d->format('l');
									if ($p_day == 'Monday') {

										if ($office_shift[0]->monday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Tuesday') {
										if ($office_shift[0]->tuesday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Wednesday') {
										if ($office_shift[0]->wednesday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Thursday') {
										if ($office_shift[0]->thursday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Friday') {
										if ($office_shift[0]->friday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Saturday') {
										if ($office_shift[0]->saturday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									} else if ($p_day == 'Sunday') {
										if ($office_shift[0]->sunday_in_time != '' && in_array($d->format('Y-m-d'), $holiday_array_new) !== true) {
											$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
											if ($l->is_half_day == 0) {
												$leaves_taken_count += 1;
											} else {
												$leaves_taken_count += 0.5;
											}
										}
									}
								}
								$leave_period[$k]['is_half'] = $l->is_half_day;
							}
						}
						
						
						$month_date_join = date('m-Y', strtotime($user[0]->date_of_joining));
						$lastday = date('t-m-Y', strtotime("01-" . $p_date));
						$month_last_date = date('m-Y', strtotime($lastday));

						$first_date =  new DateTime($user[0]->date_of_joining);
						$no_of_days_worked = 0;
						$same_month_holidays_count = 0;
						if ($month_date_join == $this->input->post('pay_date')) {
							$interval = new DateInterval('P1D');
							$period = new DatePeriod($first_date, $interval, $month_end_date);
							foreach ($period as $p) {
								$p_day = $p->format('l');
								$p_date = $p->format('Y-m-d');

								//holidays in a month

								$is_holiday = $this->Timesheet_model->is_holiday_on_date($user[0]->company_id, $p_date);
								if ($is_holiday) {
									$same_month_holidays_count += 1;
								}

								//working days excluding holidays based on office shift
								if ($p_day == 'Monday') {
									if ($office_shift[0]->monday_in_time != '') {
										$no_of_days_worked += 1;
									}
								} else if ($p_day == 'Tuesday') {
									if ($office_shift[0]->tuesday_in_time != '') {
										$no_of_days_worked += 1;
									}
								} else if ($p_day == 'Wednesday') {
									if ($office_shift[0]->wednesday_in_time != '') {
										$no_of_days_worked += 1;
									}
								} else if ($p_day == 'Thursday') {
									if ($office_shift[0]->thursday_in_time != '') {
										$no_of_days_worked += 1;
									}
								} else if ($p_day == 'Friday') {
									if ($office_shift[0]->friday_in_time != '') {
										$no_of_days_worked += 1;
									}
								} else if ($p_day == 'Saturday') {
									if ($office_shift[0]->saturday_in_time != '') {
										$no_of_days_worked += 1;
									}
								} else if ($p_day == 'Sunday') {
									if ($office_shift[0]->sunday_in_time != '') {
										$no_of_days_worked += 1;
									}
								}
							}

							// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
							$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
						} else {
							// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
							$no_of_days_worked = $no_of_working_days -  $holidays_count;
						}

						
						if ($month_date_join == $this->input->post('pay_date')) {
							$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
						} else {
							$show_main_salary = $basic_salary;
						}

						

						// set allowance
						$allowance_amount = 0;
						$gross_allowance_amount = 0;
						if (in_array('chk_total_allowances',$check_id)) {
							// $check_id = array_values(array_diff($check_id, ['chk_total_allowances']));
							$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($employee_id, $this->input->post('pay_date'));
							if ($salary_allowances) {
								foreach ($salary_allowances as $sa) {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$eallowance_amount = $sa->allowance_amount / 2;
										} else {
											$eallowance_amount = $sa->allowance_amount;
										}
									} else {
										$eallowance_amount = $sa->allowance_amount;
									}
									if (!empty($sa->salary_month)) {
										$g_additional_wage += $eallowance_amount;
									} else {
										// for no of working day
										if ($month_date_join == $this->input->post('pay_date')) {
											$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
										}
										$g_ordinary_wage += $eallowance_amount;
										if ($sa->id == 2) {
											$gross_allowance_amount = $eallowance_amount;
										}
									}
						
									if ($sa->sdl == 1) {
										$g_sdl += $eallowance_amount;
									}
									if ($sa->shg == 1) {
										$g_shg += $eallowance_amount;
									}
						
									$allowance_amount += $eallowance_amount;

									$allowance_data = array(
										'payslip_id' 		=> 	$result,
										'employee_id' 		=> 	$employee_id,
										'salary_month' 		=> 	$this->input->post('pay_date'),
										'allowance_title' 	=> 	$sa->allowance_title,
										'allowance_amount' 	=> 	$eallowance_amount,
										'created_at' 		=> 	date('d-m-Y h:i:s')
									);
									$_allowance_data = $this->Payroll_model->add_salary_payslip_allowances($allowance_data);
			
								}
							}
						}
						$gross_allowance_amount = $allowance_amount;


						// employee deduction
						$employee_deduction = $this->Payroll_model->get_deduction_detail($employee_id, $this->input->post('pay_date'));
						if (in_array('chk_total_employee_deduction',$check_id)) {
							// $check_id = array_values(array_diff($check_id, ['chk_total_employee_deduction']));
							unset($check_id[array_search('chk_total_employee_deduction', $check_id)]);
							foreach ($employee_deduction as $deduction) {
								$xin_salary_deduction = array(
									'payslip_id' 		=> 	$result,
									'employee_id' 		=> 	$employee_id,
									'salary_month' 		=> 	$this->input->post('pay_date'),
									'name' 				=> 	$deduction->deduction_type,
									'amount' 			=> 	$deduction->amount,

								);
								$this->Payroll_model->add_salary_payslip_deduction($xin_salary_deduction);
							}

							$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($employee_id, $this->input->post('pay_date'));
							foreach ($other_benefit_list->result() as $other_benefit) {
								$xin_salary_deduction = array(
									'payslip_id' 		=> 	$result,
									'employee_id' 		=> 	$employee_id,
									'salary_month' 		=> 	$this->input->post('pay_date'),
									'name' 				=> 	$other_benefit->other_benefit,
									'amount' 			=> 	$other_benefit->other_benefit_cost,

								);
								$this->Payroll_model->add_salary_payslip_deduction($xin_salary_deduction);
							}

							$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($employee_id, $this->input->post('pay_date'));

							foreach ($get_employee_accommodations as $get_employee_accommodation) {
								$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
								$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
								if ($period_from == $this->input->post('pay_date') || $period_to == $this->input->post('pay_date')) {
									$xin_salary_deduction = array(
										'payslip_id' 		=> 	$result,
										'employee_id' 		=> 	$employee_id,
										'salary_month' 		=> 	$this->input->post('pay_date'),
										'name' 				=> 	$get_employee_accommodation->title,
										'amount' 			=> 	$get_employee_accommodation->rent_paid,
			
									);
									$this->Payroll_model->add_salary_payslip_deduction($xin_salary_deduction);
								}
							}

						}


						// commissions
						if (in_array('chk_total_commissions',$check_id)) {
							// $check_id = array_values(array_diff($check_id, ['chk_total_commissions']));
							unset($check_id[array_search('chk_total_commissions', $check_id)]);
							$commission_amount = 0;
							$commissions = $this->Employees_model->getEmployeeMonthlyCommission($employee_id, $this->input->post('pay_date'));
							if ($commissions) {
								foreach ($commissions as $c) {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$ecommission_amount = $c->commission_amount / 2;
										} else {
											$ecommission_amount = $c->commission_amount;
										}
									} else {
										$ecommission_amount = $c->commission_amount;
									}

									if ($c->commission_type == 9) {
										$g_ordinary_wage += $ecommission_amount;
									} elseif ($c->commission_type == 10) {
										$g_additional_wage += $ecommission_amount;
									}

									if ($c->sdl == 1) {
										$g_sdl += $ecommission_amount;
									}
									if ($c->shg == 1) {
										$g_shg += $ecommission_amount;
									}

									$commissions_data = array(
										'payslip_id' 		=> 	$result,
										'employee_id' 		=> 	$employee_id,
										'salary_month' 		=> 	$this->input->post('pay_date'),
										'commission_id' 	=> 	$c->commission_type,
										'commission_amount' => 	$ecommission_amount,
										'created_at' 		=> 	date('d-m-Y h:i:s')
									);
									$this->Payroll_model->add_salary_payslip_commissions($commissions_data);
									$commission_amount += $ecommission_amount;
								}
							}
						}

						//share options
						if (in_array('chk_total_share',$check_id)) {
							// $check_id = array_values(array_diff($check_id, ['chk_total_share']));
							unset($check_id[array_search('chk_total_share', $check_id)]);
							$share_options_amount = 0;
							$share_options = $this->Employees_model->getEmployeeShareOptions($employee_id, $this->input->post('pay_date'));
							if ($share_options) {
								$eebr_amount = 0;
								$eris_amount = 0;
								foreach ($share_options as $s) {
									$scheme = $s->so_scheme;
									if ($scheme == 1) {
										$price_doe = $s->price_date_of_excercise;
										$price_ex = $s->excercise_price;
										$no_shares = $s->no_of_shares;
										$amount = ($price_doe - $price_ex) * $no_shares;
										$eebr_amount += $amount;
									} else {
										$price_doe = $s->price_date_of_excercise;
										$price_dog = $s->price_date_of_grant;
										$price_ex = $s->excercise_price;
										$no_shares = $s->no_of_shares;

										$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
										$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
										$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
									}
								}
								$share_options_amount = round($eebr_amount + $eris_amount, 2);
								$g_additional_wage += $share_options_amount;
								$g_sdl += $share_options_amount;
								$g_shg += $share_options_amount;

								$share_options_data = array(
									'payslip_id' 		=> 	$result,
									'employee_id' 		=> 	$employee_id,
									'salary_month' 		=> 	$this->input->post('pay_date'),
									'amount' 			=> 	round($share_options_amount, 2)
								);
								$this->Payroll_model->add_salary_payslip_share_options($share_options_data);
							}
						}


						// set other payments
						$other_payments_amount = 0;
						if (in_array('chk_total_other_payments',$check_id)) {
							// $check_id = array_values(array_diff($check_id, ['chk_total_other_payments']));
							$salary_other_payments = $this->Employees_model->read_salary_other_payments($employee_id);
							$count_other_payment = $this->Employees_model->count_employee_other_payments($employee_id);
							if ($count_other_payment > 0) {
								foreach ($salary_other_payments as $sl_other_payments) {
									if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
										if ($this->input->post('pay_date') == date('m-Y', strtotime($sl_other_payments->date))) {

											$esl_other_payments = $sl_other_payments->payments_amount;
											if ($system[0]->is_half_monthly == 1) {
												if ($system[0]->half_deduct_month == 2) {
													$epayments_amount = $esl_other_payments / 2;
												} else {
													$epayments_amount = $esl_other_payments;
												}
											} else {
												$epayments_amount = $esl_other_payments;
											}

											if ($sl_other_payments->cpf_applicable == 1) {
												$g_additional_wage += $epayments_amount;
												$g_shg += $epayments_amount;
												$g_sdl += $epayments_amount;
											}
											$other_payments_amount += $epayments_amount;
											$other_payments_data = array(
												'payslip_id' 		=> 	$result,
												'employee_id' 		=> 	$employee_id,
												'salary_month' 		=> 	$this->input->post('pay_date'),
												'payments_title' 	=> 	$sl_other_payments->payments_title,
												'payments_amount' 	=> 	$epayments_amount,
												'created_at' 		=> 	date('d-m-Y h:i:s')
											);
											$this->Payroll_model->add_salary_payslip_other_payments($other_payments_data);
										}
									} else {
										$first_date = new DateTime($sl_other_payments->date);
										if ($first_date->format('m-Y') == $this->input->post('pay_date')) {
											$first_date =  new DateTime($sl_other_payments->date);
										} else {
											$first_date = new DateTime('01-' . $this->input->post('pay_date'));
										}
						
										$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));
						
										if (!empty($sl_other_payments->end_date)) {
											$last_date = new DateTime($sl_other_payments->end_date);
											if ($last_date->format('m-Y') == $this->input->post('pay_date')) {
												$last_date = new DateTime($sl_other_payments->end_date);
											} else if ($last_date->format('m-Y') >= $this->input->post('pay_date')) {
												$last_date = $month_end_date_for_other;
											} else {
												$last_date = '';
											}
										} else {
											$last_date = $month_end_date_for_other;
										}
						
										if (!empty($last_date)) {
											$last_date->modify('+1 day');
											$final_last_day = new DateTime($last_date->format('d-m-Y'));
											if ($final_last_day->format('m-Y') >= $this->input->post('pay_date')) {
												if ($system[0]->is_half_monthly == 1) {
													if ($system[0]->half_deduct_month == 2) {
														$epayments_amount = $sl_other_payments->payments_amount / 2;
													} else {
														$epayments_amount = $sl_other_payments->payments_amount;
													}
												} else {
													$epayments_amount = $sl_other_payments->payments_amount;
												}
						
						
												// it for no of working day
												$no_of_days_worked_for_other_payment = 0;
												$same_month_holidays_count_for_other_payment = 0;
												$interval = new DateInterval('P1D');
												$period = new DatePeriod($first_date, $interval, $last_date);
												foreach ($period as $p) {
													$p_day = $p->format('l');
													$p_date = $p->format('Y-m-d');
						
													//holidays in a month
						
													$is_holiday = $this->Timesheet_model->is_holiday_on_date($user[0]->company_id, $p_date);
													if ($is_holiday) {
														$same_month_holidays_count_for_other_payment += 1;
													}
						
													//working days excluding holidays based on office shift
													if ($p_day == 'Monday') {
														if ($office_shift[0]->monday_in_time != '') {
															$no_of_days_worked_for_other_payment += 1;
														}
													} else if ($p_day == 'Tuesday') {
														if ($office_shift[0]->tuesday_in_time != '') {
															$no_of_days_worked_for_other_payment += 1;
														}
													} else if ($p_day == 'Wednesday') {
														if ($office_shift[0]->wednesday_in_time != '') {
															$no_of_days_worked_for_other_payment += 1;
														}
													} else if ($p_day == 'Thursday') {
														if ($office_shift[0]->thursday_in_time != '') {
															$no_of_days_worked_for_other_payment += 1;
														}
													} else if ($p_day == 'Friday') {
														if ($office_shift[0]->friday_in_time != '') {
															$no_of_days_worked_for_other_payment += 1;
														}
													} else if ($p_day == 'Saturday') {
														if ($office_shift[0]->saturday_in_time != '') {
															$no_of_days_worked_for_other_payment += 1;
														}
													} else if ($p_day == 'Sunday') {
														if ($office_shift[0]->sunday_in_time != '') {
															$no_of_days_worked_for_other_payment += 1;
														}
													}
												}
						
												$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
												$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);
						
						
												if ($sl_other_payments->cpf_applicable == 1) {
													$g_additional_wage += $epayments_amount;
													$g_shg += $epayments_amount;
													$g_sdl += $epayments_amount;
												}
												$other_payments_amount += $epayments_amount;
												$other_payments_data = array(
													'payslip_id' 		=> 	$result,
													'employee_id' 		=> 	$employee_id,
													'salary_month' 		=> 	$this->input->post('pay_date'),
													'payments_title' 	=> 	$sl_other_payments->payments_title,
													'payments_amount' 	=> 	$epayments_amount,
													'created_at' 		=> 	date('d-m-Y h:i:s')
												);
												$this->Payroll_model->add_salary_payslip_other_payments($other_payments_data);
											}
										}
									}
								}
							}
						}


						$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
			
						if ($unpaid_leaves) {
							$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
						}
						// unpaid leave
						// if ($this->input->post('chk_leave_deductions')) {
							if ($unpaid_leaves) {
								foreach ($leave_period as $l) {
									$is_half = $l['is_half'];
									$leave_dates = $l['leave_date'];
									$leave_day_pay = round(($basic_salary + $allowance_amount + $other_payments_amount) / $no_of_working_days, 2);
									if ($is_half) {
										$leave_day_pay = $leave_day_pay / 2;
									}
									foreach ($leave_dates as $ld) {
										$unpaid_leave_data = array(
											'payslip_id' 		=> 	$result,
											'employee_id' 		=> 	$employee_id,
											'salary_month' 		=> 	$this->input->post('pay_date'),
											'leave_date' 		=> 	$ld,
											'leave_amount' 		=> 	$leave_day_pay,
											'is_half' 			=> 	$is_half,
											'total_leave_amount' => $unpaid_leave_amount
										);
										$this->Payroll_model->add_salary_payslip_leave_deduction($unpaid_leave_data);
									}
								}
							}
						// }


						// employee claims
						if (in_array('chk_employee_claim',$check_id)){
							// $check_id = array_values(array_diff($check_id, ['chk_employee_claim']));
							unset($check_id[array_search('chk_employee_claim', $check_id)]);
							$get_employee_claims = $this->Employees_model->getEmployeeClaim($employee_id);
							foreach ($get_employee_claims->result() as $claims) {
								$date 	= 	date('m-Y', strtotime($claims->date));
								if ($date == $this->input->post('pay_date')) {
									$claim_data = array(
										'payslip_id' 		=> 	$result,
										'employee_id' 		=> 	$employee_id,
										'salary_month' 		=> 	$this->input->post('pay_date'),
										'claim_type' 		=> 	$claims->name,
										'amount' 			=> 	$claims->amount,
										'year'				=>	$claims->claim_year,
										'date'				=>	$claims->date,
										'employee_claim_id'	=>	$claims->claim_id
									);
									$this->Payroll_model->add_salary_payslip_claim($claim_data);
								}
							}
						}
					

						// set statutory_deductions
						if (in_array('chk_total_statutory_deductions',$check_id) || in_array('chk_gross_salary',$check_id) || in_array('chk_total_allowances',$check_id) || in_array('chk_total_other_payments',$check_id)) {
							// $check_id = array_values(array_diff($check_id, ['chk_total_statutory_deductions']));
							unset($check_id[array_search('chk_total_statutory_deductions', $check_id)]);
							$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($employee_id);
							$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($employee_id);
							$statutory_deductions_amount = 0;
							if ($count_statutory_deductions > 0) {
								foreach ($statutory_deductions->result() as $sl_statutory_deductions) {
									if ($system[0]->statutory_fixed != 'yes') :
										$sta_salary = $gross_pay;
										$st_amount = $sta_salary / 100 * $sl_statutory_deductions->deduction_amount;
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$single_sd = $st_amount / 2;
											} else {
												$single_sd = $st_amount;
											}
										} else {
											$single_sd = $st_amount;
										}
										$statutory_deductions_amount += $single_sd;
									else :
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$single_sd = $sl_statutory_deductions->deduction_amount / 2;
											} else {
												$single_sd = $sl_statutory_deductions->deduction_amount;
											}
										} else {
											$single_sd = $sl_statutory_deductions->deduction_amount;
										}
										$statutory_deductions_amount += $single_sd;
									endif;


									$statutory_deduction_data = array(
										'payslip_id' 		=> 	$result,
										'employee_id' 		=> 	$employee_id,
										'salary_month' 		=> 	$this->input->post('pay_date'),
										'deduction_title' 	=> 	$sl_statutory_deductions->deduction_title,
										'deduction_amount' 	=> 	$statutory_deductions_amount,
										'created_at' 		=> 	date('d-m-Y h:i:s')
									);
									$this->Payroll_model->add_salary_payslip_statutory_deductions($statutory_deduction_data);

								}
							}
						}else if (in_array('chk_total_other_payments',$check_id)){
							// $check_id = array_values(array_diff($check_id, ['chk_total_other_payments']));
							unset($check_id[array_search('chk_total_other_payments', $check_id)]);
						}else if(in_array('chk_total_allowances',$check_id)){
							// $check_id = array_values(array_diff($check_id, ['chk_total_allowances']));
							unset($check_id[array_search('chk_total_allowances', $check_id)]);
						}else if(in_array('chk_gross_salary',$check_id)){
							// $check_id = array_values(array_diff($check_id, ['chk_gross_salary']));
							unset($check_id[array_search('chk_gross_salary', $check_id)]);
						}
						
						
						// set loan
						if (in_array('chk_loan_de_amount',$check_id)) {
							// $check_id = array_values(array_diff($check_id, ['chk_loan_de_amount']));
							unset($check_id[array_search('chk_loan_de_amount', $check_id)]);
							$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($employee_id);
							$count_loan_deduction = $this->Employees_model->count_employee_deductions($employee_id);
							$loan_de_amount = 0;
							if ($count_loan_deduction > 0) {
								foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
									$esl_salary_loan_deduction = $sl_salary_loan_deduction->loan_deduction_amount;
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$eloan_deduction_amount = $esl_salary_loan_deduction / 2;
										} else {
											$eloan_deduction_amount = $esl_salary_loan_deduction;
										}
									} else {
										$eloan_deduction_amount = $esl_salary_loan_deduction;
									}
									$loan_data = array(
										'payslip_id' 		=> 	$result,
										'employee_id' 		=> 	$employee_id,
										'salary_month' 		=> 	$this->input->post('pay_date'),
										'loan_title' 		=> 	$sl_salary_loan_deduction->loan_deduction_title,
										'loan_amount' 		=> 	$eloan_deduction_amount,
										'created_at' 		=> 	date('d-m-Y h:i:s')
									);
									$_loan_data = $this->Payroll_model->add_salary_payslip_loan($loan_data);
								}
							}
						}


						// overtime
						if(in_array('chk_total_overtime',$check_id)){
							// $check_id = array_values(array_diff($check_id, ['chk_total_overtime']));
							unset($check_id[array_search('chk_total_overtime', $check_id)]);
							$overtime_amount = 0;
							$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($employee_id, $this->input->post('pay_date'));
							if ($overtime) {
								$ot_days = 0;
								$ot_hrs = 0;
								$ot_mins = 0;
								foreach ($overtime as $ot) {
									$total_hours = explode(':', $ot->total_hours);
									$ot_hrs += $total_hours[0];
									$ot_mins += $total_hours[1];
									$ot_days += 1;
								}
								if ($ot_mins > 0) {
									$ot_hrs += round($ot_mins / 60, 2);
								}

								//overtime rate
								$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($employee_id);

								if ($overtime_rate) {
									$rate = $overtime_rate->overtime_pay_rate;
								} else {
									$week_hours = 44;

									$rate = round((12 * $basic_salary) / (52 * $week_hours), 2);
									$rate = $rate * 1.5;
								}

								if ($ot_hrs > 0) {
									$overtime_amount = round($ot_hrs * $rate, 2);
								}
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$overtime_amount = $overtime_amount / 2;
									}
								}

								$overtime_data = array(
									'payslip_id' 			=> 	$result,
									'employee_id' 			=> 	$employee_id,
									'overtime_salary_month' => 	$this->input->post('pay_date'),
									'overtime_no_of_days' 	=> 	$ot_days,
									'overtime_hours' 		=> 	$ot_hrs,
									'overtime_rate' 		=> 	$rate,
									'total_overtime' 		=> 	$overtime_amount,
									'created_at' 			=> 	date('d-m-Y h:i:s')
								);
								$_overtime_data = $this->Payroll_model->add_salary_payslip_overtime($overtime_data);
							}
						}

						//cpf
						$total_cpf =  $this->input->post('total_cpf');
						// if ($total_cpf && $total_cpf > 0) {
						// 	$ow_paid = $this->input->post('ow_paid');
						// 	$cpf_data = [
						// 		'payslip_id' 	=> 	$result,
						// 		'month_year' 	=> 	'01-' . $this->input->post('pay_date'),
						// 		'ow_paid'		=> 	$ow_paid,
						// 		'ow_cpf'		=> 	$this->input->post('ow_cpf'),
						// 		'ow_cpf_employer'	=> $this->input->post('ow_cpf_employer'),
						// 		'ow_cpf_employee'	=> $this->input->post('ow_cpf_employee'),
						// 		'aw_paid'		=> 	$this->input->post('aw_paid'),
						// 		'aw_cpf'		=> 	$this->input->post('aw_cpf'),
						// 		'aw_cpf_employer'	=> $this->input->post('aw_cpf_employer'),
						// 		'aw_cpf_employee'	=> $this->input->post('aw_cpf_employee')
						// 	];

						// 	$cpf_payslip = $this->Cpf_payslip_model->add_cpf_payslip($cpf_data);
						// }

						//Other Fund Contributions
						$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($employee_id);
						if ($employee_contributions) {
							$fund_deduction_amount = 0;
							$gross_s = $g_shg;
							$contribution_id = $employee_contributions->contribution_id;
							$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);

							$fund_deduction_amount += $contribution_amount;
							$cdata = array(
								'payslip_id' 			=> 	$result,
								'contribution_id' 		=> 	$contribution_id,
								'contribution_amount' 	=> $contribution_amount
							);
							$this->Contribution_fund_model->setContributionPayslip($cdata);
						}


						//ASHG Fund Contributions
						$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($employee_id);
						if ($employee_ashg_contributions) {
							$fund_deduction_amount = 0;
							$gross_s = $g_shg;
							$contribution_id = $employee_ashg_contributions->contribution_id;
							$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);

							$fund_deduction_amount += $contribution_amount;
							$cdata = array(
								'payslip_id' 			=> 	$result,
								'contribution_id' 		=> 	$contribution_id,
								'contribution_amount' 	=> 	$contribution_amount
							);
							$this->Contribution_fund_model->setContributionPayslip($cdata);
						}

						//sdl
						$sdl = 0;
						if ($g_sdl > 1 && $g_sdl <= 800) {
							$sdl = 2;
						} elseif ($g_sdl > 800 && $g_sdl <= 4500) {
							$sdl_amount = (0.25 * $g_sdl) / 100;
							$sdl = $sdl_amount;
						} elseif ($g_sdl > 4500) {
							$sdl = 11.25;
						}

						$cdata = array(
							'payslip_id'		 	=> 	$result,
							'contribution_id' 		=> 	5,
							'contribution_amount' 	=> 	$sdl
						);
						$this->Contribution_fund_model->setContributionPayslip($cdata);

						$Return['result'] = $this->lang->line('xin_success_payment_paid');
					} else {
						$Return['error'] = $this->lang->line('xin_error_msg');
					}
					$man_arr = [];
					$bank = '';
					if($this->input->post('payment_mode') == 'DBS'){
						$company_info = $this->Company_model->read_company_information($user[0]->company_id);
						$account_number = '';
						if(!empty($company_info[0]->bank_details)){
							foreach(json_decode($company_info[0]->bank_details) as $info){
								if($info->bank_name == 'DBS'){
									$account_number = $info->bank_account_number;
								}
							}
						}
						$Organization_Assigned_Id = $company_info[0]->organization_id;
						$currencyCode = explode(' - ', $company_info[0]->default_currency);
						$payment_date = date('d-m-Y',strtotime($this->input->post('pay_date')));
						$bank_ac = $this->Employees_model->get_bank_account_info_add($employee_id);
						$total_amount += $this->input->post('net_salary')[$key];
						$file_data[] = array(
							PHP_EOL . 'PAYMENT',
							'SAL',
							$account_number,
							$currencyCode[0],
							'',
							'SGD',
							'',
							$payment_date,
							'',
							'',
							$user[0]->first_name.' '.$user[0]->last_name,
							'',
							'',
							'',
							'',
							$bank_ac->account_number ?? '',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							$this->input->post('net_salary')[$key],
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'A0',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
							'',
						);
						$c_name = $company_info[0]->name;
						$formattedDate = str_replace('-', '', $this->input->post('pay_date'));
						$man_arr[] = ['HEADER', $formattedDate, $Organization_Assigned_Id, $c_name];
						$man_arr[] = $file_data;
						$man_arr[] = [PHP_EOL . 'TRAILER', count($file_data), $total_amount];
						$bank = "DBS";

					}
				}
				
			}
			echo json_encode([
				'status'	=> 'success',
				'data'		=>	$man_arr,
				'date'		=>	date('d-m-Y'),
				'bank'		=>	$bank
			]);
			exit;
		}else{
			$Return['error'] = 'Please Select Employee';
			$this->output($Return);
		}
	}


	// for advance payment list
	public function payslip_list_bulk_advance()
	{


		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/payroll/generate_payslip", $data);
		} else {
			redirect('admin/');
		}

		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		// payment month
		$pay_date = $this->input->get("month_year");

		$role_resources_ids = $this->Xin_model->user_role_resource();
		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		if ($user_info[0]->user_role_id == 1 || in_array('314', $role_resources_ids)) {
			if ($this->input->get("employee_id") == 0 && $this->input->get("company_id") == 0) {
				$payslip = $this->Employees_model->get_employees_payslip($pay_date);
			} else if ($this->input->get("employee_id") == 0 && $this->input->get("company_id") != 0) {
				$payslip = $this->Payroll_model->get_comp_template($this->input->get("company_id"), 0, $pay_date);
			} else if ($this->input->get("employee_id") != 0 && $this->input->get("company_id") != 0) {
				$payslip = $this->Payroll_model->get_employee_comp_template($this->input->get("company_id"), $this->input->get("employee_id"), $pay_date);
			} else {
				$payslip = $this->Employees_model->get_employees_payslip($pay_date);
			}
		} else {
			$payslip = $this->Payroll_model->get_employee_comp_template($user_info[0]->company_id, $session['user_id']);
		}

		$system = $this->Xin_model->read_setting_info(1);
		$data = array();
		foreach ($payslip->result() as $r) {
			$exit_employee = $this->Employees_model->get_employee_exit($r->user_id);

			if (count($exit_employee) > 0) {
				$e_date = date('Y-m-d', strtotime($exit_employee[0]->exit_date));
				$exit_date = date('m-Y', strtotime($e_date));
				if ($exit_date >= $pay_date){
					$emp_name = $r->first_name . ' ' . $r->last_name;
					// office shift
					$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);
	
					//overtime request
					$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $this->input->get('month_year'));
					$re_hrs_old_int1 = 0;
					$re_hrs_old_seconds = 0;
					$re_pcount = 0;
					foreach ($overtime_count as $overtime_hr) {
						$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
						$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
						$re_interval_late = $request_clock_in->diff($request_clock_out);
						$re_hours_r  = $re_interval_late->format('%h');
						$re_minutes_r = $re_interval_late->format('%i');
						$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';
	
						$re_str_time = $re_total_time;
	
						$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);
	
						sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$re_hrs_old_int1 += $re_hrs_old_seconds;
	
						$re_pcount = gmdate("H", $re_hrs_old_int1);
					}
	
					$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
					$hrs_old_int1 = 0;
					$pcount = 0;
					foreach ($result->result() as $hour_work) {
						$clock_in =  new DateTime($hour_work->clock_in);
						$clock_out =  new DateTime($hour_work->clock_out);
						$interval_late = $clock_in->diff($clock_out);
						$hours_r  = $interval_late->format('%h');
						$minutes_r = $interval_late->format('%i');
						$total_time = $hours_r . ":" . $minutes_r . ":" . '00';
	
						$str_time = $total_time;
	
						$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
	
						sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
	
						$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;
	
						$hrs_old_int1 += $hrs_old_seconds;
	
						$pcount = gmdate("H", $hrs_old_int1);
					}
					$pcount = $pcount + $re_pcount;
	
					// get company
					$company = $this->Xin_model->read_company_info($r->company_id);
					if (!is_null($company)) {
						$comp_name = $company[0]->name;
					} else {
						$comp_name = '--';
					}
	
					/**
					 * Local Variable
					 */
					$g_ordinary_wage = 0;
					$g_additional_wage = 0;
					$g_shg = 0;
					$g_sdl = 0;
	
					// 1: salary type
					if ($r->wages_type == 1) {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					} else if ($r->wages_type == 2) {
						$wages_type = $this->lang->line('xin_employee_daily_wages');
						if ($pcount > 0) {
							$basic_salary = $pcount * $r->basic_salary;
						} else {
							$basic_salary = $pcount;
						}
						$p_class = 'emo_hourly_pay';
						$view_p_class = 'hourlywages_template_modal';
					} else {
						$wages_type = $this->lang->line('xin_payroll_basic_salary');
						if ($system[0]->is_half_monthly == 1) {
							$basic_salary = $r->basic_salary / 2;
						} else {
							$basic_salary = $r->basic_salary;
						}
						$p_class = 'emo_monthly_pay';
						$view_p_class = 'payroll_template_modal';
					}
	
					
	
					// employee deduction
					$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
					$pa_month = date('m-Y', strtotime('01-' . $pay_date));
					$deduction_amount = 0;
					if ($employee_deduction) {
						foreach ($employee_deduction as $deduction) {
							if ($deduction->type_id == 1) {
								$deduction_amount +=  $deduction->amount;
							}
							if ($deduction->type_id == 2) {
								$from_month_year = date('m-Y', strtotime($deduction->from_date));
								$to_month_year = date('d-m-Y', strtotime($deduction->to_date));
	
	
								if ($from_month_year != "" && $to_month_year != "") {
									if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
										$deduction_amount +=  $deduction->amount;
									}
								}
							}
						}
					}
	
	
	
					//3: Gross rate of pay (unpaid leave deduction)
					$holidays_count = 0;
					$no_of_working_days = 0;
					$month_start_date = new DateTime('01-' . $pay_date);
					$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
					$month_end_date->modify('+1 day');
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($month_start_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$period_day = $p->format('l');
						$period_date = $p->format('Y-m-d');
	
						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
						if ($is_holiday) {
							$holidays_count += 1;
						}
	
						//working days excluding holidays based on office shift
						if ($period_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_working_days += 1;
							}
						} else if ($period_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_working_days += 1;
							}
						}
					}
	
					//unpaid leave
					$unpaid_leave_amount = 0;
					$leaves_taken_count = 0;
					$leave_period = array();
					$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
					if ($unpaid_leaves) {
						foreach ($unpaid_leaves as $k => $l) {
							$pay_date_month = new DateTime('01-' . $pay_date);
							$l_from_date = new DateTime($l->from_date);
							$l_to_date = new DateTime($l->to_date);
	
							if ($l_from_date->format('m') == $l_to_date->format('m')) {
								$start_date = $l_from_date;
								$end_date = $l_to_date;
							} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $l_from_date;
								$end_date = new DateTime($start_date->format('Y-m-t'));
							} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
								$start_date = $pay_date_month;
								$end_date = $l_to_date;
							}
							$end_date->modify('+1 day');
							$interval = new DateInterval('P1D');
							$period = new DatePeriod($start_date, $interval, $end_date);
							foreach ($period as $d) {
								$p_day = $d->format('l');
								if ($p_day == 'Monday') {
									if ($office_shift[0]->monday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Tuesday') {
									if ($office_shift[0]->tuesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Wednesday') {
									if ($office_shift[0]->wednesday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Thursday') {
									if ($office_shift[0]->thursday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Friday') {
									if ($office_shift[0]->friday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Saturday') {
									if ($office_shift[0]->saturday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								} else if ($p_day == 'Sunday') {
									if ($office_shift[0]->sunday_in_time != '') {
										$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
										if ($l->is_half_day == 0) {
											$leaves_taken_count += 1;
										} else {
											$leaves_taken_count += 0.5;
										}
									}
								}
							}
							$leave_period[$k]['is_half'] = $l->is_half_day;
						}
					}
	
					// if joining date and pay date same then this logic work
					$month_date_join = date('m-Y', strtotime($r->date_of_joining));
					$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
					$month_last_date = date('m-Y', strtotime($lastday));
	
					$first_date =  new DateTime($r->date_of_joining);
					$no_of_days_worked = 0;
					$same_month_holidays_count = 0;
					if ($month_date_join == $pay_date) {
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($first_date, $interval, $month_end_date);
						foreach ($period as $p) {
							$p_day = $p->format('l');
							$m_p_date = $p->format('Y-m-d');
	
							//holidays in a month
							$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
							if ($is_holiday) {
								$same_month_holidays_count += 1;
							}
	
							//working days excluding holidays based on office shift
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$no_of_days_worked += 1;
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$no_of_days_worked += 1;
								}
							}
						}
					// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
					$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
					} else {
						// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
						$no_of_days_worked = $no_of_working_days -  $holidays_count;
					}
	
	
					if ($month_date_join == $pay_date){
						$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
					}else{
						$show_main_salary = $basic_salary;
					}
				
					$g_ordinary_wage += $show_main_salary;
					$g_shg += $show_main_salary;
					$g_sdl += $show_main_salary;
				
					$g_ordinary_wage -= $deduction_amount;
					$g_shg -= $deduction_amount;
					$g_sdl -= $deduction_amount;
	
	
					// 3: all loan/deductions
					$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
					$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
					$loan_de_amount = 0;
					if ($count_loan_deduction > 0) {
						foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
								} else {
									$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
								}
							} else {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
							}
							$loan_de_amount += $er_loan;
						}
					} else {
						$loan_de_amount = 0;
					}
					$loan_de_amount = number_format(floatval($loan_de_amount), 2);
	
					
					// 2: all allowances
					$allowance_amount = 0;
					$gross_allowance_amount = 0;
					$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
					if ($salary_allowances) {
						foreach ($salary_allowances as $sa) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$eallowance_amount = $sa->allowance_amount / 2;
								} else {
									$eallowance_amount = $sa->allowance_amount;
								}
							} else {
								$eallowance_amount = $sa->allowance_amount;
							}
	
	
							if (!empty($sa->salary_month)) {
								$g_additional_wage += $eallowance_amount;
							} else {
								if ($month_date_join == $pay_date){
									$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
								}
								$g_ordinary_wage += $eallowance_amount;
								if ($sa->id == 2) {
									$gross_allowance_amount = $eallowance_amount;
								}
							}
	
							if ($sa->sdl == 1) {
								$g_sdl += $eallowance_amount;
							}
							if ($sa->shg == 1) {
								$g_shg += $eallowance_amount;
							}
	
							$allowance_amount += $eallowance_amount;
						}
					}
					$gross_allowance_amount = $allowance_amount;
	
	
	
					// commissions
					$commissions_amount = 0;
					$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
					if ($commissions) {
						foreach ($commissions as $c) {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$ecommissions_amount = $c->commission_amount / 2;
								} else {
									$ecommissions_amount = $c->commission_amount;
								}
							} else {
								$ecommissions_amount = $c->commission_amount;
							}
	
							if ($c->commission_type == 9) {
								$g_ordinary_wage += $ecommissions_amount;
							} elseif ($c->commission_type == 10) {
								$g_additional_wage += $ecommissions_amount;
							}
	
							if ($c->sdl == 1) {
								$g_sdl += $ecommissions_amount;
							}
							if ($c->shg == 1) {
								$g_shg += $ecommissions_amount;
							}
	
							$commissions_amount += $ecommissions_amount;
						}
					}
	
					//share options
					$share_options_amount = 0;
					$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
					if ($share_options) {
						$eebr_amount = 0;
						$eris_amount = 0;
						foreach ($share_options as $s) {
							$scheme = $s->so_scheme;
							if ($scheme == 1) {
								$price_doe = $s->price_date_of_excercise;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
								$amount = ($price_doe - $price_ex) * $no_shares;
								$eebr_amount += $amount;
							} else {
								$price_doe = $s->price_date_of_excercise;
								$price_dog = $s->price_date_of_grant;
								$price_ex = $s->excercise_price;
								$no_shares = $s->no_of_shares;
	
								$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
								$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
								$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
							}
						}
						$share_options_amount = round($eebr_amount + $eris_amount, 2);
						$g_additional_wage += $share_options_amount;
						$g_sdl += $share_options_amount;
						$g_shg += $share_options_amount;
					}
	
					
					// otherpayments
					$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
					$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
					$other_payments_amount = 0;
					if ($count_other_payments > 0) {
						foreach ($other_payments->result() as $sl_other_payments) {
							if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
								if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$epayments_amount = $sl_other_payments->payments_amount / 2;
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}
	
									if ($sl_other_payments->cpf_applicable == 1) {
										$g_additional_wage += $epayments_amount;
										$g_shg += $epayments_amount;
										$g_sdl += $epayments_amount;
									}
	
									$other_payments_amount += $epayments_amount;
								}
							} else {
								$first_date = new DateTime($sl_other_payments->date);
								if ($first_date->format('m-Y') == $pay_date) {
									$first_date =  new DateTime($sl_other_payments->date);
								} else {
									$first_date = new DateTime('01-' . $pay_date);
								}
	
								$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));
	
								if (!empty($sl_other_payments->end_date)) {
									$last_date = new DateTime($sl_other_payments->end_date);
									if ($last_date->format('m-Y') == $pay_date) {
										$last_date = new DateTime($sl_other_payments->end_date);
									} else if($last_date->format('m-Y') >= $pay_date){
										$last_date = $month_end_date_for_other;
									} else {
										$last_date = '';
									}
								} else {
									$last_date = $month_end_date_for_other;
								}
								if(!empty($last_date)){
									$last_date->modify('+1 day');
									$final_last_day = new DateTime($last_date->format('d-m-Y'));
									if ($final_last_day->format('m-Y') >= $pay_date) {
										if ($system[0]->is_half_monthly == 1) {
											if ($system[0]->half_deduct_month == 2) {
												$epayments_amount = $sl_other_payments->payments_amount / 2;
											} else {
												$epayments_amount = $sl_other_payments->payments_amount;
											}
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
	
	
										// it for no of working day
										$no_of_days_worked_for_other_payment = 0;
										$same_month_holidays_count_for_other_payment = 0;
										$interval = new DateInterval('P1D');
										$period = new DatePeriod($first_date, $interval, $last_date);
										foreach ($period as $p) {
											$p_day = $p->format('l');
											$p_date = $p->format('Y-m-d');
	
											//holidays in a month
	
											$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
											if ($is_holiday) {
												$same_month_holidays_count_for_other_payment += 1;
											}
	
											//working days excluding holidays based on office shift
											if ($p_day == 'Monday') {
												if ($office_shift[0]->monday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Tuesday') {
												if ($office_shift[0]->tuesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Wednesday') {
												if ($office_shift[0]->wednesday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Thursday') {
												if ($office_shift[0]->thursday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Friday') {
												if ($office_shift[0]->friday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Saturday') {
												if ($office_shift[0]->saturday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											} else if ($p_day == 'Sunday') {
												if ($office_shift[0]->sunday_in_time != '') {
													$no_of_days_worked_for_other_payment += 1;
												}
											}
										}
	
										$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
										$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);
	
	
										if ($sl_other_payments->cpf_applicable == 1) {
											$g_additional_wage += $epayments_amount;
											$g_shg += $epayments_amount;
											$g_sdl += $epayments_amount;
										}
										$other_payments_amount += $epayments_amount;
									}
								}
							}
						}
					} else {
						$other_payments_amount = 0;
					}
	
					$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
		
					if ($unpaid_leaves) {
						$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
					}
					
					// $g_ordinary_wage = $gross_pay;
					$g_shg = $gross_pay;
					$g_sdl = $gross_pay;
	
					$g_ordinary_wage -= $unpaid_leave_amount;
	
					
	
					// other benefit
					$other_benefit_mount = 0;
					$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
					foreach ($other_benefit_list->result() as $benefit_list) {
						$other_benefit_mount += $benefit_list->other_benefit_cost;
					}
	
	
					// statutory_deductions
					$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
					$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
					$statutory_deductions_amount = 0;
					if ($count_statutory_deductions > 0) {
						foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
							if ($system[0]->statutory_fixed != 'yes') {
								$sta_salary = $gross_pay;
								$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $st_amount / 2;
									} else {
										$single_sd = $st_amount;
									}
								} else {
									$single_sd = $st_amount;
								}
								$statutory_deductions_amount += $single_sd;
							} else {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
									} else {
										$single_sd = $sl_salary_statutory_deductions->deduction_amount;
									}
								} else {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount;
								}
								$statutory_deductions_amount += $single_sd;
							}
						}
					} else {
						$statutory_deductions_amount = 0;
					}
	
					// overtime
					$overtime_amount = 0;
					$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
					if ($overtime) {
						$ot_hrs = 0;
						$ot_mins = 0;
						foreach ($overtime as $ot) {
							$total_hours = explode(':', $ot->total_hours);
							$ot_hrs += $total_hours[0];
							$ot_mins += $total_hours[1];
						}
						if ($ot_mins > 0) {
							$ot_hrs += round($ot_mins / 60, 2);
						}
	
						//overtime rate
						$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
						if ($overtime_rate) {
							$rate = $overtime_rate->overtime_pay_rate;
						} else {
							$week_hours = 44;
							$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
							$rate = $rate * 1.5;
						}
	
						if ($ot_hrs > 0) {
							$overtime_amount = round($ot_hrs * $rate, 2);
						}
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$overtime_amount = $overtime_amount / 2;
							}
						}
						$g_ordinary_wage += $overtime_amount;
						$g_sdl += $overtime_amount;
					}
	
					
					$payment_check = $this->Payroll_model->check_make_payment_payslip_for_first_payment($r->user_id, $pay_date);
					$payment_check_final = $this->Payroll_model->check_make_payment_payslip_for_final_payment($r->user_id, $pay_date);
					$balance = 0;
					$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
					if($check && $check[0]->balance_amount > 0){
						$balance = $check[0]->balance_amount;
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
					
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
						$status = '<span class="label label-warning">' . "Partially Paid" . '</span>';
	
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
						$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
						
						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
						// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
						$delete  = $delete;
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						
					}else if($check && $check[0]->balance_amount == 0){
						$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
					
						$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;
	
						$status = '<span class="label label-success">Paid</span>';
	
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
						
						if (in_array('313', $role_resources_ids)) {
							$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
						} else {
							$delete = '';
						}
						// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
						$delete  = $delete;
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						
					}else {
							$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
							$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
							$delete = '';
							$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
							$balance = $this->Xin_model->currency_sign(0, $r->user_id);
						}
	
					
					// employee accommodations
					$employee_accommodations = 0;
					$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
					foreach ($get_employee_accommodations as $get_employee_accommodation) {
						$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
						$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
						if ($period_from == $pay_date || $period_to == $pay_date) {
							if (!empty($get_employee_accommodation->rent_paid)) {
								$employee_accommodations += $get_employee_accommodation->rent_paid;
							}
						}
					}
	
					// employee claims
					$claim_amount = 0;
					$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
					foreach ($get_employee_claims->result() as $claims) {
						$date 	= 	date('m-Y', strtotime($claims->date));
						if ($date == $pay_date) {
							$claim_amount += $claims->amount;
						}
					}
	
	
	
					$total_earning = $show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
					$total_deduction = floatval($loan_de_amount) + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
					$total_net_salary = ($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;
	
	
					// cpf calculation
					$emp_dob = $r->date_of_birth;
					$dob = new DateTime($emp_dob);
	
					$today = new DateTime('01-' . $pay_date);
					$age = $dob->diff($today);
					$age_year = $age->y;
					$age_month = $age->m;
	
					$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
					$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
					$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;
	
					if ($age_month > 0) {
						$age_year = $age_year + 1;
					}
	
					$cpf_employee 	= 	0;
					$cpf_employer	=	0;
	
					$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
					if ($im_status) {
						$immigration_id = $im_status->immigration_id;
						if ($immigration_id == 2) {
							$issue_date = $im_status->issue_date;
							$i_date = new DateTime($issue_date);
							$today = new DateTime();
							$pr_age = $i_date->diff($today);
							$pr_age_year = $pr_age->y;
							$pr_age_month = $pr_age->m;
						}
	
						if ($immigration_id == 1 || $immigration_id == 2) {
	
							$ordinary_wage = $g_ordinary_wage;
							if ($ordinary_wage > $ordinary_wage_cap) {
								$ow = $ordinary_wage_cap;
							} else {
								$ow = $ordinary_wage;
							}
	
							//additional wage
							$additional_wage = $g_additional_wage;
							$aw = $g_additional_wage;
							$tw = $ow + $additional_wage;
							if ($im_status->issue_date != "") {
								if ($pr_age_year == 1) {
	
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(4 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 666) {
												$total_cpf = 666;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(4 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 666) {
												$total_cpf = 666;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(3.5 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw < 500) {
											$cpf_employer = round(3.5 / 100 * $tw);
											$cpf_employee = 0;
										} else if ($tw > 500 && $tw < 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
											$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;
	
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
								if ($pr_age_year == 2) {
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(9 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.45 * ($tw - 500));
											$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
											$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
											if ($count_total_cpf > 1776) {
												$total_cpf = 1776;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1110) {
												$cpf_employee = 1110;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(6 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.375 * ($tw - 500));
											$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
											$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
											if ($count_total_cpf > 1369) {
												$total_cpf = 1369;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 925) {
												$cpf_employee = 925;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(3.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.225 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
											$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
											if ($count_total_cpf > 814) {
												$total_cpf = 814;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 555) {
												$cpf_employee = 555;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(3.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
											$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
											if ($count_total_cpf > 629) {
												$total_cpf = 629;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
								if ($pr_age_year == 3 || $pr_age_year > 3) {
									if ($age_year <= 55) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(17 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.6 * ($tw - 500));
											$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
											$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
											if ($count_total_cpf > 2738) {
												$total_cpf = 2738;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1480) {
												$cpf_employee = 1480;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = $total_cpf - $cpf_employee;
										}
									} else if ($age_year > 55 && $age_year <= 60) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(15.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.51 * ($tw - 500));
											$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
											$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
											if ($count_total_cpf > 2405) {
												$total_cpf = 2405;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 1258) {
												$cpf_employee = 1258;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 60 && $age_year <= 65) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(12 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.345 * ($tw - 500));
											$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
											$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
	
											if ($count_total_cpf > 1739) {
												$total_cpf = 1739;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 851) {
												$cpf_employee = 851;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 65 && $age_year <= 70) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(9 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.225 * ($tw - 500));
											$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
											$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
	
											if ($count_total_cpf > 1221) {
												$total_cpf = 1221;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 555) {
												$cpf_employee = 555;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									} else if ($age_year > 70) {
										if ($tw < 50) {
											$cpf_employee = 0;
											$cpf_employer = 0;
										} else if ($tw > 50 && $tw <= 500) {
											$cpf_employee = 0;
											$cpf_employer = round(7.5 / 100 * $tw);
										} else if ($tw > 500 && $tw <= 750) {
											$cpf_employee = floor(0.15 * ($tw - 500));
											$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
											$cpf_employer = round($total_cpf - $cpf_employee);
										} else if ($tw > 750) {
											$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
											$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
	
											if ($count_total_cpf > 925) {
												$total_cpf = 925;
											} else {
												$total_cpf = $count_total_cpf;
											}
											if ($count_cpf_employee > 370) {
												$cpf_employee = 370;
											} else {
												$cpf_employee = floor($count_cpf_employee);
											}
											$cpf_employer = round($total_cpf - $cpf_employee);
										}
									}
								}
							}
	
	
	
							if ($immigration_id == 1) {
	
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(17 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.6 * ($tw - 500));
										$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
										$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
										if ($count_total_cpf > 2738) {
											$total_cpf = 2738;
										} else {
											$total_cpf = $count_total_cpf;
										}
				
										
										if ($count_cpf_employee > 1480) {
											$cpf_employee = 1480;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(15.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.51 * ($tw - 500));
										$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
				
										$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
										if ($count_total_cpf > 2405) {
											$total_cpf = 2405;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1258) {
											$cpf_employee = 1258;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(12 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.345 * ($tw - 500));
										$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
										$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
				
										if ($count_total_cpf > 1739) {
											$total_cpf = 1739;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 851) {
											$cpf_employee = 851;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65 && $age_year <= 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
				
										if ($count_total_cpf > 1221) {
											$total_cpf = 1221;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(7.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
				
										if ($count_total_cpf > 925) {
											$total_cpf = 925;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
	
							$total_net_salary = $total_net_salary - $cpf_employee;
							$cpf_total = $cpf_employee + $cpf_employer;
	
							$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
							$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
							$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
						}
					}
	
	
					$shg_fund_deduction_amount = 0;
					//Other Fund Contributions
					$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);
	
					if ($employee_contributions && $g_shg > 0) {
						$gross_s = $g_shg;
						$contribution_id = $employee_contributions->contribution_id;
						$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
						$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
						$shg_fund_name = $contribution_type[0]->contribution;
						$shg_fund_deduction_amount += $contribution_amount;
						$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
					}
					$ashg_fund_deduction_amount = 0;
	
					$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
					if ($employee_ashg_contributions  && $g_shg > 0) {
						$gross_s = $g_shg;
						$contribution_id = $employee_ashg_contributions->contribution_id;
						$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
						$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
						$ashg_fund_name = $contribution_type[0]->contribution;
	
						$ashg_fund_deduction_amount += $contribution_amount;
						$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
					}
	
					// echo $total_net_salary;
	
					// $sdl_total_amount = 0;
					// if ($g_sdl > 1 && $g_sdl <= 800) {
					// 	$sdl_total_amount = 2;
					// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
					// 	$sdl_amount = (0.25 * $g_sdl) / 100;
					// 	$sdl_total_amount = $sdl_amount;
					// } elseif ($g_sdl > 4500) {
					// 	$sdl_total_amount = 11.25;
					// }
	
	
					$net_salary = number_format((float)$total_net_salary, 2, '.', '');
					$basic_salary = number_format((float)$basic_salary, 2, '.', '');
					// if ($basic_salary == 0 || $basic_salary == '') {
					// 	$fmpay = '';
					// } else {
						$fmpay = $mpay;
					// }
	
					$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';
	
					//action link
					$act = $detail . $fmpay . $delete;
					// $act = $fmpay . $delete;
	
					if ($r->wages_type == 1) {
						if ($system[0]->is_half_monthly == 1) {
							$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
						} else {
							$emp_payroll_wage = $wages_type;
						}
					} else {
						$emp_payroll_wage = $wages_type;
					}
	
					$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
					$p = $payslips->result();
					
					$mode_of_payment = $r->payment_mode ?? '';
	
					$field =  '<div class="k-top"><span class="k-icon k-plus" role="presentation"></span><span class="k-checkbox" role="presentation"  style="display:inline-block;"><label><input type="checkbox"  class="role-checkbox" name="employee_id[]" value="'.$r->user_id.'"> </label></span><span class="k-in k-state-focused"></span></div>';
					$field_in =  '<input type="number"  name="advance_amount[]"  class="form-control advance_class"  style="width: 100px;">';
				
	
					// $data[] = array(
					// 	// $act,
					// 	$iemp_name,
					// 	$emp_payroll_wage,
					// 	// $basic_salary,
					// 	$this->Xin_model->currency_sign($show_main_salary, $r->user_id),
					// 	$this->Xin_model->currency_sign($cpf_employee, $r->user_id),
					// 	$net_salary,
					// 	$balance,
					// 	$mode_of_payment,
					// 	$status
					// );
	
					
					$data[] = array(
						$field,
						$iemp_name,
						$this->Xin_model->currency_sign($show_main_salary, $r->user_id),
						$field_in,
					);
				}
			} else {
				$emp_name = $r->first_name . ' ' . $r->last_name;
				// office shift
				$office_shift = $this->Timesheet_model->read_office_shift_information($r->office_shift_id);

				//overtime request
				$overtime_count = $this->Overtime_request_model->get_overtime_request_count($r->user_id, $this->input->get('month_year'));
				$re_hrs_old_int1 = 0;
				$re_hrs_old_seconds = 0;
				$re_pcount = 0;
				foreach ($overtime_count as $overtime_hr) {
					$request_clock_in =  new DateTime($overtime_hr->request_clock_in);
					$request_clock_out =  new DateTime($overtime_hr->request_clock_out);
					$re_interval_late = $request_clock_in->diff($request_clock_out);
					$re_hours_r  = $re_interval_late->format('%h');
					$re_minutes_r = $re_interval_late->format('%i');
					$re_total_time = $re_hours_r . ":" . $re_minutes_r . ":" . '00';

					$re_str_time = $re_total_time;

					$re_str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $re_str_time);

					sscanf($re_str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$re_hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$re_hrs_old_int1 += $re_hrs_old_seconds;

					$re_pcount = gmdate("H", $re_hrs_old_int1);
				}

				$result = $this->Payroll_model->total_hours_worked($r->user_id, $pay_date);
				$hrs_old_int1 = 0;
				$pcount = 0;
				foreach ($result->result() as $hour_work) {
					$clock_in =  new DateTime($hour_work->clock_in);
					$clock_out =  new DateTime($hour_work->clock_out);
					$interval_late = $clock_in->diff($clock_out);
					$hours_r  = $interval_late->format('%h');
					$minutes_r = $interval_late->format('%i');
					$total_time = $hours_r . ":" . $minutes_r . ":" . '00';

					$str_time = $total_time;

					$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

					sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

					$hrs_old_seconds = $hours * 3600 + $minutes * 60 + $seconds;

					$hrs_old_int1 += $hrs_old_seconds;

					$pcount = gmdate("H", $hrs_old_int1);
				}
				$pcount = $pcount + $re_pcount;

				// get company
				$company = $this->Xin_model->read_company_info($r->company_id);
				if (!is_null($company)) {
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';
				}

				/**
				 * Local Variable
				 */
				$g_ordinary_wage = 0;
				$g_additional_wage = 0;
				$g_shg = 0;
				$g_sdl = 0;

				// 1: salary type
				if ($r->wages_type == 1) {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				} else if ($r->wages_type == 2) {
					$wages_type = $this->lang->line('xin_employee_daily_wages');
					if ($pcount > 0) {
						$basic_salary = $pcount * $r->basic_salary;
					} else {
						$basic_salary = $pcount;
					}
					$p_class = 'emo_hourly_pay';
					$view_p_class = 'hourlywages_template_modal';
				} else {
					$wages_type = $this->lang->line('xin_payroll_basic_salary');
					if ($system[0]->is_half_monthly == 1) {
						$basic_salary = $r->basic_salary / 2;
					} else {
						$basic_salary = $r->basic_salary;
					}
					$p_class = 'emo_monthly_pay';
					$view_p_class = 'payroll_template_modal';
				}

				

				// employee deduction
				$employee_deduction = $this->Payroll_model->get_deduction_detail($r->user_id, $pay_date);
				$pa_month = date('m-Y', strtotime('01-' . $pay_date));
				$deduction_amount = 0;
				if ($employee_deduction) {
					foreach ($employee_deduction as $deduction) {
						if ($deduction->type_id == 1) {
							$deduction_amount +=  $deduction->amount;
						}
						if ($deduction->type_id == 2) {
							$from_month_year = date('m-Y', strtotime($deduction->from_date));
							$to_month_year = date('d-m-Y', strtotime($deduction->to_date));


							if ($from_month_year != "" && $to_month_year != "") {
								if ($pa_month == $from_month_year || $pa_month == $to_month_year) {
									$deduction_amount +=  $deduction->amount;
								}
							}
						}
					}
				}



				//3: Gross rate of pay (unpaid leave deduction)
				$holidays_count = 0;
				$no_of_working_days = 0;
				$month_start_date = new DateTime('01-' . $pay_date);
				$month_end_date = new DateTime($month_start_date->format('Y-m-t'));
				$month_end_date->modify('+1 day');
				$interval = new DateInterval('P1D');
				$period = new DatePeriod($month_start_date, $interval, $month_end_date);
				foreach ($period as $p) {
					$period_day = $p->format('l');
					$period_date = $p->format('Y-m-d');

					//holidays in a month
					$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $period_date);
					if ($is_holiday) {
						$holidays_count += 1;
					}

					//working days excluding holidays based on office shift
					if ($period_day == 'Monday') {
						if ($office_shift[0]->monday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Tuesday') {
						if ($office_shift[0]->tuesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Wednesday') {
						if ($office_shift[0]->wednesday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Thursday') {
						if ($office_shift[0]->thursday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Friday') {
						if ($office_shift[0]->friday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Saturday') {
						if ($office_shift[0]->saturday_in_time != '') {
							$no_of_working_days += 1;
						}
					} else if ($period_day == 'Sunday') {
						if ($office_shift[0]->sunday_in_time != '') {
							$no_of_working_days += 1;
						}
					}
				}

				//unpaid leave
				$unpaid_leave_amount = 0;
				$leaves_taken_count = 0;
				$leave_period = array();
				$unpaid_leaves = $this->Employees_model->getEmployeeMonthUnpaidLeaves($r->user_id, $pay_date);
				if ($unpaid_leaves) {
					foreach ($unpaid_leaves as $k => $l) {
						$pay_date_month = new DateTime('01-' . $pay_date);
						$l_from_date = new DateTime($l->from_date);
						$l_to_date = new DateTime($l->to_date);

						if ($l_from_date->format('m') == $l_to_date->format('m')) {
							$start_date = $l_from_date;
							$end_date = $l_to_date;
						} elseif ($l_from_date->format('m') == $pay_date_month->format('m')) {
							$start_date = $l_from_date;
							$end_date = new DateTime($start_date->format('Y-m-t'));
						} elseif ($l_to_date->format('m') == $pay_date_month->format('m')) {
							$start_date = $pay_date_month;
							$end_date = $l_to_date;
						}
						$end_date->modify('+1 day');
						$interval = new DateInterval('P1D');
						$period = new DatePeriod($start_date, $interval, $end_date);
						foreach ($period as $d) {
							$p_day = $d->format('l');
							if ($p_day == 'Monday') {
								if ($office_shift[0]->monday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Tuesday') {
								if ($office_shift[0]->tuesday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Wednesday') {
								if ($office_shift[0]->wednesday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Thursday') {
								if ($office_shift[0]->thursday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Friday') {
								if ($office_shift[0]->friday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Saturday') {
								if ($office_shift[0]->saturday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							} else if ($p_day == 'Sunday') {
								if ($office_shift[0]->sunday_in_time != '') {
									$leave_period[$k]['leave_date'][] = $d->format('Y-m-d');
									if ($l->is_half_day == 0) {
										$leaves_taken_count += 1;
									} else {
										$leaves_taken_count += 0.5;
									}
								}
							}
						}
						$leave_period[$k]['is_half'] = $l->is_half_day;
					}
				}

				// if joining date and pay date same then this logic work
				$month_date_join = date('m-Y', strtotime($r->date_of_joining));
				$lastday = date('t-m-Y', strtotime("01-" . $pay_date));
				$month_last_date = date('m-Y', strtotime($lastday));

				$first_date =  new DateTime($r->date_of_joining);
				$no_of_days_worked = 0;
				$same_month_holidays_count = 0;
				if ($month_date_join == $pay_date) {
					$interval = new DateInterval('P1D');
					$period = new DatePeriod($first_date, $interval, $month_end_date);
					foreach ($period as $p) {
						$p_day = $p->format('l');
						$m_p_date = $p->format('Y-m-d');

						//holidays in a month
						$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $m_p_date);
						if ($is_holiday) {
							$same_month_holidays_count += 1;
						}

						//working days excluding holidays based on office shift
						if ($p_day == 'Monday') {
							if ($office_shift[0]->monday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Tuesday') {
							if ($office_shift[0]->tuesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Wednesday') {
							if ($office_shift[0]->wednesday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Thursday') {
							if ($office_shift[0]->thursday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Friday') {
							if ($office_shift[0]->friday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Saturday') {
							if ($office_shift[0]->saturday_in_time != '') {
								$no_of_days_worked += 1;
							}
						} else if ($p_day == 'Sunday') {
							if ($office_shift[0]->sunday_in_time != '') {
								$no_of_days_worked += 1;
							}
						}
					}
				// $no_of_days_worked = $no_of_days_worked - ($same_month_holidays_count + $leaves_taken_count);
				$no_of_days_worked = $no_of_days_worked - $same_month_holidays_count;
				} else {
					// $no_of_days_worked = ($no_of_working_days - ($leaves_taken_count + $holidays_count));
					$no_of_days_worked = $no_of_working_days -  $holidays_count;
				}


				if ($month_date_join == $pay_date){
					$show_main_salary = round((($basic_salary) / $no_of_working_days) * $no_of_days_worked, 2);
				}else{
					$show_main_salary = $basic_salary;
				}
			
				$g_ordinary_wage += $show_main_salary;
				$g_shg += $show_main_salary;
				$g_sdl += $show_main_salary;
			
				$g_ordinary_wage -= $deduction_amount;
				$g_shg -= $deduction_amount;
				$g_sdl -= $deduction_amount;


				// 3: all loan/deductions
				$salary_loan_deduction = $this->Employees_model->read_salary_loan_deductions($r->user_id);
				$count_loan_deduction = $this->Employees_model->count_employee_deductions($r->user_id);
				$loan_de_amount = 0;
				if ($count_loan_deduction > 0) {
					foreach ($salary_loan_deduction as $sl_salary_loan_deduction) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount / 2;
							} else {
								$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
							}
						} else {
							$er_loan = $sl_salary_loan_deduction->loan_deduction_amount;
						}
						$loan_de_amount += $er_loan;
					}
				} else {
					$loan_de_amount = 0;
				}
				$loan_de_amount = number_format(floatval($loan_de_amount), 2);

				
				// 2: all allowances
				$allowance_amount = 0;
				$gross_allowance_amount = 0;
				$salary_allowances = $this->Employees_model->getEmployeeMonthlyAllowance($r->user_id, $pay_date);
				if ($salary_allowances) {
					foreach ($salary_allowances as $sa) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$eallowance_amount = $sa->allowance_amount / 2;
							} else {
								$eallowance_amount = $sa->allowance_amount;
							}
						} else {
							$eallowance_amount = $sa->allowance_amount;
						}


						if (!empty($sa->salary_month)) {
							$g_additional_wage += $eallowance_amount;
						} else {
							if ($month_date_join == $pay_date){
								$eallowance_amount = round((($eallowance_amount) / $no_of_working_days) * $no_of_days_worked, 2);
							}
							$g_ordinary_wage += $eallowance_amount;
							if ($sa->id == 2) {
								$gross_allowance_amount = $eallowance_amount;
							}
						}

						if ($sa->sdl == 1) {
							$g_sdl += $eallowance_amount;
						}
						if ($sa->shg == 1) {
							$g_shg += $eallowance_amount;
						}

						$allowance_amount += $eallowance_amount;
					}
				}
				$gross_allowance_amount = $allowance_amount;



				// commissions
				$commissions_amount = 0;
				$commissions = $this->Employees_model->getEmployeeMonthlyCommission($r->user_id, $pay_date);
				if ($commissions) {
					foreach ($commissions as $c) {
						if ($system[0]->is_half_monthly == 1) {
							if ($system[0]->half_deduct_month == 2) {
								$ecommissions_amount = $c->commission_amount / 2;
							} else {
								$ecommissions_amount = $c->commission_amount;
							}
						} else {
							$ecommissions_amount = $c->commission_amount;
						}

						if ($c->commission_type == 9) {
							$g_ordinary_wage += $ecommissions_amount;
						} elseif ($c->commission_type == 10) {
							$g_additional_wage += $ecommissions_amount;
						}

						if ($c->sdl == 1) {
							$g_sdl += $ecommissions_amount;
						}
						if ($c->shg == 1) {
							$g_shg += $ecommissions_amount;
						}

						$commissions_amount += $ecommissions_amount;
					}
				}

				//share options
				$share_options_amount = 0;
				$share_options = $this->Employees_model->getEmployeeShareOptions($r->user_id, $pay_date);
				if ($share_options) {
					$eebr_amount = 0;
					$eris_amount = 0;
					foreach ($share_options as $s) {
						$scheme = $s->so_scheme;
						if ($scheme == 1) {
							$price_doe = $s->price_date_of_excercise;
							$price_ex = $s->excercise_price;
							$no_shares = $s->no_of_shares;
							$amount = ($price_doe - $price_ex) * $no_shares;
							$eebr_amount += $amount;
						} else {
							$price_doe = $s->price_date_of_excercise;
							$price_dog = $s->price_date_of_grant;
							$price_ex = $s->excercise_price;
							$no_shares = $s->no_of_shares;

							$tax_exempt_amount = ($price_doe - $price_dog) * $no_shares;
							$tax_no_exempt_amount = ($price_dog - $price_ex) * $no_shares;
							$eris_amount += $tax_exempt_amount + $tax_no_exempt_amount;
						}
					}
					$share_options_amount = round($eebr_amount + $eris_amount, 2);
					$g_additional_wage += $share_options_amount;
					$g_sdl += $share_options_amount;
					$g_shg += $share_options_amount;
				}

				
				// otherpayments
				$count_other_payments = $this->Employees_model->count_employee_other_payments($r->user_id);
				$other_payments = $this->Employees_model->set_employee_other_payments($r->user_id);
				$other_payments_amount = 0;
				if ($count_other_payments > 0) {
					foreach ($other_payments->result() as $sl_other_payments) {
						if ($sl_other_payments->ad_hoc_allowance == "Ad Hoc") {
							if ($pay_date == date('m-Y', strtotime($sl_other_payments->date))) {
								if ($system[0]->is_half_monthly == 1) {
									if ($system[0]->half_deduct_month == 2) {
										$epayments_amount = $sl_other_payments->payments_amount / 2;
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}
								} else {
									$epayments_amount = $sl_other_payments->payments_amount;
								}

								if ($sl_other_payments->cpf_applicable == 1) {
									$g_additional_wage += $epayments_amount;
									$g_shg += $epayments_amount;
									$g_sdl += $epayments_amount;
								}

								$other_payments_amount += $epayments_amount;
							}
						} else {
							$first_date = new DateTime($sl_other_payments->date);
							if ($first_date->format('m-Y') == $pay_date) {
								$first_date =  new DateTime($sl_other_payments->date);
							} else {
								$first_date = new DateTime('01-' . $pay_date);
							}

							$month_end_date_for_other = new DateTime($month_start_date->format('Y-m-t'));

							if (!empty($sl_other_payments->end_date)) {
								$last_date = new DateTime($sl_other_payments->end_date);
								if ($last_date->format('m-Y') == $pay_date) {
									$last_date = new DateTime($sl_other_payments->end_date);
								} else if($last_date->format('m-Y') >= $pay_date){
									$last_date = $month_end_date_for_other;
								} else {
									$last_date = '';
								}
							} else {
								$last_date = $month_end_date_for_other;
							}
							if(!empty($last_date)){
								$last_date->modify('+1 day');
								$final_last_day = new DateTime($last_date->format('d-m-Y'));
								if ($final_last_day->format('m-Y') >= $pay_date) {
									if ($system[0]->is_half_monthly == 1) {
										if ($system[0]->half_deduct_month == 2) {
											$epayments_amount = $sl_other_payments->payments_amount / 2;
										} else {
											$epayments_amount = $sl_other_payments->payments_amount;
										}
									} else {
										$epayments_amount = $sl_other_payments->payments_amount;
									}


									// it for no of working day
									$no_of_days_worked_for_other_payment = 0;
									$same_month_holidays_count_for_other_payment = 0;
									$interval = new DateInterval('P1D');
									$period = new DatePeriod($first_date, $interval, $last_date);
									foreach ($period as $p) {
										$p_day = $p->format('l');
										$p_date = $p->format('Y-m-d');

										//holidays in a month

										$is_holiday = $this->Timesheet_model->is_holiday_on_date($r->company_id, $p_date);
										if ($is_holiday) {
											$same_month_holidays_count_for_other_payment += 1;
										}

										//working days excluding holidays based on office shift
										if ($p_day == 'Monday') {
											if ($office_shift[0]->monday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Tuesday') {
											if ($office_shift[0]->tuesday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Wednesday') {
											if ($office_shift[0]->wednesday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Thursday') {
											if ($office_shift[0]->thursday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Friday') {
											if ($office_shift[0]->friday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Saturday') {
											if ($office_shift[0]->saturday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										} else if ($p_day == 'Sunday') {
											if ($office_shift[0]->sunday_in_time != '') {
												$no_of_days_worked_for_other_payment += 1;
											}
										}
									}

									$no_of_days_worked_for_other_payment = $no_of_days_worked_for_other_payment - ($same_month_holidays_count);
									$epayments_amount = round((($epayments_amount) / $no_of_working_days) * $no_of_days_worked_for_other_payment, 2);


									if ($sl_other_payments->cpf_applicable == 1) {
										$g_additional_wage += $epayments_amount;
										$g_shg += $epayments_amount;
										$g_sdl += $epayments_amount;
									}
									$other_payments_amount += $epayments_amount;
								}
							}
						}
					}
				} else {
					$other_payments_amount = 0;
				}

				$gross_pay = round(($show_main_salary + $other_payments_amount + $allowance_amount), 2);
	
				if ($unpaid_leaves) {
					$unpaid_leave_amount = round(($gross_pay /$no_of_days_worked) * $leaves_taken_count ,2);
				}
				
				// $g_ordinary_wage = $gross_pay;
				$g_shg = $gross_pay;
				$g_sdl = $gross_pay;

				$g_ordinary_wage -= $unpaid_leave_amount;

				

				// other benefit
				$other_benefit_mount = 0;
				$other_benefit_list = $this->PaymentDeduction_Model->get_all_employee_other_benefits_for_payslip($r->user_id, $pay_date);
				foreach ($other_benefit_list->result() as $benefit_list) {
					$other_benefit_mount += $benefit_list->other_benefit_cost;
				}


				// statutory_deductions
				$count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions($r->user_id);
				$statutory_deductions = $this->Employees_model->set_employee_statutory_deductions($r->user_id);
				$statutory_deductions_amount = 0;
				if ($count_statutory_deductions > 0) {
					foreach ($statutory_deductions->result() as $sl_salary_statutory_deductions) {
						if ($system[0]->statutory_fixed != 'yes') {
							$sta_salary = $gross_pay;
							$st_amount = $sta_salary / 100 * $sl_salary_statutory_deductions->deduction_amount;
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$single_sd = $st_amount / 2;
								} else {
									$single_sd = $st_amount;
								}
							} else {
								$single_sd = $st_amount;
							}
							$statutory_deductions_amount += $single_sd;
						} else {
							if ($system[0]->is_half_monthly == 1) {
								if ($system[0]->half_deduct_month == 2) {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount / 2;
								} else {
									$single_sd = $sl_salary_statutory_deductions->deduction_amount;
								}
							} else {
								$single_sd = $sl_salary_statutory_deductions->deduction_amount;
							}
							$statutory_deductions_amount += $single_sd;
						}
					}
				} else {
					$statutory_deductions_amount = 0;
				}

				// overtime
				$overtime_amount = 0;
				$overtime = $this->Overtime_request_model->getEmployeeMonthOvertime($r->user_id, $pay_date);
				if ($overtime) {
					$ot_hrs = 0;
					$ot_mins = 0;
					foreach ($overtime as $ot) {
						$total_hours = explode(':', $ot->total_hours);
						$ot_hrs += $total_hours[0];
						$ot_mins += $total_hours[1];
					}
					if ($ot_mins > 0) {
						$ot_hrs += round($ot_mins / 60, 2);
					}

					//overtime rate
					$overtime_rate = $this->Employees_model->getEmployeeOvertimeRate($r->user_id);
					if ($overtime_rate) {
						$rate = $overtime_rate->overtime_pay_rate;
					} else {
						$week_hours = 44;
						$rate = round((12 * $r->basic_salary) / (52 * $week_hours), 2);
						$rate = $rate * 1.5;
					}

					if ($ot_hrs > 0) {
						$overtime_amount = round($ot_hrs * $rate, 2);
					}
					if ($system[0]->is_half_monthly == 1) {
						if ($system[0]->half_deduct_month == 2) {
							$overtime_amount = $overtime_amount / 2;
						}
					}
					$g_ordinary_wage += $overtime_amount;
					$g_sdl += $overtime_amount;
				}

				
				$payment_check = $this->Payroll_model->check_make_payment_payslip_for_first_payment($r->user_id, $pay_date);
				$payment_check_final = $this->Payroll_model->check_make_payment_payslip_for_final_payment($r->user_id, $pay_date);
				$balance = 0;
				$check = $this->Payroll_model->check_make_payment_payslip_as_desc($r->user_id, $pay_date);
				if($check && $check[0]->balance_amount > 0){
					$balance = $check[0]->balance_amount;
					$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
				
					$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

					$status = '<span class="label label-warning">' . "Partially Paid" . '</span>';

					$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
					$mpay .= '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
					
					if (in_array('313', $role_resources_ids)) {
						$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
					} else {
						$delete = '';
					}
					// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
					$delete  = $delete;
					$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					
				}else if($check && $check[0]->balance_amount == 0){
					$make_payment = $this->Payroll_model->read_make_payment_payslip($r->user_id, $pay_date);
				
					$view_url = site_url() . 'admin/payroll/payslip/id/' . $make_payment[0]->payslip_key;

					$status = '<span class="label label-success">Paid</span>';

					$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_view_payslip') . '"><a href="' . $view_url . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $make_payment[0]->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
					
					if (in_array('313', $role_resources_ids)) {
						$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $check[0]->payslip_id . '"><span class="fa fa-trash"></span></button></span>';
					} else {
						$delete = '';
					}
					// $delete  = $delete . '<code>' . $this->lang->line('xin_title_first_half') . '</code>';
					$delete  = $delete;
					$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
					
				}else {
						$status = '<span class="label label-danger">' . $this->lang->line('xin_payroll_unpaid') . '</span>';
						$mpay = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payroll_make_payment') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $p_class . '" data-employee_id="' . $r->user_id . '" data-payment_date="' . $pay_date . '" data-company_id="' . $this->input->get("company_id") . '"><span class="fa fas fa-money"></span></button></span>';
						$delete = '';
						$detail = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-outline-secondary waves-effect waves-light" data-toggle="modal" data-target=".' . $view_p_class . '" data-employee_id="' . $r->user_id . '"><span class="fa fa-eye"></span></button></span>';
						$balance = $this->Xin_model->currency_sign(0, $r->user_id);
					}

				
				// employee accommodations
				$employee_accommodations = 0;
				$get_employee_accommodations = $this->PaymentDeduction_Model->get_employee_accommodations_by_employee_id($r->user_id, $pay_date);
				foreach ($get_employee_accommodations as $get_employee_accommodation) {
					$period_from 	= 	date('m-Y', strtotime($get_employee_accommodation->period_from));
					$period_to 		= 	date('m-Y', strtotime($get_employee_accommodation->period_to));
					if ($period_from == $pay_date || $period_to == $pay_date) {
						if (!empty($get_employee_accommodation->rent_paid)) {
							$employee_accommodations += $get_employee_accommodation->rent_paid;
						}
					}
				}

				// employee claims
				$claim_amount = 0;
				$get_employee_claims = $this->Employees_model->getEmployeeClaim($r->user_id);
				foreach ($get_employee_claims->result() as $claims) {
					$date 	= 	date('m-Y', strtotime($claims->date));
					if ($date == $pay_date) {
						$claim_amount += $claims->amount;
					}
				}



				$total_earning = $show_main_salary + $allowance_amount + $overtime_amount + $commissions_amount + $other_payments_amount + $share_options_amount;
				$total_deduction = floatval($loan_de_amount) + $statutory_deductions_amount + $other_benefit_mount + $deduction_amount + $employee_accommodations;
				$total_net_salary = ($total_earning + $claim_amount) - $total_deduction - $unpaid_leave_amount;


				// cpf calculation
				$emp_dob = $r->date_of_birth;
				$dob = new DateTime($emp_dob);

				$today = new DateTime('01-' . $pay_date);
				$age = $dob->diff($today);
				$age_year = $age->y;
				$age_month = $age->m;

				$age_upto = $this->Cpf_options_model->get_option_value('emp_upto_age')->option_value;
				$age_above = $this->Cpf_options_model->get_option_value('emp_above_age')->option_value;
				$ordinary_wage_cap = $this->Cpf_options_model->get_option_value('ordinary_wage_cap')->option_value;

				if ($age_month > 0) {
					$age_year = $age_year + 1;
				}

				$cpf_employee 	= 	0;
				$cpf_employer	=	0;

				$im_status = $this->Employees_model->getEmployeeImmigrationStatus($r->user_id);
				if ($im_status) {
					$immigration_id = $im_status->immigration_id;
					if ($immigration_id == 2) {
						$issue_date = $im_status->issue_date;
						$i_date = new DateTime($issue_date);
						$today = new DateTime();
						$pr_age = $i_date->diff($today);
						$pr_age_year = $pr_age->y;
						$pr_age_month = $pr_age->m;
					}

					if ($immigration_id == 1 || $immigration_id == 2) {

						$ordinary_wage = $g_ordinary_wage;
						if ($ordinary_wage > $ordinary_wage_cap) {
							$ow = $ordinary_wage_cap;
						} else {
							$ow = $ordinary_wage;
						}

						//additional wage
						$additional_wage = $g_additional_wage;
						$aw = $g_additional_wage;
						$tw = $ow + $additional_wage;
						if ($im_status->issue_date != "") {
							if ($pr_age_year == 1) {

								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(4 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 666) {
											$total_cpf = 666;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(4 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 4 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (9 / 100) * $ow + (9 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 666) {
											$total_cpf = 666;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(3.5 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw < 500) {
										$cpf_employer = round(3.5 / 100 * $tw);
										$cpf_employee = 0;
									} else if ($tw > 500 && $tw < 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = (5 / 100) * $ow + (5 / 100) * $aw;
										$count_total_cpf = (8.5 / 100) * $ow + (8.5 / 100) * $aw;

										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
							if ($pr_age_year == 2) {
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.45 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.45 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 15 / 100 * $ow + 15 / 100 * $aw;
										$count_total_cpf = 24 / 100 * $ow + 24 / 100 * $aw;
										if ($count_total_cpf > 1776) {
											$total_cpf = 1776;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1110) {
											$cpf_employee = 1110;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(6 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.375 * ($tw - 500));
										$total_cpf = 6 / 100 * $tw + 0.375 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
										$count_total_cpf = 18.5 / 100 * $ow + 18.5 / 100 * $aw;
										if ($count_total_cpf > 1369) {
											$total_cpf = 1369;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 925) {
											$cpf_employee = 925;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(3.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 11 / 100 * $ow + 11 / 100 * $aw;
										if ($count_total_cpf > 814) {
											$total_cpf = 814;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(3.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 3.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 8.5 / 100 * $ow + 8.5 / 100 * $aw;
										if ($count_total_cpf > 629) {
											$total_cpf = 629;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
							if ($pr_age_year == 3 || $pr_age_year > 3) {
								if ($age_year <= 55) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(17 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.6 * ($tw - 500));
										$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
										$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
										if ($count_total_cpf > 2738) {
											$total_cpf = 2738;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1480) {
											$cpf_employee = 1480;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = $total_cpf - $cpf_employee;
									}
								} else if ($age_year > 55 && $age_year <= 60) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(15.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.51 * ($tw - 500));
										$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
										$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
										if ($count_total_cpf > 2405) {
											$total_cpf = 2405;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 1258) {
											$cpf_employee = 1258;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 60 && $age_year <= 65) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(12 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.345 * ($tw - 500));
										$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
										$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;

										if ($count_total_cpf > 1739) {
											$total_cpf = 1739;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 851) {
											$cpf_employee = 851;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 65 && $age_year <= 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(9 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.225 * ($tw - 500));
										$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
										$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;

										if ($count_total_cpf > 1221) {
											$total_cpf = 1221;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 555) {
											$cpf_employee = 555;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								} else if ($age_year > 70) {
									if ($tw < 50) {
										$cpf_employee = 0;
										$cpf_employer = 0;
									} else if ($tw > 50 && $tw <= 500) {
										$cpf_employee = 0;
										$cpf_employer = round(7.5 / 100 * $tw);
									} else if ($tw > 500 && $tw <= 750) {
										$cpf_employee = floor(0.15 * ($tw - 500));
										$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
										$cpf_employer = round($total_cpf - $cpf_employee);
									} else if ($tw > 750) {
										$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
										$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;

										if ($count_total_cpf > 925) {
											$total_cpf = 925;
										} else {
											$total_cpf = $count_total_cpf;
										}
										if ($count_cpf_employee > 370) {
											$cpf_employee = 370;
										} else {
											$cpf_employee = floor($count_cpf_employee);
										}
										$cpf_employer = round($total_cpf - $cpf_employee);
									}
								}
							}
						}



						if ($immigration_id == 1) {

							if ($age_year <= 55) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(17 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.6 * ($tw - 500));
									$total_cpf = 17 / 100 * $tw + 0.6 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 20 / 100 * $ow + 20 / 100 * $aw;
									$count_total_cpf = 37 / 100 * $ow + 37 / 100 * $aw;
									if ($count_total_cpf > 2738) {
										$total_cpf = 2738;
									} else {
										$total_cpf = $count_total_cpf;
									}
			
									
									if ($count_cpf_employee > 1480) {
										$cpf_employee = 1480;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 55 && $age_year <= 60) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(15.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.51 * ($tw - 500));
									$total_cpf = 15.5 / 100 * ($tw) + 0.51 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 17 / 100 * $ow + 17 / 100 * $aw;
			
									$count_total_cpf = 32.5 / 100 * ($ow) + 32.5 / 100 * ($aw);
									if ($count_total_cpf > 2405) {
										$total_cpf = 2405;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 1258) {
										$cpf_employee = 1258;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 60 && $age_year <= 65) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(12 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.345 * ($tw - 500));
									$total_cpf = 12 / 100 * $tw + 0.345 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 11.5 / 100 * $ow + 11.5 / 100 * $aw;
									$count_total_cpf = 23.5 / 100 * $ow + 23.5 / 100 * $aw;
			
									if ($count_total_cpf > 1739) {
										$total_cpf = 1739;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 851) {
										$cpf_employee = 851;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 65 && $age_year <= 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(9 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.225 * ($tw - 500));
									$total_cpf = 9 / 100 * $tw + 0.225 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 7.5 / 100 * $ow + 7.5 / 100 * $aw;
									$count_total_cpf = 16.5 / 100 * $ow + 16.5 / 100 * $aw;
			
									if ($count_total_cpf > 1221) {
										$total_cpf = 1221;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 555) {
										$cpf_employee = 555;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							} else if ($age_year > 70) {
								if ($tw < 50) {
									$cpf_employee = 0;
									$cpf_employer = 0;
								} else if ($tw > 50 && $tw <= 500) {
									$cpf_employee = 0;
									$cpf_employer = round(7.5 / 100 * $tw);
								} else if ($tw > 500 && $tw <= 750) {
									$cpf_employee = floor(0.15 * ($tw - 500));
									$total_cpf = 7.5 / 100 * $tw + 0.15 * ($tw - 500);
									$cpf_employer = round($total_cpf - $cpf_employee);
								} else if ($tw > 750) {
									$count_cpf_employee = 5 / 100 * $ow + 5 / 100 * $aw;
									$count_total_cpf = 12.5 / 100 * $ow + 12.5 / 100 * $aw;
			
									if ($count_total_cpf > 925) {
										$total_cpf = 925;
									} else {
										$total_cpf = $count_total_cpf;
									}
									if ($count_cpf_employee > 370) {
										$cpf_employee = 370;
									} else {
										$cpf_employee = floor($count_cpf_employee);
									}
									$cpf_employer = round($total_cpf - $cpf_employee);
								}
							}
						}

						$total_net_salary = $total_net_salary - $cpf_employee;
						$cpf_total = $cpf_employee + $cpf_employer;

						$cpf_employee = number_format((float)$cpf_employee, 2, '.', '');
						$cpf_employer = number_format((float)$cpf_employer, 2, '.', '');
						$cpf_total    = number_format((float)$cpf_total, 2, '.', '');
					}
				}


				$shg_fund_deduction_amount = 0;
				//Other Fund Contributions
				$employee_contributions = $this->Contribution_fund_model->getEmployeeSelfHelpContributions($r->user_id);

				if ($employee_contributions && $g_shg > 0) {
					$gross_s = $g_shg;
					$contribution_id = $employee_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					$shg_fund_name = $contribution_type[0]->contribution;
					$shg_fund_deduction_amount += $contribution_amount;
					$total_net_salary = $total_net_salary - $shg_fund_deduction_amount;
				}
				$ashg_fund_deduction_amount = 0;

				$employee_ashg_contributions = $this->Contribution_fund_model->getEmployeeAdditionalSelfHelpContributions($r->user_id);
				if ($employee_ashg_contributions  && $g_shg > 0) {
					$gross_s = $g_shg;
					$contribution_id = $employee_ashg_contributions->contribution_id;
					$contribution_amount = $this->Contribution_fund_model->getContributionRate($gross_s, $contribution_id);
					$contribution_type = $this->Contribution_fund_model->getContributionFundsById($contribution_id);
					$ashg_fund_name = $contribution_type[0]->contribution;

					$ashg_fund_deduction_amount += $contribution_amount;
					$total_net_salary = $total_net_salary - $ashg_fund_deduction_amount;
				}

				// echo $total_net_salary;

				// $sdl_total_amount = 0;
				// if ($g_sdl > 1 && $g_sdl <= 800) {
				// 	$sdl_total_amount = 2;
				// } elseif ($g_sdl > 800 && $g_sdl <= 4500) {
				// 	$sdl_amount = (0.25 * $g_sdl) / 100;
				// 	$sdl_total_amount = $sdl_amount;
				// } elseif ($g_sdl > 4500) {
				// 	$sdl_total_amount = 11.25;
				// }


				$net_salary = number_format((float)$total_net_salary, 2, '.', '');
				$basic_salary = number_format((float)$basic_salary, 2, '.', '');
				// if ($basic_salary == 0 || $basic_salary == '') {
				// 	$fmpay = '';
				// } else {
					$fmpay = $mpay;
				// }

				$iemp_name = $emp_name . '<small class="text-muted"><i> (' . $comp_name . ')<i></i></i></small><br><small class="text-muted"><i>' . $this->lang->line('xin_employees_id') . ': ' . $r->employee_id . '<i></i></i></small>';

				//action link
				$act = $detail . $fmpay . $delete;
				// $act = $fmpay . $delete;

				if ($r->wages_type == 1) {
					if ($system[0]->is_half_monthly == 1) {
						$emp_payroll_wage = $wages_type . '<br><small class="text-muted"><i>' . $this->lang->line('xin_half_monthly') . '<i></i></i></small>';
					} else {
						$emp_payroll_wage = $wages_type;
					}
				} else {
					$emp_payroll_wage = $wages_type;
				}

				$payslips = $this->Payroll_model->read_make_payment_payslip_check($r->user_id, $pay_date);
				$p = $payslips->result();
				
				$mode_of_payment = $r->payment_mode ?? '';

				$field =  '<div class="k-top"><span class="k-icon k-plus" role="presentation"></span><span class="k-checkbox" role="presentation"  style="display:inline-block;"><label><input type="checkbox"  class="role-checkbox" name="employee_id[]" value="'.$r->user_id.'"> </label></span><span class="k-in k-state-focused"></span></div>';
				$field_in =  '<input type="number"  name="advance_amount[]"  class="form-control advance_class"  style="width: 100px;">';
			

				// $data[] = array(
				// 	// $act,
				// 	$iemp_name,
				// 	$emp_payroll_wage,
				// 	// $basic_salary,
				// 	$this->Xin_model->currency_sign($show_main_salary, $r->user_id),
				// 	$this->Xin_model->currency_sign($cpf_employee, $r->user_id),
				// 	$net_salary,
				// 	$balance,
				// 	$mode_of_payment,
				// 	$status
				// );

				
				$data[] = array(
					$field,
					$iemp_name,
					$this->Xin_model->currency_sign($show_main_salary, $r->user_id),
					$field_in,
				);
			}
		}
		$output = array(
			"draw" 				=> 	$draw,
			"recordsTotal" 		=> 	$payslip->num_rows(),
			"recordsFiltered" 	=> 	$payslip->num_rows(),
			"data" 				=> 	$data
		);
		echo json_encode($output);
		exit();
	}


	// for advance salary make payment
	public function add_pay_bulk_advance(){

		if(!empty($this->input->post('employee_id'))){
			foreach($this->input->post('employee_id') as $key => $employee_id){
				$user = $this->Xin_model->read_user_info($employee_id);
				$jurl = random_string('alnum', 40);
				$data = [
					'employee_id' 			=> 	$employee_id,
					'department_id' 		=> 	$user[0]->department_id,
					'company_id' 			=> 	$user[0]->company_id,
					'location_id' 			=> 	$user[0]->location_id,
					'designation_id' 		=> 	$user[0]->designation_id,
					'salary_month' 			=> 	$this->input->post('pay_date'),
					'basic_salary' 			=> 	$user[0]->basic_salary,
					'status' 				=> 	3,
					'payslip_type' 			=> 	'full_monthly',
					'payslip_key' 			=> 	$jurl,
					'year_to_date' 			=> 	date('d-m-Y'),
					'created_at' 			=> 	date('d-m-Y h:i:s'),
					'advance_amount'		=>	$this->input->post('advance_amount')[$key],
					'is_advance'			=> 	1
				];
			}
			$result = $this->Payroll_model->add_salary_payslip($data);
			if($result){
				$Return['result'] = $this->lang->line('xin_success_payment_paid');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
		}else{
			$Return['error'] = 'Please Select Employee';
			$this->output($Return);
		}
	}


	// get_bank_details
	public function get_bank_details(){
		$result = $this->Company_model->read_company_information($this->input->get('company_id'));
		$data = [];
		if(!empty($result[0]->bank_details)){
			foreach(json_decode($result[0]->bank_details) as $item){
				$data[] = [
					'bank_name'		=>	$item->bank_name
				];
			}
		}

		echo json_encode($data);
	}

	// for pdf generation
	public function pdf_create()
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		$system = $this->Xin_model->read_setting_info(1);
		// 	// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$key = $this->uri->segment(5);
		$payment = $this->Payroll_model->read_salary_payslip_info_key($key);

		$payslip_detail = $this->Payroll_model->read_salary_payslip_info_employee_id($payment[0]->employee_id,$payment[0]->salary_month); 
		
		if (is_null($payment)) {
			redirect('admin/payroll/generate_payslip');
		}
		$user = $this->Xin_model->read_user_info($payment[0]->employee_id);

		// if password generate option enable
		if ($system[0]->is_payslip_password_generate == 1) {
			/**
			 * Protect PDF from being printed, copied or modified. In order to being viewed, the user needs
			 * to provide password as selected format in settings module.
			 */
			if ($system[0]->payslip_password_format == 'dateofbirth') {
				$password_val = date("dmY", strtotime($user[0]->date_of_birth));
			} else if ($system[0]->payslip_password_format == 'contact_no') {
				$password_val = $user[0]->contact_no;
			} else if ($system[0]->payslip_password_format == 'full_name') {
				$password_val = $user[0]->first_name . $user[0]->last_name;
			} else if ($system[0]->payslip_password_format == 'email') {
				$password_val = $user[0]->email;
			} else if ($system[0]->payslip_password_format == 'password') {
				$password_val = $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'user_password') {
				$password_val = $user[0]->username . $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'employee_id') {
				$password_val = $user[0]->employee_id;
			} else if ($system[0]->payslip_password_format == 'employee_id_password') {
				$password_val = $user[0]->employee_id . $user[0]->password;
			} else if ($system[0]->payslip_password_format == 'dateofbirth_name') {
				$dob = date("dmY", strtotime($user[0]->date_of_birth));
				$fname = $user[0]->first_name;
				$lname = $user[0]->last_name;
				$password_val = $dob . $fname[0] . $lname[0];
			}
			$pdf->SetProtection(array('print', 'copy', 'modify'), $password_val, $password_val, 0, null);
		}

		$_des_name = $this->Designation_model->read_designation_information($user[0]->designation_id);
		if (!is_null($_des_name)) {
			$_designation_name = $_des_name[0]->designation_name;
		} else {
			$_designation_name = '';
		}
		$department = $this->Department_model->read_department_information($user[0]->department_id);
		if (!is_null($department)) {
			$_department_name = $department[0]->department_name;
		} else {
			$_department_name = '';
		}

		$company = $this->Xin_model->read_company_info($user[0]->company_id);

		$p_method = '';
		if (!is_null($company)) {
			$company_name = $company[0]->name;
			$company_logo = $company[0]->logo;
			$address_1 = $company[0]->address_1;
			$address_2 = $company[0]->address_2;
			$city = $company[0]->city;
			$state = $company[0]->state;
			$zipcode = $company[0]->zipcode;
			$country = $this->Xin_model->read_country_info($company[0]->country);
			if (!is_null($country)) {
				$country_name = $country[0]->country_name;
			} else {
				$country_name = '--';
			}
			$c_info_email = $company[0]->email;
			$c_info_phone = $company[0]->contact_number;
		} else {
			$company_name = '--';
			$company_logo = '--';
			$address_1 = '--';
			$address_2 = '--';
			$city = '--';
			$state = '--';
			$zipcode = '--';
			$country_name = '--';
			$c_info_email = '--';
			$c_info_phone = '--';
		}
		$fname = $user[0]->first_name . ' ' . $user[0]->last_name;

		$payment_mode = '';
		$cpf_employee = 0;
		$cpf_employer = 0;
		$leave_deduction =	0;
		$total_pay	=	0;
		$advance_pay	=	0;
		$total_hour_work = 0;
		foreach($payslip_detail as $p){
			 $payment_mode = $p->payment_mode;
			 $cpf_employee += $p->cpf_employee_amount;
			 $cpf_employer += $p->cpf_employer_amount;
			 $leave_deduction += $p->leave_deduction;
			 $total_pay	+=	$p->net_salary;
			 if($p->is_advance == 1){
				$advance_pay += $p->advance_amount;
			 }
			 if($p->wages_type == 2 && !empty($p->hours_worked)){
				$total_hour_work = $p->hours_worked;
			}
		}

		if($user[0]->wages_type == 2){
			$basic_pay = $user[0]->basic_salary *  $total_hour_work;
		}else{
			$basic_pay = $user[0]->basic_salary;
		}

		
		
		$pay_data['payslip_detail']	=	$payslip_detail;
		$pay_data['company_name'] = $company_name;
		$pay_data['company_logo'] = $company_logo;
		$pay_data['company_detals'] = $company;
		$pay_data['user_id'] = $user[0]->user_id;
		$pay_data['name'] = $fname;
		$pay_data['mode'] = $payment_mode;
		$pay_data['basic_pay'] = $basic_pay;

		$pay_data['cpf_employee'] = $cpf_employee;
		$pay_data['cpf_employer'] = $cpf_employer;
		$pay_data['leave_deduction'] = $leave_deduction;
		$pay_data['date_of_payment'] = $payment[0]->salary_month;
		$pay_data['total_pay'] = $total_pay;
		$pay_data['advance_pay'] = $advance_pay;
		

		$payslip_id = [];
		foreach($payslip_detail as $p){
			 $payslip_id[] = $p->payslip_id;
		}
		
	
		$pay_data['allowances'] = $this->Payroll_model->read_make_payment_allowances($payslip_id);
		$pay_data['deductions'] = $this->Payroll_model->read_make_payment_deduction($payslip_id);
		$pay_data['loans'] = $this->Payroll_model->read_make_payment_loan($payslip_id);
		$pay_data['statutory_deductions'] = $this->Payroll_model->read_make_payment_statutory_deductions($payslip_id);
		$pay_data['overtimes'] = $this->Payroll_model->read_make_payment_overtime($payslip_id);
		$pay_data['commissions'] = $this->Payroll_model->read_make_payment_commissions($payslip_id);
		$pay_data['other_payments'] = $this->Payroll_model->read_make_payment_other_payments($payslip_id);
		$pay_data['claims'] = $this->Payroll_model->read_make_payment_claims($payslip_id);
		
		echo $this->load->view('admin/payroll/new_ff', $pay_data,true);exit;

		$options = new Options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new Dompdf($options);
		$view =  $this->load->view('admin/payroll/new_ff', $pay_data,true);
		$dompdf->loadHtml($view);


		$customPaper = array(0, 0, 1000, 1500); // Adjust this as needed
		$dompdf->setPaper($customPaper);
		$dompdf->render();

		$dompdf->stream($fname . '_' . $payment[0]->salary_month . '.pdf', ["Attachment" => false]);

		exit;
	}

	public function payslip_delete()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		// $this->Payroll_model->delete_payslip_mapping_salary_backup($id);
		$result = $this->Payroll_model->delete_record($id);
		if (isset($id)) {
			$this->db->delete('xin_salary_payslips_check', ['payslip_id' => $id]);
			// $this->db->delete('xin_general', ['payslip_id' => $id]);
			// $this->db->delete('xin_motivation', ['payslip_id' => $id]);
			$this->db->delete('xin_salary_deduction', ['payslip_id' => $id]);
			$this->Payroll_model->delete_payslip_allowances_items($id);
			$this->Payroll_model->delete_payslip_commissions_items($id);
			$this->Payroll_model->delete_payslip_other_payment_items($id);
			$this->Payroll_model->delete_payslip_statutory_deductions_items($id);
			$this->Payroll_model->delete_payslip_overtime_items($id);
			$this->Payroll_model->delete_payslip_loan_items($id);
			$this->Contribution_fund_model->delete_contribution_payslip($id);
			$this->Cpf_payslip_model->delete_cpf_payslip($id);
			$this->Payroll_model->delete_payslip_share_options($id);
			$this->Payroll_model->delete_payslip_leave_deduction($id);
			$this->Payroll_model->delete_payslip_mapping($id);

			$Return['result'] = $this->lang->line('xin_hr_payslip_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
	// hourly_list > templates
	public function payment_history_list()
	{

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/payroll/payment_history", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$role_resources_ids = $this->Xin_model->user_role_resource();
		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		if ($this->input->get("ihr") == 'true') {
			if ($this->input->get("company_id") == 0 && $this->input->get("location_id") == 0 && $this->input->get("department_id") == 0) {
				if ($this->input->get("salary_month") == '') {
					$history = $this->Payroll_model->all_employees_payment_history();
				} else {
					$history = $this->Payroll_model->all_employees_payment_history_month($this->input->get("salary_month"));
				}
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") == 0 && $this->input->get("department_id") == 0) {
				if ($this->input->get("salary_month") == '') {
					$history = $this->Payroll_model->get_company_payslip_history($this->input->get("company_id"));
				} else {
					$history = $this->Payroll_model->get_company_payslip_history_month($this->input->get("company_id"), $this->input->get("salary_month"));
				}
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") != 0 && $this->input->get("department_id") == 0) {
				if ($this->input->get("salary_month") == '') {
					$history = $this->Payroll_model->get_company_location_payslips($this->input->get("company_id"), $this->input->get("location_id"));
				} else {
					$history = $this->Payroll_model->get_company_location_payslips_month($this->input->get("company_id"), $this->input->get("location_id"), $this->input->get("salary_month"));
				}
			} else if ($this->input->get("company_id") != 0 && $this->input->get("location_id") != 0 && $this->input->get("department_id") != 0) {
				if ($this->input->get("salary_month") == '') {
					$history = $this->Payroll_model->get_company_location_department_payslips($this->input->get("company_id"), $this->input->get("location_id"), $this->input->get("department_id"));
				} else {
					$history = $this->Payroll_model->get_company_location_department_payslips_month($this->input->get("company_id"), $this->input->get("location_id"), $this->input->get("department_id"), $this->input->get("salary_month"));
				}
			}/**/ /*else if($this->input->get("company_id")!=0 && $this->input->get("location_id")!=0 && $this->input->get("department_id")!=0 && $this->input->get("designation_id")!=0){
				$history = $this->Payroll_model->get_company_location_department_designation_payslips($this->input->get("company_id"),$this->input->get("location_id"),$this->input->get("department_id"),$this->input->get("designation_id"));
			}*/
		} else {
			if ($user_info[0]->user_role_id == 1) {
				$history = $this->Payroll_model->employees_payment_history();
			} else {
				if (in_array('391', $role_resources_ids)) {
					$history = $this->Payroll_model->get_company_payslips($user_info[0]->company_id);
				} else {
					$history = $this->Payroll_model->get_payroll_slip($session['user_id']);
				}
			}
		}
		$data = array();

		foreach ($history->result() as $r) {

			// get addd by > template
			$user = $this->Xin_model->read_user_info($r->employee_id);
			// user full name
			if (!is_null($user)) {
				$full_name = $user[0]->first_name . ' ' . $user[0]->last_name;
				$emp_link = $user[0]->employee_id;
				$month_payment = date("F, Y", strtotime('01-' . $r->salary_month));

				$p_amount = $this->Xin_model->currency_sign($r->net_salary, $r->user_id);

				// get date > created at > and format
				$created_at = $this->Xin_model->set_date_format($r->created_at);
				// get designation
				$designation = $this->Designation_model->read_designation_information($user[0]->designation_id);
				if (!is_null($designation)) {
					$designation_name = $designation[0]->designation_name;
				} else {
					$designation_name = '--';
				}
				// department
				$department = $this->Department_model->read_department_information($user[0]->department_id);
				if (!is_null($department)) {
					$department_name = $department[0]->department_name;
				} else {
					$department_name = '--';
				}
				$department_designation = $designation_name . ' (' . $department_name . ')';
				// get company
				$company = $this->Xin_model->read_company_info($user[0]->company_id);
				if (!is_null($company)) {
					$comp_name = $company[0]->name;
				} else {
					$comp_name = '--';
				}
				// bank account
				$bank_account = $this->Employees_model->get_employee_bank_account_last($user[0]->user_id);
				if (!is_null($bank_account)) {
					$account_number = $bank_account[0]->account_number;
				} else {
					$account_number = '--';
				}
				$payslip = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><a href="' . site_url() . 'admin/payroll/payslip/id/' . $r->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span><span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/payroll/pdf_create/p/' . $r->payslip_key . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

				$ifull_name = nl2br($full_name . "\r\n <small class='text-muted'><i>" . $this->lang->line('xin_employees_id') . ': ' . $emp_link . "<i></i></i></small>\r\n <small class='text-muted'><i>" . $department_designation . '<i></i></i></small>');
				$data[] = array(
					$payslip,
					$full_name,
					$comp_name,
					$account_number,
					$p_amount,
					$month_payment,
					$created_at,
				);
			}
		} // if employee available

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $history->num_rows(),
			"recordsFiltered" => $history->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}
}