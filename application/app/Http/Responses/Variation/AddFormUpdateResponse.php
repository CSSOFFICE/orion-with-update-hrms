<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [status] process for the Variation
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Variation;
use Illuminate\Contracts\Support\Responsable;

class AddFormUpdateResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //render the form
        $jsondata['redirect_url'] = url("/projects");
        return response()->json($jsondata);
    }

}
