<?php

/** --------------------------------------------------------------------------------
 * This service provider executes database updates
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Log;

class UpdateServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        //do not run this if setup has not completed
        if (env('SETUP_STATUS') != 'COMPLETED') {
            //skip this provider
            return;
        }

        //get a list of all the sql files in the updates folder
        $path = BASE_DIR . "/updates";
        $files = File::files($path);
        foreach ($files as $file) {
            //date
            $filename = $file->getFilename();
            $extension = $file->getExtension();
            $filepath = $file->getPathname();
            if ($extension == 'sql') {
                if (\App\Models\Update::Where('update_mysql_filename', $filename)->doesntExist()) {
                    Log::info("the mysql file ($filename) has not previously been executed. Will now execute it", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                    //attempt to execute it
                    try {
                        DB::unprepared(file_get_contents($filepath));
                        //log
                        Log::info("the mysql file ($filename) executed ok - will now delete it", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                        //save record
                        $record = new \App\Models\Update();
                        $record->update_mysql_filename = $filename;
                        $record->save();
                        //delete file
                        if ($filename != '') {
                            unlink($path . "/$filename");
                        }
                    } catch (\Illuminate\Database\QueryException $e) {
                        $message = substr($e->getMessage(), 0, 150);
                        //log
                        Log::critical("the mysql file ($filename) failed to execute - will now delete it", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename, 'sql_error' => $message]);
                        //delete file
                        if ($filename != '') {
                            unlink($path . "/$filename");
                        }
                    }
                } else {
                    //log
                    Log::info("the mysql file ($filename) has previously been executed. Will now delete it", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                    //delete file
                    if ($filename != '') {
                        unlink($path . "/$filename");
                    }
                }
            } else {
                //log
                Log::info("found a non mysql file ($filename) inside the updates folder. Will try to delete it", ['process' => '[updates]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'filename' => $filename]);
                //delete
                if ($filename != '') {
                    unlink($path . "/$filename");
                }
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
