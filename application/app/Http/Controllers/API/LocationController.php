<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserLocationRepository;
use App\Models\UsersLocation;
use Carbon\Carbon;

class LocationController extends Controller
{
    /**
     * The user location repository instance.
     */
    protected $locationrepo;

    public function __construct(
        UserLocationRepository $locationrepo
    ) 
    {
        //parent
        parent::__construct();

        $this->middleware('jwt.verify');

        $this->locationrepo = $locationrepo;
    }

    public function storeLocation()
    {
        $request = request(['longitude', 'latitude', 'altitude', 'accuracy', 'altitudeAccuracy', 'heading', 'speed', 'timestmp']);

        if (!isset($request['longitude']) || !isset($request['latitude'])) {
            return response()->json([
                'error' => 'Location coords is required'
            ], 401);
        }

        $user = auth('api')->user();

        // $gps_location = UsersLocation::create([
        //     'user_id' => $user->id,
        //     'latitude' => $request['latitude'],
        //     'longitude' => $request['longitude'],
        //     'altitude' => $request['altitude'],
        //     'accuracy' => $request['accuracy'],
        //     'altitudeAccuracy' => $request['altitudeAccuracy'],
        //     'heading' => $request['heading'],
        //     'speed' => $request['speed'],
        //     'timestmp' => Carbon::createFromTimestamp($request['timestmp'])->format('Y-m-d h:i:s')
        // ]);

        //create the location
        $location_id = $this->locationrepo->create($user->id);

        if($location_id) {
            return response()->json([
                'success' => 'Location saved successfully',
                'save_id' => $location_id
            ], 200);
        }else {
            return response()->json([
                'error' => 'Location could not be saved'
            ], 401);
        }
    }
}
