<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for estimates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Estimates\EstimateSave;
use App\Http\Requests\Estimates\EstimateStoreUpdate;
use App\Repositories\TaskRepository;
use App\Repositories\TimerRepository;
use App\Http\Responses\Common\ChangeCategoryResponse;
use App\Http\Responses\Variation\AcceptResponse;
use App\Http\Responses\Variation\AttachProjectResponse;
use App\Http\Responses\Variation\ChangeCategoryUpdateResponse;
use App\Http\Responses\Variation\ChangeStatusResponse;
use App\Http\Responses\Variation\AddFormResponse;
use App\Http\Responses\Variation\AddFormUpdateResponse;
use App\Http\Responses\Variation\CreateResponse;
use App\Http\Responses\Variation\DeclineResponse;
use App\Http\Responses\Variation\DestroyResponse;
use App\Http\Responses\Variation\EditResponse;
use App\Http\Responses\Variation\IndexResponse;
use App\Http\Responses\Variation\PDFResponse;
use App\Http\Responses\Variation\PublishResponse;
use App\Http\Responses\Variation\PublishRevisedResponse;
use App\Http\Responses\Variation\ResendResponse;
use App\Http\Responses\Variation\SaveResponse;
use App\Http\Responses\Variation\ShowResponse;
use App\Http\Responses\Variation\StoreResponse;
use App\Http\Responses\Variation\UpdateResponse;

use App\Repositories\CategoryRepository;
use App\Repositories\DestroyRepository;
use App\Repositories\EmailerRepository;
use App\Permissions\TaskPermissions;
use App\Http\Responses\Variation\IndexListResponse;

use App\Repositories\VariationGeneratorRepository;
use App\Repositories\VariationRepository;
use App\Repositories\EstimateRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Http\Responses\Variation\IndexKanbanResponse;
use App\Models\variation_templates;
use App\Repositories\LineitemRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\TagRepository;
use App\Repositories\TaxRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Validator;

use App\Models\Estimate;

class Variation extends Controller
{

    /**
     * The estimate repository instance.
     */
    protected $estimaterepo;
    protected $variation_order;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    /**
     * The user repository instance.
     */
    protected $userrepo;
    protected $timerrepo;
    protected $projectrepo;
    /**
     * The tax repository instance.
     */
    protected $taxrepo;

    /**
     * The line item repository instance.
     */
    protected $lineitemrepo;


    /**
     * The unit repository instance.
     */
    protected $unitrepo;
    protected $taskrepo;
    protected $taskpermissions;

    /**
     * The event tracking repository instance.
     */
    protected $trackingrepo;

    /**
     * The event repository instance.
     */
    protected $eventrepo;

    /**
     * The emailer repository
     */
    protected $emailerrepo;

    /**
     * The estimate generator repository
     */
    protected $estimategenerator;

    public function __construct(
        EstimateRepository $estimaterepo,
        VariationRepository $variation_order,
        ProjectRepository $projectrepo,
        TagRepository $tagrepo,
        UserRepository $userrepo,
        TaxRepository $taxrepo,
        LineitemRepository $lineitemrepo,
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        EmailerRepository $emailerrepo,
        VariationGeneratorRepository $estimategenerator,
        TaskPermissions $taskpermissions,
        TimerRepository $timerrepo,
        TaskRepository $taskrepo,

    ) {

        //parent
        parent::__construct();
        $this->projectrepo = $projectrepo;
        //authenticated
        $this->middleware('auth');
        //route middleware

        $this->middleware('variationMiddlewareIndex')->only([
            'index',
            'update',
            'store',
            'changeCategoryUpdate',
            'attachProjectUpdate',
            'changeStatusUpdate',
        ]);

        $this->middleware('variationMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('variationMiddlewareEdit')->only([
            'edit',
            'update',
            'emailClient',
            'dettachProject',
            'attachProject',
            'attachProjectUpdate',
            'convertToInvoice',
            'changeStatusUpdate',
            'changeStatus',
            'saveInvoice',
            'changeStatusUpdate',
        ]);

        $this->middleware('variationMiddlewareShow')->only([
            'show',
            'downloadPDF',
            'acceptEstimate',
            'declineEstimate',
        ]);

        $this->middleware('variationMiddlewareDestroy')->only(['destroy']);

        //only needed for the [action] methods
        $this->middleware('variationMiddlewareBulkEdit')->only(['changeCategoryUpdate']);

        //repos
        $this->estimaterepo = $estimaterepo;
        $this->variation_order = $variation_order;
        $this->tagrepo = $tagrepo;
        $this->userrepo = $userrepo;
        $this->lineitemrepo = $lineitemrepo;
        $this->taxrepo = $taxrepo;
        $this->eventrepo = $eventrepo;
        $this->trackingrepo = $trackingrepo;
        $this->emailerrepo = $emailerrepo;
        $this->estimategenerator = $estimategenerator;
        $this->taskrepo = $taskrepo;
        $this->timerrepo = $timerrepo;
    }

    /**
     * Display a listing of estimates
     * @param object ProjectRepository instance of the repository
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function index(ProjectRepository $projectrepo, CategoryRepository $categoryrepo)
    {

        $projects = [];
        //    die;
        //get estimate
        $estimates = $this->variation_order->search(null, ['v_id' => request('variationresource_id')]);
        // print_r($estimates);
        // die;

        //get all categories (type: estimate) - for filter panel
        $categories = $categoryrepo->get('estimate');

        //get all tags (type: lead) - for filter panel
        $tags = $this->tagrepo->getByType('estimate');

        //get clients project list
        if (config('visibility.filter_panel_clients_projects')) {
            if (is_numeric(request('variationresource_id'))) {
                $projects = $projectrepo->search('', ['project_clientid' => request('variationresource_id')]);
            }
        }
        if (request()->filled('taskresource_id') && request('taskresource_type') == 'project') {
            $milestones = \App\Models\Milestone::Where('milestone_projectid', request('taskresource_id'))->get();
        }
        $tasks = $this->taskrepo->search();


        //reponse payload
        $payload = [
            'page' => $this->pageSettings('estimates'),
            'estimates' => $estimates,
            // 'milestones' => $milestones,
            'projects' => $projects,
            'stats' => $this->statsWidget(),
            'categories' => $categories,
            'tags' => $tags,
            'tasks' => $tasks,
        ];

        return new IndexResponse($payload);
    }

    public function indexList($projectrepo, $categoryrepo)
    {

        $projects = [];

        //get estimate
        $estimates = $this->estimaterepo->search();

        //get all categories (type: estimate) - for filter panel
        $categories = $categoryrepo->get('estimate');

        //get all tags (type: lead) - for filter panel
        $tags = $this->tagrepo->getByType('estimate');

        //get clients project list
        if (config('visibility.filter_panel_clients_projects')) {
            if (is_numeric(request('variationresource_id'))) {
                $projects = $projectrepo->search('', ['project_clientid' => request('variationresource_id')]);
            }
        }
        if (request()->filled('taskresource_id') && request('taskresource_type') == 'project') {
            $milestones = \App\Models\Milestone::Where('milestone_projectid', request('taskresource_id'))->get();
        }
        $tasks = $this->taskrepo->search();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('estimates'),
            'estimates' => $estimates,
            // 'milestones' => $milestones,
            'projects' => $projects,
            'stats' => $this->statsWidget(),
            'categories' => $categories,
            'tags' => $tags,
            'tasks' => $tasks,
        ];

        //show the view
        return $payload;
    }
    public function indexKanban($projectrepo, $categoryrepo)
    {
        $categories = $categoryrepo->get('estimate');
        //get tasks
        $tasks = $this->taskrepo->search();

        //count rows
        $count = $tasks->total();

        //process for timers
        $this->processTasks($tasks);

        //apply some permissions
        if ($tasks) {
            foreach ($tasks as $task) {
                $this->applyPermissions($task);
            }
        }
        $projects = [];
        //defaults
        $milestones = [];

        $boards = $this->taskBoards();

        //basic page settings
        $page = $this->pageSettings('Kanwan', []);

        //page setting for embedded view
        if (request('source') == 'ext') {
            $page = $this->pageSettings('ext', []);
        }
        $estimates = $this->estimaterepo->search();

        //get all tags (type: lead) - for filter panel
        $tags = $this->tagrepo->getByType('task');

        //get all milestones if viewing from project page (for use in filter panel)
        if (request()->filled('taskresource_id') && request('taskresource_type') == 'project') {
            $milestones = \App\Models\Milestone::Where('milestone_projectid', request('taskresource_id'))->get();
        }
        if (config('visibility.filter_panel_clients_projects')) {
            if (is_numeric(request('variationresource_id'))) {
                $projects = $projectrepo->search('', ['project_clientid' => request('variationresource_id')]);
            }
        }

        //reponse payload
        $payload = [
            'page' => $page,
            'boards' => $boards,
            'milestones' => $milestones,
            'stats' => $this->statsWidget(),
            'tags' => $tags,
            'tasks' => $tasks,
            'page' => $this->pageSettings('estimates'),
            'estimates' => $estimates,
            'projects' => $projects,
            'stats' => $this->statsWidget(),
            'categories' => $categories,
            'tags' => $tags,
        ];

        return $payload;
    }

    /**
     * Show the form for creating a new estimate.
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo)
    {

        //estimate categories
        $categories = $categoryrepo->get('estimate');
        $estimate = Estimate::where('bill_projectid', request('variationresource_id'))->get();


        // $estimate = $this->estimaterepo->search(request('variationresource_id'));

        //get tags
        $tags = $this->tagrepo->getByType('estimate');

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
            'categories' => $categories,
            'tags' => $tags,
            'estimate' => $estimate[0],
        ];

        //show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created estimate  in storage.
     * @param object EstimateStoreUpdate
     * @return \Illuminate\Http\Response
     */
    public function store(EstimateStoreUpdate $request)
    {
        // echo "ggstore";die;

        //create the estimate
        if (!$bill_estimateid = $this->variation_order->create()) {
            abort(409);
        }

        //add tags
        $this->tagrepo->add('estimate', $bill_estimateid);

        //reponse payload
        $payload = [
            'id' => $bill_estimateid,
            'bill_projectid' => request('bill_projectid'),
        ];

        //process reponse
        return new StoreResponse($payload);
    }
    private function processTasks($tasks = '')
    {
        //sanity - make sure this is a valid tasks object
        if ($tasks instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            foreach ($tasks as $task) {
                $this->processTask($task);
            }
        }
    }
    private function processTask($task = '')
    {

        //sanity - make sure this is a valid task object
        if ($task instanceof \App\Models\Task) {

            //default values
            $task->assigned_to_me = false;
            $task->running_timers = false;
            $task->timer_current_status = false;
            $task->has_attachments = false;
            $task->has_comments = false;
            $task->has_checklist = false;

            //check if the task is assigned to me
            foreach ($task->assigned as $user) {
                if ($user->id == auth()->id()) {
                    //its assigned to me
                    $task->assigned_to_me = true;
                }
            }

            $task->has_attachments = ($task->attachments_count > 0) ? true : false;
            $task->has_comments = ($task->comments_count > 0) ? true : false;
            $task->has_checklist = ($task->checklists_count > 0) ? true : false;

            //check if there are any running timers
            foreach ($task->timers as $timer) {
                if ($timer->timer_status == 'running') {
                    //its has a running timer
                    $task->running_timers = true;
                    if ($timer->timer_creatorid == auth()->id()) {
                        $task->timer_current_status = true;
                    }
                }
            }

            //get users current/refreshed time for the task (if applcable)
            $task->my_time = $this->timerrepo->sumTimers($task->task_id, auth()->id());
        }
    }
    private function applyPermissions($task = '')
    {

        //sanity - make sure this is a valid task object
        if ($task instanceof \App\Models\Task) {
            //edit permissions
            // $task->permission_edit_task = $this->taskpermissions->check('edit', $task);
            // //delete permissions
            // $task->permission_delete_task = $this->taskpermissions->check('delete', $task);
            // //delete participate
            // $task->permission_participate = $this->taskpermissions->check('participate', $task);
            // //super user
            // $task->permission_assign_users = $this->taskpermissions->check('assign-users', $task);
            // //super user
            // $task->permission_super_user = $this->taskpermissions->check('super-user', $task);
        }
    }
    private function taskBoards()
    {

        $list = [
            'new' => [],
            'in_progress' => [],
            'testing' => [],
            'awaiting_feedback' => [],
            'completed' => [],
        ];

        foreach ($list as $key => $value) {
            request()->merge([
                'filter_single_task_status' => $key,
                'query_type' => 'kanban',
            ]);

            //get tasks
            $tasks = $this->taskrepo->search();

            //count rows
            $count = $tasks->total();

            //process for timers
            $this->processTasks($tasks);

            //apply some permissions
            if ($tasks) {
                foreach ($tasks as $task) {
                    $this->applyPermissions($task);
                }
            }

            //initial loadmore button
            if ($tasks->currentPage() < $tasks->lastPage()) {
                $boards[$key]['load_more'] = '';
                $boards[$key]['load_more_url'] = loadMoreButtonUrl($tasks->currentPage() + 1, $key);
            } else {
                $boards[$key]['load_more'] = 'hidden';
                $boards[$key]['load_more_url'] = '';
            }

            $boards[$key]['name'] = $key;
            $boards[$key]['tasks'] = $tasks;
        }

        return $boards;
    }

    /**
     * Display the specified estimate.
     * @param int $id estimate  id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $estimate_id)
    {
        // echo $estimate_id.'/';die;
        //get invoice object payload
        if (!$payload = $this->estimategenerator->generate($estimate_id)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }
        // print_r($payload['bill']['bill_projectid']);die;
        $data = DB::table('billing_addresses')->where('client_id', $payload['bill']->bill_clientid)->get();

        //append to payload
        $payload['page'] = $this->pageSettings('estimate', $payload['bill']);
        $payload['d_add'] = $data;

        //mark events as read
        // \App\Models\EventTracking::where('parent_id', $id)
        //     ->where('parent_type', 'estimate')
        //     ->where('eventtracking_userid', auth()->id())
        //     ->update(['eventtracking_status' => 'readj']);

        //pdf estimate
        // dd($payload);
        // exit;
        if (request()->segment(3) == 'pdf') {
            return new PDFResponse($payload);
        }

        //process reponse
        return new ShowResponse($payload);
    }

    /**
     * save estimate changes, when an ioice is being edited
     * @param object EstimateSave
     * @return \Illuminate\Http\Response
     */
    public function saveEstimate(EstimateSave $request, $id)
    {
        // print_r($request);die;

        try {
            // print_r(request()->segment(2));
            // die;
            // Get the estimate
            $estimates = $this->variation_order->search($id);
            $estimate = $estimates->first();

            // Save each line item in the database
            $this->variation_order->saveLineItems($id);
            $this->variation_order->SaveProductItel($id);
            if ($this->savevariation_templatess($request, $estimate->vo_id)) {
                $totalAmount = DB::table('variation_templates')->where('estimates_id', $estimate->vo_id)->sum(DB::raw('amount + (amount * 0.1)'));
                $calculatedAmount = $totalAmount * 0.05 + $totalAmount;


                DB::table('variation_order')->where('vo_id', $estimate->vo_id)->update(['bill_final_amount' => $calculatedAmount]);
            }

            // Update taxes
            $this->updateEstimateTax($id);

            // Redirect to the estimate show page
            return response()->json([
                'redirect_url' => url("/project/$request->project_id/estimates/$id")
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving estimate: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }


    // public function savevariation_templatess(Request $request, $id)
    // {
    //     try {
    //         // Validate the request data
    //         $request->validate([
    //             'description.*' => 'required|string',
    //             'unit.*' => 'required|string',
    //             'qty.*' => 'required|numeric|min:1',
    //             'labour.*' => 'required',
    //             'material.*' => 'required',
    //             'misc.*' => 'required',
    //             'wastage_percent.*' => 'required|numeric',
    //             'wastage_amount.*' => 'required|numeric',
    //             'sc.*' => 'required|numeric',
    //             'total.*' => 'required|numeric',
    //             'amount.*' => 'required|numeric',
    //             'template_id.*' => 'required|numeric',
    //         ]);

    //         // Iterate over the request data
    //         foreach ($request->description as $index => $description) {
    //             if (isset($request->id[$index])) {
    //                 $lineItem = variation_templates::find($request->id[$index]);
    //                 if ($lineItem) {
    //                     $lineItem->update([
    //                         'description' => $description,
    //                         'unit' => $request->unit[$index],
    //                         'quotation_no' => $request->quotation_no[$index],
    //                         'template_id' => $request->template_id[$index],
    //                         'qty' => $request->qty[$index],
    //                         'labour' => $request->labour[$index],
    //                         'material' => $request->material[$index],
    //                         'misc' => $request->misc[$index],
    //                         'wastage_percent' => $request->wastage_percent[$index],
    //                         'wastage_amount' => $request->wastage_amount[$index],
    //                         'sc' => $request->sc[$index],
    //                         'total' => $request->total[$index],
    //                         'amount' => $request->amount[$index],
    //                     ]);
    //                 } else {
    //                     variation_templates::create([
    //                         'description' => $description,
    //                         'quotation_no' => $request->quotation_no[$index],
    //                         'template_id' => $request->template_id[$index],
    //                         'unit' => $request->unit[$index],
    //                         'qty' => $request->qty[$index],
    //                         'labour' => $request->labour[$index],
    //                         'material' => $request->material[$index],
    //                         'misc' => $request->misc[$index],
    //                         'wastage_percent' => $request->wastage_percent[$index],
    //                         'wastage_amount' => $request->wastage_amount[$index],
    //                         'sc' => $request->sc[$index],
    //                         'total' => $request->total[$index],
    //                         'amount' => $request->amount[$index],
    //                     ]);
    //                 }
    //             } else {
    //                 variation_templates::create([
    //                     'description' => $description,
    //                     'quotation_no' => $request->quotation_no[$index],
    //                     'template_id' => $request->template_id[$index],
    //                     'unit' => $request->unit[$index],
    //                     'qty' => $request->qty[$index],
    //                     'labour' => $request->labour[$index],
    //                     'material' => $request->material[$index],
    //                     'misc' => $request->misc[$index],
    //                     'wastage_percent' => $request->wastage_percent[$index],
    //                     'wastage_amount' => $request->wastage_amount[$index],
    //                     'sc' => $request->sc[$index],
    //                     'total' => $request->total[$index],
    //                     'amount' => $request->amount[$index],
    //                 ]);
    //             }
    //         }

    //         return response()->json(['success' => 'Quotation templates saved successfully']);
    //     } catch (\Exception $e) {
    //         Log::error('Error saving quotation templates: ' . $e->getMessage());
    //         return response()->json(['error' => 'An unexpected error occurred. Please try again.'], 500);
    //     }
    // }
    public function savevariation_templatess(Request $request, $id)
    {
        try {
            Log::info('savevariation_templatess method called', ['id' => $id, 'request' => $request->all()]);
            $this->variation_order->update($id);

            // Delete all existing records for this estimate/template ID before inserting new ones
            variation_templates::where('estimates_id', $id)->delete();
            // Initialize an array to track processed hashes (optional, to prevent duplicates within the same request)
            $processedIds = [];
// die;
            // Iterate over the request data and insert new records
            foreach ($request->template_id as $index => $template_id) {

                // Prepare line item data
                $lineItemData = [
                    'description' => $request->description[$index] ?? null,
                    'unit' => $request->unit[$index] ?? null,
                    'quotation_no' => $request->quotation_no[$index] ?? null,
                    'qty' => $request->qty[$index] ?? null,
                    'labour' => $request->labour[$index] ?? null,
                    'material' => $request->material[$index] ?? null,
                    'misc' => $request->misc[$index] ?? null,
                    'wastage_percent' => $request->wastage_percent[$index] ?? null,
                    'wastage_amount' => $request->wastage_amount[$index] ?? null,
                    'contractor_percent' => $request->contractor_percent[$index] ?? null,
                    'contractor_amount' => $request->contractor_amount[$index] ?? null,
                    'sc' => $request->sc[$index] ?? null,
                    'net_rate' => $request->net_rate[$index] ?? null,
                    'rate' => $request->rate[$index] ?? null,
                    'total' => $request->total[$index] ?? null,
                    'amount' => $request->amount[$index] ?? null,
                    'type' => $request->type[$index] ?? null,
                    'template_id' => $template_id,
                    'estimates_id' => $id,
                ];
                // variation_templates::
                DB::table('variation_templates')->insert($lineItemData);
                // where('qty', '')->where('labour','')->where('material','')->where('misc','')->where('wastage_percent','')->where('wastage_amount','')->where('sc','')->
                // where('total', null)->delete();
                // Check if this record is already processed (optional)
                // $hash = md5(serialize($lineItemData));
                // if (in_array($hash, $processedIds)) {
                    //     Log::warning('Duplicate record prevented', ['lineItemData' => $lineItemData]);
                //     continue;
                // }

                // Insert the new record into the database
                // variation_templates::create($lineItemData);


                // Track the processed data
                // $processedIds[] = $hash;
            }
            // print_r($lineItemData);die;

            return response()->json(['success' => 'Quotation templates saved successfully']);
        } catch (\Exception $e) {
            Log::error('Error saving quotation templates', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }

    public function convertToProject(Request $request, $id)
    {

        $quotation = DB::table('variation_order')->where('vo_id', request()->segment(4))->first();
        // dd($quotation);
        // $project = new Project();
        // // $project->quotation_id = $quotation->task_id;
        // // $project->project_companyid = $quotation->task_companyid;
        // $project->quotation_no = $quotation->quo_number;
        // $project->project_clientid = $quotation->task_clientid;
        // $project->project_title = $quotation->task_title;
        // $project->project_date_start = $quotation->task_date_start;
        // $project->project_date_due = $quotation->task_date_due;
        // // $project->project_sn = $quotation->AMO;
        // $project->save();

        $dd = DB::table('variation_order')->where(['vo_id' => request()->segment(4)])->update(['bill_projectid' => request()->segment(2), 'is_project_creates' => "Yes"]);



        $template_data = DB::table('variation_templates')->where('estimates_id', $quotation->vo_id)->get();

        // $lineitem = Lineitem::where('lineitemresource_id', $id)->get();
        // dd($template_data);

        foreach ($template_data as $line) {
            $task = new Task();
            $task->task_cat_id = $line->template_id;
            $task->task_title = $line->description;
            $task->task_projectid = request()->segment(2);
            $task->task_clientid =  $quotation->bill_clientid;
            $task->save();
        }
        $estimates = $this->estimaterepo->search(request()->route('estimate'));

        $payload = [
            'estimates' => $estimates,
            'bill_estimateid' => request()->route('estimate'),
            'bill_project_id' => request()->segment(2),
            'stats' => $this->statsWidget(),
        ];
        //response
        return new UpdateResponse($payload);
    }


    public function F_waitingforappoval()
    {

        $status = "waiting_for_approval";
        $xyz = DB::table('estimates')->where('bill_estimateid', request('id'))->update(['bill_status' => $status]);
        if ($xyz) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * update the tax for an estimate
     * (1) delete existing estimate taxes
     * (2) for summary taxes - save new taxes
     * @param int $bill_estimateid
     * @return \Illuminate\Http\Response
     */
    private function updateEstimateTax($bill_estimateid = '')
    {

        //delete current estimate taxes
        \App\Models\Tax::Where('taxresource_type', 'estimate')
            ->where('taxresource_id', $bill_estimateid)
            ->delete();

        //save taxes [summary taxes]
        if (is_array(request('bill_logic_taxes'))) {
            foreach (request('bill_logic_taxes') as $tax) {
                //get data elements
                $list = explode('|', $tax);
                $data = [
                    'tax_taxrateid' => $list[2],
                    'tax_name' => $list[1],
                    'tax_rate' => $list[0],
                    'taxresource_type' => 'estimate',
                    'taxresource_id' => $bill_estimateid,
                ];
                $this->taxrepo->create($data);
            }
        }
    }

    /**
     * publish an estimate
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function publishEstimate($id)
    {

        //generate the invoice
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //estimate
        $estimate = $payload['bill'];

        //validate current status
        if ($estimate->bill_status != 'draft') {
            abort(409, __('lang.estimate_already_piblished'));
        }

        /** ----------------------------------------------
         * record event [comment]
         * ----------------------------------------------*/
        $data = [
            'event_creatorid' => auth()->id(),
            'event_item' => 'estimate',
            'event_item_id' => $estimate->bill_estimateid,
            'event_item_lang' => 'event_created_estimate',
            'event_item_content' => __('lang.estimate') . ' - ' . $estimate->quotetation_no,
            'event_item_content2' => '',
            'event_parent_type' => 'estimate',
            'event_parent_id' => $estimate->bill_estimateid,
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
            //record notification
            $emailusers = $this->trackingrepo->recordEvent($data, $users, $event_id);
        }

        /** ----------------------------------------------
         * send email [queued]
         * ----------------------------------------------*/
        if (isset($emailusers) && is_array($emailusers)) {
            //send to users
            if ($users = \App\Models\User::WhereIn('id', $emailusers)->get()) {
                foreach ($users as $user) {
                    $mail = new \App\Mail\PublishEstimate($user, [], $estimate);
                    $mail->build();
                }
            }
        }

        //update estimate status
        \App\Models\Estimate::where('bill_estimateid', $estimate->bill_estimateid)
            ->update(['bill_status' => 'new']);

        //response
        return new PublishResponse();
    }

    /**
     * publish a revised estimate
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function publishRevisedEstimate($id)
    {

        //generate the invoice
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //estimate
        $estimate = $payload['bill'];

        //validate current status
        if ($estimate->bill_status != 'declined') {
            abort(409, __('lang.action_only_available_on_declined_estimates'));
        }

        //check if estimate is not already expired
        $bill_expiry_date = \Carbon\Carbon::parse($estimate->bill_expiry_date);
        if ($bill_expiry_date->diffInDays(today(), false) > 0) {
            abort(409, __('lang.estimate_has_expired_update_date'));
        }

        /** ----------------------------------------------
         * record event [comment]
         * ----------------------------------------------*/
        $data = [
            'event_creatorid' => auth()->id(),
            'event_item' => 'estimate',
            'event_item_id' => $estimate->bill_estimateid,
            'event_item_lang' => 'event_revised_estimate',
            'event_item_content' => __('lang.estimate') . ' - ' . $estimate->formatted_bill_estimateid,
            'event_item_content2' => '',
            'event_parent_type' => 'estimate',
            'event_parent_id' => $estimate->bill_estimateid,
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
            //record notification
            $emailusers = $this->trackingrepo->recordEvent($data, $users, $event_id);
        }

        /** ----------------------------------------------
         * send email [queued]
         * ----------------------------------------------*/
        if (isset($emailusers) && is_array($emailusers)) {
            //send to users
            if ($users = \App\Models\User::WhereIn('id', $emailusers)->get()) {
                foreach ($users as $user) {
                    $mail = new \App\Mail\PublishRevisedEstimate($user, [], $estimate);
                    $mail->build();
                }
            }
        }

        //update estimate status
        \App\Models\Estimate::where('bill_estimateid', $estimate->bill_estimateid)
            ->update(['bill_status' => 'revised']);

        //response
        return new PublishRevisedResponse();
    }

    /**
     * resend an estimate via email
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function resendEstimate($id)
    {

        //generate the estimate
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //estimate
        $estimate = $payload['bill'];

        //validate current status
        if ($estimate->bill_status == 'draft') {
            abort(409, __('lang.estimate_still_draft'));
        }
        // this is costomize code
        $mail = new \App\Mail\PublishEstimate($estimate, [], $estimate);
        $mail->build();
        // this is costomize code

        /** ----------------------------------------------
         * send email [queued]
         * ----------------------------------------------*/
        // $users = $this->userrepo->getClientUsers($estimate->bill_clientid, 'owner', 'collection');
        // print_r($users);die;
        // foreach ($users as $user) {
        //     $mail = new \App\Mail\PublishEstimate($user, [], $estimate);
        //     $mail->build();
        // }

        //response
        return new ResendResponse();
    }

    /**
     * customer accepting estimate
     * @param int $id estimate id
     * @return \Illuminate\Http\Response
     */
    public function acceptEstimate($id)
    {

        //generate the estimate
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //estimate
        $estimate = $payload['bill'];

        //validate current status
        if (!in_array($estimate->bill_status, ['new', 'revised'])) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //update estimate status
        \App\Models\Estimate::where('bill_estimateid', $estimate->bill_estimateid)
            ->update(['bill_status' => 'accepted']);

        /** ----------------------------------------------
         * record event [comment]
         * see database table to details of each key
         * ----------------------------------------------*/
        $data = [
            'event_creatorid' => auth()->id(),
            'event_item' => 'estimate',
            'event_item_id' => $estimate->bill_estimateid,
            'event_item_lang' => 'event_accepted_estimate',
            'event_item_content' => __('lang.estimate') . ' - ' . $estimate->formatted_bill_estimateid,
            'event_item_content2' => '',
            'event_clientid' => $estimate->bill_clientid,
            'event_parent_type' => 'estimate',
            'event_parent_id' => $estimate->bill_estimateid,
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
            //get estimate team users, with billing app notifications enabled
            $users = $this->userrepo->mailingListTeamEstimates('app');
            //record notification
            $this->trackingrepo->recordEvent($data, $users, $event_id);
        }

        /** --------------------------------------------------------
         * send email [queued]
         * - estimate users, with biling email preference enabled
         * --------------------------------------------------------*/
        $users = $this->userrepo->mailingListTeamEstimates('email');
        foreach ($users as $user) {
            $mail = new \App\Mail\AcceptEstimate($user, [], $estimate);
            $mail->build();
        }

        //response
        return new AcceptResponse();
    }

    /**
     * customer declining an estimate
     * @param int $id estimate id
     * @return \Illuminate\Http\Response
     */
    public function declineEstimate($id)
    {

        //generate the estimate
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_loading_item'));
        }

        //estimate
        $estimate = $payload['bill'];

        //validate current status
        if (!in_array($estimate->bill_status, ['new', 'revised'])) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //update estimate status
        \App\Models\Estimate::where('bill_estimateid', $estimate->bill_estimateid)
            ->update(['bill_status' => 'declined']);

        /** ----------------------------------------------
         * record event [comment]
         * see database table to details of each key
         * ----------------------------------------------*/
        $data = [
            'event_creatorid' => auth()->id(),
            'event_item' => 'estimate',
            'event_item_id' => $estimate->bill_estimateid,
            'event_item_lang' => 'event_declined_estimate',
            'event_item_content' => __('lang.estimate') . ' - ' . $estimate->formatted_bill_estimateid,
            'event_item_content2' => '',
            'event_clientid' => $estimate->bill_clientid,
            'event_parent_type' => 'estimate',
            'event_parent_id' => $estimate->bill_estimateid,
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
            //get estimate team users, with billing app notifications enabled
            $users = $this->userrepo->mailingListTeamEstimates('app');
            //record notification
            $this->trackingrepo->recordEvent($data, $users, $event_id);
        }

        /** --------------------------------------------------------
         * send email [queued]
         * - estimate users, with biling email preference enabled
         * --------------------------------------------------------*/
        $users = $this->userrepo->mailingListTeamEstimates('email');
        foreach ($users as $user) {
            $mail = new \App\Mail\DeclineEstimate($user, [], $estimate);
            $mail->build();
        }

        //response
        return new DeclineResponse();
    }

    /**
     * Show the form for editing the specified estimate.
     * @param object CategoryRepository instance of the repository
     * @param int $id estimate  id
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryRepository $categoryrepo, $id)
    {
        // echo "1";exit;
        //get the project
        $estimate = $this->estimaterepo->search($id);
        //client categories
        $categories = $categoryrepo->get('estimate');

        //get tags
        $tags_resource = $this->tagrepo->getByResource('estimate', $id);
        $tags_user = $this->tagrepo->getByType('estimate');
        $tags = $tags_resource->merge($tags_user);

        //not found
        if (!$estimate = $estimate->first()) {
            abort(409, __('lang.estimate_not_found'));
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'estimate' => $estimate,
            'categories' => $categories,
            'tags' => $tags,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Update the specified estimate in storage.
     * @param int $id estimate  id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        // echo "hi";die;
        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'bill_date' => 'required|date',
            'bill_expiry_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value != '' && request('bill_date') != '' && (strtotime($value) < strtotime(request('bill_date')))) {
                        return $fail(__('lang.expiry_date_must_be_after_estimate_date'));
                    }
                }
            ],
            // 'bill_categoryid' => [
            //     'required',
            //     Rule::exists('categories', 'category_id'),
            // ],
        ], $messages);

        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //update
        if (!$this->estimaterepo->update($id)) {
            abort(409);
        }

        //delete & update tags
        $this->tagrepo->delete('estimate', $id);
        $this->tagrepo->add('estimate', $id);

        //get project
        $estimates = $this->estimaterepo->search($id);

        //reponse payload
        $payload = [
            'estimates' => $estimates,
            'stats' => $this->statsWidget(),
        ];

        //generate a response
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified estimate from storage.
     * @param object DestroyRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function delete_variation()
    {
        $id_of_vo = request()->segment(4);

        $data_vo = DB::table('variation_order')->where('vo_id', $id_of_vo)->delete();
        $data = DB::table('variation_templates')->where('estimates_id', $id_of_vo)->delete();



        $payload = [
            'allrows' => 5,
            'stats' => $this->statsWidget(),
        ];

        //generate a response
        return new DestroyResponse($payload);

        //generate a response

    }


    public function deletedata($id)
    {
        $data = variation_templates::find($id);
        if ($data) {
            $data->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false], 404);
        }
    }

    /**
     * Show the form for changing estimate category
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategory(CategoryRepository $categoryrepo)
    {


        //get all estimate categories
        $categories = $categoryrepo->get('estimate');

        //reponse payload
        $payload = [
            'categories' => $categories,
        ];

        //show the form
        return new ChangeCategoryResponse($payload);
    }

    /**
     * update the estimate category
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function changeCategoryUpdate(CategoryRepository $categoryrepo)
    {

        //validate the category exists
        // if (!\App\Models\Category::Where('category_name', request('category'))
        //     ->Where('category_type', 'estimate')
        //     ->first()) {
        //     abort(409, __('lang.item_not_found'));
        // }

        $dd = DB::table('quos')->where(['task_id' => request('id')])->update(['task_status' => request('category')]);
        $dd = DB::table('estimates')->where(['bill_estimateid' => request('id')])->update(['bill_status' => request('category')]);
        //update each estimate
        $allrows = array();
        foreach (request('ids') as $bill_estimateid => $value) {
            if ($value == 'on') {
                $estimate = \App\Models\Estimate::Where('bill_estimateid', $bill_estimateid)->first();
                //update the category
                // $estimate->bill_categoryid = request('category');
                $estimate->save();
                //get the estimate in rendering friendly format
                $estimates = $this->estimaterepo->search($bill_estimateid);
                //add to array
                $allrows[] = $estimates;
            }
        }

        //reponse payload
        $payload = [
            'allrows' => $allrows,
        ];

        //show the form
        return new ChangeCategoryUpdateResponse($payload);
    }

    /**
     * Show the form for changing an estimate status
     * @return \Illuminate\Http\Response
     */
    public function changeStatus()
    {


        //get the estimate
        $estimate = \App\Models\Estimate::Where('bill_estimateid', request()->route('estimate'))->first();

        //reponse payload
        $payload = [
            'estimate' => $estimate,
        ];

        //show the form
        return new ChangeStatusResponse($payload);
    }
    public function AddForm()
    {

        //get the estimate
        $estimate = \App\Models\Estimate::Where('bill_estimateid', request()->route('estimate'))->first();

        $supplier = DB::table('product')->get();
        //reponse payload
        $payload = [
            'estimate' => $estimate,
            'supplier' => $supplier
        ];

        //show the form
        return new AddFormResponse($payload);
    }
    public function AddFormUpdate()
    {
        $messages = [];
        $validator = Validator::make(request()->all(), [
            'project_cat' => 'required',
            'project_title' => 'required',
            'project_date_start' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }
        if (!$project_id = $this->projectrepo->create()) {
            abort(409);
        }

        $estimate = \App\Models\Estimate::Where('bill_estimateid', request()->route('estimate'))->first();
        //reponse payload
        $payload = [
            'estimate' => $estimate,

        ];
        return new AddFormUpdateResponse($payload);
    }
    public function Get_supplierdata()
    {

        $gst = DB::table('xin_gst')->get();


        $result = DB::table('xin_suppliers')
            ->join('xin_supplier_item_mapping', 'xin_suppliers.supplier_id', '=', 'xin_supplier_item_mapping.supplier_id')
            ->select('xin_suppliers.supplier_name', 'xin_supplier_item_mapping.supplier_item_price', 'xin_supplier_item_mapping.supplier_item_id', 'xin_suppliers.supplier_id',  'xin_supplier_item_mapping.supplier_item_description')
            ->where('xin_supplier_item_mapping.supplier_item_name', request('id'))
            ->get();

        $resultArray['to'] = $result->toArray();
        $resultArray['g'] = $gst;
        return json_encode($resultArray);
    }
    public function updateapproveEstimate()
    {
        // echo "1";
        // die;
        // if (!$estimate = $this->estimates->find($id)) {
        //     Log::error("unable to load estimate record", ['process' => '[VariationRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'estimate_id' => $id ?? '']);
        //     return false;
        // }

        // $estimate->bill_status="new";
        // $estimate->save();

    }

    /**
     * change estimate status
     * @return \Illuminate\Http\Response
     */
    public function changeStatusUpdate()
    {
        // echo "12";die;
        if (request('iiid')) {

            $estimate = \App\Models\Estimate::Where('bill_estimateid', request('iiid'))->first();
            $task = \App\Models\Quos::Where('task_id', request('iiid'))->first();

            //update the estimate
            $task->task_status = "Approval_from_Management";
            $task->save();
            $estimate->bill_status = "new";
            $estimate->save();
            return 1;
        } else {

            //validate the estimate exists
            $estimate = \App\Models\Estimate::Where('bill_estimateid', request()->route('estimate'))->first();
            $task = \App\Models\Quos::Where('task_id', request()->route('estimate'))->first();

            //update the estimate
            $task->task_status = (request('bill_status') == "new") ? "Approval_from_Management" : request('bill_status');
            $task->save();
            $estimate->bill_status = request('bill_status');
            $estimate->save();
        }
        //get refreshed estimate
        $estimates = $this->estimaterepo->search(request()->route('estimate'));

        //reponse payload
        $payload = [
            'estimates' => $estimates,
            'bill_estimateid' => request()->route('estimate'),
            'stats' => $this->statsWidget(),
        ];
        // echo "gg";die;
        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * [UPCOMING]
     * convert to an estimate
     * @return \Illuminate\Http\Response
     */
    public function convertToInvoice()
    {

        //validate the estimate exists
        $estimate = \App\Models\Estimate::Where('bill_estimateid', request('id'))->first();
    }

    /**
     * Show the form for attaching a project to an estimate
     * @return \Illuminate\Http\Response
     */
    public function attachProject()
    {

        //get client id
        $client_id = request('client_id');

        //reponse payload
        $payload = [
            'projects_feed_url' => url("/feed/projects?ref=clients_projects&client_id=$client_id"),
        ];

        //show the form
        return new AttachProjectResponse($payload);
    }

    /**
     * attach a project to an estimate
     * @return \Illuminate\Http\Response
     */
    public function attachProjectUpdate($id)
    {

        //validate the estimate exists


        // $estimate = \App\Models\estimate::Where('bill_estimateid', request()->route('estimates'))->first();
        $estimate = \App\Models\estimate::Where('bill_estimateid', $id)->first();

        //validate the project exists
        if (!$project = \App\Models\Project::Where('project_id', request('attach_project_id'))->first()) {
            abort(409, __('lang.item_not_found'));
        }

        //update the estimate
        $estimate->bill_projectid = request('attach_project_id');
        $estimate->bill_clientid = $project->project_clientid;
        $estimate->save();
        //get refreshed estimate
        // $estimates = $this->estimaterepo->search(request()->route('estimates'));
        $estimates = $this->estimaterepo->search($id);
        $estimate = $estimates->first();
        //refresh estimate
        $this->estimaterepo->refreshestimate($estimate);

        //reponse payload
        $payload = [
            'estimates' => $estimates,
        ];

        //show the form

        return new UpdateResponse($payload);
    }

    /**
     * dettach estimate from a project
     * @return \Illuminate\Http\Response
     */
    public function dettachProject()
    {

        //validate the estimate exists
        $estimate = \App\Models\estimate::Where('bill_estimateid', request()->route('estimate'))->first();

        //update the estimate
        $estimate->bill_projectid = null;
        $estimate->save();

        //get refreshed estimate
        $estimates = $this->estimaterepo->search(request()->route('estimate'));

        //reponse payload
        $payload = [
            'estimates' => $estimates,
        ];

        //show the form
        return new UpdateResponse($payload);
    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = [])
    {



        //common settings
        $page = [
            'crumbs' => [
                __('lang.sales'),
                "Variation Order",
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'Quotation',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_invoices' => 'active',
            'mainmenu_sales' => 'active',
            'submenu_invoices' => 'active',
            'sidepanel_id' => 'sidepanel-filter-estimates',
            'dynamic_search_url' => url('estimates/search?action=search&variationresource_id=' . request('variationresource_id') . '&variationresource_type=' . request('variationresource_type')),
            'add_button_classes' => 'add-edit-estimate-button',
            'load_more_button_route' => 'estimates',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => "Add Variation Order",
            'add_modal_create_url' => url('variation/create?variationresource_id=' . request('variationresource_id') . '&variationresource_type=' . request('variationresource_type')),
            'add_modal_action_url' => url('variation?variationresource_id=' . request('variationresource_id') . '&variationresource_type=' . request('variationresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //estimates list page
        if ($section == 'estimates') {
            $page += [
                'meta_title' => __('lang.estimates'),
                'heading' => "Variation Order",

                'sidepanel_id' => 'sidepanel-filter-estimates',
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            return $page;
        }

        //estimate page
        if ($section == 'estimate') {
            //adjust
            $page['page'] = 'Variation Order';
            //add
            $page += [
                'crumbs' => [
                    "Variation Order",
                ],
                'meta_title' => __('lang.estimate') . ' #' . $data->formatted_bill_estimateid,
                'heading' => __('lang.project') . ' -' . $data->q_title,
                'bill_estimateid' => request()->segment(2),
                'source_for_filter_panels' => 'ext',
                'section' => 'overview',
            ];

            $page['crumbs'] = [
                __('lang.sales'),
                "Variation Order",
                $data['est_quotation_no'],
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
            $page['mode'] = 'editing';
            $page += [
                'section' => 'edit',
            ];
            return $page;
        }

        //return
        return $page;
    }

    /**
     * data for the stats widget
     * @return array
     */
    private function statsWidget($data = array())
    {

        //stats
        $count_new = $this->estimaterepo->search('', ['stats' => 'count-new']);
        $count_accepted = $this->estimaterepo->search('', ['stats' => 'count-accepted']);
        $count_declined = $this->estimaterepo->search('', ['stats' => 'count-declined']);
        $count_expired = $this->estimaterepo->search('', ['stats' => 'count-expired']);

        $sum_new = $this->estimaterepo->search('', ['stats' => 'sum-new']);
        $sum_accepted = $this->estimaterepo->search('', ['stats' => 'sum-accepted']);
        $sum_declined = $this->estimaterepo->search('', ['stats' => 'sum-declined']);
        $sum_expired = $this->estimaterepo->search('', ['stats' => 'sum-expired']);

        //default values
        $stats = [
            [
                'value' => runtimeMoneyFormat($sum_new),
                'title' => __('lang.pending') . " ($count_new)",
                'percentage' => '100%',
                'color' => 'bg-info',
            ],
            [
                'value' => runtimeMoneyFormat($sum_accepted),
                'title' => __('lang.accepted') . " ($count_accepted)",
                'percentage' => '100%',
                'color' => 'bg-success',
            ],
            [
                'value' => runtimeMoneyFormat($sum_expired),
                'title' => __('lang.expired') . " ($count_expired)",
                'percentage' => '100%',
                'color' => 'bg-warning',
            ],
            [
                'value' => runtimeMoneyFormat($sum_declined),
                'title' => __('lang.declined') . " ($count_declined)",
                'percentage' => '100%',
                'color' => 'bg-danger',
            ],
        ];
        //return
        return $stats;
    }
}
