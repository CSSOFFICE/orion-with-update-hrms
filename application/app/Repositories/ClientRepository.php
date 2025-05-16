<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for clients
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Schema;
use Log;

class ClientRepository
{

    /**
     * The clients repository instance.
     */
    protected $clients;

    /**
     * Inject dependecies
     */
    public function __construct(Client $clients)
    {
        $this->clients = $clients;
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object clients collection
     */
    public function get($id = '')
    {

        $clients = $this->clients->newQuery();
        $clients->selectRaw('*');
        $clients->from('clients');
        $clients->whereRaw("1 = 1");
        if (is_numeric($id)) {
            $clients->where('client_id', $id);
        }

        // Get the results and return them.
        return $clients->paginate(config('system.settings_system_pagination_limits'));
    }
    public function search($id = '')
    {

        $clients = $this->clients->newQuery();

        // all client fields
        $clients->selectRaw('*');

        //count: clients projects by status
        foreach (config('settings.project_statuses') as $key => $value) {
            $clients->countProjects($key);
        }
        $clients->countProjects('all');
        $clients->countProjects('pending');

        //count: clients invoices by status
        foreach (config('settings.invoice_statuses') as $key => $value) {
            $clients->countInvoices($key);
        }
        $clients->countInvoices('all');

        //sum: clients invoices by status
        foreach (config('settings.invoice_statuses') as $key => $value) {
            $clients->sumInvoices($key);
        }
        $clients->sumInvoices('all');

        //sum payments
        $clients->selectRaw("(SELECT SUM(payment_amount)
                                     FROM payments
                                     WHERE payments.payment_clientid = clients.client_id
                                     ) AS sum_all_payments");

        //join: primary contact
        $clients->leftJoin('users', function ($join) {
            $join->on('users.clientid', '=', 'clients.client_id');
            $join->on('users.account_owner', '=', DB::raw("'yes'"));
        });

        //join: client category
        $clients->leftJoin('categories', 'categories.category_id', '=', 'clients.client_categoryid');

        //default where
        $clients->whereRaw("1 = 1");

        //filters: id
        if (request()->filled('filter_client_id')) {
            $clients->where('client_id', request('filter_client_id'));
        }
        if (is_numeric($id)) {
            $clients->where('client_id', $id);
        }

        //filter: status
        if (request()->filled('filter_client_status')) {
            $clients->where('client_status', request('filter_client_status'));
        }

        //filter: created date (start)
        if (request()->filled('filter_date_created_start')) {
            $clients->where('client_created', '>=', request('filter_date_created_start'));
        }

        //filter: created date (end)
        if (request()->filled('filter_date_created_end')) {
            $clients->where('client_created', '<=', request('filter_date_created_end'));
        }

        //filter: contacts
        if (is_array(request('filter_client_contacts')) && !empty(array_filter(request('filter_client_contacts'))) && !empty(array_filter(request('filter_client_contacts')))) {
            $clients->whereHas('users', function ($query) {
                $query->whereIn('id', request('filter_client_contacts'));
            });
        }

        //filter: catagories
        if (is_array(request('filter_client_categoryid')) && !empty(array_filter(request('filter_client_categoryid'))) && !empty(array_filter(request('filter_client_categoryid')))) {
            $clients->whereHas('category', function ($query) {
                $query->whereIn('category_id', request('filter_client_categoryid'));
            });
        }

        //filter: tags
        if (is_array(request('filter_tags')) && !empty(array_filter(request('filter_tags'))) && !empty(array_filter(request('filter_tags')))) {
            $clients->whereHas('tags', function ($query) {
                $query->whereIn('tag_title', request('filter_tags'));
            });
        }

        //search: various client columns and relationships (where first, then wherehas)
        if (request()->filled('search_query')) {
            $clients->where(function ($query) {
                $query->Where('client_id', '=', request('search_query'));
                $query->orwhere('client_company_name', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('client_created', 'LIKE', '%' . request('search_query') . '%');
                $query->orWhere('client_status', '=', request('search_query'));
                $query->orWhereHas('tags', function ($query) {
                    $query->where('tag_title', 'LIKE', '%' . request('search_query') . '%');
                });
                $query->orWhereHas('category', function ($query) {
                    $query->where('category_name', 'LIKE', '%' . request('search_query') . '%');
                });
            });
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            //direct column name
            if (Schema::hasColumn('clients', request('orderby'))) {
                $clients->orderBy(request('orderby'), request('sortorder'));
            }
            //others
            switch (request('orderby')) {
                case 'contact':
                    $clients->orderBy('first_name', request('sortorder'));
                    break;
                case 'count_projects':
                    $clients->orderBy('count_projects_all', request('sortorder'));
                    break;
                case 'sum_invoices':
                    $clients->orderBy('sum_invoices_all', request('sortorder'));
                    break;
                case 'category':
                    $clients->orderBy('category_name', request('sortorder'));
                    break;
            }
        } else {
            //default sorting
            $clients->orderBy('client_company_name', 'asc');
        }

        //eager load
        $clients->with([
            'tags',
            'users',
        ]);

        // Get the results and return them.
        return $clients->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * Create a new record
     * @return mixed object|bool  object or process outcome
     */
    public function create()
    {
        $client = new $this->clients;

        $custType = request('cust_type');
        $client->client_creatorid = Auth()->user()->id;
        $client->cust_type = $custType;

        if ($custType == 0) {
            $client->client_company_name = request('client_company_name');
            $client->u_email = request('c_email');
            $client->client_phone = request('mobile_no');
            $client->credit_term = request('c_credit_term');
            $client->credit_lim_etra = request('c_credit_term');
            $client->currency = request('c_currency');
            $client->com_uen = request('com_uen');
            $name = request('client_company_name');
        } else {
            $client->f_name = request('first_name');
            $client->u_email = request('cu_email');
            $client->client_phone = request('cu_mobile_no');
            $client->credit_term = request('cu_credit_term');
            $client->credit_lim_etra = request('cu_credit_term');
            $client->currency = request('cu_currency');
            $name = request('first_name');
        }

        // Generate Customer Code
        $prefix = strtoupper(substr(trim($name), 0, 1));

        $lastCode = DB::table('clients') // use primary source
            ->where('cust_code1', 'LIKE', $prefix . '%')
            ->orderBy('cust_code1', 'desc')
            ->value('cust_code1');

        $number = $lastCode ? intval(substr($lastCode, 1)) + 1 : 2000;
        $cust_code1 = $prefix . $number;

        $client->cust_code1 = $cust_code1;

        if ($client->save()) {
            DB::table('cust_info')->insert([
                'cust_id' => $cust_code1,
                'client_id' => $client->client_id
            ]);
            return $client->client_id;
        } else {
            Log::error("Record could not be saved - database error", [
                'request' => request()->all()
            ]);
            return response()->json(['error' => 'Failed to save client'], 500);
        }
    }



    /**
     * Create a new client
     * @return mixed object|bool client object or failed
     */
    public function signUp()
    {

        //save new user
        $client = new $this->clients;

        //data
        $client->client_company_name = request('client_company_name');
        $client->client_creatorid = 0;

        //save and return id
        if ($client->save()) {
            return $client;
        } else {
            Log::error("record could not be saved - database error", ['process' => '[ClientRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * update a record
     * @param int $id client id
     * @return mixed int|bool client id or failed
     */
    public function update($id)
    {
        // Get the record
        if (!$client = $this->clients->find($id)) {
            Log::error("client record could not be found", [
                'process' => '[ClientRepository]',
                config('app.debug_ref'),
                'function' => __FUNCTION__,
                'file' => basename(__FILE__),
                'line' => __LINE__,
                'path' => __FILE__,
                'client_id' => $id ?? ''
            ]);
            return false;
        }

        $oldName = ($client->cust_type == 0) ? $client->client_company_name : $client->f_name;

        // General info
        if (request('cust_type') == 0) {
            $client->client_creatorid = Auth()->user()->id;
            $client->cust_type = request('cust_type');
            $client->client_company_name = request('client_company_name');
            $client->u_email = request('c_email');
            $client->client_phone = request('mobile_no');
            $client->credit_term = request('c_credit_term');
            $client->credit_lim_etra = request('c_credit_term');
            $client->currency = request('c_currency');
            $client->com_uen = request('com_uen');

            $newName = request('client_company_name');
        }

        if (request('cust_type') == 1) {
            $client->client_creatorid = Auth()->user()->id;
            $client->cust_type = request('cust_type');
            $client->f_name = request('first_name');
            $client->u_email = request('cu_email');
            $client->client_phone = request('cu_mobile_no');
            $client->credit_term = request('cu_credit_term');
            $client->credit_lim_etra = request('cu_credit_term');
            $client->currency = request('cu_currency');

            $newName = request('first_name');
        }

        // If name has changed, regenerate cust_code1
        if (trim($oldName) !== trim($newName)) {
            $prefix = strtoupper(substr(trim($newName), 0, 1)); // Only first character

            // Step 1: Try to find last matching cust_id from cust_info
            $lastCode = DB::table('cust_info')
                ->where('cust_id', 'LIKE', $prefix . '%')
                ->orderBy('cust_id', 'desc')
                ->value('cust_id');

            // Step 2: If not found, fallback to clients table
            if (!$lastCode) {
                $lastCode = DB::table('clients')
                    ->where('cust_code1', 'LIKE', $prefix . '%')
                    ->orderBy('cust_code1', 'desc')
                    ->value('cust_code1');
            }

            // Step 3: Extract numeric part
            if ($lastCode && preg_match('/^' . $prefix . '(\d+)$/', $lastCode, $matches)) {
                $lastNumber = intval($matches[1]);
            } else {
                $lastNumber = 1999; // Start from 2000
            }

            $newCode = $prefix . ($lastNumber + 1);

            // Step 4: Assign to client model
            $client->cust_code1 = $newCode;

            // Step 5: Update or Insert into cust_info
            $exists = DB::table('cust_info')->where('client_id', $client->client_id)->first();

            if ($exists) {
                DB::table('cust_info')
                    ->where('client_id', $client->client_id)
                    ->update(['cust_id' => $newCode]);
            } else {
                DB::table('cust_info')->insert([
                    'cust_id' => $newCode,
                    'client_id' => $client->client_id,

                ]);
            }
        }


        // Finally, save client
        $client->save();

        return true;
    }




    /**
     * various feeds for ajax auto complete
     * @param string $type (company_name)
     * @param string $searchterm
     * @return object client model object
     */
    public function autocompleteFeed($type = '', $searchterm = '')
    {

        //validation
        if ($type == '' || $searchterm == '') {
            return [];
        }

        //start
        $query = $this->clients->newQuery();

        //feed: company names
        if ($type == 'company_name') {
            $query->selectRaw('CONCAT(COALESCE(f_name, ""), "  ", COALESCE(client_company_name, "")) AS value, client_id AS id');
            $query->where(function ($query) use ($searchterm) {
                $query->where(function ($query) use ($searchterm) {
                    $query->where('f_name', 'LIKE', '%' . $searchterm . '%');
                    // $query->orWhere('f_name', 'IS', null); // Match if f_name is null
                });
                $query->orWhere(function ($query) use ($searchterm) {
                    $query->where('client_company_name', 'LIKE', '%' . $searchterm . '%');
                    // $query->orWhere('client_company_name', 'IS', null); // Match if client_company_name is null
                });
            });
        }








        //return
        return $query->get();
    }

    /**
     * update a record
     * @param int $id record id
     * @return bool process outcome
     */
    public function updateLogo($id)
    {

        //get the user
        if (!$client = $this->clients->find($id)) {
            return false;
        }

        //update logo
        $client->client_logo_folder = request('logo_directory');
        $client->client_logo_filename = request('logo_filename');

        //save
        if ($client->save()) {
            return true;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[ClientRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
}
