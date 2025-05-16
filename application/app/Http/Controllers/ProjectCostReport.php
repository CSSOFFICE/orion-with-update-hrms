<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for milestones
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Responses\ProjectCostReport\IndexResponse;
use App\Http\Responses\ProjectCostReport\IndexListResponse;
use App\Http\Responses\ProjectCostReport\IndexKanbanResponse;


use App\Repositories\ProjectCostReportRepository;
use Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Validator;

class ProjectCostReport extends Controller
{
    protected $ProjectCostReportrepo;

    public function __construct(ProjectCostReportRepository $ProjectCostReportrepo)
    {
        parent::__construct();
        $this->ProjectCostReportrepo = $ProjectCostReportrepo;
    }
    public function index()
    {


        $id = request('project_cost_reportresource_id');
        if ($id) {
            request()->merge([
                'projectresource_id' => $id,
            ]);
        }


        $payload = $this->indexList();
        return new IndexResponse($payload);
    }

    public function indexList()
    {
        $id = request('projectresource_id');

        $page = $this->pageSettings('reports/projectcost');

        //get project milestones
        $Invoice = $this->ProjectCostReportrepo->get_invoice($id);

        $Invoice_items = $this->ProjectCostReportrepo->get_invoice_items($id);

        $Projects = $this->ProjectCostReportrepo->get_project($id);

        $start_date = date('m-Y', strtotime($Projects[0]->project_date_start));
        if ($Projects[0]->project_date_due != '') {
            $end_date = date('m-Y', strtotime($Projects[0]->project_date_due));
        } else {
            $end_date = date('m-Y');
        }

        $user_salaries = $this->ProjectCostReportrepo->get_assign_employees_salary($id, $start_date, $end_date);

        //DB::enableQueryLog();

        $user_total_salaries = $this->ProjectCostReportrepo->get_assign_employees_total_salary($id, $start_date, $end_date);
        //dd(DB::getQueryLog());



        $count =  $Invoice->total();


        //reponse payload
        $payload = [
            'page' => $page,
            'invoice' => $Invoice,
            'invoice_items' =>  $Invoice_items,
            'employee_salary' => $user_salaries,
            'employee_total_salary' => $user_total_salaries,
            'count' => $count
        ];
        //  echo "<pre>";print_r($payload);exit;
        return $payload;
    }
    private function pageSettings($section = '', $data = [])
    {

        //common settings
        $page = [
            'crumbs' => [
                __('lang.expense_report'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'reports/progressclaim',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_milestones' => 'active',
            'sidepanel_id' => 'sidepanel-filter-milestones',
            'dynamic_search_url' => url('projectcostreport/search?action=search&project_id=' . request('project_id') . '&resource_type=' . request('resource_type')),
            'add_button_classes' => 'add-edit-milestone-button',
            'load_more_button_route' => 'reports',
            'source' => 'list',
        ];
        //default modal settings (modify for sepecif sections)
        // $page += [
        //     'add_modal_title' => __('lang.add_milestone'),
        //     'add_modal_create_url' => url('expense_report/create?milestoneresource_id=' . request('milestoneresource_id') . '&milestoneresource_type=' . request('milestoneresource_type')),
        //     'add_modal_action_url' => url('expense_report?milestoneresource_id=' . request('milestoneresource_id') . '&milestoneresource_type=' . request('milestoneresource_type') . '&count=' . ($data['count'] ?? '')),
        //     'add_modal_action_ajax_class' => '',
        //     'add_modal_action_ajax_loading_target' => 'commonModalBody',
        //     'add_modal_action_method' => 'POST',
        // ];


        //milestones list page
        if ($section == 'reports/ProjectCostReportrepo') {

            $page += [
                'meta_title' => __('lang.expense_report'),
                'heading' => __('lang.expense_report'),
                'sidepanel_id' => 'sidepanel-filter-milestones',
            ];

            return $page;
        }

        //ext page settings
        if ($section == 'ext') {
            $page += [
                'list_page_actions_size' => 'col-lg-12',

            ];
            return $page;
        }

        //create new resource
        if ($section == 'create') {
            $page += [
                'section' => 'create',
            ];
            return $page;
        }

        //edit new resource
        if ($section == 'edit') {
            $page += [
                'section' => 'edit',
            ];
            return $page;
        }

        //return
        return $page;
    }
    function ordinal($number)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . 'th';
        else
            return $number . $ends[$number % 10];
    }
    public function exportCSV(Request $request)
    {


        $id = request('project_id');

        $fileName = 'ProjectCost.csv';
        //DB::enableQueryLog();

        $Invoice = $this->ProjectCostReportrepo->get_invoice($id);

        $Invoice_items = $this->ProjectCostReportrepo->get_invoice_items($id);

        $Projects = $this->ProjectCostReportrepo->get_project($id);
        $start_date = date('m-Y', strtotime($Projects[0]->project_date_start));
        if ($Projects[0]->project_date_due != '') {
            $end_date = date('m-Y', strtotime($Projects[0]->project_date_due));
        } else {
            $end_date = date('m-Y');
        }

        // DB::enableQueryLog();
        $user_salaries = $this->ProjectCostReportrepo->get_assign_employees_salary($id, $start_date, $end_date);
        //  dd(DB::getQueryLog());

        //echo "<pre>";print_r($user_salaries);exit;
        $user_total_salaries = $this->ProjectCostReportrepo->get_assign_employees_total_salary($id, $start_date, $end_date);

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array();

        $callback = function () use ($Invoice, $Invoice_items, $Projects, $user_salaries, $user_total_salaries, $columns) {
            $file = fopen('php://output', 'w');
            $columns[] = "Project Cost Report";
            fputcsv($file, $columns);

            $row['title'] = "";
            fputcsv($file, array($row['title']));

            $row['title'] = "Project Cost Report";
            fputcsv($file, array($row['title']));

            $row['title'] = "Claim Receivable";
            $row['title1'] = "Invoice No.";
            $row['title3'] = "Status";
            $row['title2'] = "Amount";


            fputcsv($file, array($row['title'], $row['title1'], $row['title3'], $row['title2']));

            $i = 1;
            foreach ($Invoice as $invoice) {
                $row['rank']  = $this->ordinal($i) . ' claim';

                $row['product_name']  = $invoice->invoice_no;
                $row['product_amount']    = $invoice->total;
                $row['product_status']    = $invoice->bill_status;


                $total_amount1[] = ($row['product_amount']);

                fputcsv($file, array($row['rank'], $row['product_name'], $row['product_status'], $row['product_amount']));
                $i++;
            }

            $row['product_name1']  = '';

            $row['product_amount1']    = 'Total:';
            $row['total_amount']    = array_sum($total_amount1);
            fputcsv($file, array($row['product_name1'], $row['product_amount1'], $row['total_amount']));


            $row['title'] = "";
            fputcsv($file, array($row['title']));


            $row['title1'] = "Item Name";
            $row['title2'] = "Invoice No.";
            $row['title3'] = "Amount";



            fputcsv($file, array($row['title1'], $row['title2'], $row['title3']));
            $total_amount = array();
            foreach ($Invoice_items as $invoice_item) {
                $row['product_name']  = $invoice_item->job_description;
                $row['product_qty']    = $invoice_item->invoice_no;
                $row['product_amount']    = $invoice_item->total;


                $total_amount[] = ($row['product_amount']);

                fputcsv($file, array($row['product_name'], $row['product_qty'], $row['product_amount']));
            }
            $row['product_name1']  = '';

            $row['product_amount1']    = 'Total:';
            $row['total_amount']    = array_sum($total_amount);
            fputcsv($file, array($row['product_name1'], $row['product_amount1'], $row['total_amount']));

            $row['title'] = "";
            fputcsv($file, array($row['title']));

            $row['salary_title'] = "Employee Salary Details";
            fputcsv($file, array($row['salary_title']));

            $row['employee_name'] = "Employee Name";
            $row['salary_month'] = "Salary Month";
            $row['net_salary'] = "Net Salary";
            fputcsv($file, array($row['employee_name'], $row['salary_month'], $row['net_salary']));

            $total_salary = array();
            foreach ($user_salaries as $salary) {
                $row['employee_name1'] = $salary->first_name . ' ' . $salary->last_name;
                $row['salary_month1'] = $salary->salary_month;
                $row['net_salary1'] = $salary->net_salary;
                $total_salary[] = $salary->net_salary;
                fputcsv($file, array($row['employee_name1'], $row['salary_month1'], $row['net_salary1']));
            }
            $row['employee_name2'] = '';
            $row['salary_month2'] = 'Total Salary:';
            $row['net_salary2'] = array_sum($total_salary);

            fputcsv($file, array($row['employee_name2'], $row['salary_month2'], $row['net_salary2']));
            $row['title'] = "";
            fputcsv($file, array($row['title']));

            $row['paid_salary_title'] = "Employee Paid Salary";
            fputcsv($file, array($row['salary_title']));

            $row['employee_name3'] = 'Employee Name';
            $row['net_salary3'] = 'Net Salary';

            fputcsv($file, array($row['employee_name3'], $row['net_salary3']));
            foreach ($user_total_salaries as $salary) {
                $row['employee_name4'] = $salary->first_name . ' ' . $salary->last_name;
                $row['net_salary4'] = $salary->total_salary;

                fputcsv($file, array($row['employee_name4'], $row['net_salary4']));
            }
            $row['total_cost'] = 'Total Cost';
            $row['total_cost_value'] = array_sum($total_amount) + array_sum($total_salary);

            fputcsv($file, array($row['total_cost'], $row['total_cost_value']));
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
