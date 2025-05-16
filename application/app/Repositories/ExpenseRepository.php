<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for expenses
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Client;
use App\Models\Expense;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Log;

class ExpenseRepository
{

    /**
     * The expenses repository instance.
     */
    protected $expenses;

    /**
     * Inject dependecies
     */
    public function __construct(Expense $expenses)
    {
        $this->expenses = $expenses;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @param array $data optional data payload
     * @return object expense collection
     */
    // public function search($id = '', $data = array())
    // {


    //     //default - always apply filters
    //     if (!isset($data['apply_filters'])) {
    //         $data['apply_filters'] = true;
    //     }

    //     //carbon dates
    //     $now = \Carbon\Carbon::now();
    //     $this_month = \Carbon\Carbon::now()->startOfMonth();

    //     $expenses = $this->expenses->newQuery();
    //     //joins$expenses = $this->expenses->newQuery();
    //     //joins
    //     $expenses->leftJoin('clients', 'clients.client_id', '=', 'expenses.expense_clientid');
    //     $expenses->leftJoin('projects', 'projects.project_id', '=', 'expenses.expense_projectid');
    //     $expenses->leftJoin('categories', 'categories.category_id', '=', 'expenses.expense_categoryid');
    //     $expenses->leftJoin('users', 'users.id', '=', 'expenses.expense_creatorid');
    //     $expenses->leftJoin('purchase_order', 'purchase_order.purchase_order_id', '=', 'expenses.purchase_order_id');

    //     $expenses->groupBy('expenses.purchase_order_id');
    //     $expenses->selectRaw('expenses.*,clients.f_name,clients.client_company_name,clients.cust_type,projects.project_title, purchase_order.porder_id, SUM(expenses.expense_amount) as total_amount');
    //     // all client fields
    //     // $expenses->selectRaw('*');

    //     //default where
    //     $expenses->whereRaw("1 = 1");

    //     //filter by passed id
    //     if (is_numeric($id)) {
    //         $expenses->where('expense_id', $id);
    //     }
    //     // all client fields
    //     // $expenses->selectRaw('*');

    //     //default where
    //     $expenses->whereRaw("1 = 1");

    //     //filter by passed id
    //     if (is_numeric($id)) {
    //         $expenses->where('expense_id', $id);
    //     }

    //     //filter by client - used for counting on external pages
    //     if (isset($data['project_clientid'])) {
    //         $expenses->where('project_clientid', $data['expense_clientid']);
    //     }

    //     //apply filters
    //     if ($data['apply_filters']) {

    //         //filters: id
    //         if (request()->filled('filter_expense_id')) {
    //             $expenses->where('expense_id', request('filter_expense_id'));
    //         }

    //         //filters: client id
    //         if (request()->filled('filter_expense_clientid') && $data['apply_filters']) {
    //             $expenses->where('expense_clientid', request('filter_expense_clientid'));
    //         }

    //         //filters: creator id
    //         if (request()->filled('filter_expense_creatorid')) {
    //             $expenses->where('expense_creatorid', request('filter_expense_creatorid'));
    //         }

    //         //filter: amount (min)
    //         if (request()->filled('filter_expense_amount_min')) {
    //             $expenses->where('expense_amount', '>=', request('filter_expense_amount_min'));
    //         }

    //         //filter: amount (max)
    //         if (request()->filled('filter_expense_amount_max')) {
    //             $expenses->where('expense_amount', '<=', request('filter_expense_amount_max'));
    //         }

    //         //filter: date (start)
    //         if (request()->filled('filter_expense_date_start')) {
    //             $expenses->where('expense_date', '>=', request('filter_expense_date_start'));
    //         }

    //         //filter: date (start)
    //         if (request()->filled('filter_expense_date_end')) {
    //             $expenses->where('expense_date', '<=', request('filter_expense_date_end'));
    //         }

    //         //filters: billing status
    //         if (request()->filled('expense_billing_status')) {
    //             $expenses->where('expense_billing_status', request('expense_billing_status'));
    //         }

    //         //filters: billable
    //         if (request()->filled('expense_billable')) {
    //             $expenses->where('expense_billable', request('expense_billable'));
    //         }

    //         //filters: project id
    //         if (request()->filled('filter_expense_projectid') && $data['apply_filters']) {
    //             $expenses->where('expense_projectid', request('filter_expense_projectid'));
    //         }

    //         //stats: - sum billable
    //         if (isset($data['stats']) && $data['stats'] == 'sum-invoiced') {
    //             $expenses->where('expense_billing_status', 'invoiced');
    //         }

    //         //stats: - sum unbillable
    //         if (isset($data['stats']) && $data['stats'] == 'sum-not-invoiced') {
    //             $expenses->where('expense_billing_status', 'not_invoiced');
    //         }

    //         //resource filtering
    //         if (request()->filled('expenseresource_type') && request()->filled('expenseresource_id')) {
    //             switch (request('expenseresource_type')) {
    //                 case 'client':
    //                     $expenses->where('expense_clientid', request('expenseresource_id'));
    //                     break;
    //                 case 'project':
    //                     $expenses->where('expense_projectid', request('expenseresource_id'));
    //                     break;
    //             }
    //         }

    //         //filter category
    //         if (is_array(request('filter_expense_categoryid'))) {
    //             $expenses->whereIn('expense_categoryid', request('filter_expense_categoryid'));
    //         }

    //         //search: various client columns and relationships (where first, then wherehas)
    //         if (request()->filled('search_query') || request()->filled('query')) {
    //             $expenses->where(function ($query) {
    //                 $query->Where('expense_id', '=', request('search_query'));
    //                 if (is_numeric(request('search_query'))) {
    //                     $query->orWhere('expense_amount', '=', request('search_query'));
    //                 }
    //                 $query->orWhere('expense_date', '=', date('Y-m-d', strtotime(request('search_query'))));
    //                 $query->orWhere('expense_billing_status', '=', request('search_query'));
    //                 $query->orWhere('expense_billable', '=', request('search_query'));
    //                 $query->orWhere('expense_description', 'LIKE', '%' . request('search_query') . '%');
    //                 $query->orWhereHas('category', function ($q) {
    //                     $q->where('category_name', 'LIKE', '%' . request('search_query') . '%');
    //                 });
    //                 $query->orWhereHas('client', function ($q) {
    //                     $q->where('client_company_name', 'LIKE', '%' . request('search_query') . '%');
    //                 });
    //                 $query->orWhereHas('project', function ($q) {
    //                     $q->where('project_title', 'LIKE', '%' . request('search_query') . '%');
    //                 });
    //             });
    //         }
    //     }

    //     //sorting
    //     if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
    //         //direct column name
    //         if (Schema::hasColumn('expenses', request('orderby'))) {
    //             $expenses->orderBy(request('orderby'), request('sortorder'));
    //         }
    //         //others
    //         switch (request('orderby')) {
    //             case 'client':
    //                 $expenses->orderBy('client_company_name', request('sortorder'));
    //                 break;
    //             case 'project':
    //                 $expenses->orderBy('project_title', request('sortorder'));
    //                 break;
    //             case 'category':
    //                 $expenses->orderBy('category_name', request('sortorder'));
    //                 break;
    //         }
    //     } else {
    //         //default sorting
    //         $expenses->orderBy(
    //             config('settings.ordering_expenses.sort_by'),
    //             config('settings.ordering_expenses.sort_order')
    //         );
    //     }

    //     //eager load
    //     $expenses->with([
    //         'project',
    //         'client',
    //         'category',
    //     ]);

    //     //stats - count all
    //     if (isset($data['stats']) && $data['stats'] == 'count-all') {
    //         return $expenses->count();
    //     }
    //     //stats - sum all
    //     if (isset($data['stats']) && in_array($data['stats'], [
    //         'sum-all',
    //         'sum-invoiced',
    //         'sum-not-invoiced',
    //     ])) {
    //         return $expenses->sum('expense_amount');
    //     }

    //     // return paginated rows
    //     return $expenses->paginate(config('system.settings_system_pagination_limits'));
    // }

    public function search($id = '', $data = array())
    {
        // Default filters
        if (!isset($data['apply_filters'])) {
            $data['apply_filters'] = true;
        }

        // Carbon dates
        $now = \Carbon\Carbon::now();
        $this_month = \Carbon\Carbon::now()->startOfMonth();

        // Fetch target expense and related payable
        // $expense = DB::table('expenses')->where('expense_id', $id)->first();


        // Start main query
        $expenses = $this->expenses->newQuery();
        $expenses->leftJoin('users', 'users.id', '=', 'expenses.expense_creatorid');
        $expenses->leftJoin('purchase_order', 'purchase_order.purchase_order_id', '=', 'expenses.purchase_order_id');
        $expenses->leftJoin('projects', 'projects.project_id', '=', 'purchase_order.project_id');
        $expenses->leftJoin('clients', 'clients.client_id', '=', 'projects.project_clientid');



        //filter by passed id
        if (is_numeric($id)) {
            $expenses->where('expense_id', $id);
        }
        // all client fields
        // $expenses->selectRaw('*');

        //default where
        $expenses->whereRaw("1 = 1");

        //filter by passed id
        if (is_numeric($id)) {
            $expenses->where('expense_id', $id);
        }

        //filter by client - used for counting on external pages
        if (isset($data['project_clientid'])) {
            $expenses->where('project_clientid', $data['expense_clientid']);
        }

        //apply filters
        if ($data['apply_filters']) {

            //filters: id
            if (request()->filled('filter_expense_id')) {
                $expenses->where('expense_id', request('filter_expense_id'));
            }

            //filters: client id
            if (request()->filled('filter_expense_clientid') && $data['apply_filters']) {
                $expenses->where('expense_clientid', request('filter_expense_clientid'));
            }

            //filters: creator id
            if (request()->filled('filter_expense_creatorid')) {
                $expenses->where('expense_creatorid', request('filter_expense_creatorid'));
            }

            //filter: amount (min)
            if (request()->filled('filter_expense_amount_min')) {
                $expenses->where('expense_amount', '>=', request('filter_expense_amount_min'));
            }

            //filter: amount (max)
            if (request()->filled('filter_expense_amount_max')) {
                $expenses->where('expense_amount', '<=', request('filter_expense_amount_max'));
            }

            //filter: date (start)
            if (request()->filled('filter_expense_date_start')) {
                $expenses->where('expense_date', '>=', request('filter_expense_date_start'));
            }

            //filter: date (start)
            if (request()->filled('filter_expense_date_end')) {
                $expenses->where('expense_date', '<=', request('filter_expense_date_end'));
            }

            //filters: billing status
            if (request()->filled('expense_billing_status')) {
                $expenses->where('expense_billing_status', request('expense_billing_status'));
            }

            //filters: billable
            if (request()->filled('expense_billable')) {
                $expenses->where('expense_billable', request('expense_billable'));
            }

            //filters: project id
            if (request()->filled('filter_expense_projectid') && $data['apply_filters']) {
                $expenses->where('expense_projectid', request('filter_expense_projectid'));
            }

            //stats: - sum billable
            if (isset($data['stats']) && $data['stats'] == 'sum-invoiced') {
                $expenses->where('expense_billing_status', 'invoiced');
            }

            //stats: - sum unbillable
            if (isset($data['stats']) && $data['stats'] == 'sum-not-invoiced') {
                $expenses->where('expense_billing_status', 'not_invoiced');
            }

            //resource filtering
            if (request()->filled('expenseresource_type') && request()->filled('expenseresource_id')) {
                switch (request('expenseresource_type')) {
                    case 'client':
                        $expenses->where('expense_clientid', request('expenseresource_id'));
                        break;
                    case 'project':
                        $expenses->where('expense_projectid', request('expenseresource_id'));
                        break;
                }
            }

            //filter category
            if (is_array(request('filter_expense_categoryid'))) {
                $expenses->whereIn('expense_categoryid', request('filter_expense_categoryid'));
            }

            //search: various client columns and relationships (where first, then wherehas)
            if (request()->filled('search_query') || request()->filled('query')) {
                $expenses->where(function ($query) {
                    $query->Where('expense_id', '=', request('search_query'));
                    if (is_numeric(request('search_query'))) {
                        $query->orWhere('expense_amount', '=', request('search_query'));
                    }
                    $query->orWhere('expense_date', '=', date('Y-m-d', strtotime(request('search_query'))));
                    $query->orWhere('expense_billing_status', '=', request('search_query'));
                    $query->orWhere('expense_billable', '=', request('search_query'));
                    $query->orWhere('expense_description', 'LIKE', '%' . request('search_query') . '%');
                    $query->orWhereHas('category', function ($q) {
                        $q->where('category_name', 'LIKE', '%' . request('search_query') . '%');
                    });
                    $query->orWhereHas('client', function ($q) {
                        $q->where('client_company_name', 'LIKE', '%' . request('search_query') . '%');
                    });
                    $query->orWhereHas('project', function ($q) {
                        $q->where('project_title', 'LIKE', '%' . request('search_query') . '%');
                    });
                });
            }
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('expenses', request('orderby'))) {
                $expenses->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
                case 'client':
                    $expenses->orderBy('client_company_name', request('sortorder'));
                    break;
                case 'project':
                    $expenses->orderBy('project_title', request('sortorder'));
                    break;
                case 'category':
                    $expenses->orderBy('category_name', request('sortorder'));
                    break;
            }
        } else {
            //default sorting
            $expenses->orderBy(
                config('settings.ordering_expenses.sort_by'),
                config('settings.ordering_expenses.sort_order')
            );
        }

        //eager load
        $expenses->with([
            'project',
            'client',
            'category',
        ]);

        //stats - count all
        if (isset($data['stats']) && $data['stats'] == 'count-all') {
            return $expenses->count();
        }
        //stats - sum all
        if (isset($data['stats']) && in_array($data['stats'], [
            'sum-all',
            'sum-invoiced',
            'sum-not-invoiced',
        ])) {
            return $expenses->sum('expense_amount');
        }

        // return paginated rows
        $expenses = $expenses->paginate(config('system.settings_system_pagination_limits'));
        // print_r($expenses);die;
        foreach ($expenses as $item) {
            // project

            $project = Project::find($item->project_id);

            $item->project_title = $project->project_title ?? null;
            $item->client_id = $project->project_clientid ?? null;

            // client

            $client = Client::find($item->client_id);

            $item->cust_type = $client->cust_type ?? null;
            $item->f_name = $client->f_name ?? null;
            $item->client_company_name = $client->client_company_name ?? null;

            // status
            if ($item->purchase_order_id != 0) {
                $xin_payable_total = DB::table('xin_payable')
                    ->where('purchase_order_id', $item->purchase_order_id)
                    // ->where('flag', 2)
                    ->sum('total');
            } else {
                $xin_payable_total = DB::table('xin_payable')
                    ->where('manual_po_number', $item->manual_po_number)
                    // ->where('flag', 2)
                    ->sum('total');
            }
            if ($xin_payable_total == 0) {
                $item->status = "Not Paid";
            } else if ($xin_payable_total == $item->total_amount) {
                $item->status = "Paid";
            } else {
                $item->status = "Partially Paid";
            }

            // for view start

            if (!empty($id)) {
                if ($item->purchase_order_id != 0) {
                    $item->total_amount = Expense::where('purchase_order_id', $item->purchase_order_id)->sum('expense_amount');
                } else {
                    $item->total_amount = Expense::where('purchase_order_no', $item->manual_po_number)->sum('expense_amount');
                }
            }

            $item->xin_payable = DB::table('xin_payable')
                ->where('purchase_order_id', $item->purchase_order_id)
                // ->groupBy('invoice_no')
                ->select('*')
                ->get();

            // for view end
        }
        // foreach ($expenses as $item) {
        //     // project
        //     $project = Project::find($item->project_id);
        //     $item->project_title = $project->project_title ?? null;
        //     $item->client_id = $project->project_clientid ?? null;
        //     // $item->manual_po_number=$item->manual_po_number ?? null;
        //     // client
        //     $client = Client::find($item->client_id);
        //     $item->cust_type = $client->cust_type ?? null;
        //     $item->f_name = $client->f_name ?? null;
        //     $item->client_company_name = $client->client_company_name ?? null;

        //     // Determine payable records and total based on purchase_order_id
        //     if ($item->purchase_order_id != 0) {
        //         // Logic when purchase_order_id is present
        //         $xin_payable_total = DB::table('xin_payable')
        //             ->where('purchase_order_id', $item->purchase_order_id)
        //             ->sum('total');

        //         $item->xin_payable = DB::table('xin_payable')
        //             ->where('purchase_order_id', $item->purchase_order_id)
        //             ->groupBy('invoice_no')
        //             ->select('*')
        //             ->get();

        //         // total_amount
        //         if (!empty($id)) {
        //             $item->total_amount = Expense::where('purchase_order_id', $item->purchase_order_id)->sum('expense_amount');
        //         }

        //     } else {
        //         // Logic when purchase_order_id is zero (manual / subcontractor)
        //         $xin_payable_total = DB::table('xin_payable')
        //             ->where('project_id_subcon', $item->project_id)
        //             ->sum('total');

        //         $item->xin_payable = DB::table('xin_payable')
        //             ->where('project_id_subcon', $item->project_id)
        //             ->groupBy('invoice_no')
        //             ->select('*')
        //             ->get();

        //         // total_amount
        //         if (!empty($id)) {
        //             $item->total_amount = Expense::where('project_id', $item->project_id)
        //                 // ->where('purchase_order_id', 0)
        //                 ->where('manual_po_number', $item->manual_po_number)
        //                 ->sum('expense_amount');
        //         }

        //         // Use manual PO number if available
        //         $manualPO = DB::table('xin_payable')
        //             ->where('project_id_subcon', $item->project_id)
        //             ->value('manual_po_number');

        //         $item->porder_id = $manualPO ?? null;
        //     }

        //     // status
        //     if ($xin_payable_total == 0) {
        //         $item->status = "Not Paid";
        //     } else if ($xin_payable_total == $item->total_amount) {
        //         $item->status = "Paid";
        //     } else {
        //         $item->status = "Partially Paid";
        //     }
        // }


        return $expenses;
    }

    public function get_expense()
    {


        $results = DB::table('xin_payable as p')
            ->select([
                'p.*',
                DB::raw("IF(p.purchase_order_id IS NULL OR p.purchase_order_id = 0, p.manual_po_number, po.porder_id) as purchase_order_no"),
                's.supplier_name',
                'p.after_gst_po_gt as potal',
            ])
            ->leftJoin('purchase_order as po', 'p.purchase_order_id', '=', 'po.purchase_order_id')
            ->leftJoin('purchase_order_item_mapping as m', 'po.purchase_order_id', '=', 'm.porder_id')
            ->leftJoin('xin_suppliers as s', function ($join) {
                $join->on(DB::raw('1'), '=', DB::raw('1'))
                    ->whereRaw('
                 (
                    (p.purchase_order_id IS NULL OR p.purchase_order_id = 0) AND p.subcon_id = s.supplier_id
                    OR
                    (p.purchase_order_id IS NOT NULL AND p.purchase_order_id != 0 AND m.supplier_id = s.supplier_id)
                 )
             ');
            })
            ->get();
        return $results;
    }
    /**
     * Create a new record
     * @return mixed int|bool
     */
    // public function create()
    // {
    //     // Save new expense
    //     $expense = new $this->expenses;

    //     // Set data
    //     // $expense->purchase_invoice_no = request('purchase_invoice_no');
    //     $expense->purchase_order_no = request('porder_id');
    //     $expense->expense_date = request('expense_date');
    //     $expense->expense_clientid = request('expense_clientid');
    //     $expense->expense_creatorid = auth()->id();
    //     $expense->expense_projectid = request('expense_projectid');
    //     $expense->expense_categoryid = request('expense_categoryid');
    //     $expense->expense_amount = request('expense_amount');
    //     $expense->expense_description = request('expense_description');
    //     $expense->expense_billable = (request('expense_billable') == 'on') ? 'billable' : 'not_billable';

    //     // Handle file upload
    //     if ($expense->expense_billing_status != 'invoiced') {
    //         $expense->expense_billable = (request('expense_billable') == 'on') ? 'billable' : 'not_billable';
    //     }

    //     // Handle file upload
    //     if (request()->hasFile('expense_attachment')) {
    //         // Validate the uploaded file
    //         request()->validate([
    //             'expense_attachment' => 'required|image|mimes:jpeg,png,jpg,gif,PNG,JPEG,JPG,GIF',
    //         ]);

    //         // Store the uploaded file in the public/uploads directory
    //         $imageName = "purchase_order_invoice" . time() . '.' . request()->file('expense_attachment')->extension();
    //         request()->file('expense_attachment')->move(public_path('uploads'), $imageName);

    //         // Update the file path in the database
    //         $expense->expense_attachment = $imageName;
    //     }

    //     // Save and return id
    //     if ($expense->save()) {
    //         return $expense->expense_id;
    //     } else {
    //         Log::error("Unable to create record - database error", [
    //             'process' => '[ExpenseRepository]',
    //             config('app.debug_ref'),
    //             'function' => __function__,
    //             'file' => basename(__FILE__),
    //             'line' => __line__,
    //             'path' => __file__,
    //         ]);
    //         return false;
    //     }
    // }

    public function create()
    {
        // Handle file upload
        if (request()->filled('logo_filename')) {
            //path to this directory in the temp folder
            $uploaded_file = BASE_DIR . "/storage/temp/" . request()->logo_directory . "/" . request()->logo_filename;
            $new_file = BASE_DIR . "/hrms/uploads/payment/" . request()->logo_filename;

            $current_file = BASE_DIR . "/hrms/uploads/payment/" . request()->logo_filename;

            //delete the old logo
            @unlink($current_file);

            //move the file
            @rename($uploaded_file, $new_file);
        }

        $new_invoice_no = request('new_invoice_no');
        $new_invoice_amount = request('new_invoice_amount');

        $inv_no_arr = explode(',', $new_invoice_no);
        $inv_amt_arr = explode(',', $new_invoice_amount);
        //  print_r(request('porder_id'));die;
        // Set data
        $pr_all_data = DB::table('purchase_order')->where('purchase_order_id', request('porder_id'))->first();
        // foreach ($inv_no_arr as $key => $item) {
        // Save new expense
        $expense = new $this->expenses;

        $expense->purchase_order_id = request('porder_id');
        $expense->expense_categoryid = request('category_id');
        $expense->expense_description = request('task_id');
        $expense->purchase_invoice_no = request('new_invoice_no');
        $expense->expense_amount = request('expense_amount');
        $expense->expense_date = request('date');
        $expense->expense_projectid = request('expense_projectid');
        $expense->do_no = request('do_no');
        $expense->purchase_invoice_no = request('invoice');
        $expense->expense_attachment = request()->logo_filename ?? '';

        $result = $expense->save();
        // }


        // Save and return id
        if ($result) {

            // add data in xin_payable table start

            foreach ($inv_no_arr as $key => $item) {
                $payable_data[] = [
                    'invoice_no' => $inv_no_arr[$key],
                    'purchase_order_total' => $inv_amt_arr[$key],
                    'gst_on_po_total' =>  $pr_all_data->gst_amount,
                    'after_gst_po_gt' => $pr_all_data->order_total,
                    'total' => 0,
                    'amount' => 0,
                    'remaining_amount'  => $inv_amt_arr[$key],
                    'purchase_order_id' => request('porder_id'),

                    'created_datetime' => date('Y-m-d h:i:s'),
                    'created_by' => Auth::user()->id,
                    'flag' => 1,
                ];
            }
            // print_r($payable_data);die;
            if (request('petty_cash')) {

                $this->create_petty_cash($expense->expense_id);
            }
            DB::table('xin_payable')->insert($payable_data);

            // add data in xin_payable table end

            return $expense->expense_id;
        } else {
            Log::error("Unable to create record - database error", [
                'process' => '[ExpenseRepository]',
                config('app.debug_ref'),
                'function' => __function__,
                'file' => basename(__FILE__),
                'line' => __line__,
                'path' => __file__,
            ]);
            return false;
        }
    }
    public function create_petty_cash($id)
    {
        $data = array(
            'expense_projectid' => request('expense_projectid'),
            'expense_categoryid' => request('category_id'),
            'expense_amount' => request('expense_amount'),
            'expense_description' => request('task_id'),
            'expense_id' =>$id,
        );
        DB::table('petty_cash')->insert($data);
        return 1;
    }
    /**
     * update a record
     * @param int $id record id
     * @return mixed int|bool
     */
    public function update($id)
    {
        // Get the record
        if (!$expense = $this->expenses->find($id)) {
            return false;
        }

        // Data
        $expense->purchase_order_no = request('porder_id');
        $expense->expense_date = request('expense_date');
        $expense->expense_creatorid = auth()->id();
        $expense->expense_categoryid = request('expense_categoryid');
        $expense->expense_amount = request('expense_amount');
        $expense->expense_description = request('expense_description');
        $expense->expense_clientid = request('expense_clientid');
        $expense->expense_projectid = request('expense_projectid');

        // Update only if this expense has not already been invoiced
        if ($expense->expense_billing_status != 'invoiced') {
            $expense->expense_billable = (request('expense_billable') == 'on') ? 'billable' : 'not_billable';
        }

        // Handle file upload
        if (request()->hasFile('expense_attachment')) {
            // Validate the uploaded file
            request()->validate([
                'expense_attachment' => 'required|image|mimes:jpeg,png,jpg,gif,PNG,JPEG,JPG,GIF',
            ]);

            // Store the uploaded file in the public/uploads directory
            $imageName = "purchase_order_invoice" . time() . '.' . request()->file('expense_attachment')->extension();
            request()->file('expense_attachment')->move(public_path('uploads'), $imageName);

            // Update the file path in the database
            $expense->expense_attachment = $imageName;
        }
        // else{
        //     dd($expense);exit;
        // }

        // Save
        if ($expense->save()) {
            return $expense->expense_id;
        } else {
            Log::error("Unable to update record - database error", ['process' => '[ExpenseRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
}
