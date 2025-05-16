<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for profitloss
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\ProjectCostReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Log;

class ProjectCostReportRepository{
    protected $expense;

    public function __construct(ProjectCostReport $expense){
        $this->expense = $expense;
    }

    public function get_invoice($id='',$data = array()){
       
        $expense = $this->expense->newQuery();
        $expense->from('finance_invoice');
        $expense->select('*');
        $expense->whereRaw("1 = 1");

        //filter by passed id
        if (is_numeric($id)) {
            $expense->where('finance_invoice.project_id', $id);
        }
        return $expense->paginate(config('system.settings_system_pagination_limits'));

    }
    public function get_csv_expense($id='',$data = array()){
       
        $expense = $this->expense->newQuery();
        $expense->from('expenses');
        $expense->leftjoin('suppliers','expenses.expense_supplier_id','=','suppliers.supplier_id');
        $expense->select('expenses.purchase_invoice_number','expenses.expense_date','expenses.expense_amount','suppliers.name');
        $expense->whereRaw("1 = 1");

        //filter by passed id
        if (is_numeric($id)) {
            $expense->where('expenses.expense_projectid', $id);
        }
        return $expense->get();

   }
    public function get_invoice_items($id='',$data = array()){
       
        $expense = $this->expense->newQuery();

        $expense->from('finance_invoice');
        $expense->leftjoin('finance_invoice_description_mapping','finance_invoice.invoice_id','=','finance_invoice_description_mapping.invoice_id');
        
        $expense->selectRaw('finance_invoice.invoice_no,finance_invoice_description_mapping.job_description,finance_invoice_description_mapping.cost,finance_invoice_description_mapping.total');
        $expense->whereRaw("1 = 1");

        //filter by passed id
        if (is_numeric($id)) {
            $expense->where('finance_invoice.project_id', $id);
        }
        return $expense->paginate(config('system.settings_system_pagination_limits'));

    }
    public function get_project($id='',$data = array()){
       
        $progressclaim = $this->expense->newQuery();
        $progressclaim->from('projects');
        
        $progressclaim->selectRaw('projects.project_title,projects.project_date_start,projects.project_date_due');
        $progressclaim->whereRaw("1 = 1");

        //filter by passed id
        if (is_numeric($id)) {
            $progressclaim->where('projects.project_id', $id);
        }
        return $progressclaim->paginate(config('system.settings_system_pagination_limits'));

    }
    public function get_assign_employees_salary($id='',$start_date='',$end_date=''){
       
        $expense = $this->expense->newQuery();
        $expense->from('projects');
        $expense->leftjoin('projects_assigned','projects.project_id','=','projects_assigned.projectsassigned_projectid');
        $expense->leftjoin('xin_employees','projects_assigned.projectsassigned_userid','=','xin_employees.user_id');
        $expense->leftjoin('xin_salary_payslips','projects_assigned.projectsassigned_userid','=','xin_salary_payslips.employee_id');

        $expense->selectRaw('xin_salary_payslips.salary_month,xin_salary_payslips.net_salary,xin_employees.first_name,xin_employees.last_name,xin_employees.user_id');
        $expense->whereRaw("1 = 1");

        //filter by passed id
        if (is_numeric($id)) {
            $expense->where('projects.project_id', $id);
        }
        if($start_date !=''){
          
                $expense->whereBetween('xin_salary_payslips.salary_month',[$start_date,$end_date]);
            
        }
        return $expense->paginate(config('system.settings_system_pagination_limits'));
    }
    public function get_assign_employees_total_salary($id='',$start_date='',$end_date=''){
    
        $expense = $this->expense->newQuery();
        $expense->from('projects');
        $expense->leftjoin('projects_assigned','projects.project_id','=','projects_assigned.projectsassigned_projectid');
        $expense->leftjoin('xin_employees','projects_assigned.projectsassigned_userid','=','xin_employees.user_id');
        $expense->leftjoin('xin_salary_payslips','projects_assigned.projectsassigned_userid','=','xin_salary_payslips.employee_id');
        
        $expense->selectRaw('sum(xin_salary_payslips.net_salary) as total_salary,xin_employees.first_name,xin_employees.last_name,xin_salary_payslips.salary_month');
        $expense->whereRaw("1 = 1");

        //filter by passed id
        if (is_numeric($id)) {
            $expense->where('projects.project_id', $id);
        }
        if($start_date !=''){
          
                $expense->whereBetween('xin_salary_payslips.salary_month',[$start_date,$end_date]);
            
        }
        $expense->groupBy('xin_salary_payslips.salary_month');

        return $expense->get();    
    }
}