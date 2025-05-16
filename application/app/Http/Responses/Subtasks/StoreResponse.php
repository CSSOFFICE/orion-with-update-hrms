<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [store] process for the tasks settings
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Subtasks;

use Illuminate\Contracts\Support\Responsable;

class StoreResponse implements Responsable
{

    private $payload;

    public function __construct($payload = array())
    {
        $this->payload = $payload;
    }

    /**
     * render the view for task members
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

        //prepend content on top of list or show full table
        if (auth()->user()->pref_view_tasks_layout == 'list') {
            if ($count == 1) {
                $html = view('pages/subtasks/components/table/table', compact('subtasks'))->render();
                $jsondata['dom_html'][] = array(
                    'selector' => '#subtasks-view-wrapper',
                    'action' => 'replace',
                    'value' => $html
                );
            } else {
                //prepend use on top of list
                $html = view('pages/subtasks/components/table/ajax', compact('subtasks'))->render();
                $jsondata['dom_html'][] = array(
                    'selector' => '#tasks-td-container',
                    'action' => 'prepend',
                    'value' => $html
                );
            }
        }



        //refresh stats
        if (isset($stats)) {
            $html = view('misc/list-pages-stats-content', compact('stats'))->render();
            $jsondata['dom_html'][] = [
                'selector' => '#list-pages-stats-widget',
                'action' => 'replace',
                'value' => $html,
            ];
        }

        //show task after adding


        //close modal
        $jsondata['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $jsondata['notification'] = array('type' => 'success', 'value' => __('lang.request_has_been_completed'));

        //response
        return response()->json($jsondata);
    }
}
