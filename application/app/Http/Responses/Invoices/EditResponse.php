<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [edit] process for the invoices
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Invoices;

use Illuminate\Contracts\Support\Responsable;

class EditResponse implements Responsable
{

    private $payload;

    public function __construct($payload = array())
    {
        $this->payload = $payload;
    }

    /**
     * render the view for invoices
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
        // print_r($project);
        //render the form
        // $html = view('pages/invoices/components/modals/add-edit-inc', compact('page', 'invoice', 'roles', 'categories', 'tags'))->render();
        $html = view('pages/invoices/components/modals/edit', compact('page', 'invoice', 'payment_terms', 'gst12', 'invoice_item', 'quotation', 'total_invoice_amount', 'project', 'templete_category', 'grn_data','tags'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html
        );


        //show modal invoiceter
        $jsondata['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        // POSTRUN FUNCTIONS------
        $jsondata['postrun_functions'][] = [
            'value' => 'NXInvoiceCreate',
        ];

        //ajax response
        return response()->json($jsondata);
    }
}
