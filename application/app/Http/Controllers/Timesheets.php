<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for time sheets
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Timesheets\IndexResponse;
use App\Http\Responses\Timesheets\DestroyResponse;
use App\Repositories\TimerRepository;
use Illuminate\Http\Request;

class Timesheets extends Controller {

    /**
     * The timesheet repository instance.
     */
    protected $timerrepo;

    public function __construct(TimerRepository $timerrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('timesheetsMiddlewareIndex')->only(['index']);
        $this->middleware('timesheetsMiddlewareDestroy')->only(['destroy']);


        $this->timerrepo = $timerrepo;
    }

    /**
     * Display a listing of timesheets
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //only stopped timers
        request()->merge([
            'filter_timer_status' => 'stopped',
        ]);

        //get timesheets
        $timesheets = $this->timerrepo->search();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('timesheets'),
            'timesheets' => $timesheets,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }


    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy() {

        //delete each record in the array
        $allrows = array();
        foreach (request('ids') as $id => $value) {
            //only checked items
            if ($value == 'on') {
                //get the timer
                $timer = \App\Models\Timer::Where('timer_id', $id)->first();
                //delete client
                $timer->delete();
                //add to array
                $allrows[] = $id;
            }
        }
        //reponse payload
        $payload = [
            'allrows' => $allrows,
        ];

        //generate a response
        return new DestroyResponse($payload);
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
                __('lang.time_sheets'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'timesheets',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_timesheets' => 'active',
            'mainmenu_sales' => 'active',
            'submenu_timesheets' => 'active',
            'sidepanel_id' => 'sidepanel-filter-timesheets',
            'dynamic_search_url' => url('timesheets/search?action=search&timesheetresource_id=' . request('timesheetresource_id') . '&timesheetresource_type=' . request('timesheetresource_type')),
            'add_button_classes' => '',
            'load_more_button_route' => 'timesheets',
            'source' => 'list',
        ];

        //projects list page
        if ($section == 'timesheets') {
            $page += [
                'meta_title' => __('lang.time_sheets'),
                'heading' => __('lang.time_sheets'),

            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
            return $page;
        }

        //return
        return $page;
    }
}
