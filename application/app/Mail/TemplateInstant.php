<?php

/** --------------------------------------------------------------------------------
 * [template]
 * This classes renders the [new email] email and send it instantly
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class TemplateInstant extends Mailable {
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
            return;
        }

        //only active templates
        if ($template->emailtemplate_status != 'enabled') {
            return false;
        }

        //get common email variables
        $payload = config('mail.data');

        //set template variables
        $payload += [
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'foo' => runtimeDate($this->obj->bar),
            'foo' => $this->obj->bar,
        ];

        //log email
        $log = new \App\Models\EmailLog();
        $log->emaillog_email = $this->user->email;
        $log->emaillog_subject = $template->parse('subject', $payload);
        $log->emaillog_body = $template->parse('body', $payload);
        $log->save();

        //get the temple
        return $this->from(config('system.settings_email_from_address'), config('system.settings_email_from_name'))
            ->subject($template->parse('subject', $payload))
            ->with([
                'content' => $template->parse('body', $payload),
            ])
            ->view('email.template');

        /** inside controller */
        //Mail::to($user->email)->send(new TaskStatusChanged($user, $data, $task));

    }
}
