<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for notes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Budget\CreateResponse;
use App\Http\Responses\ProjectInventory\DestroyResponse;
use App\Http\Responses\Budget\EditResponse;
use App\Http\Responses\Budget\IndexResponse;
use App\Http\Responses\Budget\ShowResponse;
use App\Http\Responses\ProjectInventory\StoreResponse;
use App\Http\Responses\Budget\UpdateResponse;
use App\Permissions\ProjectInventoryPermissions;
use App\Repositories\CategoryRepository;
use App\Repositories\ProjectInventoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Repositories\BudgetRepository;
use App\Rules\NoTags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class Budget extends Controller
{

    /**
     * The note repository instance.
     */
    protected $noterepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;
    protected $budgtrepo;

    /**
     * The user repository instance.
     */
    protected $userrepo;

    /**
     * The note permission instance.
     */
    protected $notepermissions;

    public function __construct(
        ProjectInventoryRepository $noterepo,
        TagRepository $tagrepo,
        UserRepository $userrepo,
        BudgetRepository $budgtrepo,
        ProjectInventoryPermissions $notepermissions
    ) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');
        $this->middleware('budgetMiddlewareIndex')->only([
            'index',
        ]);
        $this->noterepo = $noterepo;

        $this->tagrepo = $tagrepo;

        $this->userrepo = $userrepo;

        $this->notepermissions = $notepermissions;
        $this->budgtrepo = $budgtrepo;
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
        $id = 0;
        $a = request('budgetresource_id');
        $b = request('noteresource_id');
        if ($a) {
            $id = $a;
        } else {
            $id = $b;
        }
        // $budgtrepo = $this->budgtrepo->search($id);
        $templete_category = $this->template_array();
        $budgtrepo = DB::table('tasks')->leftJoin('milestone_categories', 'milestone_categories.milestonecategory_id', '=', 'tasks.task_cat_id')
            ->leftJoin('purchase_order', 'purchase_order.task', '=', 'tasks.task_id')
            ->leftJoin('xin_suppliers', 'xin_suppliers.supplier_id', '=', 'purchase_order.supplier_id')
            ->where('tasks.task_projectid', $id)->get();
        // print_r($budgtrepo);die;
        foreach ($budgtrepo as $budget) {
            $budget->purchase_order_total = DB::table('purchase_order')->where('task', $budget->task_id)->where('mile_stone', $budget->task_cat_id)->where('project_id', $id)->sum('order_total');
            $budget->purchase_order_total_format = number_format($budget->purchase_order_total, 2);
        }
        // return $budgtrepo;

        // $grn_data = $this->noterepo->get_grn_data($a ?? $b);
        // $grn_data = DB::table('purchase_requistion')->where('project_id', $id)->get();
        $notes = $this->noterepo->search();
        if ($notes) {
            foreach ($notes as $note) {
                $this->applyPermissions($note);
            }
        }
        //reponse payload
        $payload = [
            'page' => $page,
            'notes' => $notes,
            'grn_data' => $budgtrepo,
            'templete_category' => $templete_category,

        ];
        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new  note
     * @param object CategoryRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo)
    {

        //get tags
        $tags = $this->tagrepo->getByType('notes');

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('create'),
            'tags' => $tags,
        ];

        //show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created note in storage.
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'pp' => [
                'required',
                new NoTags,
            ],
            'remark' => [
                'required',
                new NoTags,
            ],
            'pur_date' => 'required',
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

        $id = 0;
        $a = request('prqresource_id');
        $b = request('noteresource_id');
        if ($a) {
            $id = $a;
        } else {
            $id = $b;
        }
        $grn_data = $this->noterepo->get_grn_data($id);
        //create the note
        $res = "Waiting for Confirmation";
        $data = array(
            'project_id' => $id,
            'customer_id' => request('customer_id'),
            'required_date' => request('pur_date'),
            'purchase' => request('pp'),
            'location' => request('location'),
            'site_address' => request('s_address'),
            'created_by' => auth()->user()->id,
            'created_datetime' => date('Y-m-d h:i:s'),
            'status' => $res
        );
        $r = DB::table('purchase_requistion')->insertGetId($data);


        if (count(request('product_id')) > 0 || $r > 0) {

            $k = 1;
            for ($i = 0; $i < count(request('product_id')); $i++) {

                $data_opt = array(
                    'purchase_requistion_id' => $r,
                    'product_id' => request('product_id')[$k],
                    'qty' => request('quantity')[$k],
                    'remark' => request('remark')[$k],
                    'description' => request('description')[$k],
                    'created_by' => auth()->user()->id,
                    'created_datetime' => date('Y-m-d h:i:s'),

                );
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
    public function store_data_budget()
    {

        $dd = DB::table('budget_task_data')->where('category_id', request('category_id'))->where('task_name', request('Task'))->value('amount');
        if ($dd) {

            DB::table('budget_task_data')->where('category_id', request('category_id'))->where('task_name', request('Task'))->update(['amount' => request('amount')]);
        } else {
            DB::table('budget_task_data')->insert(['category_id' => request('category_id'), 'task_name' => request('Task'), 'amount' => request('amount')]);
        }
        return 1;
    }
    /**
     * display a note via ajax modal
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        //get the note
        // $note = $this->noterepo->search($id);

        //note not found
        // if (!$note = $note->first()) {
        //     abort(409, __('lang.note_not_found'));
        // }
        $templete_category = $this->template_array();
        $note = DB::table('purchase_requistion')->where('purchase_requistion_id', $id)->get();
        // $note = $this->noterepo->search($id);
        $note_item = DB::table('purchase_requistion_item_mapping')->where('purchase_requistion_id', $id)->get();
        $tags = $this->tagrepo->getByResource('note', $id);
        // print_r($note[0]);die;
        //reponse payload
        $payload = [
            'note' => $note,
            'a' => 1,
            'page' => $this->pageSettings('show'),
            'note' => $note[0],
            'note_item' => $note_item,
            'tags' => $tags,
            'templete_category' => $templete_category,
        ];

        //process reponse
        return new ShowResponse($payload);
        // return new EditResponse($payload);
    }
    public function Get_product_details()
    {

        echo json_encode(DB::table('product')->where('product_id', request('id'))->get());
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
        //get tags
        $tags = $this->tagrepo->getByResource('note', $id);

        //note not found
        if (!$note = $note->first()) {
            abort(409, __('lang.note_not_found'));
        }

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('edit'),
            'note' => $note,
            'a' => 0,
            'note_item' => $note_item,
            'tags' => $tags,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Update the specified note in storage.
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'pp' => [
                'required',
                new NoTags,
            ],
            'remark' => [
                'required',
                new NoTags,
            ],
            'pur_date' => 'required',
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

        $data = array(
            'project_id' => request('project_id'),
            'customer_id' => request('customer_id'),
            'required_date' => request('pur_date'),
            'purchase' => request('pp'),
            'location' => request('location'),
            'site_address' => request('s_address'),
            'created_by' => auth()->user()->id,
            'created_datetime' => date('Y-m-d h:i:s'),
            'status' => $res
        );
        $r = DB::table('purchase_requistion')->where('purchase_requistion_id', request('uniq_id'))->update($data);

        DB::table('purchase_requistion_item_mapping')->where('purchase_requistion_id', request('uniq_id'))->delete();

        if (count(request('product_id')) > 0 || $r > 0) {

            $k = 1;
            for ($i = 0; $i < count(request('product_id')); $i++) {

                $data_opt = array(
                    'purchase_requistion_id' => request('uniq_id'),
                    'product_id' => request('product_id')[$k],
                    'qty' => request('quantity')[$k],
                    'remark' => request('remark')[$k],
                    'description' => request('description')[$k],
                    'created_by' => auth()->user()->id,
                    'created_datetime' => date('Y-m-d h:i:s'),

                );
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
        $id = 0;
        $a = request('prqresource_id');
        $b = request('noteresource_id');
        if ($a) {
            $id = $a;
        } else {
            $id = $b;
        }

        $grn_data = $this->noterepo->get_grn_data($id);
        //reponse payload
        $payload = [
            'notes' => $notes,
            'grn_data' => $grn_data
        ];

        //generate a response
        return new UpdateResponse($payload);
    }

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
        $note->delete();

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
        $id = 0;
        $a = request('prqresource_id');
        $b = request('noteresource_id');
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
            'dynamic_search_url' => url('budget/search?action=search&noteresource_id=' . $id . '&noteresource_type=' . request('noteresource_type')),
            'add_button_classes' => 'add-edit-note-button',
            'load_more_button_route' => 'notes',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => "Material Requisition Form (MRF)",
            'add_modal_create_url' => url('budget/create?noteresource_id=' . $id . '&noteresource_type=' . request('noteresource_type')),
            'add_modal_action_url' => url('budget?noteresource_id=' . $id . '&noteresource_type=' . request('noteresource_type')),
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
