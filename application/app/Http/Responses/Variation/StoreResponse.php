<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the estimates
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Variation;

use Illuminate\Contracts\Support\Responsable;

class StoreResponse implements Responsable
{

    private $payload;

    public function __construct($payload = array())
    {
        $this->payload = $payload;
    }

    /**
     * render the view for estimates
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

        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');
        $jsondata['redirect_url'] = url("/project/$bill_projectid/estimates/$id/edit-estimate");
        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);
    }
}
