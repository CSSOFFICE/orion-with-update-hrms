<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\StatsRepository;
use App\Repositories\ProjectRepository;

class Dashboard extends Controller
{
    protected $statsrepo;

    public function __construct(StatsRepository $statsrepo) 
    {
        //parent
        parent::__construct();

        $this->middleware('jwt.verify');

        $this->statsrepo = $statsrepo;
    }

    public function index()
    {
        $user = auth('api')->user();

        $payload = [];

        //[projects][all]
        $payload['projects'] = [
            'pending' => $this->statsrepo->countProjects([
                'status' => 'pending',
                'assigned' => $user->id,
            ]),
        ];

         //tasks]
        $payload['tasks'] = [
            'new' => $this->statsrepo->countTasks([
                'status' => 'new',
                'assigned' => $user->id,
            ]),
            'in_progress' => $this->statsrepo->countTasks([
                'status' => 'in_progress',
                'assigned' => $user->id,
            ]),
            'awaiting_feedback' => $this->statsrepo->countTasks([
                'status' => 'awaiting_feedback',
                'assigned' => $user->id,
            ]),
        ];

        return response()->json($payload);
    }
}
