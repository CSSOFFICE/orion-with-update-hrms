<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for users
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\billing_address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Log;

class AddressRepository
{


    /**
     * The users repository instance.
     */
    protected $billing_address;

    /**
     * Inject dependecies
     */
    public function __construct(billing_address $billing_address)
    {
        $this->billing_address = $billing_address;
    }

    /**
     * get a single user from the database
     * @param int $id record id
     * @return object
     */
    public function get($id = '')
    {

        //new query
        $billing_address = $this->billing_address->newQuery();

        //validation
        if (!is_numeric($id)) {
            return false;
        }

        $billing_address->where('id', $id);

        //sanity: client
        if (request()->filled('clientid')) {
            $billing_address->where('client_id', request()->input('clientid'));
        }

        return $billing_address->first();
    }

    /**
     * chec if a user exists
     * @param int $id The user id
     * @return bool
     */
    public function exists($id = '')
    {

        //new query
        $billing_address = $this->billing_address->newQuery();

        //validation
        if (!is_numeric($id)) {
            Log::error("validation error - invalid params", ['process' => '[AddressRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //check
        $billing_address->where('id', '=', $id);
        return $billing_address->exists();
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object user collection
     */
    public function search($id = '')
    {

        //user object
        $billing_address = $this->billing_address->newQuery();

        //join: client category
        // $billing_address->leftjoinClients();

        //ignore system user
        // $billing_address->where('id', '>', 0);

        //filter: type
        // if (request()->filled('type')) {
        //     $billing_address->where('type', request('type'));
        // }

        //filter: status
        // if (request()->filled('status')) {
        //     $billing_address->where('status', request('status'));
        // }

        //filter: id
        if (request()->filled('id')) {
            $billing_address->where('client_id', request('id'));
        }
        if (is_numeric($id)) {
            $billing_address->where('id', $id);
        }

        // //filter: created date (start)
        // if (request()->filled('filter_date_created_start')) {
        //     $billing_address->where('created', '>=', request('filter_date_created_start'));
        // }

        // //filter: created date (end)
        // if (request()->filled('filter_date_created_end')) {
        //     $billing_address->where('created', '<=', request('filter_date_created_end'));
        // }

        //filters: primary or not
        // if (request()->filled('filter_account_owner')) {
        //     $billing_address->where('account_owner', request('filter_account_owner'));
        // }

        //filters-array: name  (NB: the user id is the value received)
        // if (is_array(request('filter_name')) && !empty(array_filter(request('filter_name')))) {
        //     $billing_address->whereIn('id', request('filter_name'));
        // }

        //filters-array: email (NB: the user id is the value received)
        // if (is_array(request('filter_email')) && !empty(array_filter(request('filter_email')))) {
        //     $billing_address->whereIn('id', request('filter_email'));
        // }

        //filters-array: client  (NB: the client id is the value received)
        // if (is_array(request('filter_clientid')) && !empty(array_filter(request('filter_clientid')))) {
        //     $billing_address->whereIn('clientid', request('filter_clientid'));
        // }

        //sanity: client
        if (request()->filled('clientid')) {
            $billing_address->where('client_id', request('clientid'));
        }

        //resource filtering
        if (request()->filled('addressresource_type') && request()->filled('addressresource_id')) {
            switch (request('addressresource_type')) {
                case 'client':
                    $billing_address->where('client_id', request('addressresource_id'));
                    break;
            }
        }

        //search: various client columns and relationships (where first, then wherehas)
        // if (request()->filled('search_query')) {
        //     $billing_address->where(function ($query) {
        //         $query->where('first_name', 'LIKE', '%' . request('search_query') . '%');
        //         $query->orWhere('last_name', 'LIKE', '%' . request('search_query') . '%');
        //         $query->orWhere('email', 'LIKE', '%' . request('search_query') . '%');
        //         $query->orWhere('phone', 'LIKE', '%' . request('search_query') . '%');
        //         $query->orWhere('client_company_name', 'LIKE', '%' . request('search_query') . '%');
        //     });
        // }

        //sorting
        // if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
        //     if (Schema::hasColumn('Billing_Address', request('orderby'))) {
        //         $billing_address->orderBy(request('orderby'), request('sortorder'));
        //     }
        //     //others
        //     switch (request('orderby')) {
        //     case 'company_name':
        //         $billing_address->orderBy('client_company_name', request('sortorder'));
        //         break;
        //     }
        // } else {
        //     //default sorting
        //     $billing_address->orderBy('Billing_Address.first_name', 'asc');
        // }

        //eager load
        $billing_address->with([
            'role',
        ]);

        // Get the results and return them.
        return $billing_address->paginate(config('system.settings_system_pagination_limits'));
    }
    public function create_bulk($data)
    {

        //save new user
        $user = new $this->billing_address;

        $user->client_id = $data['client_id'];
        $user->city = $data['client_billing_city'];
        $user->state = $data['client_billing_state'];
        $user->country = $data['client_billing_country'];
        $user->street = $data['client_billing_street'];
        $user->zipcode = $data['client_billing_zip'];
        $user->p_i = $data['p_i'];
        $user->p_email = $data['p_email'];
        $user->p_contact = $data['p_contact'];
        $user->p_unit = $data['p_unit'];


        //password
        // if ($password != '') {
        //     $user->password = $password;
        // }

        //client user
        // if (request()->filled('clientid')) {
        //     $user->clientid = request('clientid');
        // }

        //primary contact
        // if (request('account_owner') == 'yes') {
        //     $user->account_owner = 'yes';
        // }

        //dashboard access
        // $user->dashboard_access = (request('dashboard_access') == 'on') ? 'yes' : 'no';

        //save
        if ($user->save()) {
            return $user->id;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[AddressRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * Update a users preferences
     * e.g. left menu position, stats panel position etc
     * @param int $id users id
     * @return bool
     */
    public function updatePreferences($id = '')
    {

        //validation
        if (!is_numeric($id)) {
            return false;
        }

        //get user from database
        if ($billing_address = $this->billing_address->find($id)) {

            //preference: left menu position
            if (in_array(request('leftmenu_position'), array('open', 'collapsed'))) {
                $user->pref_leftmenu_position = request('leftmenu_position');
            }

            //preference: stats panel position
            if (in_array(request('statspanel_position'), array('open', 'collapsed'))) {
                $user->pref_statspanel_position = request('statspanel_position');
            }

            //preference: show own tasks or all
            if (in_array(request('pref_filter_own_tasks'), array('yes', 'no'))) {
                $user->pref_filter_own_tasks = request('pref_filter_own_tasks');
            }

            //preference: show own projects or all
            if (in_array(request('pref_filter_own_projects'), array('yes', 'no'))) {
                $user->pref_filter_own_projects = request('pref_filter_own_projects');
            }

            //update preferences
            $user->save();

            return true;
        }
        return false;
    }

    /**
     * Create a new user
     * @param string $password bcrypted password
     * @param string $type team or client
     * @return bool
     */
    public function create()
    {

        //save new user
        $user = new $this->billing_address;

        //data
        $user->client_id = request('clientid');
        // $user->email = request('email');
        // $user->state = request('first_name');
        $user->city = request('client_billing_city');
        $user->state = request('client_billing_state');
        $user->country = request('client_billing_country');
        $user->street = request('client_billing_street');
        $user->zipcode = request('client_billing_zip');
        $user->p_i = request('p_i');
        $user->p_email = request('p_email');
        $user->p_contact = request('p_contact');
        $user->p_unit = request('p_unit');

        //password
        // if ($password != '') {
        //     $user->password = $password;
        // }

        //client user
        // if (request()->filled('clientid')) {
        //     $user->clientid = request('clientid');
        // }

        //primary contact
        // if (request('account_owner') == 'yes') {
        //     $user->account_owner = 'yes';
        // }

        //dashboard access
        // $user->dashboard_access = (request('dashboard_access') == 'on') ? 'yes' : 'no';

        //save
        if ($user->save()) {
            return $user->id;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[AddressRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * Create a new user via the client signup form
     * @param string $password bcrypted password
     * @param string $type team or client
     * @return bool
     */
    public function signUp($clientId = '')
    {

        //save new user
        $user = new $this->Billing_Address;

        //data
        $user->clientid = $clientId;
        $user->password = Hash::make(request('password'));
        $user->type = 'client';
        $user->email = request('email');
        $user->first_name = request('first_name');
        $user->last_name = request('last_name');
        $user->role_id = 2;
        $user->creatorid = 0;
        $user->account_owner = 'yes';

        //notification settings
        $user->notifications_new_project = config('settings.default_notifications_client.notifications_new_project');
        $user->notifications_projects_activity = config('settings.default_notifications_client.notifications_projects_activity');
        $user->notifications_billing_activity = config('settings.default_notifications_client.notifications_billing_activity');
        $user->notifications_tasks_activity = config('settings.default_notifications_client.notifications_tasks_activity');
        $user->notifications_tickets_activity = config('settings.default_notifications_client.notifications_tickets_activity');
        $user->notifications_system = config('settings.default_notifications_client.notifications_system');

        //save
        if ($user->save()) {
            return $user;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[AddressRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * update a user record
     * @param int $id user id
     * @return bool
     */
    public function update($id)
    {

        //get the user
        if (!$user = $this->billing_address->find($id)) {
            Log::error("record could not be found", ['process' => '[AddressRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'user_id' => $id ?? '']);
            return false;
        }

        //general
        // $user = new $this->billing_address;

        //data
        $user->client_id = request('addressresource_id');
        // $user->email = request('email');
        // $user->state = request('first_name');
        $user->city = request('client_billing_city');
        $user->state = request('client_billing_state');
        $user->country = request('client_billing_country');
        $user->street = request('client_billing_street');
        $user->zipcode = request('client_billing_zip');
        $user->p_i = request('p_i');
        $user->p_email = request('p_email');
        $user->p_contact = request('p_contact');
        $user->p_unit = request('p_unit');

        //client user
        // if (request()->filled('clientid')) {
        //     $user->clientid = request('clientid');
        // }

        // //dashboard access
        // $user->dashboard_access = (request('dashboard_access') == 'on') ? 'yes' : 'no';

        // //optional
        // if (request('password') != '') {
        //     $user->password = bcrypt(request('password'));
        // }
        // if (request('position') != '') {
        //     $user->position = request('position');
        // }
        // if (request('phone') != '') {
        //     $user->phone = request('phone');
        // }
        // if (request('role_id') != '') {
        //     $user->role_id = request('role_id');
        // }

        //save changes
        if ($user->save()) {
            return true;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[AddressRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * autocomplete feed for user names
     * @param string $type (team|client)
     * @param string $searchterm
     * @return array
     */
    public function autocompleteNames($type = '', $searchterm = '')
    {

        //validation
        if ($searchterm == '') {
            return [];
        }

        //start
        $query = $this->users->newQuery();
        $query->selectRaw("CONCAT_WS(' ', first_name, last_name) AS value, id");

        //filter
        if ($type != '') {
            $query->where('type', '=', $type);
        }

        $query->whereRaw("CONCAT_WS(' ', first_name, last_name) LIKE '%$searchterm%'");

        //return
        return $query->get();
    }

    /**
     * autocomplete feed for email addresses
     * @param string $type (team|client)
     * @param string $searchterm
     * @return array
     */
    public function autocompleteEmail($type = '', $searchterm = '')
    {

        //validation
        if ($searchterm == '') {
            return [];
        }

        //start
        $query = $this->users->newQuery();

        $query->selectRaw("email AS value, id");

        //filter
        if ($type != '') {
            $query->where('type', '=', $type);
        }

        $query->where('email', 'like', "%$searchterm%");

        //return
        return $query->get();
    }

    /**
     * get all team members who can receive estimate emails
     * @return object
     */
    public function mailingListTeamEstimates($notification_type = '')
    {

        //start query
        $query = $this->users->newQuery();
        $query->where('type', '=', 'team');

        //email notification
        if ($notification_type == 'email') {
            $query->where('notifications_billing_activity', '=', 'yes_email');
        }

        //email notification
        if ($notification_type == 'app') {
            $query->whereIn('notifications_billing_activity', ['yes', 'yes_email']);
        }

        //has permissions to view estimates
        $query->whereHas('role', function ($q) {
            $q->where('role_estimates', '>=', 1);
        });

        //with roles
        $query->with([
            'role',
        ]);

        //get the users
        $users = $query->get();

        //return list
        return $users;
    }

    /**
     * get all team members who can receive invoice & payments emails
     * @return object
     */
    public function mailingListInvoices($notification_type = '')
    {

        //start query
        $query = $this->users->newQuery();
        $query->where('type', '=', 'team');

        //email notification
        if ($notification_type == 'email') {
            $query->where('notifications_billing_activity', '=', 'yes_email');
        }

        //email notification
        if ($notification_type == 'app') {
            $query->whereIn('notifications_billing_activity', ['yes', 'yes_email']);
        }

        //has permissions to view invoices and payments
        $query->whereHas('role', function ($q) {
            $q->where('role_invoices', '>=', 1);
        });

        //with roles
        $query->with([
            'role',
        ]);

        //get the users
        $users = $query->get();

        //return list
        return $users;
    }

    /**
     * various feeds for ajax auto complete
     * @example $this->userrepo->getClientUsers(1, 'all', 'ids')
     * @param numeric $type (company_name)
     * @param string $results the result return type (ids|collection)
     * @param string $user_type return all users or just the primary user (all|owner)
     * @return array
     */
    public function getClientUsers($client_id = '', $user_type = 'all', $results = 'ids')
    {

        //validation
        if (!is_numeric($client_id) || !in_array($results, ['ids', 'collection']) || !in_array($user_type, ['all', 'owner'])) {
            return false;
        }

        //start
        $query = $this->users->newQuery();

        //basics
        $query->where('type', 'client');
        $query->where('clientid', $client_id);

        //primary user only
        if ($user_type == 'owner') {
            $query->where('account_owner', 'yes');
        }

        //with roles
        $query->with([
            'role',
        ]);

        //get the users
        $users = $query->get();

        //create a list of id's
        $list = [];
        foreach ($users as $user) {
            $list[] = $user->id;
        }

        //return collection
        if ($results == 'collection') {
            return $users;
        } else {
            return $list;
        }
    }

    /**
     * get all team members
     * @param string $results the result return type (ids|collection)
     * @return object
     */
    public function getTeamMembers($results = 'collection')
    {

        //start query
        $query = $this->users->newQuery();
        $query->where('type', '=', 'team');

        //with roles
        $query->with([
            'role',
        ]);

        //get the users
        $users = $query->get();

        //create a list of id's
        $list = [];
        foreach ($users as $user) {
            $list[] = $user->id;
        }

        //return collection
        if ($results == 'collection') {
            return $users;
        } else {
            return $list;
        }
    }

    /**
     * Get the client account owner
     * @param numeric $client_id client did
     * @return object client model object
     */
    public function getClientAccountOwner($client_id = '')
    {

        if (!is_numeric($client_id)) {
            Log::error("validation error - invalid params", ['process' => '[AddresRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //start
        $query = $this->users->newQuery();
        $query->where('type', 'client');
        $query->where('account_owner', 'yes');
        $query->where('clientid', $client_id);

        //return client
        $users = $query->take(1)->get();

        return $users->first();
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed bool or id of record
     */
    public function updateAvatar($id)
    {

        //get the user
        if (!$user = $this->users->find($id)) {
            Log::error("record could not be found", ['process' => '[AddressRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'user_id' => $id ?? '']);
            return false;
        }

        //update users avatar
        $user->avatar_directory = request('avatar_directory');
        $user->avatar_filename = request('avatar_filename');

        //save
        if ($user->save()) {
            return true;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[AddressRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * get all team members
     * @param int $client_id
     * @param int $new_owner_id the user to set as new owner
     * @return object
     */
    public function updateAccountOwner($client_id = '', $new_owner_id = '')
    {

        //validation
        if (!is_numeric($client_id) || !is_numeric($new_owner_id)) {
            Log::error("validation error - invalid params", ['process' => '[UserRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //reset existing account owner
        $query = $this->users->newQuery();
        $query->where('clientid', $client_id);
        $query->update(['account_owner' => 'no']);

        //set owner
        $query = $this->users->newQuery();
        $query->where('clientid', $client_id);
        $query->where('id', $new_owner_id);
        $query->update(['account_owner' => 'yes']);
    }
}