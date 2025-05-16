<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for projects
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Log;
use Warehouse;

class ProjectRepository
{

    /**
     * The projects repository instance.
     */
    protected $projects;

    /**
     * Inject dependecies
     */
    public function __construct(Project $projects)
    {
        $this->projects = $projects;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @param array $data optional data payload
     * @return object project collection
     */
    public function search($id = '', $data = [])
    {

        $projects = $this->projects->newQuery();

        //default - always apply filters
        if (!isset($data['apply_filters'])) {
            $data['apply_filters'] = true;
        }

        // select all
        $projects->leftJoin('clients', 'clients.client_id', '=', 'projects.project_clientid')
            ->leftJoin('categories', 'categories.category_id', '=', 'projects.project_categoryid')
            ->leftJoin('users', 'users.id', '=', 'projects.project_creatorid')
            ->leftJoin('xin_designations as supervisor_designation', 'supervisor_designation.designation_id', '=', 'projects.Supervisor');
        // ->leftJoin('xin_designations as engineer_designation', 'engineer_designation.designation_id', '=', 'projects.Engineer');

        $projects->selectRaw('*');

        //count al tasks
        $projects->selectRaw("(SELECT COUNT(*)
                                      FROM tasks
                                      WHERE task_projectid = projects.project_id)
                                      AS count_all_tasks");



        //count completed tasks
        $projects->selectRaw("(SELECT COUNT(*)
                                      FROM tasks
                                      WHERE task_projectid = projects.project_id
                                      AND task_status = 'completed')
                                      AS count_completed_tasks");

        //project progress - task based
        $projects->selectRaw("(SELECT COALESCE(count_completed_tasks/count_all_tasks*100, 0))
                                               AS task_based_progress");

        //sum invoices: all
        $projects->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0.00)
                                      FROM invoices
                                      WHERE bill_projectid = projects.project_id)
                                      AS sum_invoices_all");

        //sum invoices: due
        $projects->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0.00)
                                      FROM invoices
                                      WHERE bill_projectid = projects.project_id
                                      AND bill_status = 'due')
                                      AS sum_invoices_due");

        //sum invoices: overdue
        $projects->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0.00)
                                      FROM invoices
                                      WHERE bill_projectid = projects.project_id
                                      AND bill_status = 'overdue')
                                      AS sum_invoices_overdue");

        //sum invoices: paid
        $projects->selectRaw("(SELECT COALESCE(SUM(bill_final_amount), 0.00)
                                      FROM invoices
                                      WHERE bill_projectid = projects.project_id
                                      AND bill_status = 'paid')
                                      AS sum_invoices_paid");
        //default where
        $projects->whereRaw("1 = 1");

        //params: project id
        if (is_numeric($id)) {
            $projects->where('project_id', $id);
        }

        //[data filter] - clients
        if (isset($data['project_clientid'])) {
            $projects->where('project_clientid', $data['project_clientid']);
        }

        //[data filter] resource_id
        if (isset($data['projectresource_id'])) {
            $projects->where('projectresource_id', $data['projectresource_id']);
        }

        //[data filter] resource_type
        if (isset($data['projectresource_type'])) {
            $projects->where('projectresource_type', $data['projectresource_type']);
        }

        //do not show items that not yet ready (i.e exclude items in the process of being cloned that have status 'invisible')
        $projects->where('project_visibility', 'visible');

        //apply filters
        if ($data['apply_filters']) {

            //filter project id
            if (request()->filled('filter_project_id')) {
                $projects->where('project_id', request('filter_project_id'));
            }

            //filter clients
            if (request()->filled('filter_project_clientid')) {
                $projects->where('project_clientid', request('filter_project_clientid'));
            }

            //filter: start date (start)
            if (request()->filled('filter_start_date_start')) {
                $projects->where('project_date_start', '>=', request('filter_start_date_start'));
            }

            //filter: due date (end)
            if (request()->filled('filter_start_date_end')) {
                $projects->where('project_date_start', '<=', request('filter_start_date_end'));
            }

            //filter: due date (start)
            if (request()->filled('filter_due_date_start')) {
                $projects->where('project_date_due', '>=', request('filter_due_date_start'));
            }

            //filter: start date (end)
            if (request()->filled('filter_due_date_end')) {
                $projects->where('project_date_due', '<=', request('filter_due_date_end'));
            }

            //resource filtering
            if (request()->filled('projectresource_type') && request()->filled('projectresource_id')) {
                switch (request('projectresource_type')) {
                    case 'client':
                        $projects->where('project_clientid', '>=', request('projectresource_id'));
                        break;
                }
            }

            //stats: - counting
            if (isset($data['stats']) && $data['stats'] == 'count-in-progress') {
                $projects->where('project_status', 'in_progress');
            }

            //stats: - counting
            if (isset($data['stats']) && $data['stats'] == 'count-on-hold') {
                $projects->where('project_status', 'on_hold');
            }

            //stats: - counting
            if (isset($data['stats']) && $data['stats'] == 'count-completed') {
                $projects->where('project_status', 'completed');
            }

            //filter category
            if (is_array(request('filter_project_categoryid')) && !empty(array_filter(request('filter_project_categoryid')))) {
                $projects->whereIn('project_categoryid', request('filter_project_categoryid'));
            }

            //filter status
            if (is_array(request('filter_project_status')) && !empty(array_filter(request('filter_project_status')))) {
                $projects->whereIn('project_status', request('filter_project_status'));
            }

            //filter assigned
            if (is_array(request('filter_assigned')) && !empty(array_filter(request('filter_assigned')))) {
                $projects->whereHas('assigned', function ($query) {
                    $query->whereIn('projectsassigned_userid', request('filter_assigned'));
                });
            }

            //filter my projects (using the actions button)
            if (request()->filled('filter_my_projects')) {
                //projects assigned to me
                $projects->whereHas('assigned', function ($query) {
                    $query->whereIn('projectsassigned_userid', [auth()->id()]);
                });
            }

            //filter: tags
            if (is_array(request('filter_tags')) && !empty(array_filter(request('filter_tags')))) {
                $projects->whereHas('tags', function ($query) {
                    $query->whereIn('tag_title', request('filter_tags'));
                });
            }
        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $projects->where(function ($query) {
                $query->Where('project_id', '=', request('search_query'));
                $query->orWhere('project_date_start', 'LIKE', '%' . date('Y-m-d', strtotime(request('search_query'))) . '%');
                $query->orWhere('project_date_due', 'LIKE', '%' . date('Y-m-d', strtotime(request('search_query'))) . '%');
                //$query->orWhereRaw("YEAR(project_date_start) = ?", [request('search_query')]); //example binding - buggy
                //$query->orWhereRaw("YEAR(project_date_due) = ?", [request('search_query')]); //example binding - buggy
                $query->orWhere('project_title', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('project_status', '=', request('search_query'));
                $query->orWhereHas('tags', function ($q) {
                    $q->where('tag_title', 'LIKE', '%' . request('search_query') . '%');
                });
                $query->orWhereHas('category', function ($q) {
                    $q->where('category_name', 'LIKE', '%' . request('search_query') . '%');
                });
                $query->orWhereHas('client', function ($q) {
                    $q->where('client_company_name', 'LIKE', '%' . request('search_query') . '%');
                    // $q->where('f_name', 'LIKE', '%' . request('search_query') . '%');
                });
                $query->orWhereHas('assigned', function ($q) {
                    $q->where('f_name', '=', request('search_query'));
                    // $q->where('last_name', '=', request('search_query'));
                });
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('projects', request('orderby'))) {
                $projects->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
                case 'project_client':
                    $projects->orderBy('client_company_name', request('sortorder'));
                    break;
                case 'category':
                    $projects->orderBy('category_name', request('sortorder'));
                    break;
            }
        } else {
            //default sorting
            $projects->orderBy(
                config('settings.ordering_projects.sort_by'),
                config('settings.ordering_projects.sort_order')
            );
        }

        //eager load
        $projects->with([
            'tags',
            'assigned',
            'managers',
        ]);

        //stats - count all
        if (isset($data['stats']) && in_array($data['stats'], [
            'count-all',
            'count-in-progress',
            'count-on-hold',
            'count-completed',
        ])) {
            return $projects->count();
        }

        // Get the results and return them.
        if (isset($data['limit']) && is_numeric($data['limit'])) {
            $limit = $data['limit'];
        } else {
            $limit = config('system.settings_system_pagination_limits');
        }
        // print_r($limit);die;
        return $projects->paginate($limit);
    }
    public function Get_Employee($de)
    {
        $data = DB::table('xin_employees as e')
            ->join('xin_designations as d', 'd.designation_id', '=', 'e.designation_id')
            ->where('d.designation_name', '=', $de)
            ->select('e.user_id', 'e.first_name', 'e.last_name')
            ->get();

        return $data;
    }
    public function Get_Employee_ById_En($pid, $id)
    {
        $data = DB::table('projects as e')
            // ->join('xin_designations as d', 'd.designation_id', '=', 'e.designation_id')
            ->where('e.Engineer', '=', $id)
            ->where('e.project_id', '=', $pid)
            // ->select('e.user_id', 'e.first_name', 'e.last_name')
            ->get();
        if (count($data) > 0) {
            return 1;
        } else {

            return 0;
        }
    }
    public function Get_Employee_ById_Sn($pid, $id)
    {
        $data = DB::table('projects as e')
            // ->join('xin_designations as d', 'd.designation_id', '=', 'e.designation_id')
            ->where('e.Supervisor', '=', $id)
            ->where('e.project_id', '=', $pid)
            // ->select('e.user_id', 'e.first_name', 'e.last_name')
            ->get();
        if (count($data) > 0) {
            return 1;
        } else {

            return 0;
        }
    }

    /**
     * Create a new record
     * @return mixed int|bool project model object or false
     */
    public function create()
    {

        //save new user
        $project = new $this->projects;
        $defectsLiabilityPeriod = Carbon::createFromFormat('d-m-Y', request('defects_liability_period'))->format('Y-m-d');

        //data
        $project->project_cat = request('project_cat');
        $project->project_sn = request('project_sn');
        $project->project_code = request('project_code');
        $project->project_title = request('project_title');
        $project->project_clientid = request('project_clientid');
        $project->project_creatorid = auth()->id();
        // $project->Supervisor = request('Supervisor');
        // $project->employee_postal = request('employee_postal');

        // $project->Engineer = request('Engineer');
        $project->project_description = request('project_description');
        $project->project_categoryid = request('project_categoryid');
        $project->project_date_start = request('project_date_start');
        $project->extension_of_time_period = request('extension_of_time_period');
        $project->project_date_due = request('project_date_due');
        // $project->latitude = request('latitude');
        // $project->longitude = request('longitude');

        $project->defects_liability_period = $defectsLiabilityPeriod;
        // $project->warehouse_id = $this->addWarehouse();

        if (auth()->user()->role->role_projects_billing == 2) {
            $project->project_billing_type = (in_array(request('project_billing_type'), ['hourly', 'fixed'])) ? request('project_billing_type') : 'hourly';
            $project->project_billing_rate = (is_numeric(request('project_billing_rate'))) ? request('project_billing_rate') : 0;
            $project->project_billing_estimated_hours = (is_numeric(request('project_billing_estimated_hours'))) ? request('project_billing_estimated_hours') : 0;
            $project->project_billing_costs_estimate = (is_numeric(request('project_billing_costs_estimate'))) ? request('project_billing_costs_estimate') : 0;
        }

        //progress manually
        $project->project_progress_manually = (request('project_progress_manually') == 'on') ? 'yes' : 'no';
        if (request('project_progress_manually') == 'on') {
            $project->project_progress = request('project_progress');
        }

        //default project status
        $project->project_date_start = request('project_date_start');

        //project permissions (make sure same in 'update method')
        $project->clientperm_tasks_view = (request('clientperm_tasks_view') == 'on') ? 'yes' : 'no';
        $project->clientperm_tasks_collaborate = (request('clientperm_tasks_collaborate') == 'on') ? 'yes' : 'no';
        $project->clientperm_tasks_create = (request('clientperm_tasks_create') == 'on') ? 'yes' : 'no';
        $project->clientperm_timesheets_view = (request('clientperm_timesheets_view') == 'on') ? 'yes' : 'no';
        $project->assignedperm_tasks_collaborate = (request('assignedperm_tasks_collaborate') == 'on') ? 'yes' : 'no';

        //save and return id
        if ($project->save()) {
            // $this->addWarehouse($project->project_title, $project->project_id);
            return $project->project_id;
        } else {
            Log::error("record could not be created - database error", ['process' => '[ProjectRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    private function addWarehouse()
    {
        $companyId = \DB::table('xin_employees')
            ->where('user_id', auth()->id())
            ->value('company_id');

        $data = [
            'w_name' => request('project_title'),
            'w_address' => request('project_address'),
            'w_postal_code' => $this->getPostalCode(request('project_address')),
            'org_id' => $companyId,
            'w_unit_no' => 0,
            'created_by' => auth()->id(),
            'w_type' => 'Project',

            'created_at' => now(),
        ];



        $result = DB::table('warehouse')->insertGetId($data);
        return $result;
    }
    function getPostalCode($address)
    {
        // if (preg_match('/\b\d{6}\b/', $address, $matches)) {
        //     return $matches[0];
        // }
        return null; // Return null if no postal code found
    }


    /**
     * update a record
     * @param int $id project id
     * @return mixed int|bool  project id or false
     */
    public function update($id)
    {

        $defectsLiabilityPeriod = Carbon::createFromFormat('d-m-Y', request('defects_liability_period'))->format('Y-m-d');

        //get the record
        if (!$project = $this->projects->find($id)) {
            Log::error("record could not be found", ['process' => '[ProjectRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'project_id' => $id ?? '']);
            return false;
        }
        // die;

        //general
        $project->project_title = request('project_title');
        $project->project_address = request('project_address');
        $project->project_sn = request('project_sn');
        $project->project_clientid = request('project_clientid');

        $project->project_cat = request('project_cat');
        $project->project_code = request('project_code');
        $project->project_description = request('project_description');
        $project->project_categoryid = request('project_categoryid');
        $project->project_date_start = request('project_date_start');
        $project->extension_of_time_period = request('extension_of_time_period');
        $project->project_date_due = request('project_date_due');
        $project->defects_liability_period = $defectsLiabilityPeriod;
        // $project->Supervisor = request('Supervisor');
        // $project->Engineer = request('Engineer');
        // $project->latitude = request('latitude');
        // $project->longitude = request('longitude');
        $project->employee_postal = request('employee_postal');

        //project permissions (make sure same in 'create method')
        $project->clientperm_tasks_view = (request('clientperm_tasks_view') == 'on') ? 'yes' : 'no';
        $project->clientperm_tasks_collaborate = (request('clientperm_tasks_collaborate') == 'on') ? 'yes' : 'no';
        $project->clientperm_tasks_create = (request('clientperm_tasks_create') == 'on') ? 'yes' : 'no';
        $project->clientperm_timesheets_view = (request('clientperm_timesheets_view') == 'on') ? 'yes' : 'no';
        $project->clientperm_expenses_view = (request('clientperm_expenses_view') == 'on') ? 'yes' : 'no';
        $project->assignedperm_tasks_collaborate = (request('assignedperm_tasks_collaborate') == 'on') ? 'yes' : 'no';

        if (auth()->user()->role->role_projects_billing == 2) {
            $project->project_billing_type = (in_array(request('project_billing_type'), ['hourly', 'fixed'])) ? request('project_billing_type') : 'hourly';
            $project->project_billing_rate = (is_numeric(request('project_billing_rate'))) ? request('project_billing_rate') : 0;
            $project->project_billing_estimated_hours = (is_numeric(request('project_billing_estimated_hours'))) ? request('project_billing_estimated_hours') : 0;
            $project->project_billing_costs_estimate = (is_numeric(request('project_billing_costs_estimate'))) ? request('project_billing_costs_estimate') : 0;
        }

        //progress manually
        $project->project_progress_manually = (request('project_progress_manually') == 'on') ? 'yes' : 'no';
        if (request('project_progress_manually') == 'on') {
            $project->project_progress = request('project_progress');
        }

        //save
        if ($project->save()) {
            return $project->project_id;
        } else {
            return false;
        }
    }

    /**
     * feed for projects
     *
     * @param string $status project status
     * @param string $limit assigned|null limit to projects assiged to auth() user
     * @param string $searchterm
     * @return object project model object
     */
    public function autocompleteFeed($status = '', $limit = '', $searchterm = '')
    {

        //validation
        if ($searchterm == '') {
            return [];
        }

        //start
        $query = $this->projects->newQuery();
        $query->selectRaw("project_title AS value, project_id AS id");

        //[filter] project status
        if ($status != '') {
            if ($status == 'active') {
                $query->where('project_status', '!=', 'completed');
            } else {
                $query->where('project_status', '=', $status);
            }
        }

        //[filter] search term
        $query->where('project_title', 'like', '%' . $searchterm . '%');

        //[filter] assigned
        if ($limit == 'assigned') {
            $query->whereHas('assigned', function ($q) {
                $q->whereIn('projectsassigned_userid', [auth()->user()->id]);
            });
        }

        //return
        return $query->get();
    }

    /**
     * feed for projects for a specified client
     *  - client ID is optional. If not specified, then all general projects are returned
     *
     * @param string $status project status
     * @param string $client_id clients id
     * @param string $limit assigned|null limit to projects assiged to auth() user
     * @return object project model object
     */
    public function autocompleteClientsProjectsFeed($status = '', $limit = '', $client_id = '', $searchterm = '')
    {

        //start
        $query = $this->projects->newQuery();
        $query->selectRaw("project_title AS value, project_id AS id");

        //[filter] project status
        if ($status != '') {
            if ($status == 'active') {
                $query->where('project_status', '!=', 'completed');
            } else {
                $query->where('project_status', '=', $status);
            }
        }
        //[filter] search term (optional)
        if ($searchterm != '') {
            $query->where('project_title', 'like', '%' . $searchterm . '%');
        }

        //[filter] client id
        if (is_numeric($client_id)) {
            $query->where('project_clientid', '=', $client_id);
        }

        //[filter] assigned
        if ($limit == 'assigned') {
            $query->whereHas('assigned', function ($q) {
                $q->whereIn('projectsassigned_userid', [auth()->user()->id]);
            });
        }

        //return
        return $query->get();
    }

    /**
     * feed for projects for a specified client
     *  - client ID is optional. If not specified, then all general projects are returned
     *
     * @param string $status project status
     * @param string $client_id clients id
     * @param string $limit assigned|null limit to projects assiged to auth() user
     * @return object project model object
     */
    public function autocompleteAssignedFeed($id = '')
    {

        //start
        $query = $this->projects->newQuery();
        $query->selectRaw("project_title AS value, project_id AS id");

        //[filter] project status
        if ($status != '') {
            if ($status == 'active') {
                $query->where('project_status', '!=', 'completed');
            } else {
                $query->where('project_status', '=', $status);
            }
        }

        //[filter] search term (optional)
        if ($searchterm != '') {
            $query->where('project_title', 'like', '%' . $searchterm . '%');
        }

        //[filter] client id
        if (is_numeric($client_id)) {
            $query->where('project_clientid', '=', $client_id);
        }

        //[filter] assigned
        if ($limit == 'assigned') {
            $query->whereHas('assigned', function ($q) {
                $q->whereIn('projectsassigned_userid', [auth()->user()->id]);
            });
        }

        //return
        return $query->get();
    }

    /**
     * refresh an project
     * @param mixed $project can be an project id or an project object
     * @return mixed null|bool
     */
    public function refreshProject($project)
    {

        //get the project
        if (is_numeric($project)) {
            if ($projects = $this->search($project)) {
                $project = $projects->first();
            }
        }

        //validate project
        if (!$project instanceof \App\Models\Project) {
            Log::error("record could not be found", ['process' => '[ProjectRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //update task based percentage
        if ($project->project_progress_manually == 'no') {
            //progress
            $project->project_progress = round($project->task_based_progress, 2);
        }

        //update project
        $project->save();
    }
}
