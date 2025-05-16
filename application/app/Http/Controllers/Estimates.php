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
use App\Http\Responses\Estimates\AcceptResponse;
use App\Http\Responses\Estimates\AttachProjectResponse;
use App\Http\Responses\Estimates\ChangeCategoryUpdateResponse;
use App\Http\Responses\Estimates\ChangeStatusResponse;
use App\Http\Responses\Estimates\AddFormResponse;
use App\Http\Responses\Estimates\AddFormUpdateResponse;
use App\Http\Responses\Estimates\CreateResponse;
use App\Http\Responses\Estimates\DeclineResponse;
use App\Http\Responses\Estimates\DestroyResponse;
use App\Http\Responses\Estimates\EditResponse;
use App\Http\Responses\Estimates\IndexResponse;
use App\Http\Responses\Estimates\PDFResponse;
use App\Http\Responses\Estimates\PublishResponse;
use App\Http\Responses\Estimates\PublishRevisedResponse;
use App\Http\Responses\Estimates\ResendResponse;
use App\Http\Responses\Estimates\SaveResponse;
use App\Http\Responses\Estimates\ShowResponse;
use App\Http\Responses\Estimates\StoreResponse;
use App\Http\Responses\Estimates\UpdateResponse;

use App\Repositories\CategoryRepository;
use App\Repositories\DestroyRepository;
use App\Repositories\EmailerRepository;
use App\Permissions\TaskPermissions;
use App\Http\Responses\Estimates\IndexListResponse;

use App\Repositories\EstimateGeneratorRepository;
use App\Repositories\EstimateRepository;
use App\Repositories\EventRepository;
use App\Repositories\EventTrackingRepository;
use App\Http\Responses\Estimates\IndexKanbanResponse;
use App\Models\quotationTemplate;
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

class Estimates extends Controller
{

    /**
     * The estimate repository instance.
     */
    protected $estimaterepo;

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
        ProjectRepository $projectrepo,
        TagRepository $tagrepo,
        UserRepository $userrepo,
        TaxRepository $taxrepo,
        LineitemRepository $lineitemrepo,
        EventRepository $eventrepo,
        EventTrackingRepository $trackingrepo,
        EmailerRepository $emailerrepo,
        EstimateGeneratorRepository $estimategenerator,
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

        $this->middleware('estimatesMiddlewareIndex')->only([
            'index',
            'update',
            'store',
            'changeCategoryUpdate',
            'attachProjectUpdate',
            'changeStatusUpdate',
        ]);

        $this->middleware('estimatesMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('estimatesMiddlewareEdit')->only([
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

        $this->middleware('estimatesMiddlewareShow')->only([
            'show',
            'downloadPDF',
            'acceptEstimate',
            'declineEstimate',
        ]);

        $this->middleware('estimatesMiddlewareDestroy')->only(['destroy']);

        //only needed for the [action] methods
        $this->middleware('estimatesMiddlewareBulkEdit')->only(['changeCategoryUpdate']);

        //repos
        $this->estimaterepo = $estimaterepo;
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

        //get estimate
        $estimates = $this->estimaterepo->search();

        //get all categories (type: estimate) - for filter panel
        $categories = $categoryrepo->get('estimate');

        //get all tags (type: lead) - for filter panel
        $tags = $this->tagrepo->getByType('estimate');

        //get clients project list
        if (config('visibility.filter_panel_clients_projects')) {
            if (is_numeric(request('estimateresource_id'))) {
                $projects = $projectrepo->search('', ['project_clientid' => request('estimateresource_id')]);
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
            if (is_numeric(request('estimateresource_id'))) {
                $projects = $projectrepo->search('', ['project_clientid' => request('estimateresource_id')]);
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
            if (is_numeric(request('estimateresource_id'))) {
                $projects = $projectrepo->search('', ['project_clientid' => request('estimateresource_id')]);
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

        //get tags
        $tags = $this->tagrepo->getByType('estimate');

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
            'categories' => $categories,
            'tags' => $tags,
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

        //create the estimate
        if (!$bill_estimateid = $this->estimaterepo->create()) {
            abort(409);
        }

        //add tags
        $this->tagrepo->add('estimate', $bill_estimateid);

        //reponse payload
        $payload = [
            'id' => $bill_estimateid,
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
    public function show($id)
    {

        //get invoice object payload
        if (!$payload = $this->estimategenerator->generate($id)) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }
        $data = DB::table('billing_addresses')->where('client_id', $payload['bill']->bill_clientid)->get();

        //append to payload
        $payload['page'] = $this->pageSettings('estimate', $payload['bill']);
        // $payload['estd_id'] = $id;
        $payload['d_add'] = $data;
        //   echo "<pre>";
        //    print_r($payload['bill']);
        //   die;
        //mark events as read
        // \App\Models\EventTracking::where('parent_id', $id)
        //     ->where('parent_type', 'estimate')
        //     ->where('eventtracking_userid', auth()->id())
        //     ->update(['eventtracking_status' => 'readj']);

        //pdf estimate
        //    dd(request()->segment);exit;
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



    // public function saveQuotationtemplates(Request $request, $id)
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
    //                 $lineItem = QuotationTemplate::find($request->id[$index]);
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
    //                     QuotationTemplate::create([
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
    //                 QuotationTemplate::create([
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

    public function saveEstimate(EstimateSave $request, $id)
    {
        // die;
        // return $request->all();
        try {

            // Get the estimate
            $estimates = $this->estimaterepo->search($id);
            $estimate = $estimates->first();

            // Save each line item in the database
            $this->estimaterepo->saveLineItems($id);
            $this->estimaterepo->SaveProductItel($id);
            $this->saveQuotationtemplates($request, $estimate->bill_estimateid);
            // return $this->saveQuotationtemplateate()s($request, $estimate->bill_estimateid);

            // Update taxes
            $this->updateEstimateTax($id);
            $this->estimaterepo->update($id);
            // Redirect to the estimate show page
            return response()->json(['redirect_url' => route('estimates.show', $id)]);
        } catch (\Exception $e) {
            Log::error('Error saving estimate: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }
    public function saveQuotationtemplates(Request $request, $id)
    {

        try {
            Log::info('saveQuotationtemplates method called', ['id' => $id, 'request' => $request->all()]);

            // Delete all existing records for this estimate/template ID before inserting new ones
            QuotationTemplate::where('estimates_id', $id)->delete();

            // Initialize an array to track processed hashes (optional, to prevent duplicates within the same request)
            $processedIds = [];
            $lineItemData = [];
            // Iterate over the request data and insert new records
            foreach ($request->template_id as $index => $template_id) {

                // Prepare line item data
                $lineItemData[] = [
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

                // QuotationTemplate::
                // where('qty', '')->where('labour','')->where('material','')->where('misc','')->where('wastage_percent','')->where('wastage_amount','')->where('sc','')->
                // where('total', null)->delete();
                // Check if this record is already processed (optional)
                $hash = md5(serialize($lineItemData));
                if (in_array($hash, $processedIds)) {
                    // Log::warning('Duplicate record prevented', ['lineItemData' => $lineItemData]);
                    continue;
                }
                // dd($lineItemData);die;
                // Insert the new record into the database
                // if (QuotationTemplate::create($lineItemData)) {

                //     $this->update_summary($request->quotation_no[0]);
                // }

                // Track the processed data
                $processedIds[] = $hash;
            }
            // return $lineItemData;
            if (QuotationTemplate::insert($lineItemData)) {

                $this->update_summary($request->quotation_no[0]);
            }
            return response()->json(['success' => 'Quotation templates saved successfully']);
        } catch (\Exception $e) {
            Log::error('Error saving quotation templates', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }
    public function update_summary($est_quotation_no)
    {

        $quotation_templates = DB::table('quotation_templates')
            ->select('template_id', DB::raw('SUM(total) as total_amount'))
            ->where('quotation_no', $est_quotation_no)
            ->groupBy('template_id')
            ->get();
        DB::table('summary_details')->where('quotation_id', $est_quotation_no)->delete();

        $letterIndex = 0;
        $sum = 0;
        $sum2 = 0;

        $data = [];
        $count = 0;

        foreach ($quotation_templates as $template) {
            if ($template->template_id >= 1 && $template->template_id <= 6) {
                $letter = chr(65 + $letterIndex);
                $letterIndex++;
                $sum += $template->total_amount;
                $count++;
                $row = [
                    'letter' => $letter,
                    'description' => '',
                    'amount' => $template->total_amount
                ];
                switch ($template->template_id) {
                    case 1:
                        $row['description'] = 'General Preliminaries';
                        $row['tem_id'] = $template->template_id;
                        break;
                    case 2:
                        $row['description'] = 'Insurances';
                        $row['tem_id'] = $template->template_id;

                        break;
                    case 3:
                        $row['description'] = 'Proposed Building Works';
                        $row['tem_id'] = $template->template_id;

                        break;
                    case 4:
                        $row['description'] = 'Proposed Electrical & ACMV Works';
                        $row['tem_id'] = $template->template_id;

                        break;
                    case 5:
                        $row['description'] = 'Proposed Plumbing & Sanitary Works';
                        $row['tem_id'] = $template->template_id;

                        break;
                    case 6:
                        $row['description'] = 'Proposed External Works';
                        $row['tem_id'] = $template->template_id;
                        break;
                }
                $data[] = $row;
            }
        }
        $data[] = [
            'letter' => '',
            'description' => '',
            'amount' => $sum
        ];


        $letter = chr(65 + $letterIndex++);
        $data[] = [
            'letter' => $letter,
            'description' => "Contractor's Profit (%)",
            'amount' => 0.05 * $sum
        ];


        $letter = chr(65 + $letterIndex++);
        $data[] = [
            'letter' => $letter,
            'description' => 'NETT MAIN CONTRACTOR\'S PRICE',
            'amount' => $sum + (0.05 * $sum)
        ];
        $sum2 = $sum + (0.05 * $sum);

        foreach ($quotation_templates as $template) {
            if ($template->template_id >= 7 && $template->template_id <= 8) {
                $letter = chr(65 + $letterIndex);
                $letterIndex++;
                $row = [
                    'letter' => $letter,
                    'description' => '',
                    'amount' => $template->total_amount
                ];
                switch ($template->template_id) {
                    case 7:
                        $row['description'] = 'PC & Provisional Sums';
                        $sum2 += $template->total_amount;
                        break;
                    case 8:
                        $row['description'] = 'Others';
                        $sum2 += $template->total_amount;
                        break;
                }


                $data[] = $row;
            }
        }

        $data[] = [
            'letter' => '',
            'description' => 'TOTAL TENDER / QUOTATION AMOUNT',
            'amount' => $sum2
        ];
        $quotation_amount_total = 0;


        // return $data;

        foreach ($data as $row) {
            $quotation_amount_total = $row['amount'];
            $re = DB::table('summary_details')->insert([
                'letter' => $row['letter'],
                'description' => $row['description'],
                'amount' => $row['amount'],
                'quotation_id' => $est_quotation_no,
            ]);
        }
        DB::table('quos')->where(['quo_number' => $est_quotation_no])->update(['AMO' => $quotation_amount_total]);


        return 1;
    }
    public function convertToProject(Request $request, $id)
    {
        // echo "1";exit;
        $quotation = DB::table('quos')->where('task_id', $id)->first();
        $amount = DB::table('summary_details')->orderBy('amount', 'DESC')->first();


        $project = new Project();
        // $project->quotation_id = $quotation->task_id;
        // $project->project_companyid = $quotation->task_companyid;
        $project->quotation_no = $quotation->quo_number;
        $project->project_clientid = $quotation->task_clientid;
        $project->project_title = $quotation->task_title;
        $project->project_date_start = $quotation->task_date_start;
        $project->project_date_due = $quotation->task_date_due;
        $project->project_sn = $amount->amount;
        $project->warehouse_id = $this->addWarehouse($project);

        $project->save();
        $dd = DB::table('estimates')->where(['bill_estimateid' => $id])->update(['bill_projectid' => $project->project_id, 'is_project_creates' => "Yes"]);

        $milestoneData = DB::table('estimates')
            ->join('quotation_templates', 'estimates.bill_estimateid', '=', 'quotation_templates.estimates_id')
            ->join('milestone_categories', 'quotation_templates.template_id', '=', 'milestone_categories.milestonecategory_id')
            ->where('estimates.bill_projectid',  $project->project_id)
            ->select('milestone_categories.*', 'estimates.bill_projectid')
            ->groupBy('milestone_categories.milestonecategory_title')
            ->get();

// print_r($project->project_id);exit;
            foreach ($milestoneData as $milestone) {
            DB::table('milestones')->insert([
                'milestone_title' => $milestone->milestonecategory_title,
                'milestone_projectid' => $project->project_id,
                'milestone_created'=>now(),
                'milestone_updated'=>now()
            ]);
        }



        $dd = DB::table('quos')->where(['task_id' => $id])->update(['is_project_creates' => "Yes"]);

        $template_data = DB::table('quotation_templates')->where('quotation_no', $quotation->quo_number)->get();

        // $lineitem = Lineitem::where('lineitemresource_id', $id)->get();
        // dd($template_data);

        foreach ($template_data as $line) {
            $task = new Task();
            $task->task_cat_id = $line->template_id;
            $task->task_title = $line->description;
            $task->task_projectid =  $project->project_id;
            $task->task_clientid =  $quotation->task_clientid;
            $task->task_qtn =  $line->qty;
            $task->task_unit =  $line->unit;
            $task->task_total =  $line->amount;
            $task->save();
        }
        $estimates = $this->estimaterepo->search(request()->route('estimate'));
        $payload = [
            'estimates' => $estimates,
            'bill_estimateid' => request()->route('estimate'),
            'stats' => $this->statsWidget(),
        ];
        //response
        return new UpdateResponse($payload);
    }

    private function addWarehouse($project)
    {
        $companyId = \DB::table('xin_employees')
            ->where('user_id', auth()->id())
            ->value('company_id');
        // print_R($project);die;

        $data = [
            'w_name' => $project->project_title,
            'w_address' => '',
            'w_postal_code' => '',
            'org_id' => $companyId,
            'w_unit_no' => 0,
            'created_by' => auth()->id(),
            'w_type' => 'Project',
            'created_at' => now(),
        ];



        $result = DB::table('warehouse')->insertGetId($data);
        return $result;
    }
    function getPostalCode($address)
    {
        if (preg_match('/\b\d{6}\b/', $address, $matches)) {
            return $matches[0];
        }
        return null; // Return null if no postal code found
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
    public function destroy(DestroyRepository $destroyrepo)
    {

        //delete each record in the array
        $allrows = array();
        foreach (request('ids') as $id => $value) {
            //only checked items
            if ($value == 'on') {
                //destroy estimate
                $destroyrepo->destroyEstimate($id);
                //add to array
                $allrows[] = $id;
            }
        }
        //reponse payload
        $payload = [
            'allrows' => $allrows,
            'stats' => $this->statsWidget(),
        ];

        //generate a response
        return new DestroyResponse($payload);
    }


    public function deletedata($id)
    {
        // return $id;
        $data = quotationTemplate::find($id);
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
        //     Log::error("unable to load estimate record", ['process' => '[EstimateRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'estimate_id' => $id ?? '']);
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
                "Quotation",
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'Quotation',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_estimates' => 'active',
            'mainmenu_sales' => 'active',
            'submenu_estimates' => 'active',
            'sidepanel_id' => 'sidepanel-filter-estimates',
            'dynamic_search_url' => url('estimates/search?action=search&estimateresource_id=' . request('estimateresource_id') . '&estimateresource_type=' . request('estimateresource_type')),
            'add_button_classes' => 'add-edit-estimate-button',
            'load_more_button_route' => 'estimates',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => "Add Quotation",
            'add_modal_create_url' => url('estimates/create?estimateresource_id=' . request('estimateresource_id') . '&estimateresource_type=' . request('estimateresource_type')),
            'add_modal_action_url' => url('estimates?estimateresource_id=' . request('estimateresource_id') . '&estimateresource_type=' . request('estimateresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //estimates list page
        if ($section == 'estimates') {
            $page += [
                'meta_title' => __('lang.estimates'),
                'heading' => "Quotation",

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
            $page['page'] = 'Quotation';
            //add
            $page += [
                'crumbs' => [
                    "Quotation",
                ],
                'meta_title' => __('lang.estimate') . ' #' . $data->formatted_bill_estimateid,
                'heading' => __('lang.project') . ' - ' . $data->project_title,
                'bill_estimateid' => request()->segment(2),
                'source_for_filter_panels' => 'ext',
                'section' => 'overview',
            ];

            $page['crumbs'] = [
                __('lang.sales'),
                "Quotation",
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
    public function downloadData($estimate)
    {
        // Fetch the data for the estimate
        $results = DB::table('milestone_categories')
            ->join('quotation_templates', 'milestone_categories.milestonecategory_id', '=', 'quotation_templates.template_id')
            ->where('quotation_templates.estimates_id', $estimate)
            ->select('milestone_categories.*', 'quotation_templates.*')
            ->get();

        // If no results, return an error message
        if ($results->isEmpty()) {
            return response()->json(['error' => 'No data found'], 404);
        }

        // Return the results as JSON
        return response()->json($results);
    }
}
