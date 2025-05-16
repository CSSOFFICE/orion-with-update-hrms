<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for estimates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Repositories\EventRepository;
// use App\Repositories\EventTrackingRepository;
use App\Repositories\UserRepository;
use App\Models\Quos;
use App\Models\EventTracking;

use App\Models\Variation;
use App\Models\Client;
use App\Models\quotationTemplate;
use App\Permissions\ProjectPermissions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Log;

class VariationRepository
{
    protected $userrepo;
    protected $eventtracking;
    protected $eventrepo;

    /**
     * The event tracking repository instance.
     */
    // protected $trackingrepo;

    /**
     * The estimates repository instance.
     */
    protected $estimates;
    protected $client;
    protected $tasks;
    protected $lineitemrepo;
    protected $projectpermissions;

    /**
     * Inject dependecies
     */
    public function __construct(
        Variation $estimates,
        UserRepository $userrepo,
        EventTracking $eventtracking,
        ProjectPermissions $projectpermissions,
        Quos $tasks,
        EventRepository $eventrepo,
        // EventTrackingRepository $trackingrepo,
        LineitemRepository $lineitemrepo,
        Client $client
    ) {
        $this->estimates = $estimates;
        $this->client = $client;
        $this->lineitemrepo = $lineitemrepo;
        $this->eventrepo = $eventrepo;
        // $this->trackingrepo = $trackingrepo;
        $this->projectpermissions = $projectpermissions;
        $this->userrepo = $userrepo;
        $this->eventtracking = $eventtracking;
        $this->tasks = $tasks;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object estimate collection
     */
    public function search($id = '', $data = [])
    {

        $estimates = $this->estimates->newQuery();

        //default - always apply filters
        if (!isset($data['apply_filters'])) {
            $data['apply_filters'] = true;
        }

        // all client fields
        $estimates->selectRaw('*, variation_order.quotation_no as est_quotation_no');

        //joins
        $estimates->leftJoin('clients', 'clients.client_id', '=', 'variation_order.bill_clientid');
        $estimates->leftJoin('users', 'users.id', '=', 'variation_order.bill_creatorid');
        $estimates->leftJoin('categories', 'categories.category_id', '=', 'variation_order.bill_categoryid');
        // $estimates->leftJoin('projects', 'projects.project_id', '=', 'estimates.bill_projectid');


        //default where
        // $estimates->whereRaw("1 = 1");

        //filters: id
        // if (request()->filled('filter_vo_id')) {
        //     $estimates->where('vo_id', request('filter_vo_id'));
        // }
        if (is_numeric($id)) {
            // $estimates->where('variation_order.bill_projectid', $id);
            // print_r($id);die;
            $estimates->where('vo_id', $id);
        }
        if (isset($data['v_id'])) {
            $estimates->where('variation_order.bill_projectid', $data['v_id']);
            // $estimates->where('vo_id', $data['v_id']);
        }

        //filter by client - used for counting on external pages
        if (isset($data['bill_projectid'])) {
            $expenses->where('bill_projectid', $data['bill_projectid']);
        }

        //do not show items that not yet ready (i.e exclude items in the process of being cloned that have status 'invisible')
        $estimates->where('bill_visibility', 'visible');

        //apply filters
        if ($data['apply_filters']) {

            //filter clients
            if (request()->filled('filter_bill_clientid')) {
                $estimates->where('bill_clientid', request('filter_bill_clientid'));
            }

            //filter clients
            if (request()->filled('filter_bill_projectid')) {
                $estimates->where('bill_projectid', request('bill_projectid'));
            }

            //filter: value (min)
            if (request()->filled('filter_bill_subtotal_min')) {
                $estimates->where('bill_final_amount', '>=', request('filter_bill_subtotal_min'));
            }

            //filter: value (max)
            if (request()->filled('filter_bill_subtotal_max')) {
                $estimates->where('bill_final_amount', '<=', request('filter_bill_subtotal_max'));
            }

            //filter: estimate date (start)
            if (request()->filled('filter_bill_date_start')) {
                $estimates->where('bill_date', '>=', request('filter_bill_date_start'));
            }

            //filter: estimate date (end)
            if (request()->filled('filter_bill_date_end')) {
                $estimates->where('bill_date', '<=', request('filter_bill_date_end'));
            }

            //filter: estimate date (start)
            if (request()->filled('filter_bill_expiry_date_start')) {
                $estimates->where('bill_expiry_date', '>=', request('filter_bill_expiry_date_start'));
            }

            //filter: estimate date (end)
            if (request()->filled('filter_bill_expiry_date_end')) {
                $estimates->where('bill_expiry_date', '<=', request('filter_bill_expiry_date_end'));
            }

            //stats: - count
            if (isset($data['stats']) && (in_array($data['stats'], [
                'count-new',
                'count-accepted',
                'count-declined',
                'count-expired',
            ]))) {
                $estimates->where('bill_status', str_replace('count-', '', $data['stats']));
            }
            //stats: - sum
            if (isset($data['stats']) && (in_array($data['stats'], [
                'sum-new',
                'sum-accepted',
                'sum-declined',
                'sum-expired',
            ]))) {
                $estimates->where('bill_status', str_replace('sum-', '', $data['stats']));
            }

            //filter category
            if (is_array(request('filter_bill_categoryid')) && !empty(array_filter(request('filter_bill_categoryid')))) {
                $estimates->whereIn('bill_categoryid', request('filter_bill_categoryid'));
            }

            //filter status
            if (is_array(request('filter_bill_status')) && !empty(array_filter(request('filter_bill_status')))) {
                $estimates->whereIn('bill_status', request('filter_bill_status'));
            }

            //filter created by
            if (is_array(request('filter_bill_creatorid')) && !empty(array_filter(request('filter_bill_creatorid')))) {
                $estimates->whereIn('bill_creatorid', request('filter_bill_creatorid'));
            }

            //filter: tags
            if (is_array(request('filter_tags')) && !empty(array_filter(request('filter_tags')))) {
                $estimates->whereHas('tags', function ($query) {
                    $query->whereIn('tag_title', request('filter_tags'));
                });
            }

            //filter - exlude draft invoices
            if (request('filter_estimate_exclude_status') == 'draft') {
                $estimates->whereNotIn('bill_status', ['draft']);
            }

            //search: various client columns and relationships (where first, then wherehas)
            if (request()->filled('search_query') || request()->filled('query')) {
                $estimates->where(function ($query) {
                    //clean for estimate id search
                    $vo_id = str_replace(config('system.settings_estimates_prefix'), '', request('search_query'));
                    $vo_id = preg_replace("/[^0-9.,]/", '', $vo_id);
                    $vo_id = ltrim($vo_id, '0');
                    $query->Where('vo_id', '=', $vo_id);

                    $query->orWhere('bill_date', 'LIKE', '%' . date('Y-m-d', strtotime(request('search_query'))) . '%');
                    $query->orWhere('bill_expiry_date', 'LIKE', '%' . date('Y-m-d', strtotime(request('search_query'))) . '%');
                    $query->orWhere('first_name', 'LIKE', '%' . request('search_query') . '%');
                    if (is_numeric(request('search_query'))) {
                        $query->orWhere('bill_final_amount', '=', request('search_query'));
                    }
                    $query->orWhere('bill_status', '=', request('search_query'));
                    $query->orWhereHas('tags', function ($q) {
                        $q->where('tag_title', 'LIKE', '%' . request('search_query') . '%');
                    });
                    $query->orWhereHas('category', function ($q) {
                        $q->where('category_name', 'LIKE', '%' . request('search_query') . '%');
                    });
                    $query->orWhereHas('client', function ($q) {
                        $q->where('client_company_name', 'LIKE', '%' . request('search_query') . '%');
                    });
                });
            }
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('variation_order', request('orderby'))) {
                $estimates->orderBy(request('orderby'), request('sortorder'));
            }
            //others client
            switch (request('orderby')) {
                case 'client':
                    $estimates->orderBy('client_company_name', request('sortorder'));
                    break;
                case 'created_by':
                    $estimates->orderBy('first_name', request('sortorder'));
                    break;
            }
        } else {
            //default sorting
            $estimates->orderBy(
                'vo_id'
            );
        }

        //eager load
        $estimates->with([
            'tags',
        ]);

        //stats: - overdue
        if (isset($data['stats']) && (in_array($data['stats'], [
            'sum-new',
            'sum-accepted',
            'sum-declined',
            'sum-expired',
        ]))) {
            return $estimates->get()->sum('bill_final_amount');
        }

        //stats: - overdue
        if (isset($data['stats']) && (in_array($data['stats'], [
            'count-new',
            'count-accepted',
            'count-declined',
            'count-expired',
        ]))) {
            return $estimates->count();
        }

        // Get the results and return them.
        if (isset($data['limit']) && is_numeric($data['limit'])) {
            $limit = $data['limit'];
        } else {
            $limit = config('system.settings_system_pagination_limits');
        }


        return $estimates->paginate($limit);
    }

    /**
     * Create a new record
     * @return mixed int|bool
     */
    public function create()
    {
        $y = date('Y');
        $mm = date('m');
        $vesselIncomigNumber =  DB::table('variation_order')->orderBy('vo_id', 'desc')->first();
        // $vesselIncomigNumber= $this->estimates->getOneOrderedByColumn('vo_id','desc');
        // $vesselIncomigNumber = PreAlert::orderBy('id', 'desc')->first();
        if ($vesselIncomigNumber == null) {
            $number = 'PTS/QTN/' . $y . '/' . $mm . '' . '01';
        } else {
            $number = str_replace('PTS/QTN/' . $y . '/' . $mm . '', '', $vesselIncomigNumber->vo_id);
            $number =  "PTS/QTN/" . $y . '/' . $mm . '' . sprintf("%03d", $number + 1);
            // dd($number);
        }
        // PTS/QTN/date('Y'YYYY/MM/00.$estimate->vo_id
        //save new user

        $estimate = new $this->estimates;
        //data
        $estimate->quotation_type = request('quotation_type_hidden');
        $quotationOptions = request('quotation_options');

        if (is_array($quotationOptions)) {
            $estimate->quotation_options = implode(',', $quotationOptions);
        } else {
            $estimate->quotation_options = $quotationOptions;
        }
        //data
        $estimate->bill_clientid = request('bill_clientid');
        $estimate->bill_creatorid = auth()->id();

        $estimate->bill_date = request('bill_date');
        $estimate->bill_expiry_date = request('bill_expiry_date');
        $estimate->bill_notes = request('bill_notes');
        $estimate->q_title = request('q_title');
        $estimate->site_address = request('site_address');
        $estimate->pic_name = request('pic_name');
        $estimate->pic_email = request('pic_email');
        $estimate->pic_contact = request('pic_contact');
        $estimate->bill_terms = request('bill_terms');
        $estimate->bill_projectid = request('bill_projectid');
        $estimate->bill_status = 'draft';
        $estimate->quotation_no = $number;
        // this is task repo
        $estimate->save();
        // $task = new $this->tasks;
        // //echo "<pre>";print_r($_REQUEST);exit;
        // //data
        // $task->task_creatorid = auth()->id();
        // $task->task_projectid = request('bill_clientid');
        // $task->task_milestoneid = request('task_milestoneid');
        // $task->task_clientid = request('bill_clientid');
        // $task->task_date_start =  request('bill_date');
        // $task->task_date_due =  request('bill_expiry_date');
        // // $task->task_date_start = (!request()->filled('task_date_start')) ? NULL : request('task_date_start');
        // // $task->task_date_due = (!request()->filled('task_date_due')) ? NULL : request('task_date_due');
        // $task->task_title = request('q_title');
        // $task->task_description = request('task_description');
        // $task->task_client_visibility = "yes";
        // $task->task_billable = (request('task_billable') == 'on') ? 'yes' : 'no';
        // $task->task_status = request('cate_quo');
        // $task->task_priority = request('task_priority') ?? "hight";
        // $task->task_position = "abc";
        // $task->view_id = $estimate->vo_id;
        // $task->quo_number = $number;
        // this is task repo


        //save and return id
        if ($estimate->save()) {







            return $estimate->vo_id;
        } else {
            Log::error("unable to create record - database error", ['process' => '[EstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * update a record
     * @param int $id estimate id
     * @return mixed int|bool
     */
    public function update($id)
    {

        // echo "Hi";
        // exit;

        //get the record
        if (!$estimate = $this->estimates->find($id)) {
            return false;
        }

        //general
        // $estimate->bill_date = request('bill_date');
        // $estimate->bill_expiry_date = request('bill_expiry_date');
        // $estimate->bill_subtotal = request('bill_subtotal');
        // $estimate->bill_notes = request('bill_notes');


        // $estimate->bill_terms = request('bill_terms');
        // $estimate->bill_status = request('bill_status');

        $estimate->q_title = request('q_title');
        $estimate->subb = request('subb');
        //save
        if ($estimate->save()) {
            return $estimate->vo_id;
        } else {
            Log::error("unable to update record - database error", ['process' => '[EstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'estimate_id' => $id ?? '']);
            return false;
        }
    }

    /**
     * refresh an estimate
     * @param mixed $estimate can be an estimate id or an estimate object
     * @return mixed bool or id of record
     */
    public function refreshEstimate($estimate)
    {

        //get the estimate
        if (is_numeric($estimate)) {
            if (!$estimate = $this->search($estimate)) {
                return false;
            }
        }

        if (!$estimate instanceof \App\Models\Estimate) {
            Log::error("unable to load estimate record", ['process' => '[EstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //change dates to carbon format
        $bill_date = \Carbon\Carbon::parse($estimate->bill_date);
        $bill_expiry_date = \Carbon\Carbon::parse($estimate->bill_expiry_date);

        //estimate status for none draft, accepted, declined estimates
        if (!in_array($estimate->bill_status, ['draft', 'accepted', 'declined', 'revised'])) {

            //estimate is expired
            if ($estimate->bill_status == 'new') {
                if ($bill_expiry_date->diffInDays(today(), false) > 0) {
                    $estimate->bill_status = 'expired';
                }
            }

            //expired but date updated
            if ($estimate->bill_status == 'expired') {
                if ($bill_expiry_date->diffInDays(today(), false) < 0) {
                    $estimate->bill_status = 'new';
                }
            }
        }

        //update estimate
        $estimate->save();
    }


    /**
     * update an estimate from he edit estimate page
     * @param int $id record id
     * @return null
     */
    public function updateEstimate($id)
    {

        //get the record
        if (!$estimate = $this->estimates->find($id)) {
            Log::error("unable to load estimate record", ['process' => '[EstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'estimate_id' => $id ?? '']);
            return false;
        }


        // $val = 1;
        // $val += request('rev_no');
        // $estimate->rev_no = $val;


        $estimate->bill_status = request('bill_status');
        $estimate->bill_date = request('bill_date');
        $estimate->bill_expiry_date = request('bill_expiry_date');
        $estimate->bill_terms = request('bill_terms');
        $estimate->bill_exclution = request('bill_exclution');
        $estimate->bill_delivery = request('bill_delivery');
        $estimate->bill_notes = request('bill_notes');
        $estimate->bill_subtotal = request('bill_subtotal');
        $estimate->bill_amount_before_tax = request('bill_amount_before_tax');
        $estimate->bill_final_amount = request('bill_final_amount');
        $estimate->bill_tax_type = request('bill_tax_type');
        $estimate->bill_tax_total_percentage = request('bill_tax_total_percentage');
        $estimate->bill_tax_total_amount = request('bill_tax_total_amount');
        $estimate->bill_discount_type = request('bill_discount_type');
        $estimate->bill_discount_percentage = request('bill_discount_percentage');
        $estimate->bill_discount_amount = request('bill_discount_amount');
        $estimate->subb = request('subb');

        $estimate->pic_name = request('p_name');
        $estimate->pic_email = request('p_email');
        $estimate->pic_contact = request('p_contact');
        $estimate->site_address = request('p_address');
        $estimate->pic_city = request('p_city');
        $estimate->pic_zipcode = request('p_zipcode');
        $estimate->pic_country = request('p_country');
        //save new user
        $task =  $this->tasks->find($id);

        $task->task_status = "draft";
        $task->AMO = request('bill_amount_before_tax');

        $task->save();
        //save
        if ($estimate->save()) {

            $client =  $this->client->find($estimate->bill_clientid);
            $limitet = $client->credit_lim_etra;
            $credit_tem = $client->credit_term - request('bill_subtotal');
            $client->credit_term = request('bill_subtotal');
            $parcent = (10 / 100) * $limitet;
            // $this->applyPermissions($estimate);
            if ($parcent >= $credit_tem) {
                // this is notification
                // $data = [
                //     'event_creatorid' => auth()->id(),
                //     'event_item' => 'status',
                //     'event_item_id' => '',
                //     'event_item_lang' => 'event_changed_project_status',
                //     'event_item_content' => "Credit Limit is to low",
                //     'event_item_content2' => '',
                //     'event_parent_type' => 'project',
                //     'event_parent_id' => $estimate->bill_clientid,
                //     'event_parent_title' => "Your Credit Limit is Low",
                //     'event_show_item' => 'yes',
                //     'event_show_in_timeline' => 'yes',
                //     'event_clientid' => $estimate->bill_clientid,
                //     'eventresource_type' => 'project',
                //     'eventresource_id' => $id,
                //     'event_notification_category' => 'notifications_projects_activity',
                // ];
                // //record event
                // if (request('bill_subtotal')) {

                // // echo "111";
                // // die;
                // if ($event_id = $this->eventrepo->create($data)) {
                //     //get users
                //     $users = $this->projectpermissions->check('users', $estimate);
                //     print_r($users."77");die;
                //     //record notification
                //     $emailusers = $this->trackingrepo->recordEvent($data, $users, $event_id);
                // }
                // }
                $data = [
                    'event_creatorid' => auth()->id(),
                    'event_item' => 'estimate',
                    'event_item_id' => $estimate->vo_id,
                    'event_item_lang' => 'Credit Limit Is To Low',
                    'event_item_content' => __('lang.estimate') . ' - ' . $estimate->quotetation_no,
                    'event_item_content2' => '',
                    'event_parent_type' => 'estimate',
                    'event_parent_id' => $estimate->vo_id,
                    'event_parent_title' => $estimate->project_title,
                    'event_clientid' => $estimate->bill_clientid,
                    'event_show_item' => 'yes',
                    'event_show_in_timeline' => 'yes',
                    'eventresource_type' => (is_numeric($estimate->bill_projectid)) ? 'project' : 'client',
                    'eventresource_id' => (is_numeric($estimate->bill_projectid)) ? $estimate->bill_projectid : $estimate->bill_clientid,
                    'event_notification_category' => 'notifications_billing_activity',

                ];
                //record event
                if ($event_id = $this->eventrepo->create($data)) {
                    //get users (main client)
                    $users = $this->userrepo->getClientUsers($estimate->bill_clientid, 'owner', 'ids');

                    // printe_r($users);die;
                    //record notification
                    // $emailusers = $this->trackingrepo->recordEvent($data, $users, $event_id);
                    // function recordEvent($event = '', $users = '', $event_id = '') {
                    $eventtracking = new $this->eventtracking;
                    $eventtracking->eventtracking_eventid = $event_id;
                    $eventtracking->eventtracking_userid = auth()->id();
                    $eventtracking->eventtracking_source = $data['event_item'];
                    $eventtracking->eventtracking_source_id = $data['event_item_id'];
                    $eventtracking->eventtracking_status = "unread";

                    $eventtracking->parent_type = $data['event_parent_type'];
                    $eventtracking->parent_id = $data['event_parent_id'];
                    $eventtracking->resource_type = $data['eventresource_type'];
                    $eventtracking->resource_id = $data['eventresource_id'];
                    $eventtracking->save();
                    // }
                }
                // this is notification


            }
            // die;
        }
    }

    public function SaveProductItel($id)
    {
        DB::table('product_line_item')->where('quotation_id', $id)->delete();

        if (!empty(request('product_name')) > 0) {
            foreach (request('product_name') as $key => $v) {
                $data = [
                    'p_name' => request('product_name')[$key],
                    'p_desc' => request('product_description')[$key],
                    'qty' => request('product_qty')[$key],
                    'unit' => request('product_unit')[$key],
                    'rate' => request('product_rate')[$key],
                    'total' => request('js_product_total')[$key],
                    'quotation_id' => $id,
                ];
                DB::table('product_line_item')->insert($data);
            }
        }
    }





    /**
     * save each estimateline item
     * (1) get all existing line items and unlink them from expenses or timers
     * (2) delete all existing line items
     * (3) save each line item
     * @param int $vo_id resource id
     * @return mixed null|bool
     */
    public function saveLineItems($vo_id = '')
    {

        Log::info("saving estimate line items - started", ['process' => '[estimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

        //validation
        if (!is_numeric($vo_id)) {
            Log::error("validation error - required information is missing", ['process' => '[estimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //delete line items
        \App\Models\Lineitem::Where('lineitemresource_type', 'estimate')
            ->where('lineitemresource_id', $vo_id)
            ->delete();


        //default position
        $position = 0;

        //loopthrough each posted line item (use description to start the loop)
        if (is_array(request('js_item_description'))) {
            foreach (request('js_item_description') as $key => $description) {

                //next position (simple increment)
                $position++;

                //skip invalid items
                if (request('js_item_description')[$key] == '' || request('js_item_unit')[$key] == '') {
                    Log::error("invalid estimate line item...skipping it", ['process' => '[estimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    continue;
                }

                //skip invalid items
                if (!is_numeric(request('js_item_rate')[$key]) || !is_numeric(request('js_item_total')[$key])) {
                    Log::error("invalid estimate line item...skipping it", ['process' => '[estimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                    continue;
                }

                //save lineitem to database
                if (request('js_item_type')[$key] == 'plain') {

                    //validate
                    if (!is_numeric(request('js_item_quantity')[$key])) {
                        Log::error("invalid estimate line item (plain) ...skipping it", ['process' => '[estimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                        continue;
                    }

                    $line = [
                        'lineitem_description' => request('js_item_description')[$key],
                        'item' => request('item')[$key],
                        'lineitem_quantity' => request('js_item_quantity')[$key],
                        'lineitem_rate' => request('js_item_rate')[$key],
                        'lineitem_unit' => request('js_item_unit')[$key],
                        'lineitem_total' => request('js_item_total')[$key],
                        'lineitemresource_linked_type' => request('js_item_linked_type')[$key],
                        'lineitemresource_linked_id' => request('js_item_linked_id')[$key],
                        'lineitem_type' => request('js_item_type')[$key],
                        'item' => request('item')[$key],
                        'lineitem_position' => $position,
                        'lineitemresource_type' => 'estimate',
                        'lineitemresource_id' => $vo_id,
                        'lineitem_time_timers_list' => null,
                        'lineitem_time_hours' => null,
                        'lineitem_time_minutes' => null,
                    ];
                    $this->lineitemrepo->create($line);
                }
                if (request('js_item_type')[$key] == 'product') {

                    //validate
                    if (!is_numeric(request('js_item_quantity')[$key])) {
                        Log::error("invalid estimate line item (product) ...skipping it", ['process' => '[estimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                        continue;
                    }

                    $line = [
                        'lineitem_description' => request('js_item_description')[$key],
                        'item' => request('item')[$key],
                        'lineitem_quantity' => request('js_item_quantity')[$key],
                        'lineitem_rate' => request('js_item_rate')[$key],
                        'lineitem_unit' => request('js_item_unit')[$key],
                        'lineitem_total' => request('js_item_total')[$key],
                        'lineitemresource_linked_type' => request('js_item_linked_type')[$key],
                        'lineitemresource_linked_id' => request('js_item_linked_id')[$key],
                        'lineitem_type' => request('js_item_type')[$key],
                        'item' => request('item')[$key],
                        'lineitem_position' => $position,
                        'lineitemresource_type' => 'estimate',
                        'lineitemresource_id' => $vo_id,
                        'lineitem_time_timers_list' => null,
                        'lineitem_time_hours' => null,
                        'lineitem_time_minutes' => null,
                    ];
                    $this->lineitemrepo->create($line);
                }

                //save time item to database
                if (request('js_item_type')[$key] == 'time') {

                    //validate
                    if (!is_numeric(request('js_item_hours')[$key]) || !is_numeric(request('js_item_minutes')[$key])) {
                        Log::error("invalid estimate line item (time) ...skipping it", ['process' => '[estimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
                        continue;
                    }

                    $line = [
                        'lineitem_description' => request('js_item_description')[$key],
                        'item' => request('item')[$key],
                        'lineitem_quantity' => null,
                        'lineitem_rate' => request('js_item_rate')[$key],
                        'lineitem_unit' => request('js_item_unit')[$key],
                        'lineitem_total' => request('js_item_total')[$key],
                        'lineitemresource_linked_type' => request('js_item_linked_type')[$key],
                        'lineitemresource_linked_id' => request('js_item_linked_id')[$key],
                        'lineitem_type' => request('js_item_type')[$key],
                        'lineitem_position' => $position,
                        'lineitemresource_type' => 'estimate',
                        'lineitemresource_id' => $vo_id,
                        'lineitem_time_hours' => request('js_item_hours')[$key],
                        'lineitem_time_minutes' => request('js_item_minutes')[$key],
                        'lineitem_time_timers_list' => request('js_item_timers_list')[$key],

                    ];
                    $this->lineitemrepo->create($line);
                }
            }
            if (!empty(request('abc'))) {
                DB::table('estimates')->where('vo_id', $vo_id)->update(['rev_no' => request('rev_no') + 1]);
            }
        }
    }
}
