<?php

namespace App\Http\Middleware\Api\Projects;

use Closure;

class Index
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //admin user permission
        if (auth('api')->user()->is_team) {
            if (auth('api')->user()->role->role_projects >= 1) {
                //[limit] - for users with only local level scope
                if (auth('api')->user()->role->role_projects_scope == 'own') {
                    request()->merge(['filter_my_projects' => array(auth()->id())]);
                }
                //toggle 'my projects' button opntions
                // $this->toggleOwnFilter();

                return $next($request);
            }
        }
        // return $next($request);

        return response()->json(['status' => 'Unauthorized'], 401);
    }
}
