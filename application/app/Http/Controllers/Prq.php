<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for notes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Http\Responses\Prq\CreateResponse;
use App\Http\Responses\ProjectInventory\DestroyResponse;
use App\Http\Responses\Prq\EditResponse;
use App\Http\Responses\Prq\IndexResponse;
use App\Http\Responses\Prq\ShowResponse;
use App\Http\Responses\ProjectInventory\StoreResponse;
use App\Http\Responses\Prq\UpdateResponse;
use App\Permissions\ProjectInventoryPermissions;
use App\Repositories\CategoryRepository;
use App\Repositories\ProjectInventoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Rules\NoTags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Repositories\BudgetRepository;
use App\Repositories\MilestoneRepository;
use App\Repositories\ProjectRepository;
use Auth;

class Prq extends Controller
{

    /**
     * The note repository instance.
     */
    protected $noterepo;
    protected $milestonerepo;
    protected $projectrepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    /**
     * The user repository instance.
     */
    protected $userrepo;
    protected $budgtrepo;
    /**
     * The note permission instance.
     */
    protected $notepermissions;

    public function __construct(
        ProjectInventoryRepository $noterepo,
        TagRepository $tagrepo,
        UserRepository $userrepo,
        MilestoneRepository $milestonerepo,
        ProjectRepository $projectrepo,

        BudgetRepository $budgtrepo,
        ProjectInventoryPermissions $notepermissions
    ) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');
        $this->middleware('prqMiddlewareIndex')->only([
            'index',
            'update',
            'store',

        ]);

        $this->middleware('prqMiddlewareCreate')->only([
            'create',
            'store',
        ]);
        $this->middleware('prqMiddlewareEdit')->only([
            // 'edit',

        ]);

        $this->middleware('prqMiddlewareDestroy')->only([
            // 'destroy',
        ]);

        $this->noterepo = $noterepo;

        $this->tagrepo = $tagrepo;
        $this->milestonerepo = $milestonerepo;
        $this->projectrepo = $projectrepo;

        $this->userrepo = $userrepo;
        $this->budgtrepo = $budgtrepo;
        $this->notepermissions = $notepermissions;
    }


    /**
     * Display a listing of notes
     * @param object CategoryRepository instance of the repository
     * @return blade view | ajax view
     */

    public function index()
    {
        //default to user notes if type is not set
        if (request('noteresource_type')) {
            $page = $this->pageSettings('mynotes');
        } else {
            $page = $this->pageSettings('notes');
        }
        $a = request('prqresource_id');
        $b = request('noteresource_id');

        // $grn_data = $this->noterepo->get_grn_data($a ?? $b);
        $grn_data = DB::table('purchase_requistion')->where('project_id', $a ?? $b)->get();
        // print_r($grn_data);die;
        $notes = $this->noterepo->search();
        if ($notes) {
            foreach ($notes as $note) {
                $this->applyPermissions($note);
            }
        }
        // print_r($notes);die;
        //reponse payload
        $payload = [
            'page' => $page,
            'notes' => $notes,
            'grn_data' => $grn_data,

        ];
        //show the view
        return new IndexResponse($payload);
    }
    public function template_array()
    {

        $template = [
            '1' => 'PRELIMINARIES',
            '2' => 'INSURANCE',
            '3' => 'SCHEDULE OF WORK',
            '4' => 'PLUMBING & SANITY',
            '5' => 'ELEC & ACME',
            '6' => 'EXTERNAL WORKS',
            '7' => 'ELEC & ACME',
            '8' => 'PC & PS SUMS',
        ];
        return $template;
    }
    /**
     * Show the form for creating a new  note
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo)
    {
        $id = 0;
        $a = request('prqresource_id');
        $b = request('noteresource_id');
        if ($a) {
            $id = $a;
        } else {
            $id = $b;
        }
        $budgtrepo = $this->budgtrepo->search($id);
        $category_quotation = $this->budgtrepo->get_category_data($id);
        $get_milestone = $this->milestonerepo->get_milestone($id);
        $dataEngineer = $this->projectrepo->Get_Employee('Engineer');
        $dataSupervisor = $this->projectrepo->Get_Employee('Supervisor');
        $project_d = $this->projectrepo->search($id);

        // print_r($project_d);die;
        //get tags

        $tags = $this->tagrepo->getByType('notes');
        $templete_category = $this->template_array();
        //reponse payload
        // print_R($get_milestone);die;
        $payload = [
            'page' => $this->pageSettings('create'),
            'tags' => $tags,
            'budgtrepo' => $budgtrepo,
            'templete_category' => $get_milestone,
            'dataSupervisor' => $dataSupervisor,
            'dataEngineer' => $dataEngineer,
            'project_d' => $project_d,
        ];

        //show the form
        return new CreateResponse($payload);
    }
    public function create_mrf($data)
    {
        // Check if project_id is not 'Select'
        if ($data['project_id'] !== 'Select') {
            // Fetch project_code using Eloquent
            $project = DB::table('projects')
                ->select('project_code')
                ->where('project_id', $data['project_id'])
                ->first();

            $pr_code = $project->project_code ?? ''; // Check if project_code is available
            $currentMonth = Carbon::now()->format('ym'); // Current month in 'ym' format

            DB::beginTransaction(); // Start the transaction

            try {
                // Check if sequence for the current month exists
                $sequence = DB::table('pr_sequence')
                    ->where('year_month', $currentMonth)
                    ->first();

                if ($sequence) {
                    // Increment sequence
                    $new_sequence = $sequence->sequence + 1;

                    DB::table('pr_sequence')
                        ->where('year_month', $currentMonth)
                        ->update(['sequence' => $new_sequence]);
                } else {
                    // Initialize sequence for the new month
                    $new_sequence = 1;

                    DB::table('pr_sequence')->insert([
                        'year_month' => $currentMonth,
                        'sequence' => $new_sequence,
                    ]);
                }

                DB::commit(); // Commit the transaction

                // Generate the new porder_id with project_code
                $new_pr_id = "OC/PR/" . $pr_code . "/" . $currentMonth . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);

                // Update the porder_id in the purchase_requistion table
                DB::table('purchase_requistion')
                    ->where('purchase_requistion_id', $data['purchase_requistion_id'])
                    ->update(['porder_id' => $new_pr_id]);
            } catch (\Exception $e) {
                DB::rollBack(); // Rollback the transaction on error
                throw $e;
            }
        } else {
            // If no project_id, generate ID without project_code
            $currentMonth = Carbon::now()->format('ym');

            DB::beginTransaction(); // Start the transaction

            try {
                // Check if sequence for the current month exists
                $sequence = DB::table('purchase_requistion_sequence')
                    ->where('month', $currentMonth)
                    ->first();

                if ($sequence) {
                    // Increment sequence
                    $new_sequence = $sequence->sequence + 1;

                    DB::table('purchase_requistion_sequence')
                        ->where('month', $currentMonth)
                        ->update(['sequence' => $new_sequence]);
                } else {
                    // Initialize sequence for the new month
                    $new_sequence = 1;

                    DB::table('purchase_requistion_sequence')->insert([
                        'month' => $currentMonth,
                        'sequence' => $new_sequence,
                    ]);
                }

                DB::commit(); // Commit the transaction

                // Generate the new porder_id without project_code
                $new_pr_id1 = "OC/PR/" . $currentMonth . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);

                // Update the porder_id in the purchase_requistion table
                DB::table('purchase_requistion')
                    ->where('purchase_requistion_id', $data['purchase_requistion_id'])
                    ->update(['porder_id' => $new_pr_id1]);
            } catch (\Exception $e) {
                DB::rollBack(); // Rollback the transaction on error
                throw $e;
            }
        }
    }


    /**
     * Store a newly created note in storage.
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // die;

        $messages = [];
        // print_R($request->all());die;

        //validate
        $validator = Validator::make(request()->all(), [
            'product_id' => [
                'required',
                new NoTags,
            ],
            'task_id' => [
                'required',
                new NoTags,
            ],
            'milestone_id' => [
                'required',
                new NoTags,
            ],
            // 'order_date' => [
            //     'required',
            //     new NoTags,
            // ],
            'product_id' => [
                'required',
                new NoTags,
            ],
            // 'pur_date' => 'required',
            'tags' => [
                'bail',
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $key => $data) {
                        if (hasHTML($data)) {
                            return $fail(__('lang.tags_no_html'));
                        }
                    }
                },
            ],
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

        $a = request('prqresource_id');
        $b = request('noteresource_id');
        $id = 0;
        if ($a) {
            $id = $a;
        } else {
            $id = $b;
        }

        $grn_data = $this->noterepo->get_grn_data($id);
        $s_data = 0;
        $endata = $this->projectrepo->Get_Employee_ById_En($id, Auth()->id());
        $sendata = $this->projectrepo->Get_Employee_ById_Sn($id, Auth()->id());
        if ($endata) {
            $s_data = 1;
        }
        if ($sendata) {
            $s_data = 2;
        }
        // Get_Employee_ById($de, $id)

        //create the note
        $res = "Waiting for Confirmation";
        $data = [
            'project_id' => $id,

            'site_address' => $request->input('site_address'),
            'mile_stone' => $request->input('milestone_id'),
            'task' => $request->input('task_id'),

            // 'mrf_no' => $request->input('mrf_no'),
            'order_date' => date('d-m-Y'),
            'supervisor' => $request->input('supervisor'),
            'sub_contractor' => $request->input('sub_contractor'),
            'earliest_date' => $request->input('earliest_date'),
            'latest_date' => $request->input('latest_date'),
            'noteresource_type' => "project",
            'note_visibility' => "public",
            'note_creatorid' => auth()->id(),

            'created_by' => 1,
            'created_datetime' => now()->format('Y-m-d H:i:s'),
            'status' => ($s_data == 1) ? 'Pending Project Manager Approval' : 'Created',
            'engineer_status' => ($s_data == 1) ? 1 : 0,
            'project_status' => ($s_data == 2) ? 1 : 0,
            // 'status_reason' => $status_reason
        ];

        $r = DB::table('purchase_requistion')->insertGetId($data);
        $data_aaa = array(
            'project_id' => $id,
            'purchase_requistion_id' => $r,
        );
        $this->create_mrf($data_aaa);

        if (count(request('product_id')) > 0 || $r > 0) {

            $k = 1;
            for ($i = 0; $i < count(request('product_id')); $i++) {
                $data_opt = [
                    'purchase_requistion_id' => $r,
                    'product_id' => $request->input('product_id')[$i],
                    'level' => $request->input('level')[$i],
                    'qty' => $request->input('qty')[$i],
                    'where_use' => $request->input('use')[$i],
                    'sub_con' => $request->input('sub_con')[$i],
                    'po_no' => $request->input('po_no')[$i],
                    'do_no' => $request->input('do_no')[$i],
                    'created_by' => 1,
                    'created_datetime' => now()->format('Y-m-d H:i:s'),
                    // 'status' => $res
                ];


                $k++;
                DB::table('purchase_requistion_item_mapping')->insertGetId($data_opt);
            }
        }


        //create the note
        // if (!$note_id = $this->noterepo->create()) {
        //     abort(409);
        // }

        // //add tags
        // $this->tagrepo->add('note', $note_id);

        // //get the note object (friendly for rendering in blade template)
        // $notes = $this->noterepo->search($note_id);

        //permissions
        // $this->applyPermissions($notes->first());

        //counting rows
        $rows = $this->noterepo->search();
        $count = $rows->total();

        //reponse payload
        $payload = [
            'notes' => 10,
            'count' => $count,
            'grn_data' => $grn_data
        ];

        //process reponse
        return new StoreResponse($payload);
    }
    public function Change_status_by_manage()
    {
        $data = [];
        if (request('type') == 'pending_engineer') {

            $data = [
                'status' => 'Pending Project Manager Approval',

                'project_status' => 1,
                'managemant_status' => null,
                'engineer_status' => null,
            ];
        }
        if (request('type') == 'pending_project') {

            $data = [
                'status' => 'Pending Management/Procurement Team Approval',

                'project_status' => null,
                'engineer_status' => null,
                'managemant_status' => 2,
            ];
        }
        if (request('type') == 'pending_approved') {
            // print_r($data);die;

            $data = [
                'status' => 'Pending Management/Procurement Team Approval',

                'engineer_status' => null,
                'project_status' => null,
                'managemant_status' => 3,
            ];
        }
        if (request('type') == 'pending_rejected') {
            // print_r($data);die;

            $data = [
                'status' => 'Rejected',

                'engineer_status' => null,
                'project_status' => null,
                'managemant_status' => null,
            ];
        }
        DB::table('purchase_requistion')->where('purchase_requistion_id', request('id'))->update($data);

        return 1;
    }
    public function update(Request $request, $id)
    {
        //    echo "pp";die;
        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'product_id' => [
                'required',
                new NoTags,
            ],
            'task_id' => [
                'required',
                new NoTags,
            ],
            'milestone_id' => [
                'required',
                new NoTags,
            ],
            // 'order_date' => [
            //     'required',
            //     new NoTags,
            // ],
            'product_id' => [
                'required',
                new NoTags,
            ],
            'tags' => [
                'bail',
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $key => $data) {
                        if (hasHTML($data)) {
                            return $fail(__('lang.tags_no_html'));
                        }
                    }
                },
            ],
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


        //create the note
        $res = "Waiting for Confirmation";
        $a = request('project_id');
        $b = request('project_id');
        $id = 0;
        if ($a) {
            $id = $a;
        } else {
            $id = $b;
        }

        $data = [
            'project_id' => $id,

            'site_address' => $request->input('site_address'),
            'mile_stone' => $request->input('milestone_id'),
            'task' => $request->input('task_id'),

            // 'mrf_no' => $request->input('mrf_no'),
            'order_date' => date('d-m-Y'),
            'supervisor' => $request->input('supervisor'),
            'sub_contractor' => $request->input('sub_contractor'),
            'earliest_date' => $request->input('earliest_date'),
            'latest_date' => $request->input('latest_date'),

            'created_by' => 1,
            'created_datetime' => now()->format('Y-m-d H:i:s'),
            'status' => $request->input('status'),
            // 'status_reason' => $status_reason
        ];
        $r = DB::table('purchase_requistion')->where('purchase_requistion_id', request('uniq_id'))->update($data);

        DB::table('purchase_requistion_item_mapping')->where('purchase_requistion_id', request('uniq_id'))->delete();

        if (count(request('product_id')) > 0 || $r > 0) {

            $k = 1;
            for ($i = 0; $i < count(request('product_id')); $i++) {

                $data_opt = [
                    'purchase_requistion_id' => request('uniq_id'),
                    'product_id' => $request->input('product_id')[$i],
                    'level' => $request->input('level')[$i],
                    'qty' => $request->input('qty')[$i],
                    'where_use' => $request->input('use')[$i],
                    'sub_con' => $request->input('sub_con')[$i],
                    'po_no' => $request->input('po_no')[$i],
                    'do_no' => $request->input('do_no')[$i],
                    'created_by' => 1,
                    'created_datetime' => now()->format('Y-m-d H:i:s'),
                    // 'status' => $res
                ];
                $k++;
                DB::table('purchase_requistion_item_mapping')->insertGetId($data_opt);
            }
        }


        //create the note
        //update
        // if (!$this->noterepo->update($id)) {
        //     abort(409);
        // }

        //delete & update tags
        $this->tagrepo->delete('note', $id);
        $this->tagrepo->add('note', $id);

        //get note
        $notes = $this->noterepo->search($id);

        $this->applyPermissions($notes->first());
        $a = request('projectinventoryresource_id');
        $b = request('noteresource_id');

        $grn_data = $this->noterepo->get_grn_data($a ?? $b);
        //reponse payload
        $payload = [
            'notes' => $notes,
            'grn_data' => $grn_data
        ];

        //generate a response
        return new UpdateResponse($payload);
    }
    /**
     * display a note via ajax modal
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = DB::table('purchase_requistion')->where('purchase_requistion_id', $id)->get();


        //get the note
        // $note = $this->noterepo->search($id);
        $dataEngineer = $this->projectrepo->Get_Employee('Engineer');
        $dataSupervisor = $this->projectrepo->Get_Employee('Supervisor');
        $project_d = $this->projectrepo->search($note[0]->project_id);

        //note not found
        // if (!$note = $note->first()) {
        //     abort(409, __('lang.note_not_found'));
        // }
        // $note = $this->noterepo->search($id);
        $note_item = DB::table('purchase_requistion_item_mapping')->where('purchase_requistion_id', $id)->get();
        $tags = $this->tagrepo->getByResource('note', $id);
        $budgtrepo = $this->budgtrepo->search($note[0]->project_id);
        $templete_category = $this->budgtrepo->get_category_data($note[0]->project_id);;
        // print_r($note[0]);die;
        //reponse payload
        $payload = [
            'note' => $note,
            'a' => 1,
            'page' => $this->pageSettings('show'),
            'note' => $note[0],
            'note_item' => $note_item,
            'tags' => $tags,
            'budgtrepo' => $budgtrepo,
            'templete_category' => $templete_category,
            'dataSupervisor' => $dataSupervisor,
            'dataEngineer' => $dataEngineer,
            'project_d' => $project_d,
        ];

        //process reponse
        return new ShowResponse($payload);
        // return new EditResponse($payload);
    }
    public function Get_product_details()
    {

        // echo json_encode(DB::table('product')->where('product_id', request('id'))->get());
        $ress = DB::table('stock_management')->where('prd_id', request('id'))
            ->Join('warehouse', 'warehouse.w_id', '=', 'stock_management.warehouse_id')
            ->select('warehouse.w_id', 'warehouse.w_name', 'stock_management.quantity')
            ->get();
        $data = [
            'ress' => $ress,
        ];

        return response()->json($data);
    }



    /**
     * Show the form for editing the specified  note
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        //get the note
        $note = DB::table('purchase_requistion')->where('purchase_requistion_id', $id)->get();
        // $note = $this->noterepo->search($id);
        $note_item = DB::table('purchase_requistion_item_mapping')->where('purchase_requistion_id', $id)->get();
        $dataEngineer = $this->projectrepo->Get_Employee('Engineer');
        $dataSupervisor = $this->projectrepo->Get_Employee('Supervisor');
        $project_d = $this->projectrepo->search($note[0]->project_id);

        //get tags
        $tags = $this->tagrepo->getByResource('note', $id);
        $budgtrepo = $this->budgtrepo->search($note[0]->project_id);
        //note not found
        if (!$note = $note->first()) {
            abort(409, __('lang.note_not_found'));
        }
        // print_r($project_d);
        // die;
        // print_r($note);die;
        $get_milestone = $this->milestonerepo->get_milestone($note->project_id);
        $templete_category = $this->budgtrepo->get_category_data($note->project_id);

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'note' => $note,
            'a' => 0,
            'note_item' => $note_item,
            'budgtrepo' => $budgtrepo,
            'tags' => $tags,
            'templete_category' => $get_milestone,
            'dataSupervisor' => $dataSupervisor,
            'dataEngineer' => $dataEngineer,
            'project_d' => $project_d,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Update the specified note in storage.
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified note from storage.
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $note = \App\Models\Note::Where('note_id', $id)->first();
        $r = DB::table('purchase_requistion')->where('purchase_requistion_id', $id)->delete();

        DB::table('purchase_requistion_item_mapping')->where('purchase_requistion_id', $id)->delete();

        //remove the item
        // $note->delete();

        //reponse payload
        $payload = [
            'note_id' => $id,
        ];

        //generate a response
        return new DestroyResponse($payload);
    }

    /**
     * pass the file through the ProjectPermissions class and apply user permissions.
     * @param object note instance of the note model object
     * @return object
     */
    private function applyPermissions($note = '')
    {

        //sanity - make sure this is a valid file object
        if ($note instanceof \App\Models\Note) {
            //delete permissions
            $note->permission_edit_delete_note = $this->notepermissions->check('edit-delete', $note);
        }
    }
    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = [])
    {
        $a = request('prqresource_id');
        $b = request('noteresource_id');
        $id = 0;
        if ($a) {
            $id = $a;
        } else {
            $id = $b;
        }
        //common settings
        $page = [
            'crumbs' => [
                "Material Requisition Form (MRF)",
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'Material Requisition Form (MRF)',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_notes' => 'active',
            'sidepanel_id' => 'sidepanel-filter-notes',
            'dynamic_search_url' => url('prq/search?action=search&noteresource_id=' . $id . '&noteresource_type=' . request('noteresource_type')),
            'add_button_classes' => 'add-edit-note-button',
            'load_more_button_route' => 'notes',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => "Material Requisition Form (MRF)",
            'add_modal_create_url' => url('prq/create?noteresource_id=' . $id . '&noteresource_type=' . request('noteresource_type')),
            'add_modal_action_url' => url('prq?noteresource_id=' . $id . '&noteresource_type=' . request('noteresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //notes list page
        if ($section == 'Material Requisition Form (MRF)') {
            $page += [
                'meta_title' => "Material Requisition Form (MRF)",
                'heading' => "Material Requisition Form (MRF)",
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            return $page;
        }

        //notes list my notes
        if ($section == 'mynotes') {
            $page += [
                'meta_title' => "Material Requisition Form (MRF)",
                'heading' => "Material Requisition Form (MRF)",
            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            return $page;
        }

        //note page
        if ($section == 'note') {
            //adjust
            $page['page'] = 'note';
            //add
            $page += [
                'crumbs' => [
                    "Material Requisition Form (MRF)",
                ],
                'meta_title' => "Material Requisition Form (MRF)",
                'note_id' => request()->segment(2),
                'section' => 'overview',
            ];
            //ajax loading and tabs
            $page += $this->setActiveTab(request()->segment(3));
            return $page;
        }

        //create new resource
        if ($section == 'create') {
            $page += [
                'section' => 'create',
            ];
            return $page;
        }
        if ($section == 'show') {
            $page += [
                'section' => 'show',
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
}
