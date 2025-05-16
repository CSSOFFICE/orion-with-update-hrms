<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for project assignments
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\ProjectAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;


class ProjectAssignedRepository
{

    /**
     * The assigned repository instance.
     */
    protected $assigned;

    /**
     * Inject dependecies
     */
    public function __construct(ProjectAssigned $assigned)
    {
        $this->assigned = $assigned;
    }

    /**
     * Bulk delete assigned users for a particular project
     * @param int $project_id the id of the project
     * @return bool
     */
    public function delete($project_id = '')
    {

        //validations
        if (!is_numeric($project_id)) {
            Log::error("record could not be found - database error", ['process' => '[ProjectAssignedRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $id ?? '']);
            return false;
        }

        $query = $this->assigned->newQuery();
        $query->where('projectsassigned_projectid', '=', $project_id);
        $query->delete();
    }

    /**
     * assigned new users to a project
     * @param int $project_id the id of the project
     * @param int $user_id if specified, only this user will be assigned
     * @return bool
     */
    public function add($project_id = '', $user_id = '', $index = 0)
    {

        $list = [];

        //validation
        if (!is_numeric($project_id)) {
            return $list;
        }

        //add only to the specified user
        if (is_numeric($user_id)) {
            $assigned = new $this->assigned;
            $assigned->projectsassigned_projectid = $project_id;
            $assigned->projectsassigned_userid = $user_id;
            $assigned->project_ste = request('project_site')[$index];
            // $assigned->postal_code = request('employee_postal')[$index];
            // $assigned->address = request('project_address')[$index];
            // $assigned->latitude = request('latitude')[$index];
            // $assigned->longitude = request('longitude')[$index];
            $assigned->save();
            $list[] = $user_id;
            //return array of users
            return $list;
        }

        //add each user in the post request
        if (request()->filled('assigned')) {
            // return request('assigned')[0];
            if (request()->filled('assigned')) {
                foreach (request('assigned') as $keyy => $userr) {
                    foreach ($userr as $key => $user) {

                        $assigned = new $this->assigned;
                        $assigned->projectsassigned_projectid = $project_id;
                        $assigned->projectsassigned_userid = $user;
                        $assigned->project_ste = request('project_site')[$keyy];
                        // $assigned->postal_code = request('employee_postal')[$key];
                        // $assigned->address = request('project_address')[$key];
                        // $assigned->latitude = request('latitude')[$key];
                        // $assigned->longitude = request('longitude')[$key];
                        $assigned->save();
                        $list[] = $user;
                    }
                }
                //return array of users
                return $list;
            }
            //return array of users
            return $list;
        }
        //return array of users
        return $list;
    }
    public function addSite($project_id = '')
    {
        foreach (request('project_site') as $index => $postal) {
            DB::table('project_addresses')->insert([
                'project_id' => $project_id,
                'employee_postal' => request('employee_postal')[$index],
                'project_site' => $postal,
                'project_address' => request('project_address')[$index] ?? null,
                'engineer' => request('engineer')[$index] ?? null,
                'supervisor' => request('supervisor')[$index] ?? null,
                'latitude' => request('latitude')[$index] ?? null,
                'longitude' => request('longitude')[$index] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    public function updateSite($project_id = '')
    {
        DB::table('project_addresses')->where('project_id', $project_id)->delete();
        foreach (request('project_site') as $index => $postal) {
            DB::table('project_addresses')->insert([
                'project_id' => $project_id,
                'employee_postal' => request('employee_postal')[$index],
                'project_site' => $postal,
                'project_address' => request('project_address')[$index] ?? null,
                'engineer' => request('engineer')[$index] ?? null,
                'supervisor' => request('supervisor')[$index] ?? null,
                'latitude' => request('latitude')[$index] ?? null,
                'longitude' => request('longitude')[$index] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    /**
     * get all useers assigned to a project
     * @param numeric $id the id of the resource
     * @return object assigned model object
     */
    public function getAssigned($id = '')
    {

        //validations
        if (!is_numeric($id)) {
            return [];
        }

        $query = $this->assigned->newQuery();
        $query->leftJoin('users', 'users.id', '=', 'projects_assigned.projectsassigned_userid');
        $query->where('projectsassigned_projectid', $id);
        $query->orderBy('first_name', 'ASC');
        return $query->get();
    }
}
