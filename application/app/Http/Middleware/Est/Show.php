<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [show] precheck processes for tasks
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Est;
use App\Models\Task;
use App\Permissions\TaskPermissions;
use Closure;
use Log;

class Show {

    /**
     * The permisson repository instance.
     */
    protected $taskpermissions;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(TaskPermissions $taskpermissions) {

        //task permissions repo
        $this->taskpermissions = $taskpermissions;

    }

    /**
     * Check user permissions to edit a task
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //fronend
        $this->fronteEnd();

        //task id
        $task_id = $request->route('task');

        //does the task exist
        if ($task_id == '' || !$task = \App\Models\Task::Where('task_id', $task_id)->first()) {
            Log::error("task could not be found", ['process' => '[permissions][tasks][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'task id' => $task_id ?? '']);
            abort(404);
        }

        //permission on each one
        if ($this->taskpermissions->check('view', $task_id)) {
            return $next($request);
        }

        //no items were passed with this request
        Log::error("permission denied", ['process' => '[permissions][tasks][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'task id' => $task_id ?? '']);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {


        //default visibilities
        config([
            'visibility.tasks_card_assigned' => true,
        ]);


    }

}
