<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for payment gateways
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Gateway;
use Illuminate\Http\Request;
use Log;

class GatewayRepository {

    /**
     * The gateway repository instance.
     */
    protected $gateways;

    /**
     * Inject dependecies
     */
    public function __construct(Gateway $gateway) {
        $this->gateways = $gateway;
    }

    /**
     * update a gateway
     * @param int $id resource id
     * @return bool
     */
    public function update($id = '') {

        //validate
        if (!is_numeric($id)) {
            Log::error("validation error - invalid params", ['process' => '[GatewayRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //get the record
        if (!$gateway = $this->gateways->find($id)) {
            Log::error("gateway could not be found", ['process' => '[GatewayRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //update
        $gateway->gateway_sandbox_mode = (request('gateway_sandbox_mode') == 'on') ? 'enabled' : 'disabled';
        $gateway->gateway_status = (request('gateway_status') == 'on') ? 'enabled' : 'disabled';

        //save
        if ($gateway->save()) {
            return true;
        } else {
            Log::error("unable to update record - database error", ['process' => '[GatewayRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }
}