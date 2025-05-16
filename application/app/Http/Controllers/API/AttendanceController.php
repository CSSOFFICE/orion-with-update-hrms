<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\HrModels\XinAttendanceTime;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct() 
    {
        //parent
        parent::__construct();

        $this->middleware('jwt.verify');
    }

    public function isClockedIn()
    {
        $user = auth('api')->user();

        $attendance = User::find($user->id)->attendanceTime()
                        ->select('attendance_date', 'clock_in')
                        ->whereDate('attendance_date', '=', Carbon::today()->toDateString())
                        ->where('clock_out', '=', '')
                        ->first();

        return response()->json($attendance);
    }

    public function setClockIn()
    {
        $clockin = request(['longitude', 'latitude', 'ip_address']);

        if (!isset($clockin['longitude']) || !isset($clockin['latitude'])) {
            return response()->json([
                'error' => 'Location coords is required'
            ], 401);
        }

        $user = auth('api')->user();

        $attendance = XinAttendanceTime::create([
            'employee_id' => $user->id,
            'attendance_date' => Carbon::today()->toDateString(),
            'clock_in' => Carbon::now()->format('Y-m-d H:i:s'),
            'clock_in_ip_address' => $clockin['ip_address'],
            'clock_in_latitude' => $clockin['latitude'],
            'clock_in_longitude' => $clockin['longitude'],
            'clock_in_out' => 1,
            'attendance_status' => 'Present'
        ]);

        $save = $attendance->save();

        if($save) {
            return response()->json([
                'success' => 'Attendance saved successfully',
                'save_id' => $save
            ], 200);
        }else {
            return response()->json([
                'error' => 'Attendance could not be saved'
            ], 401);
        }
    }

    public function setClockOut()
    {
        $clockout = request(['longitude', 'latitude', 'ip_address']);

        if (!isset($clockout['longitude']) || !isset($clockout['latitude'])) {
            return response()->json([
                'error' => 'Location coords is required'
            ], 401);
        }

        $user = auth('api')->user();

        $attendance = XinAttendanceTime::where('employee_id', '=', $user->id)
                        ->whereDate('attendance_date', '=', Carbon::today()->toDateString())
                        ->where('clock_in_out', '=', 1)
                        ->first();
        
        if(!$attendance) {
            return response()->json([
                'error' => 'No attendance found'
            ], 401);
        }

        $clock_in_time = Carbon::parse($attendance->clock_in);
        $now = Carbon::now();
        $diff = $now->diff($clock_in_time)->format('%H:%I');

        $attendance->clock_out = Carbon::now()->format('Y-m-d H:i:s');
        $attendance->clock_out_ip_address = $clockout['ip_address'];
        $attendance->clock_in_out = 0;
        $attendance->clock_out_latitude = $clockout['latitude'];
        $attendance->clock_out_longitude = $clockout['longitude'];
        $attendance->total_work = $diff;

        if($attendance->save()) {
            return response()->json([
                'success' => 'Attendance saved successfully'
            ], 200);
        }else {
            return response()->json([
                'error' => 'Attendance could not be saved'
            ], 401);
        }
    }
}
