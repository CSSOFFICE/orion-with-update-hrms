<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for contacts
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Addressb;

use App\Models\shipping_address;
use Closure;
use Log;

class Index {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] contacts
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //various frontend and visibility settings
        $this->fronteEnd();

        //embedded request: limit by supplied resource data
        if (request()->filled('addressbresource_type') && request()->filled('addressbresource_id')) {
            //client contacts
            if (request('addressbresource_type') == 'client') {
                request()->merge([
                    'filter_address_clientid' => request('addressbresource_id'),
                ]);
            }
        }

        //client user permission
        if (auth()->user()->is_client) {
            if (auth()->user()->role->role_delivery_address >= 1) {
                //exclude draft contacts
                request()->merge([
                    'filter_address_exclude_status' => 'draft',
                ]);

                return $next($request);
            }
        }

        //admin user permission
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_delivery_address >= 1) {

                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][addressb][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        /**
         * shorten resource_type and resource_id (for easy appending in blade templates - action url's)
         * [usage]
         *   replace the usual url('contact/edit/etc') with urlResource('contact/edit/etc'), in blade templated
         *   usually in the ajax.blade.php files (actions links)
         * */
        if (request('addressbresource_type') != '' || is_numeric(request('addressbresource_id'))) {
            request()->merge([
                'resource_query' => 'ref=list&addressbresource_type=' . request('addressbresource_type') . '&addressbresource_id=' . request('addressbresource_id'),
            ]);
        } else {
            request()->merge([
                'resource_query' => 'ref=list',
            ]);
        }

        //default show some table columns
        config([
            'visibility.address_col_client' => true,
            'visibility.address_col_last_active' => true,
        ]);

        //permissions -viewing
        if (auth()->user()->role->role_delivery_address >= 1) {
            if (auth()->user()->is_team) {
                config([
                    //visibility
                    'visibility.list_page_actions_filter_button' => true,
                    'visibility.list_page_actions_search' => true,
                    'visibility.filter_panel_client' => true,
                ]);
            }
        }

        //permissions -adding
        if (auth()->user()->role->role_delivery_address >= 2) {
            config([
                //visibility
                'visibility.action_column' => true,
                'visibility.list_page_actions_add_button' => true,
                'visibility.action_buttons_edit' => true,
                'visibility.address_col_checkboxes' => true,
            ]);
        }

        //permissions -deleting
        if (auth()->user()->is_client) {
            if (auth()->user()->account_owner == 'yes') {
                config([
                    //visibility
                    'visibility.action_buttons_edit' => true,
                    'visibility.action_buttons_delete' => true,
                    'visibility.list_page_actions_add_button' => true,
                ]);
            }
        }

        //client user
        if (auth()->user()->role->role_delivery_address >= 3) {
            config([
                //visibility
                'visibility.action_buttons_edit' => true,
                'visibility.action_buttons_delete' => true,
            ]);
        }

        //columns visibility
        if (request('addressresource_type') == 'client') {
            config([
                //visibility
                'visibility.address_col_client' => false,
                'visibility.address_col_last_active' => false,
                'visibility.filter_panel_client' => false,
            ]);
        }
    }
}
