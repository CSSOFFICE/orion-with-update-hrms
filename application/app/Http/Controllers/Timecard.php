<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for time sheets
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Responses\Timecard\IndexResponse;
use App\Http\Responses\Timecard\DestroyResponse;
use App\Http\Responses\Timecard\StoreResponse;
use Shuchkin\SimpleXLSX;
use App\Repositories\TimecardRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Timecard extends Controller
{

    /**
     * The timesheet repository instance.
     */
    protected $timerrepo;

    public function __construct(TimecardRepository $timerrepo)
    {
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
    public function index()
    {

        //only stopped timers
        request()->merge([
            'filter_timer_status' => 'stopped',
        ]);

        //get timesheets
        // $timesheets = $this->timerrepo->search();
        $emph = $this->timerrepo->getEmploeeHours();
        $timesheets = $this->timerrepo->getday();
        $bigarray = $this->timerrepo->geemployee();
        

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('TimeCard'),
            'timesheets' => $timesheets,
            'bigarray' => $bigarray,
            'dayy' => "dayy",
            'emph' => $emph,
        ];


        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        echo "ggg";
        die;
    }
    public function BulkUpload(Request $request)
    {
        $ht = 'r';
        $htz = 'r0';
        $file = $request->file('excelFile');
        if ($xlsx = SimpleXLSX::parse($file)) {
            $header_values = $rows = [];
            foreach ($xlsx->rows() as $k => $r) {
                if ($k === 0) {
                    $header_values = $r;

                    continue;
                }
                $tt = array();
                foreach ($header_values as $t) {
                    if (is_int($t) && $t < 10) {
                        $tt[] = $htz . $t;
                    } elseif (is_int($t)) {

                        $tt[] = $ht . $t;
                    }else{
                        $tt[] =  $t;


                    }
                }

                $rows[] = array_combine($tt, $r);
            }

            foreach ($rows as $value) {

                unset($value["sr "]);
                unset($value["employee"]);
                $value['date'] = date('m-Y');
                $value['status'] = true;
                $d = "";
                $res = DB::table('timecards')->where('user_id', $value['user_id'])->get();
                if (count($res) > 0) {
                    $d = DB::table('timecards')->where(['user_id' => $value['user_id']])->update($value);
                } else {

                    $d = DB::table('timecards')->insert($value);
                }
                if ($d) {


                    echo $d;
                }
            }
        } else {
            echo SimpleXLSX::parseError();
        }
    }
    public function update(Request $r)
    {
        dd($r->file());
    }
    public function show()
    {
        //
    }
    public function store(Request $r)
    {


        $data = array($r->all());
        unset($data[0]["visibility_left_menu_toggle_button"]);
        unset($data[0]["_token"]);
        unset($data[0]["system_languages"]);
        unset($data[0]["system_language"]);
        $data[0]['date'] = date('m-Y');
        $data[0]['status'] = true;

        $d = "";
        $res = DB::table('timecards')->where('user_id', $data[0]['user_id'])->get();
        if (count($res) > 0) {
            $d = DB::table('timecards')->where(['user_id' => $data[0]['user_id']])->update($data[0]);
        } else {

            $d = DB::table('timecards')->insert($data);
        }
        if ($d) {


            echo $d;
        }
    }
    public function delete_att()
    {
        return DB::table('timecards')->where('user_id', request('id'))->update(['status' => 0]);
    }



    public function destroy($id)
    {
        echo "gg";
        die;
    }
    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = [])
    {

        //common settings
        $page = [
            'crumbs' => [
                "TimeCard",
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'timecard',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_timecard' => 'active',
            'mainmenu_sales' => 'active',
            'submenu_timecard' => 'active',
            'sidepanel_id' => 'sidepanel-filter-timecard',
            'dynamic_search_url' => url('timecard/search?action=search&timesheetresource_id=' . request('timesheetresource_id') . '&timesheetresource_type=' . request('timesheetresource_type')),
            'add_button_classes' => '',
            'load_more_button_route' => 'timecard',
            'source' => 'list',
        ];

        //projects list page
        if ($section == 'timesheets') {
            $page += [
                'meta_title' => "timecard",
                'heading' => "Timecard",

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
