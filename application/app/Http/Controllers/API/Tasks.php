<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Permissions\ProjectPermissions;
use App\Permissions\TaskPermissions;
use App\Repositories\TaskRepository;
use App\Repositories\TagRepository;
use App\Repositories\TimerRepository;

class Tasks extends Controller
{
    /**
     * The task repository instance.
     */
    protected $taskrepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    /**
     * The user repository instance.
     */
    protected $userrepo;

    /**
     * The timer repository instance.
     */
    protected $timerrepo;

    /**
     * The task model instance.
     */
    protected $taskmodel;

    /**
     * The comment permission instance.
     */
    protected $commentpermissions;

    /**
     * The attachment permission instance.
     */
    protected $attachmentpermissions;

    /**
     * The checklist permission instance.
     */
    protected $checklistpermissions;

    /**
     * The task permission instance.
     */
    protected $taskpermissions;

    /**
     * The event repository instance.
     */
    protected $eventrepo;

    /**
     * The event tracking repository instance.
     */
    protected $trackingrepo;

    /**
     * The emailer repository
     */
    protected $emailerrepo;

    public function __construct(
        TaskRepository $taskrepo,
        TagRepository $tagrepo,
        // UserRepository $userrepo,
        TimerRepository $timerrepo,
        TaskPermissions $taskpermissions
        // CommentPermissions $commentpermissions,
        // AttachmentPermissions $attachmentpermissions,
        // ChecklistPermissions $checklistpermissions,
        // EventRepository $eventrepo,
        // EventTrackingRepository $trackingrepo,
        // EmailerRepository $emailerrepo,
        // Task $taskmodel
    ) {

        //core controller instantation
        parent::__construct();

        $this->taskrepo = $taskrepo;
        $this->tagrepo = $tagrepo;
        // $this->userrepo = $userrepo;
        $this->taskpermissions = $taskpermissions;
        // $this->taskmodel = $taskmodel;
        // $this->commentpermissions = $commentpermissions;
        // $this->attachmentpermissions = $attachmentpermissions;
        // $this->checklistpermissions = $checklistpermissions;
        $this->timerrepo = $timerrepo;
        // $this->eventrepo = $eventrepo;
        // $this->trackingrepo = $trackingrepo;
        // $this->emailerrepo = $emailerrepo;

        //authenticated
        $this->middleware('jwt.verify');

        //route middleware
        // $this->middleware('tasksMiddlewareTimer')->only([
        //     'timerStart',
        //     'timerStop',
        //     'timerStopAll',
        // ]);

        //Permissions on methods
        $this->middleware('api.tasksMiddlewareIndex')->only([
            'index',
            // 'update',
            // 'toggleStatus',
            // 'store',
            // 'updateStartDate',
            // 'updateDueDate',
            // 'updateStatus',
            // 'updatePriority',
            // 'updateVisibility',
            // 'updateMilestone',
            // 'updateAssigned',
            // 'timerStart',
            // 'timerStop',
            // 'timerStopAll',
        ]);

        // $this->middleware('tasksMiddlewareCreate')->only([
        //     'create',
        //     'store',
        // ]);

        // $this->middleware('tasksMiddlewareShow')->only([
        //     'show',
        // ]);

        // $this->middleware('tasksMiddlewareEdit')->only([
        //     'updateDescription',
        //     'updateTitle',
        //     'updateStartDate',
        //     'updateDueDate',
        //     'updateStatus',
        //     'updatePriority',
        //     'updateVisibility',
        //     'updateMilestone',
        //     'updateAssigned',
        //     'storeChecklist',
        // ]);

        // $this->middleware('tasksMiddlewareParticipate')->only([
        //     'storeComment',
        //     'attachFiles',
        // ]);

        // $this->middleware('tasksMiddlewareDeleteAttachment')->only([
        //     'deleteAttachment',
        // ]);

        // $this->middleware('tasksMiddlewareDownloadAttachment')->only([
        //     'downloadAttachment',
        // ]);

        // $this->middleware('tasksMiddlewareDeleteComment')->only([
        //     'deleteComment',
        // ]);

        // $this->middleware('tasksMiddlewareEditDeleteChecklist')->only([
        //     'updateChecklist',
        //     'deleteChecklist',
        //     'toggleChecklistStatus',
        // ]);

        // $this->middleware('tasksMiddlewareDestroy')->only([
        //     'destroy',
        // ]);

        // $this->middleware('tasksMiddlewareAssign')->only([
        //     'updateAssigned',
        // ]);
    }

    /**
     * Display a listing of tasks
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //defaults
        $milestones = [];

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

        //basic page settings
        // $page = $this->pageSettings('tasks', ['count' => $count]);

        //page setting for embedded view
        // if (request('source') == 'ext') {
        //     $page = $this->pageSettings('ext', ['count' => $count]);
        // }

        //get all tags (type: lead) - for filter panel
        $tags = $this->tagrepo->getByType('task');

        //get all milestones if viewing from project page (for use in filter panel)
        if (request()->filled('taskresource_id') && request('taskresource_type') == 'project') {
            $milestones = \App\Models\Milestone::Where('milestone_projectid', request('taskresource_id'))->get();
        }

        //reponse payload
        $payload = [
            // 'page' => $page,
            'milestones' => $milestones,
            'tasks' => $tasks,
            // 'stats' => $this->statsWidget(),
            'tags' => $tags,
        ];

        //show the view
        return response()->json($tasks);
    }

    /**
     * send each task for processing
     * @return null
     */
    private function processTasks($tasks = '') {
        //sanity - make sure this is a valid tasks object
        if ($tasks instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            foreach ($tasks as $task) {
                $this->processTask($task);
            }
        }
    }

    /**
     * check the task for the following:
     *    1. Check if task is assigned to me - add 'assigned_to_me' (yes/no) attribute
     *    2. check if there are any running timers on the tasks - add 'running_timer' (yes/no)
     * @param object task instance of the task model object
     * @return object
     */
    private function processTask($task = '') {

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
                if ($user->id == auth('api')->id()) {
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
                    if ($timer->timer_creatorid == auth('api')->id()) {
                        $task->timer_current_status = true;
                    }
                }
            }

            //get users current/refreshed time for the task (if applcable)
            $task->my_time = $this->timerrepo->sumTimers($task->task_id, auth('api')->id());
        }
    }

    /**
     * apply permissions.
     * @param object $task instance of the task model object
     * @return object
     */
    private function applyPermissions($task = '') {

        //sanity - make sure this is a valid task object
        if ($task instanceof \App\Models\Task) {
            //edit permissions
            $task->permission_edit_task = $this->taskpermissions->check('edit', $task);
            //delete permissions
            $task->permission_delete_task = $this->taskpermissions->check('delete', $task);
            //delete participate
            $task->permission_participate = $this->taskpermissions->check('participate', $task);
            //super user
            $task->permission_assign_users = $this->taskpermissions->check('assign-users', $task);
            //super user
            $task->permission_super_user = $this->taskpermissions->check('super-user', $task);
        }
    }
}
