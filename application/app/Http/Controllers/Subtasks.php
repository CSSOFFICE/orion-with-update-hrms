<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for tasks
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\SubtaskRepository;
use App\Http\Responses\Subtasks\IndexResponse;
use App\Http\Responses\Subtasks\CreateResponse;
use App\Http\Responses\Subtasks\EditResponse;
use App\Http\Responses\Subtasks\UpdateResponse;
use App\Http\Responses\Subtasks\DestroyResponse;
use App\Http\Responses\Subtasks\StoreResponse;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class Subtasks extends Controller
{

    protected $subtaskrepo;

    public function __construct(
        SubtaskRepository $subtaskrepo
    ) {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('subtasksMiddlewareIndex')->only([
            'index',
        ]);
        $this->subtaskrepo = $subtaskrepo;
    }
    public function index()
    {

        //basic page settings
        $page = $this->pageSettings('subtasks');

        //get project milestones

        $subtasks = $this->subtaskrepo->search();

        //reponse payload
        $payload = [
            'page' => $page,
            'subtasks' => $subtasks,
        ];

        //show the view
        return new IndexResponse($payload);
    }
    public function create()
    {
        $page = $this->pageSettings('create');
        $tak = DB::table('tasks')->get();

        $payload = [
            'page' => $page,
            'task' => $tak,


        ];

        //show the form
        return new CreateResponse($payload);
    }

    public function editSubtasks($id)
    {
        //page settings
        $page = $this->pageSettings('edit');

        //get the milestone
        $subtask = $this->subtaskrepo->search($id);

        $unit = $this->subtaskrepo->get_units();

        //not found
        if (!$subtask = $subtask->first()) {
            abort(409, __('lang.subtask_not_found'));
        }
        $tak = DB::table('tasks')->get();
        //reponse payload
        $payload = [
            'page' => $page,
            'subtask' => $subtask,
            'unit' => $unit,
            'task' => $tak,
        ];

        //response
        return new EditResponse($payload);
    }
    public function store()
    {

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'tasks' => 'required',
            'subtask_description' => 'required',
            'subtask_detail' => 'required',
            'unit_rate' => 'required',
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


        //create the milestone
        if (!$sub_task_id = $this->subtaskrepo->create()) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }


        //get the milestone object (friendly for rendering in blade template)
        $subtasks = $this->subtaskrepo->search($sub_task_id);

        //counting rows
        $rows = $this->subtaskrepo->search();
        $count = $rows->total();

        //reponse payload
        $payload = [
            'subtasks' => $subtasks,
            'count' => $count,
        ];

        //process reponse
        return new StoreResponse($payload);
    }
    public function update($id)
    {

        //custom error messages
        $messages = [];

        //validate
        $validator = Validator::make(request()->all(), [
            'subtask_description' => 'required',
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
        if (!$this->subtaskrepo->update($id)) {
            abort(409);
        }

        //get milestone
        $subtasks = $this->subtaskrepo->search($id);

        //reponse payload
        $payload = [
            'subtasks' => $subtasks,
        ];

        //generate a response
        return new UpdateResponse($payload);
    }
    public function destroy($id)
    {

        //get the milestone
        $subtasks = $this->subtaskrepo->search($id);
        $subtasks = $subtasks->first();
        $subtasks->delete();
        //are we deleteing the tasks
        if (request('delete_milestone_tasks') == 'on') {


            $subtasks->delete();
        }

        //reponse payload
        $payload = [
            'sub_task_id' => $id,
        ];

        //process reponse
        return new DestroyResponse($payload);
    }
    private function pageSettings($section = '', $data = [])
    {

        //common settings
        $page = [
            'crumbs' => [
                __('lang.subtask'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'subtasks',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_subtasks' => 'active',
            'sidepanel_id' => 'sidepanel-filter-tasks',
            'dynamic_search_url' => url('subtasks/search?action=search&taskresource_id=' . request('taskresource_id') . '&taskresource_type=' . request('taskresource_type')),
            'add_button_classes' => '',
            'load_more_button_route' => 'subtasks',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => __('lang.subtask'),
            'add_modal_create_url' => url('subtasks/create?taskresource_id=' . request('taskresource_id') . '&taskresource_type=' . request('taskresource_type')),
            'add_modal_action_url' => url('subtasks?taskresource_id=' . request('taskresource_id') . '&taskresource_type=' . request('taskresource_type') . '&count=' . ($data['count'] ?? '')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //tasks list page
        if ($section == 'subtasks') {
            $page += [
                'meta_title' => __('lang.subtask'),
                'heading' => __('lang.subtask'),
                'mainmenu_subtasks' => 'active',
            ];
            return $page;
        }

        //task page
        if ($section == 'subtasks') {
            //adjust
            $page['page'] = 'subtasks';
            //add
            $page += [];
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
}
