<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for clients
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Clients\CommonResponse;
use App\Http\Responses\Clients\CreateResponse;
use App\Http\Responses\Clients\DestroyResponse;
use App\Http\Responses\Clients\EditLogoResponse;
use App\Http\Responses\Clients\EditResponse;
use App\Http\Responses\Clients\IndexResponse;
use App\Http\Responses\Clients\ShowDynamicResponse;
use App\Http\Responses\Clients\ShowResponse;
use App\Http\Responses\Clients\StoreResponse;
use App\Http\Responses\Clients\UpdateResponse;
use App\Repositories\AttachmentRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ClientRepository;
use App\Repositories\DestroyRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Repositories\AddressRepository;

use Validator;

class Clients extends Controller
{

    /**
     * The users repository instance.
     */
    protected $userrepo;

    /**
     * The clients repository instance.
     */
    protected $clientrepo;
    protected $address;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    public function __construct(AddressRepository $address, UserRepository $userrepo, ClientRepository $clientrepo, TagRepository $tagrepo)
    {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('clientsMiddlewareIndex')->only([
            'index',
            'update',
            //   'store',
        ]);

        $this->middleware('clientsMiddlewareEdit')->only([
            'edit',
            'update',
        ]);

        // $this->middleware('clientsMiddlewareCreate')->only([
        //     'create',
        //     'store',
        // ]);

        $this->middleware('clientsMiddlewareDestroy')->only(['destroy']);

        $this->middleware('clientsMiddlewareShow')->only(['show']);

        //dependencies
        $this->userrepo = $userrepo;
        $this->clientrepo = $clientrepo;
        $this->tagrepo = $tagrepo;
        $this->address = $address;
    }

    /**
     * Display a listing of clients
     * @param object CategoryRepository category repository
     * @return blade view | ajax view
     */
    public function index(CategoryRepository $categoryrepo)
    {
        // echo "1";
        //basic page settings
        $page = $this->pageSettings('clients');
        $cust_types = auth()->user()->cust_types ?? 1;
        //get clients
        if (request('client_status')) {
            // $cust_types = request('client_status');
            auth()->user()->cust_types = request('client_status');
            auth()->user()->save();
            $cust_types = auth()->user()->cust_types;
        }
        // print_r($cust_types);
        // die;
        $clients = $this->clientrepo->search();

        //client categories
        $categories = $categoryrepo->get('client');

        //get tags
        $tags = $this->tagrepo->getByType('client');
        //    print_r($clients);die;
        //reponse payload
        $payload = [
            'page' => $page,
            'stats' => $this->statsWidget(),
            'cust_type' => $cust_types,
            'clients' => $clients,
            'categories' => $categories,
            'tags' => $tags,
        ];

        //show views
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new client
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //page settings
        $page = $this->pageSettings('create');

        //get tags
        $tags = $this->tagrepo->getByType('client');

        //reponse payload
        $payload = [
            'page' => $page,
            'tags' => $tags,
        ];
        //show the form
        return new CreateResponse($payload);
    }
    public function Delete_logo()
    {
        if (request('client_id')) {

            return (DB::table('clients')->where('client_id', request('client_id'))->update(['client_logo_folder' => '', 'client_logo_filename' => '']));
        }
    }
    public function client_status_change()
    {
        if (request('client_id')) {
            if (request('type') == 1) {

                return (DB::table('clients')->where('client_id', request('client_id'))->update(['client_status' => 'suspended']));
            } else {
                return (DB::table('clients')->where('client_id', request('client_id'))->update(['client_status' => 'active']));
            }
        }
    }
    public function Add_more_tha_owner()
    {
        if (request('client_id')) {
            $data = DB::table('xin_employees')->where('client_id', request('client_id'))->update(['client_id' => '']);
            if (request('data')) {

                foreach (request('data') as $d) {
                    $data = DB::table('xin_employees')->where('user_id', $d)->update(['client_id' => request('client_id')]);
                }
            }
            return $data;
        }
    }

    /**
     * Store a newly created client in storage.
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        //custom error messages
        $messages = [];

        //validate for Individual
        if (request('cust_type') == 1) {
            $validator = Validator::make(request()->all(), [
                'first_name' => 'required',
                'customer_code' => 'unique:clients,cust_code1',
                'cu_email' => 'email|required|unique:clients,u_email',

            ], $messages);
        }

        //validate for Company
        if (request('cust_type') == 0) {
            $validator = Validator::make(request()->all(), [
                'client_company_name' => 'required',
                'customer_code' => 'unique:clients,cust_code1',
                'c_email' => 'email|required|unique:clients,u_email',

            ], $messages);
        }


        //errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //save the client first

        if (!$client_id = $this->clientrepo->create()) {
            abort(409);
        }

        //add tags
        $this->tagrepo->add('client', $client_id);

        //create new user (client role = 2)
        request()->merge([
            'account_owner' => 'yes',
            'role_id' => 2,
            'type' => 'client',
            'clientid' => $client_id,
        ]);
        $password = str_random(7);
        // if (!$userid = $this->userrepo->create(bcrypt($password))) {
        //     abort(409);
        // }

        //get the contact
        //DB::enableQueryLog();
        // $users = $this->userrepo->search($userid);
        //dd(DB::getQueryLog());
        // $user = $users->first();

        //update client user specific - default notification settings
        // $user->notifications_new_project = config('settings.default_notifications_client.notifications_new_project');
        // $user->notifications_projects_activity = config('settings.default_notifications_client.notifications_projects_activity');
        // $user->notifications_billing_activity = config('settings.default_notifications_client.notifications_billing_activity');
        // $user->notifications_tasks_activity = config('settings.default_notifications_client.notifications_tasks_activity');
        // $user->notifications_tickets_activity = config('settings.default_notifications_client.notifications_tickets_activity');
        // $user->notifications_system = config('settings.default_notifications_client.notifications_system');
        // $user->force_password_change = config('settings.force_password_change');
        // $user->save();

        /** ----------------------------------------------
         * send welcome email
         * ----------------------------------------------*/
        // $data = [
        //     'password' => $password,
        // ];
        // $mail = new \App\Mail\UserWelcome($user, $data);
        // $mail->build();

        //get the client object (friendly for rendering in blade template)

        $clients = $this->clientrepo->search($client_id);

        //counting rows
        $rows = $this->clientrepo->search();
        $count = $rows->total();

        //reponse payload
        $payload = [
            'clients' => $clients,
            'count' => $count,
        ];

        //process reponse
        return new StoreResponse($payload);
    }
    public function bulk(Request $request)
    {
        $file = $request->file('excelFile');




        // Open the file and read its contents
        $fileHandle = fopen($file, 'r');

        // Skip the first row if it's a header
        $header = fgetcsv($fileHandle);

        // Loop through each row and save to the database
        while ($row = fgetcsv($fileHandle)) {
            // print_R($row);
            // die;
            if (request('Customer_Type') == 0) {

                $crm_data = [
                    'Customer_Type' => request('Customer_Type'),
                    'first_name' => $row[2],
                    'email' => $row[3],
                    'mobile_no' => $row[4],
                    'company_code' => $row[1],
                    'credit_term' => $row[5],
                    'company_uen' => $row[0],
                    'currency' => $row[6],
                ];
            } else {
                $crm_data = [
                    'Customer_Type' => request('Customer_Type'),
                    'first_name' => $row[0],
                    'email' => $row[1],
                    'mobile_no' => $row[2],
                    'credit_term' => $row[3],
                    'currency' => $row[4],
                    'company_code' => 0,
                    'company_uen' => 0,
                ];
            }
            if (!$client_id = $this->clientrepo->create_bulk($crm_data)) {
                abort(409);
            }
            $user_data = [
                'type' => 'client',
                'email' => $row[8],
                'first_name' => $row[7],
                'last_name' => '',
                'mobile_no' => $row[9],
                'phone' => $row[9],
                'position' => $row[10],
                'role_id' => 2,
                'clientid' => $client_id,
                'creatorid' => Auth()->user()->id,
            ];
            $addre_data = [
                'client_id' => $client_id,
                'client_billing_city' => $row[16],
                'client_billing_state' => $row[17],
                'client_billing_country' => $row[19],
                'client_billing_street' => $row[14],
                'client_billing_zip' => $row[18],
                'p_i' => $row[11],
                'p_email' => $row[15],
                'p_contact' => $row[12],
                'p_unit' => $row[13],
            ];


            if (!$userid = $this->address->create_bulk($addre_data)) {
                abort(409);
            }
            $password = str_random(7);
            if (!$userid = $this->userrepo->create_bulk(bcrypt($password), $user_data)) {
                abort(409);
            }
        }

        fclose($fileHandle);
        return 1;
    }


    /**
     * Display the specified client
     * @param int $id client id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // echo "1";exit;
        //get the client
        $clients = $this->clientrepo->search($id);

        //client
        $client = $clients->first();

        //owner - primary contact
        $owner = \App\Models\User::Where('clientid', $id)
            ->Where('account_owner', 'yes')
            ->first();

        //page settings
        $page = $this->pageSettings('client', $client);

        //set dynamic url for use in template
        switch (request()->segment(3)) {
            case 'files':
            case 'invoices':
            case 'expenses':
            case 'payments':
            case 'timesheets':
            case 'notes':
            case 'tickets':
            case 'contacts':
            case 'projects':
                $sections = request()->segment(3);
                $section = rtrim($sections, 's');
                $page['dynamic_url'] = url($sections . '?source=ext&' . $section . 'resource_type=client&' . $section . 'resource_id=' . $client->client_id);
                break;
            default:
                $page['dynamic_url'] = url("timeline/client?request_source=client&source=ext&timelineclient_id=$id&page=1");
                break;
        }

        //reponse payload
        $payload = [
            'page' => $page,
            'client' => $client,
            'owner' => $owner,
        ];

        //response
        return new ShowResponse($payload);
    }

    /**
     * Display the specified client.
     * @param int $id id of the client
     * @return \Illuminate\Http\Response
     */
    public function showDynamic($id)
    {

        //get the client
        $clients = $this->clientrepo->search($id);

        //client
        $client = $clients->first();

        //owner - primary contact
        $owner = \App\Models\User::Where('clientid', $id)
            ->Where('account_owner', 'yes')
            ->first();

        //page settings
        $page = $this->pageSettings('client', $client);

        //set dynamic url for use in template

        switch (request()->segment(3)) {
            case 'invoices':
            case 'expenses':
            case 'estimates':
            case 'payments':
            case 'timesheets':
            case 'notes':
            case 'address':
            case 'addressb':
            case 'tickets':
            case 'contacts':
            case 'projects':
                $sections = request()->segment(3);
                if ($sections == "address") {
                    $section = $sections;
                } else {

                    $section = rtrim($sections, 's');
                }
                $page['dynamic_url'] = url($sections . '?source=ext&' . $section . 'resource_type=client&' . $section . 'resource_id=' . $client->client_id);
                break;
            case 'project-files':
                $sections = request()->segment(3);
                $page['dynamic_url'] = url($sections . '?source=ext&' . $section . 'fileresource_type=project&' . $section . 'filter_file_clientid=' . $client->client_id);
                break;
            case 'client-files':
                $sections = request()->segment(3);
                $page['dynamic_url'] = url($sections . '?source=ext&' . $section . 'fileresource_type=client&' . $section . 'fileresource_id=' . $client->client_id);
                break;
            default:
                $page['dynamic_url'] = url("timeline/client?request_source=client&source=ext&timelineclient_id=$id&page=1");
                break;
        }

        //reponse payload
        $payload = [
            'page' => $page,
            'client' => $client,
            'owner' => $owner,
        ];

        //response
        return new ShowDynamicResponse($payload);
    }

    /**
     * Show the form for editing the specified client.
     * @param int $id client id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        //page settings
        $page = $this->pageSettings('edit');

        //get the client
        if (!$client = $this->clientrepo->get($id)) {
            abort(409);
        }

        //get client tags and users tags
        $tags_resource = $this->tagrepo->getByResource('client', $id);
        $tags_user = $this->tagrepo->getByType('client');
        $tags = $tags_resource->merge($tags_user);

        //clients
        $client = $client->first();

        //reponse payload
        $payload = [
            'page' => $page,
            'client' => $client,
            'tags' => $tags,
        ];

        //response
        return new EditResponse($payload);
    }

    /**
     * Update the specified client in storage.
     * @param int $id client id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //validate for Individual
        $type = 0;
        if (request('cust_type') == 1) {
            $type = 1;
            $validator = Validator::make(request()->all(), [
                'first_name' => 'required',
                // 'customer_code'=>'unique:clients,cust_code',
                // 'email' => 'email|unique:clients',
            ]);
        }

        //validate for Company
        if (request('cust_type') == 0) {
            $validator = Validator::make(request()->all(), [
                'client_company_name' => 'required',
                // 'customer_code'=>'unique:clients,cust_code',
                // 'email' => 'email|unique:clients',
            ]);
        }

        //validation errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //update client

        if (!$this->clientrepo->update($id)) {

            abort(409);
        }

        //delete & update tags
        if (auth()->user()->is_team) {
            $this->tagrepo->delete('client', $id);
            $this->tagrepo->add('client', $id);
        }

        //if we are suspending the client - logout all users
        if (request('client_status') == 'suspended') {
            if ($users = \App\Models\User::Where('clientid', $id)->get()) {
                //each user - logout
                foreach ($users as $user) {
                    \App\Models\Session::Where('user_id', $user->id)->delete();
                }
            }
        }

        //client
        $clients = $this->clientrepo->search($id);

        //reponse payload
        $payload = [
            'clients' => $clients,
            'type' => $type,
        ];

        //generate a response
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified client from storage.
     * @param object DestroyRepository instance of the repository
     * @param int $id client id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRepository $destroyrepo, $id)
    {

        //delete client
        $destroyrepo->destroyClient($id);

        //reponse payload
        $payload = [
            'client_id' => $id,
        ];

        //generate a response
        return new DestroyResponse($payload);
    }

    /**
     * Return ajax details for project
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {

        //get client details
        $client = [
            'client_id' => random_int(1, 999),
            'description' => 'hello world',
        ];

        //set the view
        $html = view('pages/client/components/tabs/profile', compact('client'))->render();

        //[action options] replace|append|prepend
        $ajax['dom_html'][] = [
            'selector' => '#embed-content-container',
            'action' => 'replace',
            'value' => $html,
        ];

        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Show the form for editing the specified clients logo
     * @return \Illuminate\Http\Response
     */
    public function logo()
    {

        //is this client or admin
        if (auth()->user()->type == 'client') {
            $client_id = auth()->user()->clientid;
        } else {
            $client_id = request('client_id');
        }

        //reponse payload
        $payload = [
            'client_id' => $client_id,
        ];

        //response
        return new EditLogoResponse($payload);
    }
    // public function bulk(){
    //     echo "gg";die;
    // }
    /**
     * Update the specified client logo in storage.
     * @param int $id client id
     * @return \Illuminate\Http\Response
     */
    public function updateLogo(AttachmentRepository $attachmentrepo)
    {

        //validate input
        $data = [
            'directory' => request('logo_directory'),
            'filename' => request('logo_filename'),
        ];

        //process and save to db
        if (!$attachmentrepo->processClientLogo($data)) {
            abort(409);
        }

        //sanity check
        if (auth()->user()->type == 'client') {
            $clientid = auth()->user()->clientid;
        } else {
            $clientid = request('client_id');
        }

        //update avatar
        if (!$this->clientrepo->updateLogo(request('client_id'))) {
            abort(409);
        }

        //reponse payload
        $payload = [
            'type' => 'upload-logo',
            'client_id' => $clientid,
        ];

        //generate a response
        return new CommonResponse($payload);
    }
    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = [])
    {

        //

        //common settings
        $page = [
            'crumbs' => [
                "Customer",
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'clients',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_customers' => 'active',
            'mainmenu_clients' => 'active',
            'submenu_customers' => 'active',
            'tabmenu_timeline' => 'active',
            'sidepanel_id' => 'sidepanel-filter-clients',
            'dynamic_search_url' => url('clients/search?action=search&clientresource_id=' . request('clientresource_id') . '&clientresource_type=' . request('clientresource_type')),
            'add_button_classes' => '',
            'load_more_button_route' => 'clients',
            'source' => 'list',
        ];

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' => "Add Customer",
            'add_modal_create_url' => url('clients/create?clientresource_id=' . request('clientresource_id') . '&clientresource_type=' . request('clientresource_type')),
            //'add_modal_action_url' => url('clients/store'),
            'add_modal_action_url' => url('clients?clientresource_id=' . request('clientresource_id') . '&clientresource_type=' . request('clientresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //projects list page
        if ($section == 'clients') {
            $page += [
                'meta_title' => __('lang.clients'),
                'heading' => "Customer",
                'mainmenu_customers' => 'active',

            ];
            return $page;
        }

        //client page
        if ($section == 'client') {
            //adjust
            $page['page'] = 'client';
            //add
            $page += [
                'crumbs' => [
                    "Customer",
                ],
                'meta_title' => __('lang.client') . ' - ' . $data->client_company_name,
                'heading' => "Customer" . ' - ' . ($data->client_company_name)  ?? $data->f_name,
                'project_id' => request()->segment(2),
                'source_for_filter_panels' => 'ext',
            ];
            //ajax loading and tabs
            return $page;
        }

        //create new resource
        if ($section == 'create') {
            $page += [
                'section' => 'create',
            ];
            return $page;
        }

        //edit new resource
        if ($section == 'edit') {
            $page += [
                'section' => 'edit',
            ];
            return $page;
        }

        //return
        return $page;
    }

    /**
     * data for the stats widget
     * @return array
     */
    private function statsWidget($data = array())
    {

        //default values
        $stats = [
            [
                'value' => '0',
                'title' => __('lang.projects'),
                'percentage' => '0%',
                'color' => 'bg-success',
            ],
            [
                'value' => '$0.00',
                'title' => __('lang.invoices'),
                'percentage' => '0%',
                'color' => 'bg-info',
            ],
            [
                'value' => '0',
                'title' => __('lang.users'),
                'percentage' => '0%',
                'color' => 'bg-primary',
            ],
            [
                'value' => '0',
                'title' => __('lang.active'),
                'percentage' => '0%',
                'color' => 'bg-inverse',
            ],
        ];
        //calculations - set real values
        if (!empty($data)) {
            $stats[0]['value'] = '1';
            $stats[0]['percentage'] = '10%';
            $stats[1]['value'] = '2';
            $stats[1]['percentage'] = '20%';
            $stats[2]['value'] = '3';
            $stats[2]['percentage'] = '30%';
            $stats[3]['value'] = '4';
            $stats[3]['percentage'] = '40%';
        }
        //return
        return $stats;
    }
}
