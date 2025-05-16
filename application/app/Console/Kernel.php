<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel {
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Nextloop
     * This is the applications task/cron scheduler. You can create more tasks in this schedule
     *      For cronjob tasks, you create the classes inside of '/App/Cronjobs/'
     *      Example is the email cronjob that runs every minute
     *      This scheduler is executed every minute by the single cronjob that is set in cpanel
     *      Teh Cpanel cronjob executes (using real file path) the comman below
     *                  - php artisan schedule:run
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {

        //send regular queued emails
        $schedule->call(new \App\Cronjobs\EmailCron)->everyMinute();

        //send pdf generating emails (invoice & estimate)
        $schedule->call(new \App\Cronjobs\EmailBillsCron)->everyMinute();

        
        //process webhooks for paypal onetime payments
        $schedule->call(new \App\Cronjobs\PaypalCompleteOnetimePayments)->everyMinute();

        //process webhooks for stripe onetime payments
        $schedule->call(new \App\Cronjobs\StripeCompleteOnetimePayments)->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
