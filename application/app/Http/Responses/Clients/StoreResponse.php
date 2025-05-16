<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the clients
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Clients;

use Illuminate\Contracts\Support\Responsable;

class StoreResponse implements Responsable
{

    private $payload;

    public function __construct($payload = array())
    {
        $this->payload = $payload;
    }

    /**
     * render the view for team members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }
        if (auth()->user()->role->role_clients >= 1) {
            config([
                'visibility.list_page_actions_filter_button' => true,
                'visibility.list_page_actions_search' => true,
            ]);
        }

        //permissions -adding
        if (auth()->user()->role->role_clients >= 2) {
            config([
                'visibility.list_page_actions_add_button' => true,
                'visibility.action_buttons_edit' => true,
                'visibility.clients_col_checkboxes' => true,
                'visibility.action_column' => true,
            ]);
        }

        //permissions -deleting
        if (auth()->user()->role->role_clients >= 3) {
            config([
                'visibility.list_page_actions_add_button' => true,
                'visibility.action_buttons_delete' => true,
            ]);
        }
        //prepend content on top of list or show full table
        if ($count == 1) {
            $html = view('pages/clients/components/table/table', compact('clients'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#clients-table-wrapper',
                'action' => 'replace',
                'value' => $html
            );
        } else {
            //prepend use on top of list
            $html = view('pages/clients/components/table/ajax', compact('clients'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => '#clients-td-container',
                'action' => 'prepend',
                'value' => $html
            );
        }

        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);
    }
}
