<?php

namespace App\Http\Middleware\Api\Tasks;

use Closure;
use App\Permissions\ProjectPermissions;
use App\Permissions\TaskPermissions;

class Index
{
    /**
     * The project permisson repository instance.
     */
    protected $projectpermissons;

    /**
     * The permisson repository instance.
     */
    protected $taskpermissions;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(ProjectPermissions $projectpermissons, TaskPermissions $taskpermissions) {

        $this->projectpermissons = $projectpermissons;

        $this->taskpermissions = $taskpermissions;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //team user permission
        if (auth('api')->user()->is_team) {
            //generally
            if (auth('api')->user()->role->role_tasks >= 1) {
                if (auth('api')->user()->role->role_tasks_scope == 'own') {
                    request()->merge(['filter_my_tasks' => array(auth('api')->id())]);
                }
                return $next($request);
            }
        }

        return response()->json(['status' => 'Unauthorized'], 401);
    }
}
