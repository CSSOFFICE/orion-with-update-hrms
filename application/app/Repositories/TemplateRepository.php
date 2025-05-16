<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for templates
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Log;


class TemplateRepository{



    /**
     * The leads repository instance.
     */
    protected $user;

    /**
     * Inject dependecies
     */
    public function __construct(User $user) {
        $this->user = $user;
    }

}