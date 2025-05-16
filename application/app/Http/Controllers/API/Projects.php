<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProjectRepository;
use App\Repositories\TagRepository;
use App\Repositories\TimerRepository;
use App\Permissions\ProjectPermissions;

class Projects extends Controller
{
    /**
     * The project repository instance.
     */
    protected $projectrepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    /**
     * The project permission instance.
     */
    protected $projectpermissions;

    public function __construct(
        ProjectRepository $projectrepo,
        ProjectPermissions $projectpermissions,
        TagRepository $tagrepo
    ) 
    {
        //parent
        parent::__construct();

        //authenticated
        $this->middleware('jwt.verify');

        //Permissions on methods
        $this->middleware('api.projectsMiddlewareIndex')->only([
            'index',
            'latest',
        ]);

        $this->middleware('api.projectsMiddlewareShow')->only([
            'show',
        ]);

        //vars
        $this->projectrepo = $projectrepo;
        $this->tagrepo = $tagrepo;
        // $this->userrepo = $userrepo;
        $this->projectpermissions = $projectpermissions;
    }


    /**
     * Display a listing of projects
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //get team projects
        $projects = $this->projectrepo->search();

        //apply some permissions
        if ($projects) {
            foreach ($projects as $project) {
                $this->applyPermissions($project);
            }
        }

        //show the view
        return response()->json($projects);
    }

    /**
     * Display a latest listing of projects
     * @return \Illuminate\Http\Response
     */
    public function latest() {

        //get team projects
        $projects = $this->projectrepo->search('', ['limit' => 3]);

        //apply some permissions
        if ($projects) {
            foreach ($projects as $project) {
                $this->applyPermissions($project);
            }
        }

        //show the view
        return response()->json($projects);
    }

    /**
     * Display a stats of projects
     * @return \Illuminate\Http\Response
     */
    public function stats() {

        $stats = $this->statsWidget();

        //show the view
        return response()->json($stats);
    }

    /**
     * Display the specified project
     * @param object TimerRepository instance of the repository
     * @param int $id project id
     * @return \Illuminate\Http\Response
     */
    public function show(TimerRepository $timerrepo, $id) {

        //get the project
        $projects = $this->projectrepo->search($id);

        //project
        $project = $projects->first();

        //set page
        // $page = $this->pageSettings('project', $project);

        //refresh project
        $this->projectrepo->refreshProject($project);

        //apply permissions
        $this->applyPermissions($project);

        //get tags
        $tags_resource = $this->tagrepo->getByResource('project', $id);
        $tags_user = $this->tagrepo->getByType('project');
        $tags = $tags_resource->merge($tags_user);

        //clients contacts
        $contacts = \App\Models\User::where('clientid', $project['project_clientid'])->where('type', 'client')->get();

        //set intitial loading of timeline
        // $page['dynamic_url'] = url('timeline/project?source=ext&timelineresource_type=project&timelineresource_id=' . $project->project_id);

        /** --------------------------------------------------------------------------------
         *  mark general project event-tracking events as 'read'. Excluding the following,
         *  which must only be marked as read, when the actual content item has been viewed
         *  [excluding]
         *         - Task, Invoice, Estimate, Ticket, comment, file
         *
         * -------------------------------------------------------------------------------*/
        \App\Models\EventTracking::where('resource_id', $id)
            ->where('resource_type', 'project')
            ->whereNotIn('eventtracking_source', ['task', 'ticket', 'invoice', 'estimate', 'file', 'comment'])
            ->where('eventtracking_userid', auth()->id())
            ->update(['eventtracking_status' => 'read']);

        //stats - time logged
        $time_logged = $timerrepo->projectLoggedHours([
            'timer_projectid' => $id,
            'timer_billing_status' => 'all',
            'return' => 'seconds',
        ]);

        //reponse payload
        $payload = [
            // 'page' => $page,
            'project' => $project,
            'time_logged' => $time_logged,
            'tags' => $tags,
            'contacts' => $contacts,
        ];

        //response
        return response()->json($payload);
    }

    /**
     * pass the project through the ProjectPermissions class and apply user permissions.
     * @param object project instance of the project model object
     * @return object
     */
    private function applyPermissions($project = '') {

        //sanity - make sure this is a valid project object
        if ($project instanceof \App\Models\Project) {
            //edit permissions
            $project->permission_edit_project = $this->projectpermissions->check('edit', $project);
            //delete permissions
            $project->permission_delete_project = $this->projectpermissions->check('delete', $project);
        }
    }

    /**
     * data for the stats widget
     * @return array
     */
    private function statsWidget($data = array()) {

        //get expense (all rows - for stats etc)
        $count_all = $this->projectrepo->search('', ['stats' => 'count-all']);
        $count_in_progress = $this->projectrepo->search('', ['stats' => 'count-in-progress']);
        $count_on_hold = $this->projectrepo->search('', ['stats' => 'count-on-hold']);
        $count_completed = $this->projectrepo->search('', ['stats' => 'count-completed']);

        //default values
        $stats = [
            [
                'value' => $count_all,
                'title' => __('lang.all'),
                'percentage' => '100%',
                'color' => 'bg-info',
            ],
            [
                'value' => $count_in_progress,
                'title' => __('lang.in_progress'),
                'percentage' => '100%',
                'color' => 'bg-primary',
            ],
            [
                'value' => $count_on_hold,
                'title' => __('lang.on_hold'),
                'percentage' => '100%',
                'color' => 'bg-danger',
            ],
            [
                'value' => $count_completed,
                'title' => __('lang.completed'),
                'percentage' => '100%',
                'color' => 'bg-success',
            ],
        ];

        //return
        return $stats;
    }
}
