<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for tasks
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Log;

class SubtaskRepository {

    /**
     * The tasks repository instance.
     */
    protected $subtasks;

    /**
     * Inject dependecies
     */
    public function __construct(Subtask $subtasks) {
        $this->subtasks = $subtasks;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object task collection
     */
    public function search($id = '') {

        //new query
        $subtasks = $this->subtasks->newQuery();

        // all client fields
        $subtasks->selectRaw('tasks.task_title,sub_tasks.*,xin_unit.unit');
        $subtasks->join('tasks','tasks.task_id','=','sub_tasks.subtask_taskid');
        $subtasks->leftjoin('xin_unit','sub_tasks.unit_id','=','xin_unit.unit_id');


        //default where
        $subtasks->whereRaw("1 = 1");

        //filters: project id
        if (request()->filled('taskresource_id')) {
            $subtasks->where('subtask_projectid', request('taskresource_id'));
        }

        //filters: id
        if (request()->filled('filter_subtask_id')) {
            $subtasks->where('sub_task_id', request('filter_subtask_id'));
        }
        if (is_numeric($id)) {
            $subtasks->where('sub_task_id', $id);
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('subtasks', request('orderby'))) {
                $subtasks->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'total_tasks':
                $subtasks->orderBy('subtask_count_tasks_all', request('sortorder'));
                break;
            case 'pending_tasks':
                $subtasks->orderBy('subtask_count_tasks_pending', request('sortorder'));
                break;
            case 'completed_tasks':
                $subtasks->orderBy('subtask_count_tasks_completed', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $subtasks->orderBy('sub_task_id', 'asc');
        }

        return $subtasks->paginate();
    }
    public function get_units(){
        $units = $this->subtasks->newQuery();
        $units->selectRaw('*');
        $units->from('xin_unit');
        $units->whereRaw("1 = 1");
        return $units;

    }
    /**
     * Create a new record
     * @param int $position new position of the record
     * @return mixed object|bool
     */
    public function create($position = '') {

        //validate
        if (!is_numeric($position)) {
            Log::error("record could not be saved - database error", ['process' => '[TaskRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);

            // return false;
        }
        $iid=request('taskresource_id')??request('taskresource_id');

        //save new user
        $subtasks = new $this->subtasks;

        $subtasks->subtask_creatorid = auth()->id();
        $subtasks->subtask_projectid = $iid;

        $subtasks->subtask_description = request('subtask_description');
        $subtasks->subtask_detail=request('subtask_detail');
        $subtasks->unit_rate=request('unit_rate');
        $subtasks->subtask_taskid=request('tasks');

        //save and return id
        if ($subtasks->save()) {
            return $subtasks->sub_task_id;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[SubtaskRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
    public function update($id) {

        //get the record
        if (!$subtasks = $this->subtasks->find($id)) {
            Log::error("Subtask record could not be found", ['process' => '[SubtaskRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'milestone_id' => $id ?? '']);
            return false;
        }

        //general
        $subtasks->subtask_description = request('subtask_description');
        $subtasks->subtask_detail = request('subtask_detail');
        $subtasks->unit_rate = request('unit_rate');


        //save
        if ($subtasks->save()) {
            return $subtasks->sub_task_id;
        } else {
            Log::error("record could not be save - database error", ['process' => '[SubtaskRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
    /**
     * update a record
     * @param int $id record id
     * @return mixed bool or id of record
     */
    // public function timerStop($id) {

    //     //get the record
    //     if (!$item = $this->items->find($id)) {
    //         return false;
    //     }

    //     //general
    //     $item->item_categoryid = request('item_categoryid');
    //     $item->item_description = request('item_description');
    //     $item->item_unit = request('item_unit');
    //     $item->item_rate = request('item_rate');

    //     //save
    //     if ($item->save()) {
    //         return $item->item_id;
    //     } else {
    //         Log::error("record could not be updated - database error", ['process' => '[TaskRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
    //         return false;
    //     }
    // }

}
