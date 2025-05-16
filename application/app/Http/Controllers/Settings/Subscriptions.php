<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for subsccriptions settings
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\Subscriptions\PlansResponse;

class Subscriptions extends Controller {

    public function __construct() {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //settings general
        $this->middleware('settingsMiddlewareIndex');

    }

    /**
     * connect to stripe and get a list of the current plans
     *
     * @return \Illuminate\Http\Response
     */
    public function plans() {

        //get settings
        $settings = \App\Models\Settings::find(1);

        $error = false;

        $stripe = new \Stripe\StripeClient('asasa');

        //try and get the plans
        try {
            $plans = $stripe->plans->all(['limit' => 3]);
        } catch ( \Stripe\Exception\AuthenticationException $e) {
            $error = true;
        }

        if($error){
            return new PlansResponse([
                'page' => $this->pageSettings('plans'),
                'section' => 'stripe-not-configured',
            ]);
        }

    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => ' - ' . __('lang.settings'),
            'heading' => __('lang.settings'),
            'settingsmenu_general' => 'active',
        ];

        if ($section == 'plans') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.subscription_plans'),
            ];

        }
        return $page;
    }

}
