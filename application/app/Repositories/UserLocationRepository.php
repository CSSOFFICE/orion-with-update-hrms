<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for user locations
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\UsersLocation;
use Illuminate\Http\Request;
use Log;

class UserLocationRepository {

    /**
     * The user location repository instance.
     */
    protected $location;

    /**
     * Inject dependecies
     */
    public function __construct(UsersLocation $location) {
        $this->location = $location;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @param array $data optional data payload
     * @return object note collection
     */
    public function search($id = '') {

        $location = $this->location->newQuery();

        // all location fields
        $location->selectRaw('*');

        //joins
        $location->leftJoin('users', 'users.id', '=', 'users_locations.user_id');

        //default where
        $location->whereRaw("1 = 1");

        //filters: id
        if (request()->filled('filter_note_id')) {
            $location->where('note_id', request('filter_note_id'));
        }
        if (is_numeric($id)) {
            $location->where('note_id', $id);
        }

        //resource filtering
        if (request()->filled('noteresource_type') && request()->filled('noteresource_id')) {
            $location->where('noteresource_type', request('noteresource_type'));
            $location->where('noteresource_id', request('noteresource_id'));
        }

        //only public or users own private notes
        // $notes->where(function ($query) {
        //     $query->where('note_visibility', 'public');
        //     $query->orWhere('note_creatorid', auth()->id());
        // });

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query') || request()->filled('query')) {
            $location->where(function ($query) {
                $query->where('note_title', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('note_description', 'LIKE', '%' . request('search_query') . '%');
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('notes', request('orderby'))) {
                $location->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
            case 'category':
                $location->orderBy('category_name', request('sortorder'));
                break;
            }
        } else {
            //default sorting
            $location->orderBy('user_id', 'desc');
        }

        //eager load
        // $notes->with(['tags']);

        // Get the results and return them.
        return $location->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @param array $data optional data payload
     * @return object note collection
     */
    public function get_last_location($id = '') {

        $location = $this->location->newQuery();

        // all location fields
        $location->selectRaw('*');

        // last record for each users
        $latestLocations = $this->location
                            ->selectRaw('user_id, max(created_at) as create_date')
                            ->groupBy('user_id');

        //join latest records
        $location->joinSub($latestLocations, 'latest_locations', function ($join) {
            $join->on('users_locations.user_id', '=', 'latest_locations.user_id');
            $join->on('users_locations.created_at', '=', 'latest_locations.create_date');
        });

        //joins
        $location->leftJoin('users', 'users.id', '=', 'users_locations.user_id');

        //default where
        $location->whereRaw("1 = 1");

        //filters : time interval
        if(request()->filled('filter_time_interval')) {
            // $location->where(function($query) {
            //     $query->where('users_locations.created_at', '>=', 'NOW() - INTERVAL ' . request('filter_time_interval'));
            // });

            $location->whereRaw("users_locations.created_at >= NOW() - INTERVAL " . request('filter_time_interval'));
        }else {
            $location->whereRaw("users_locations.created_at >= NOW() - INTERVAL 1 HOUR");
        }

        //filters: id
        // if (request()->filled('filter_note_id')) {
        //     $location->where('note_id', request('filter_note_id'));
        // }
        // if (is_numeric($id)) {
        //     $location->where('note_id', $id);
        // }

        //resource filtering
        // if (request()->filled('noteresource_type') && request()->filled('noteresource_id')) {
        //     $location->where('noteresource_type', request('noteresource_type'));
        //     $location->where('noteresource_id', request('noteresource_id'));
        // }

        //only public or users own private notes
        // $notes->where(function ($query) {
        //     $query->where('note_visibility', 'public');
        //     $query->orWhere('note_creatorid', auth()->id());
        // });

        //search: various client columns and relationships (where first, then wherehas)
        // if (request()->filled('search_query') || request()->filled('query')) {
        //     $location->where(function ($query) {
        //         $query->where('note_title', 'LIKE', '%' . request('search_query') . '%');
        //         $query->orWhere('note_description', 'LIKE', '%' . request('search_query') . '%');
        //     });
        // }

        //sorting
        // if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
        //     //direct column name
        //     if (Schema::hasColumn('notes', request('orderby'))) {
        //         $location->orderBy(request('orderby'), request('sortorder'));
        //     }
        //     //others
        //     switch (request('orderby')) {
        //     case 'category':
        //         $location->orderBy('category_name', request('sortorder'));
        //         break;
        //     }
        // } else {
            
        // }

        //default sorting
        $location->orderBy('users_locations.id', 'desc');

        // Get the results and return them.
        return $location->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Create a new record
     * @return mixed int|bool
     */
    public function create($user_id = '') {

        //save new location
        $location = new $this->location;

        //data
        $location->user_id = $user_id;
        $location->latitude = request('latitude');
        $location->longitude = request('longitude');
        $location->altitude = request('altitude');
        $location->altitudeAccuracy = request('altitudeAccuracy');
        $location->heading = request('heading');
        $location->speed = request('speed');
        $location->timestmp = request('timestmp');

        //save and return id
        if ($location->save()) {
            return $location->id;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[UserLocationRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * update a record
     * @param int $id note id
     * @return bool
     */
    public function update($id) {

        //get the record
        if (!$note = $this->notes->find($id)) {
            Log::error("record could not be found - database error", ['process' => '[MilestoneRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'note_id' => $id ?? '']);
            return false;
        }

        //general
        $note->note_title = request('note_title');
        $note->note_description = request('note_description');

        //save
        if ($note->save()) {
            return $note->note_id;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[NoteRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
}