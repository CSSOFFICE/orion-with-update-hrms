<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for contacts
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\Addressb\CreateResponse;
use App\Http\Responses\Addressb\DestroyResponse;
use App\Http\Responses\Addressb\EditResponse;
use App\Http\Responses\Addressb\IndexResponse;
use App\Http\Responses\Addressb\StoreResponse;
use App\Http\Responses\Addressb\UpdateResponse;
use App\Repositories\CategoryRepository;
use App\Repositories\ClientRepository;
use App\Repositories\UserRepository;
use App\Repositories\AddressBRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Validator;

class Addressb extends Controller {

    /**
     * The users repository instance.
     */
    protected $addressb;

    /**
     * The category repository instance.
     */
    protected $categoryrepo;

    /**
     * The client repository instance.
     */
    protected $clientrepo;

    public function __construct(AddressBRepository $addressb, CategoryRepository $categoryrepo, ClientRepository $clientrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('addressbMiddlewareIndex')->only([
            'index',
            'update',
            'store',
        ]);

        $this->middleware('addressbMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('addressbMiddlewareEdit')->only([
            'edit',
            'update',
        ]);

        $this->middleware('addressbMiddlewareDestroy')->only([
            'destroy',
        ]);

        //dependencies
        $this->addressb = $addressb;
        $this->categoryrepo = $categoryrepo;
        $this->clientrepo = $clientrepo;
    }

    /**
     * Display a listing of contacts
     * @return \Illuminate\Http\Response
     */
    public function index() {


        //get contacts
        request()->merge([
            'type' => 'client',
            'status' => 'active',
        ]);
        $clientrepo = $this->clientrepo->search();
        $addressb = $this->addressb->search();

        //reponse payload
        $payload = [
            'page' => $this->pageSettings('addressb'),
            'addressb' => $addressb,
            'clients' =>$clientrepo
        ];


        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new contact.
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r) {



        $page = $this->pageSettings('create');

        //reponse payload
        $payload = [
            'page' => $page,
        ];

        //show the form
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created contact in storage.
     * @param object ClientRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRepository $clientrepo) {


        //custom error messages
        $messages = [
            'clientid.exists' => __('lang.item_not_found'),
        ];

        //validate
        $validator = Validator::make(request()->all(), [
            'client_billing_street' => 'required',
            // 'client_billing_city' => 'required',
            // 'client_billing_state' => 'required',
            'client_billing_zip' => 'required',
            // 'email' => [
            //     'required',
            //     'email',
            //     Rule::unique('users', 'email'),
            // ],
            // 'clientid' => [
            //     'required',
            //     Rule::exists('clients', 'client_id'),
            // ],
        ], $messages);

        //validation errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //set other data (client role = 3)
        request()->merge([
            'role_id' => 2,
            'type' => 'client',
        ]);

        //password
        $password = str_random(9);

        //save contact

        if (!$userid = $this->addressb->create()) {
            abort(409);
        }

        //get the contact
        $addressb = $this->addressb->search($userid);
        $contact = $addressb->first();

        //update client user specific - default notification settings
        // $contact->notifications_new_project = config('settings.default_notifications_client.notifications_new_project');
        // $contact->notifications_projects_activity = config('settings.default_notifications_client.notifications_projects_activity');
        // $contact->notifications_billing_activity = config('settings.default_notifications_client.notifications_billing_activity');
        // $contact->notifications_tasks_activity = config('settings.default_notifications_client.notifications_tasks_activity');
        // $contact->notifications_tickets_activity = config('settings.default_notifications_client.notifications_tickets_activity');
        // $contact->notifications_system = config('settings.default_notifications_client.notifications_system');
        // $contact->force_password_change = config('settings.force_password_change');
        // $contact->save();

        /** ----------------------------------------------
         * send email to user
         * ----------------------------------------------*/
        // $data = [
        //     'password' => $password,
        // ];
        // $mail = new \App\Mail\UserWelcome($contact, $data);
        // $mail->build();

        //counting rows
        $rows = $this->addressb->search();
        $count = $rows->total();

        //reponse payload
        $payload = [
            'addressb' => $addressb,
            'count' => $count,
        ];

        //process reponse
        return new StoreResponse($payload);
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id contact id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {


        //page settings
        $page = $this->pageSettings('edit');

        //the user

        $address = \App\Models\shipping_address::Where('id', $id)->first();


        $client = \App\Models\Client::Where('client_id', $address->clientid)->first();

        //reponse payload
        $payload = [
            'page' => $page,
            'address' => $address,
            'client'=>$client
        ];

        //process reponse
        return new EditResponse($payload);

    }

    /**
     * Update the specified contact in storage.
     * @param int $id contact id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {

        //vars
        $original_owner = '';

        //validate the form
        $validator = Validator::make(request()->all(), [
            'client_billing_state' => [
                // 'required',
            ],
            'client_billing_zip' => [
                // 'required',
            ],
            // 'email' => [
            //     'required',
            //     'email',
            //     Rule::unique('users', 'email')->ignore($id, 'id'),
            // ],
        ]);

        //validation errors
        if ($validator->fails()) {
            $errors = $validator->errors();
            $messages = '';
            foreach ($errors->all() as $message) {
                $messages .= "<li>$message</li>";
            }

            abort(409, $messages);
        }

        //get the user
        $user = \App\Models\User::Where('id', $id)->first();

        //update the user
        if (!$this->addressb->update($id)) {
            abort(409);
        }


        // if (!$this->clientrepo->update($user->clientid)) {
        //     abort(409);
        // }


        //update accout owner
        // if (request('account_owner') == 'on') {
        //     //get the current account owner
        //     $owner = \App\Models\User::Where('clientid', $user->clientid)->where('account_owner', 'yes')->first();
        //     //update owner
        //     $this->userrepo->updateAccountOwner($user->clientid, $id);
        //     //get original owner in friendly format
        //     $original_owner = $this->userrepo->search($owner->id);
        // }

        //get the user
        $contacts = $this->addressb->search($id);
        $contact = $contacts->first();


        //reponse payload

        $payload = [
            'address' => $contacts,
            // 'clientid' => $user->clientid,
            'id' => $id,
            // 'original_owner' => $original_owner,
            'user' => $contact,
        ];

        // update in xin_table
        $change_xin_table = DB::table('xin_employees')->where('user_id',$id)->first();
        if($change_xin_table){
            DB::table('xin_employees')->where('user_id',$id)->update([
                'email'         => request('email'),
                'first_name'    => request('first_name'),
                'last_name'     => request('last_name'),
                'contact_no'    => request('phone'),
                'facebook_link' => request('social_facebook'),
                'twitter_link'  => request('social_twitter'),
                'linkdedin_link'=> request('social_linkedin'),
            ]);
        }


        //generate a response
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified contact from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy() {

        //delete each record in the array
        $allrows = array();
        foreach (request('ids') as $id => $value) {
            //only checked items
            if ($value == 'on') {
                //get the item
                $user = \App\Models\shipping_address::Where('id', $id)->first();
                //make account as deleted
                // $user->status = 'deleted';
                //remove avater
                // $user->avatar_filename = '';
                //delete email
                // $user->email = '';
                //delete password
                // $user->password = '';
                //update delete date
                $user->delete();
                //save user
                // $user->save();
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
     * Update preferences of logged in user
     * @return null silent
     */
    public function updatePreferences() {

        $this->userrepo->updatePreferences(auth()->id());

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
                __('lang.clients'),
                __('lang.clients'),
            ],
            'crumbs_special_class' => 'list-pages-crumbs',
            'page' => 'addressb',
            'no_results_message' => __('lang.no_results_found'),
            'mainmenu_addressb' => 'active',
            'mainmenu_customers' => 'active',
            'submenu_addressb' => 'active',
            'sidepanel_id' => 'sidepanel-filter-addressb',
            'dynamic_search_url' => url('addressb/search?action=search&addressbresource_id=' .request('addressbresource_id') . '&addressbresource_type=' . request('addressbresource_type')),
            'add_button_classes' => '',
            'load_more_button_route' => 'addressb',
            'source' => 'list',
        ];

        //client user settings
        if (auth()->user()->is_client) {
            $page['visibility_list_page_actions_filter_button'] = '';
            $page['visibility_list_page_actions_search'] = '';
        }

        //default modal settings (modify for sepecif sections)
        $page += [
            'add_modal_title' =>"Delivery Address",
            'add_modal_create_url' => url('addressb/create?addressbresource_id=' .request('addressbresource_id') . '&addressbresource_type=' . request('addressbresource_type')),
            'add_modal_action_url' => url('addressb?addressbresource_id=' . request('addressbresource_id') . '&addressbresource_type=' . request('addressbresource_type')),
            'add_modal_action_ajax_class' => '',
            'add_modal_action_ajax_loading_target' => 'commonModalBody',
            'add_modal_action_method' => 'POST',
        ];

        //contracts list page
        if ($section == 'addressb') {
            $page += [
                'meta_title' => "Delivery Address",
                'heading' =>"Delivery Address",

            ];
            if (request('source') == 'ext') {
                $page += [
                    'list_page_actions_size' => 'col-lg-12',
                ];
            }
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

        //ext page settings
        if ($section == 'ext') {
            $page += [
                'list_page_actions_size' => 'col-lg-12',
                'source' => 'list',
            ];
            return $page;
        }

        //return
        return $page;
    }
}
