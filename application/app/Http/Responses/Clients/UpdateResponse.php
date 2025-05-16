<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the clients
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Clients;
use Illuminate\Contracts\Support\Responsable;

class UpdateResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for team members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }
        if($type==0){
            $html = view('pages/clients/components/tables/ajax', compact('clients'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => "#client_" . $clients->first()->client_id,
                'action' => 'replace-with',
                'value' => $html);

        }else{
            $html = view('pages/clients/components/table/ajax', compact('clients'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => "#client_" . $clients->first()->client_id,
                'action' => 'replace-with',
                'value' => $html);

        }
        //replace the row of this record


        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);

    }

}
