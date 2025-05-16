<?php

namespace App\Http\Middleware\Api\Projects;

use App\Models\Project;
use App\Permissions\ProjectPermissions;
use App\Repositories\ProjectRepository;
use Closure;

class Show
{
    //vars
    protected $projectpermissions;
    protected $projectmodel;
    protected $projectrepo;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(ProjectPermissions $projectpermissions, Project $projectmodel, ProjectRepository $projectrepo) {

        $this->projectpermissions = $projectpermissions;
        $this->projectmodel = $projectmodel;
        $this->projectrepo = $projectrepo;

    }

    /**
     * This middleware does the following:
     *   1. validates that the project exists
     *   2. checks users permissions to [show] the resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //project id
        $project_id = $request->route('project');

        //basic validation
        if (!$project = \App\Models\Project::Where('project_id', $project_id)->first()) {
            return response()->json(['status' => 'project could not be found'], 404);
        }

        //friendly format
        $projects = $this->projectrepo->search($project_id);
        $project = $projects->first();

        //permission: does user have permission to view this project
        if ($this->projectpermissions->check('view', $project)) {
            //permission granted
            return $next($request);
        }

        return response()->json(['status' => 'Unauthorized'], 401);
    }
}
