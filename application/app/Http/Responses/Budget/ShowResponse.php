<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [show] process for the notes
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\budget;
use Illuminate\Contracts\Support\Responsable;

class ShowResponse implements Responsable {

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

        //content & title
        $html = view('pages/budget/components/modals/add-edit-inc', compact('page', 'note', 'tags','note_item','templete_category'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#plainModalBody',
            'action' => 'replace',
            'value' => $html);
        $jsondata['dom_html'][] = array(
            'selector' => '#plainModalTitle',
            'action' => 'replace',
            'value' => "Show Data");

        ///ajax response
        return response()->json($jsondata);

    }

}
