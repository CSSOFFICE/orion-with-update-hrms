<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the invoices
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Invoices;
use Illuminate\Contracts\Support\Responsable;

class StoreResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for invoices
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        
        //redirect to invoice page
       // $jsondata['redirect_url'] = url("/invoices/$id/edit-invoice");
       $html = view('pages/invoices/components/table/ajax', compact('id','invoices','quotation','total_invoice_amount'))->render();
       $jsondata['dom_html'][] = array(
           'selector' => '#tags-td-container',
           'action' => 'prepend',
           'value' => $html);

       //close modal
       $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

       //notice
       $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);
    }

}
