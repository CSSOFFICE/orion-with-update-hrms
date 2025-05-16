<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [show] process for the estimates
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Variation;

use App\Models\variation_templates;
use Illuminate\Contracts\Support\Responsable;

class ShowResponse implements Responsable
{

    private $payload;

    public function __construct($payload = array())
    {
        $this->payload = $payload;
    }

    /**
     * render the view
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

        $quotation_templates = variation_templates::get();
        // dd( $quotation_templates );

        return view('pages/variation_bill/wrapper', compact('page', 'bill', 'taxrates', 'taxes', 'elements', 'units', 'lineitems', 'd_add', 'quotation_templates'))->render();
    }
}
