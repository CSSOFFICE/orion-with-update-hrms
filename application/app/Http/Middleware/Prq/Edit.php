<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for notes
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Prq;

use App\Permissions\ProjectInventoryPermissions;
use Closure;
use Illuminate\Support\Facades\DB;
use Log;

class Edit
{

    /**
     * The note permisson repository instance.
     */
    protected $notepermissons;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(ProjectInventoryPermissions $notepermissons)
    {

        $this->notepermissons = $notepermissons;
    }

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] notes
     *   3. modifies the request object as needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        //    print_r($request->route('prq'));die;
        //basic validation
        if (!$note = DB::table('purchase_requistion')->where('purchase_requistion_id', $request->route('prq'))->first()) {
            Log::error("note could not be found", ['process' => '[permissions][notes][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'note' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'note id' => $request->route('prq')]);
            abort(409, __('lang.note_not_found'));
        }
        //all
        if ($this->notepermissons->check('edit-delete',$note)) {
            return $next($request);
        }
        // Ensure headers are not null
        if (empty($request->headers)) {
            Log::error("request headers are null", ['process' => '[permissions][notes][edit]', 'ref' => config('app.debug_ref'), 'function' => __FUNCTION__, 'note' => basename(__FILE__), 'line' => __LINE__, 'path' => __FILE__]);
            abort(400, __('lang.invalid_request_headers'));
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][notes][edit]', 'ref' => config('app.debug_ref'), 'function' => __FUNCTION__, 'note' => basename(__FILE__), 'line' => __LINE__, 'path' => __FILE__]);
        abort(403);
    }
}
