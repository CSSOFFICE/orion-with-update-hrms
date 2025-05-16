<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserLocationRepository;
use Carbon\Carbon;

class MapController extends Controller
{
    /**
     * The user location repository instance.
     */
    protected $locationrepo;

    public function __construct(
        UserLocationRepository $locationrepo
    ){
        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->locationrepo = $locationrepo;
    }

    /**
     * Display the home page
     * @return \Illuminate\Http\Response
     */
    public function index() {

        //crumbs, page data & stats
        $page = $this->pageSettings('live_location');

        $payload = [];

        //show login page
        return view('pages/maps/live', compact('page', 'payload'));
    }

    /**
     * Out json gps coord
     */
    public function liveGps() {

        //get locations
        $locations = $this->locationrepo->get_last_location();
        // dd($locations);

        $gps_location = [];

        if($locations) {
            foreach($locations as $i => $loc) {
                $gps_location[$i]['coords'] = [ 'lat' => $loc->latitude, 'lng' => $loc->longitude];
                $gps_location[$i]['data'] = [ 
                    'user_id' => $loc->user_id, 
                    'name' => $loc->first_name . ' ' . $loc->last_name,
                    'accuracy' => ($loc->accuracy) ? $loc->accuracy : '-',
                    'altitude' => $loc->altitude,
                    'timestamp' => Carbon::createFromTimestamp($loc->timestmp)->diffForHumans(),
                    'create_time' => Carbon::parse($loc->created_at)->diffForHumans(),
                ];
            }
            
        }

        return response()->json($gps_location);
    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        //common settings
        $page = [
            'crumbs' => [
                __('lang.user_live_location'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'meta_title' => __('lang.live_location'),
            'heading' => __('lang.live_location'),
            'page' => 'live_location',
            // 'no_results_message' => __('lang.no_results_found'),
            'mainmenu_livelocation' => 'active',
            // 'mainmenu_sales' => 'active',
            // 'submenu_timesheets' => 'active',
            // 'sidepanel_id' => 'sidepanel-filter-timesheets',
            // 'dynamic_search_url' => url('timesheets/search?action=search&timesheetresource_id=' . request('timesheetresource_id') . '&timesheetresource_type=' . request('timesheetresource_type')),
            // 'add_button_classes' => '',
            // 'load_more_button_route' => 'timesheets',
            // 'source' => 'list',
        ];

        //return
        return $page;
    }
}