<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [timers] process for the polling
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Polling;

use App\Repositories\TimerRepository;
use Illuminate\Contracts\Support\Responsable;

class TimersResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * various common responses. Add more as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        $jsondata = [];

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        /** ----------------------------------------------------------------
         * General Timers
         * ---------------------------------------------------------------*/
        // update timers which were stopped ....see Tasks controller for explanation
        foreach ($timers as $timer) {
            //list timer
            $jsondata['dom_html'][] = array(
                'selector' => '#task_timer_table_' . $timer->timer_taskid,
                'action' => 'replace',
                'value' => runtimeSecondsHumanReadable($timer->timers_sum, config('settings.timers.display_seconds')));
            //card timer
            $jsondata['dom_html'][] = array(
                'selector' => '#task_timer_card_' . $timer->timer_taskid,
                'action' => 'replace',
                'value' => runtimeSecondsHumanReadable($timer->timers_sum, config('settings.timers.display_seconds')));
        }

         /** ----------------------------------------------------------------
         * Topnav active timer
         * ---------------------------------------------------------------*/
        if ($update_top_nav_timer) {
            $jsondata['dom_html'][] = array(
                'selector' => '#my-timer-time-topnav',
                'action' => 'replace',
                'value' => runtimeSecondsHumanReadable($seconds, false));
        }else{
            //reset and hide top nav timer
            $jsondata['dom_visibility'][] = [
                'selector' => '#my-timer-container-topnav',
                'action' => 'hide',
            ];
            $jsondata['dom_html'][] = [
                'selector' => '#my-timer-time-topnav',
                'action' => 'replace',
                'value' => runtimeSecondsHumanReadable(0, false),
            ];
            //update the dropdown details
            $jsondata['dom_html'][] = [
                'selector' => '#active-timer-topnav-container',
                'action' => 'replace',
                'value' => '',
            ];

        }
        // dd(runtimeSecondsHumanReadable($seconds, false));

        //skip dom initialization
        $jsondata['skip_dom_reset'] = true;

        //skip tinymce reload
        $jsondata['skip_dom_tinymce'] = true;

        //response
        return response()->json($jsondata);

    }

     /**
     * @param object TimerRepository instance of the repository
     * @return \Illuminate\Http\Response
     */
    public function activeTimerPoll(TimerRepository $timerrepo) {

        //get active running timer for this user
        if (!$timer = \App\Models\Timer::where('timer_status', 'running')->where('timer_creatorid', auth()->id())->first()) {
            $payload = [
                'action' => 'hide',
            ];
            return new ActiveTimerResponse($payload);
        }

        //culaculate time lapsed
        $seconds = \Carbon\Carbon::now()->timestamp - $timer->timer_started;

        //get the task
        $task = \App\Models\Task::Where('task_id', $timer->timer_taskid)->first();

        //needed by the topnav timer dropdown
        request()->merge([
            'users_running_timer_task_id' => $task->task_id,
            'users_running_timer_title' => $task->task_title,
            'users_running_timer_task_title' => str_slug($task->task_title),
        ]);

        //payload
        $payload = [
            'action' => 'update',
            'seconds' => $seconds,
            'task' => $task,
        ];

        //response
        return new ActiveTimerResponse($payload);
    }




}
