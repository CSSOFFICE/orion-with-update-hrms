<?php

/** --------------------------------------------------------------------------------
 * This classes renders the [task status changed] email and stores it in the queue
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail;

use App\Repositories\EmailerRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class TaskStatusChanged extends Mailable {
    use Queueable;

    /**
     * The data for merging into the email
     */
    public $data;

    /**
     * Model instance
     */
    public $obj;

    /**
     * Model instance
     */
    public $user;

    public $emailerrepo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user = [], $data = [], $obj = []) {

        $this->data = $data;
        $this->user = $user;
        $this->obj = $obj;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        //email template
        if (!$template = \App\Models\EmailTemplate::Where('emailtemplate_name', 'Task Status Change')->first()) {
            return false;
        }

        //validate
        if (!$this->obj instanceof \App\Models\Task || !$this->user instanceof \App\Models\User) {
            return false;
        }

        //only active templates
        if($template->emailtemplate_status != 'enabled'){
            return false;
        }


        //get common email variables
        $payload = config('mail.data');

        //set template variables
        $payload += [
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'task_id' => $this->obj->task_id,
            'task_title' => $this->obj->task_title,
            'task_created_date' => $this->obj->task_created_date,
            'task_date_start' => $this->obj->task_date_start,
            'task_description' => $this->obj->task_description,
            'task_date_due' => $this->obj->task_date_due,
            'project_title' => $this->obj->project_title,
            'project_id' => $this->obj->project_id,
            'client_name' => $this->obj->client_company_name,
            'client_id' => $this->obj->client_id,
            'task_status' => runtimeSystemLang($this->obj->task_status),
            'task_milestone' => $this->obj->milestone_title,
            'task_url' => url('/tasks'),
        ];

        //save in the database queue
        $queue = new \App\Models\EmailQueue();
        $queue->emailqueue_to = $this->user->email;
        $queue->emailqueue_subject = $template->parse('subject', $payload);
        $queue->emailqueue_message = $template->parse('body', $payload);
        $queue->save();
    }
}
