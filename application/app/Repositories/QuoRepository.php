<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for tasks
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Estimate;

use App\Models\Quos;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;

class QuoRepository
{

    /**
     * The tasks repository instance.
     */
    protected $tasks;
    protected $estimates;
    /**
     * Inject dependecies
     */
    public function __construct(Estimate $estimates, Quos $tasks)
    {
        $this->tasks = $tasks;
        $this->estimates = $estimates;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object task collection
     */
    public function search($id = '', $data = [])
    {

        $tasks = $this->tasks->newQuery();

        //default - always apply filters
        if (!isset($data['apply_filters'])) {
            $data['apply_filters'] = true;
        }

        //joins
        // $tasks->leftJoin('projects', 'projects.project_id', '=', 'quos.task_projectid');
        // $tasks->leftJoin('milestones', 'milestones.milestone_id', '=', 'quos.task_milestoneid');
        // $tasks->leftJoin('users', 'users.id', '=', 'quos.task_creatorid');
        $tasks->leftJoin('clients', 'clients.client_id', '=', 'quos.task_clientid');

        //my id
        $myid = auth()->id();

        // all client fields
        $tasks->selectRaw('*');

        //count unread notifications
        $tasks->selectRaw('(SELECT COUNT(*)
                                      FROM events_tracking
                                      LEFT JOIN events ON events.event_id = events_tracking.eventtracking_eventid
                                      WHERE eventtracking_userid = ' . auth()->id() . '
                                      AND events_tracking.eventtracking_status = "unread"
                                      AND events.event_parent_type = "quos"
                                      AND events.event_parent_id = quos.task_id
                                      AND events.event_item = "comment")
                                      AS count_unread_comments');

        //count unread notifications
        $tasks->selectRaw('(SELECT COUNT(*)
                                      FROM events_tracking
                                      LEFT JOIN events ON events.event_id = events_tracking.eventtracking_eventid
                                      WHERE eventtracking_userid = ' . auth()->id() . '
                                      AND events_tracking.eventtracking_status = "unread"
                                      AND events.event_parent_type = "quos"
                                      AND events.event_parent_id = quos.task_id
                                      AND events.event_item = "attachment")
                                      AS count_unread_attachments');

        //sum all timers for this task
        $tasks->selectRaw('(SELECT COALESCE(SUM(timer_time), 0)
                                           FROM timers WHERE timer_taskid = quos.task_id)
                                           AS sum_all_time');

        //sum my timers for this task
        $tasks->selectRaw("(SELECT COALESCE(SUM(timer_time), 0)
                                           FROM timers WHERE timer_taskid = quos.task_id
                                           AND timer_creatorid = $myid)
                                           AS sum_my_time");

        //sum invoiced time
        $tasks->selectRaw("(SELECT COALESCE(SUM(timer_time), 0)
                                           FROM timers WHERE timer_taskid = quos.task_id
                                           AND timer_billing_status = 'invoiced')
                                           AS sum_invoiced_time");

        //sum not invoiced time
        $tasks->selectRaw("(SELECT COALESCE(SUM(timer_time), 0)
                                           FROM timers WHERE timer_taskid = quos.task_id
                                           AND timer_billing_status = 'not_invoiced')
                                           AS sum_not_invoiced_time");

        //default where
        $tasks->whereRaw("1 = 1");

        //filters: id
        if (request()->filled('filter_task_id')) {
            $tasks->where('task_id', request('filter_task_id'));
        }
        if (is_numeric($id)) {
            $tasks->where('task_id', $id);
        }

        //do not show items that not yet ready (i.e exclude items in the process of being cloned that have status 'invisible')
        $tasks->where('task_visibility', 'visible');

        //apply filters
        if ($data['apply_filters']) {

            //filter clients
            if (request()->filled('filter_task_clientid')) {
                $tasks->where('task_clientid', request('filter_task_clientid'));
            }

            //filter: added date (start)
            if (request()->filled('filter_task_date_start_start')) {
                $tasks->where('task_date_start', '>=', request('filter_task_date_start_start'));
            }

            //filter: added date (end)
            if (request()->filled('filter_task_date_start_end')) {
                $tasks->where('task_date_start', '<=', request('filter_task_date_start_end'));
            }

            //filter: due date (start)
            if (request()->filled('filter_task_date_due_start')) {
                $tasks->where('task_date_due', '>=', request('filter_task_date_due_start'));
            }

            //filter: start date (end)
            if (request()->filled('filter_task_date_due_end')) {
                $tasks->where('task_date_due', '<=', request('filter_task_date_due_end'));
            }

            //filter milestone id
            if (request()->filled('filter_task_milestoneid')) {
                $tasks->where('task_milestoneid', request('filter_task_milestoneid'));
            }

            //filter: only tasks visible to the client
            if (request()->filled('filter_task_client_visibility')) {
                $tasks->where('task_client_visibility', request('filter_task_client_visibility'));
            }

            //resource filtering
            if (request()->filled('taskresource_id')) {
                $tasks->where('task_projectid', request('taskresource_id'));
            }

            //filter single task status
            if (request()->filled('filter_single_task_status')) {
                $tasks->where('task_status', request('filter_single_task_status'));
            }

            //stats: - counting
            if (isset($data['stats']) && $data['stats'] == 'Draft') {
                $tasks->where('task_status', 'Draft');
            }
            if (isset($data['stats']) && $data['stats'] == 'Expired') {
                $tasks->where('task_status', 'Expired');
            }

            //stats: - counting
            if (isset($data['stats']) && $data['stats'] == 'accepted') {
                $tasks->where('task_status', 'accepted');
            }

            // stats: - counting
            if (isset($data['stats']) && $data['stats'] == 'declined') {
                $tasks->where('task_status', 'declined');
            }

            //stats: - counting
            if (isset($data['stats']) && $data['stats'] == 'count-completed') {
                $tasks->where('task_status', 'completed');
            }

            //filter: only tasks visible to the client - as per project permissions
            if (request()->filled('filter_as_per_project_permissions')) {
                $tasks->where('clientperm_tasks_view', 'yes');
            }

            //filter: project
            if (request()->filled('filter_task_projectid')) {
                $tasks->where('task_projectid', request('filter_task_projectid'));
            }

            //filter status
            if (is_array(request('filter_tasks_status')) && !empty(array_filter(request('filter_tasks_status')))) {
                $tasks->whereIn('task_status', request('filter_tasks_status'));
            }

            //filter project
            if (is_array(request('filter_task_projectid'))) {
                $tasks->whereIn('task_projectid', request('filter_task_projectid'));
            }

            //filter priority
            if (is_array(request('filter_task_priority')) && !empty(array_filter(request('filter_task_priority')))) {
                $tasks->whereIn('task_priority', request('filter_task_priority'));
            }

            //filter assigned
            if (is_array(request('filter_assigned')) && !empty(array_filter(request('filter_assigned')))) {
                $tasks->whereHas('assigned', function ($query) {
                    $query->whereIn('tasksassigned_userid', request('filter_assigned'));
                });
            }

            //filter: tags
            if (is_array(request('filter_tags')) && !empty(array_filter(request('filter_tags')))) {
                $tasks->whereHas('tags', function ($query) {
                    $query->whereIn('tag_title', request('filter_tags'));
                });
            }

            //filter my tasks (using the actions button)
            if (request()->filled('filter_my_tasks')) {
                $tasks->whereHas('assigned', function ($query) {
                    $query->whereIn('tasksassigned_userid', [auth()->id()]);
                });
            }
        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $tasks->where(function ($query) {
                $query->Where('task_id', '=', request('search_query'));
                $query->orWhere('task_date_start', 'LIKE', '%' . date('Y-m-d', strtotime(request('search_query'))) . '%');
                $query->orWhere('task_date_due', 'LIKE', '%' . date('Y-m-d', strtotime(request('search_query'))) . '%');
                $query->orWhere('task_title', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('task_status', '=', request('search_query'));
                $query->orWhere('task_priority', '=', request('search_query'));
                //$query->orWhereRaw("YEAR(task_date_start) = ?", [request('search_query')]); //example binding - buggy
                //$query->orWhereRaw("YEAR(task_date_due) = ?", [request('search_query')]); //example binding  - buggy
                $query->orWhereHas('tags', function ($q) {
                    $q->where('tag_title', 'LIKE', '%' . request('search_query') . '%');
                });
                $query->orWhereHas('assigned', function ($q) {
                    $q->where('first_name', '=', request('search_query'));
                    $q->where('last_name', '=', request('search_query'));
                });
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('quos', request('orderby'))) {
                $tasks->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
                case 'project':
                    $tasks->orderBy('project_title', request('sortorder'));
                    break;
                case 'time':
                    $tasks->orderBy('timers_sum', request('sortorder'));
                    break;
            }
        } else {
            //default sorting
            if (request('query_type') == 'kanban') {
                $tasks->orderBy('task_position', 'asc');
            } else {
                $tasks->orderBy('task_id', 'desc');
            }
        }

        //eager load
        $tasks->with([
            'tags',
            'timers',
            'assigned',
            'projectmanagers',
        ]);

        //count relationships
        $tasks->withCount([
            'tags',
            'comments',
            'attachments',
            'timers',
            'checklists',
        ]);

        //stats - count all
        if (isset($data['stats']) && in_array($data['stats'], [
            'Draft',
            'accepted',
            'declined',
            'Expired',
        ])) {
            return $tasks->count();
        }

        // Get the results and return them.
        if (request('query_type') == 'kanban') {
            return $tasks->paginate(config('system.settings_system_kanban_pagination_limits'));
        } else {
            return $tasks->paginate(config('system.settings_system_pagination_limits'));
        }
    }

    /**
     * Create a new record
     * @param int $position new position of the record
     * @return mixed object|bool
     */
    public function create($position = '')
    {
        //validate
        if (!is_numeric($position)) {
            Log::error("validation error - invalid params", ['process' => '[QuoRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
        //    estimate

        $estimate = new $this->estimates;
        $y = date('Y');
        $mm = date('m');
        $vesselIncomigNumber =  DB::table('estimates')->orderBy('bill_estimateid', 'desc')->first();
        // $vesselIncomigNumber= $this->estimates->getOneOrderedByColumn('bill_estimateid','desc');
        // $vesselIncomigNumber = PreAlert::orderBy('id', 'desc')->first();
        if ($vesselIncomigNumber == null) {
            $number = 'OC/QTN/' . $y . '/' . $mm . ''. '01';
        } else {
            $number = str_replace('PTS/QTN/' . $y . '' . $mm . '/', '', $vesselIncomigNumber->bill_estimateid);
            $number =  "OC/QTN/" . $y . '/' . $mm . '' . sprintf("%03d", $number + 1);
            // dd($number);
        }
        // PTS/QTN/date('Y'YYYY/MM/00.$estimate->bill_estimateid
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
        $estimate->bill_status = 'draft';
        $estimate->quotation_no = $number;
        $estimate->bill_exclution =request('bill_exclution');
        $estimate->save();

        //    estimate
        //save new user
        $task = new $this->tasks;
        //echo "<pre>";print_r($_REQUEST);exit;
        //data
        $task->task_creatorid = auth()->id();
        $task->task_projectid = request('bill_clientid');
        $task->task_milestoneid = request('task_milestoneid');
        $task->task_clientid = request('bill_clientid');
        $task->task_date_start =  request('bill_date');
        $task->task_date_due =  request('bill_expiry_date');
        // $task->task_date_start = (!request()->filled('task_date_start')) ? NULL : request('task_date_start');
        // $task->task_date_due = (!request()->filled('task_date_due')) ? NULL : request('task_date_due');
        $task->task_title = request('q_title');
        $task->task_description = request('task_description');
        $task->task_client_visibility ="yes";
        $task->task_billable = (request('task_billable') == 'on') ? 'yes' : 'no';
        $task->task_status = request('bill_categoryid');
        $task->task_priority = request('task_priority') ?? "hight";
        $task->task_position = $position;
        $task->view_id =$estimate->bill_estimateid;
        $task->quo_number=$number;

        //save and return id
        if ($task->save()) {
            return $task->task_id;
        } else {
            // Log::error("record could not be saved - database error", ['process' => '[QuoRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            // return false;

        }
    }
    private function createeEstimate()
    {
        $estimate = new $this->estimates;
        $y = date('Y');
        $mm = date('m');
        $vesselIncomigNumber =  DB::table('estimates')->orderBy('bill_estimateid', 'desc')->first();
        // $vesselIncomigNumber= $this->estimates->getOneOrderedByColumn('bill_estimateid','desc');
        // $vesselIncomigNumber = PreAlert::orderBy('id', 'desc')->first();
        if ($vesselIncomigNumber == null) {
            $number = 'PTS/QTN/' . $y . '/' . $mm . '001';
        } else {
            $number = str_replace('PTS/QTN/' . $y . '/' . $mm . '/', '', $vesselIncomigNumber->bill_estimateid);
            $number =  "PTS/QTN/" . $y . '/' . $mm . '/' . sprintf("%03d", $number + 1);
            // dd($number);
        }
        // PTS/QTN/date('Y'YYYY/MM/00.$estimate->bill_estimateid
        //save new user
        $estimate = new $this->estimates;

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
        $estimate->bill_status = 'draft';
        $estimate->quotation_no = $number;



        //save and return id
        if ($estimate->save()) {
            return $estimate->bill_estimateid;
        } else {
            Log::error("unable to create record - database error", ['process' => '[EstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
    /**
     * update a record
     * @param int $id record id
     * @return mixed bool or id of record
     */
    public function timerStop($id)
    {

        //get the record
        if (!$item = $this->items->find($id)) {
            return false;
        }

        //general
        $item->item_categoryid = request('item_categoryid');
        $item->item_description = request('item_description');
        $item->item_unit = request('item_unit');
        $item->item_rate = request('item_rate');

        //save
        if ($item->save()) {
            return $item->item_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[QuoRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
}
