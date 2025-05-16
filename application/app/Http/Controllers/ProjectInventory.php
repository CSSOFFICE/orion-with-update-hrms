<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for notes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Rules\NoTags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\TagRepository;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Validator;
use App\Permissions\ProjectInventoryPermissions;
use App\Repositories\ProjectInventoryRepository;
use App\Http\Responses\ProjectInventory\EditResponse;
use App\Http\Responses\ProjectInventory\ShowResponse;
use App\Http\Responses\ProjectInventory\IndexResponse;
use App\Http\Responses\ProjectInventory\StoreResponse;
use App\Http\Responses\ProjectInventory\CreateResponse;
use App\Http\Responses\ProjectInventory\UpdateResponse;
use App\Http\Responses\ProjectInventory\DestroyResponse;
use App\Http\Responses\ProjectInventory\InventoryReturnResponse;

class ProjectInventory extends Controller
{

    /**
     * The note repository instance.
     */
    protected $noterepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

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
        ProjectInventoryPermissions $notepermissions
    ) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');
        $this->middleware('inventaryMiddlewareIndex')->only([
            'index',
            'update',
            'store',

        ]);

        $this->middleware('inventaryMiddlewareCreate')->only([
            'create',
            'store',
        ]);
        $this->middleware('inventaryMiddlewareEdit')->only([
            'edit',

        ]);

        $this->middleware('inventaryMiddlewareDestroy')->only([
            'destroy',
        ]);

        //only needed for the [action] methods

        $this->noterepo = $noterepo;

        $this->tagrepo = $tagrepo;

        $this->userrepo = $userrepo;

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

        $a = request('projectinventoryresource_id');
        $b = request('noteresource_id');

        $grn_data = $this->noterepo->get_grn_data($a ?? $b);

        $notes = $this->noterepo->search();
        if ($notes) {
            foreach ($notes as $note) {
                $this->applyPermissions($note);
            }
        }
        $project_id = 0;
        if (request('projectinventoryresource_id')) {

            $project_id = request('projectinventoryresource_id');
            request()->merge([
                'noteresource_id' => request('projectinventoryresource_id'),
                'noteresource_type' => 'project',
            ]);
        } else {
            request()->merge([
                'projectinventoryresource_id' => request('noteresource_id'),
                'projectinventoryresource_type' => 'project',
            ]);
            $project_id = request('noteresource_id');
        }

        // $stock_move_data = DB::table('stock_move_log')->where('stock_move_log.prj_id', $project_id)->where('stock_move_log.trans_type', 'INBOUND')->whereIn('stock_move_log.from_to_type', ['supplier to project', 'warehouse to project'])
        //     ->Join('product', 'product.product_id', '=', 'stock_move_log.product_id')->select('stock_move_log.*', 'product.product_name', DB::raw('SUM(stock_move_log.qtn) as total_quantity'))
        //     ->groupBy('stock_move_log.prj_id', 'stock_move_log.product_id')->get();
        $ware_id = DB::table('projects')->where('project_id', $project_id)->value('warehouse_id');
        $stock_move_data = DB::table('stock_management')
            ->join('product', 'product.product_id', '=', 'stock_management.prd_id')
            ->select('product.product_name', 'product.product_id', 'stock_management.*', DB::raw('SUM(stock_management.quantity) as total_quantity'))

            ->where('stock_management.warehouse_id', $ware_id)
            ->groupBy('product.product_id')
            ->get();
        // print_r($stock_move_data);
        // die;
        //reponse payload
        $payload = [
            'page' => $page,
            'notes' => $notes,
            'grn_data' => $grn_data,
            'stock_move_data' => $stock_move_data,
            'project_id' => $project_id

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
        // return url()->current();

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

    public function inventory_submit(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(request()->all(), [
            'product_id' => 'required',

            'warehouse' => 'required',
            'quantity' => 'required',

        ]);
        if ($validator->fails()) {
            $error = $validator->errors();

            return response()->json(['status' => "error", 'error' => $error]);
        } else {
            // return $request->all();

            $prd_id = $request->product_id;
            $w_id = $request->warehouse;
            $quantity = $request->quantity;
            $remark = $request->remark;

            if (!empty($prd_id) && !empty($w_id) && !empty($quantity)) {
                for ($i = 0; $i < count($prd_id); $i++) {
                    // return $quantity[$i];
                    if (!empty($prd_id[$i]) && !empty($w_id[$i]) && !empty($quantity[$i])) {
                        $db_stock_management = DB::table('stock_management')->where('prd_id', $prd_id[$i])->where('warehouse_id', $w_id[$i])->first();
                        if ($db_stock_management) {
                            $db_quantity = $db_stock_management->quantity;
                            DB::table('stock_management')->where('prd_id', $prd_id[$i])->where('warehouse_id', $w_id[$i])->update(['quantity' => $db_quantity - $quantity[$i]]);
                            $stock_move_log_ins = [
                                'product_id' => $prd_id[$i],
                                'qtn' => $quantity[$i],
                                'stock_from' => $w_id[$i],
                                'stock_to' => $request->prj_id,
                                'from_to_type' => "warehouse to project",
                                'trans_type' => "OUTBOUND",
                                'movement_type' => 'Issue',
                                'wh_id' => $w_id[$i],
                                'prj_id' => $request->prj_id,
                                // 'remark' => $remark[$i],
                                'created_date' => date('Y-m-d H:i:s'),
                                'by_whome' => Auth::user()->id,
                            ];
                            DB::table('stock_move_log')->insert($stock_move_log_ins);
                        }
                    } else {
                        return "hello";
                    }
                }
                return response()->json(['status' => 'success', 'message' => 'Data Saved Successfull']);
            } else {

                return response()->json(['status' => 'failed', 'message' => 'Data Saved Failed']);
            }
        }
    }

    public function store(Request $request)
    {
        return $request->all();

        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            // 'note_title' => [
            //     'required',
            //     new NoTags,
            // ],
            // 'note_description' => 'required',
            //     'tags' => [
            //         'bail',
            //         'nullable',
            //         'array',
            //         function ($attribute, $value, $fail) {
            //             foreach ($value as $key => $data) {
            //                 if (hasHTML($data)) {
            //                     return $fail(__('lang.tags_no_html'));
            //                 }
            //             }
            //         },
            //     ],
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

        $a = request('projectinventoryresource_id');
        $b = request('noteresource_id');

        $grn_data = $this->noterepo->get_grn_data($a ?? $b);
        //create the note
        if (!$note_id = $this->noterepo->create()) {
            abort(409);
        }
        if (count(request('product_id')) > 0) {
            $data = array();
            foreach (request('product_id') as $key => $value) {

                $data = array(
                    'from_w' => request('warehouse')[$key],
                    'to_w' => request('product_id')[$key],
                    'p_id' => request('product_id')[$key],
                    'total' => request('quantity')[$key],
                    'request' => request('quantity')[$key],
                    'balance' => request('quantity')[$key],
                );
            }

            DB::table('warehouse_deduct')->insert($data);
        }


        //add tags
        $this->tagrepo->add('note', $note_id);

        //get the note object (friendly for rendering in blade template)
        $notes = $this->noterepo->search($note_id);

        //permissions
        $this->applyPermissions($notes->first());

        //counting rows
        $rows = $this->noterepo->search();
        $count = $rows->total();

        //reponse payload
        $payload = [
            'notes' => $notes,
            'count' => $count,
            'grn_data' => $grn_data
        ];

        //process reponse
        return new StoreResponse($payload);
    }

    /**
     * display a note via ajax modal
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        //get the note
        $note = $this->noterepo->search($id);

        //note not found
        if (!$note = $note->first()) {
            abort(409, __('lang.note_not_found'));
        }

        //reponse payload
        $payload = [
            'note' => $note,
        ];

        //process reponse
        return new ShowResponse($payload);
    }

    /**
     * Show the form for editing the specified  note
     * @param int $id note id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        //get the note
        $note = $this->noterepo->search($id);

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
            'note_title' => [
                'required',
                new NoTags,
            ],
            'note_description' => 'required',
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

        //update
        if (!$this->noterepo->update($id)) {
            abort(409);
        }

        //delete & update tags
        $this->tagrepo->delete('note', $id);
        $this->tagrepo->add('note', $id);

        //get note
        $notes = $this->noterepo->search($id);

        $this->applyPermissions($notes->first());

        //reponse payload
        $payload = [
            'notes' => $notes,
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

        //common settings
        $page = [
            'crumbs' => [
                "projectinventory",
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'projectinventory',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_notes' => 'active',
            'sidepanel_id' => 'sidepanel-filter-notes',
            'dynamic_search_url' => url('projectinventory/search?action=search&noteresource_id=' . request('noteresource_id') . '&noteresource_type=' . request('noteresource_type')),
            'add_button_classes' => 'add-edit-note-button',
            'load_more_button_route' => 'notes',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => "projectinventory",
            'add_modal_create_url' => url('projectinventory/create?noteresource_id=' . request('noteresource_id') . '&noteresource_type=' . request('noteresource_type')),
            'add_modal_action_url' => url('projectinventory?noteresource_id=' . request('noteresource_id') . '&noteresource_type=' . request('noteresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //notes list page
        if ($section == 'projectinventory') {
            $page += [
                'meta_title' => "projectinventory",
                'heading' => "projectinventory",
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
                'meta_title' => "projectinventory",
                'heading' => "projectinventory",
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
                    "projectinventory",
                ],
                'meta_title' => "projectinventory",
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

    function inventory_return(Request $request)
    {
        // print_r($request->total_qty);die;

        $project_id = $request->project_id;
        $product_id = $request->product_id;
        $total_quantity = $request->total_qty;
        $user_email = Auth::user()->id;
        $xin_employee = DB::table('xin_employees')->where('user_id', $user_email)->first();
        $warehouse_id = DB::table('projects')->where('project_id', $project_id)->value('warehouse_id');

        $warehouse = DB::table('warehouse')
            ->where('warehouse.org_id', $xin_employee->company_id ?? '')
            ->where('warehouse.w_id', '!=', $warehouse_id)
            ->get();


        $product = DB::table('product')->where('product_id', $product_id)->first();
        $payload = [
            'project_id' => $project_id,
            'product_id' => $product_id,
            'total_quantity' => $total_quantity,
            'warehouse' => $warehouse,
            'product' => $product
        ];

        //show the form
        return new InventoryReturnResponse($payload);
    }

    function inventory_return_submit(Request $request)
    {
        // return $request->all();
        // return $request->all();
        $validator = Validator::make(request()->all(), [
            'product_id' => 'required',

            'warehouse' => 'required',
            'quantity' => 'required',
            'prj_id' => 'required',
            'date' => 'required',

        ]);
        if ($validator->fails()) {
            $error = $validator->errors();

            return response()->json(['status' => "error", 'error' => $error]);
        } else {
            $project_id = $request->prj_id;
            $product_id = $request->product_id;
            $total_quantity = $request->quantity;
            $warehouse = $request->warehouse;
            $date = $request->date;

            $db_stock_management = DB::table('stock_management')->where('prd_id', $product_id)->where('warehouse_id', $warehouse)->first();
            if (!$db_stock_management) {
                $stock_management = [
                    'prd_id' => $product_id,
                    'quantity' => $total_quantity,
                    'warehouse_id' => $warehouse,
                ];
                $result1 = DB::table('stock_management')->insert($stock_management);
            } else {
                $db_quantity = $db_stock_management->quantity;
                $result1 = DB::table('stock_management')->where('prd_id', $product_id)->where('warehouse_id', $warehouse)->update(['quantity' => $db_quantity + $total_quantity]);
            }
            $db_stock_management_old = DB::table('stock_management')->where('prd_id', $product_id)->where('warehouse_id', request('old_ware'))->value('quantity');
            DB::table('stock_management')->where('prd_id', $product_id)->where('warehouse_id', request('old_ware'))->update(['quantity' => $db_stock_management_old - $total_quantity]);
            // print_r($db_stock_management_old - $total_quantity);die;
            if ($result1) {
                $stock_move_log_ins = [
                    'product_id' => $product_id,
                    'qtn' => $total_quantity,
                    'stock_from' => $project_id,
                    'stock_to' => $warehouse,
                    'from_to_type' => "project to warehouse",
                    'trans_type' => "INBOUND",
                    'wh_id' => $warehouse,
                    'prj_id' => $project_id,
                    'remark' => "Return from Project Site",
                    'movement_type' => "Return",
                    'created_date' => date('Y-m-d', strtotime($date)),
                    'by_whome' => Auth::user()->id,
                ];
                $result = DB::table('stock_move_log')->insert($stock_move_log_ins);
                if ($result) {
                    return response()->json(['status' => 'success', 'message' => 'Data Saved Successfull']);
                } else {
                    return response()->json(['status' => 'failed', 'message' => 'Data Saved Failed']);
                }
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Data Saved Failed']);
            }
        }
    }
}
