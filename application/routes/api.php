<?php

use App\Http\Controllers\api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

header('Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', 'API\AuthController@logout');
    Route::post('refresh', 'API\AuthController@refresh');
    Route::post('me', 'API\AuthController@me');

});

Route::get('/attendance', 'API\AttendanceController@isClockedIn');
Route::post('/attendance', 'API\AttendanceController@setClockIn');
Route::post('/attendance/clockout', 'API\AttendanceController@setClockOut');

Route::post('/location', 'API\LocationController@storeLocation');

Route::get('/dashboard', 'API\Dashboard@index');

//PROJECTS & PROJECT
Route::group(['prefix' => 'projects'], function () {
    Route::get("/", "API\Projects@index");
    Route::get("/latest", "API\Projects@latest");
    Route::get("/stats", "API\Projects@stats");
    Route::get("/{project}", "API\Projects@show")->where('project', '[0-9]+');
    // Route::post("/delete", "Projects@destroy")->middleware(['demoModeCheck']);
    // Route::get("/change-category", "Projects@changeCategory");
    // Route::post("/change-category", "Projects@changeCategoryUpdate");
    // Route::get("/{project}/change-status", "Projects@changeStatus")->where('project', '[0-9]+');
    // Route::post("/{project}/change-status", "Projects@changeStatusUpdate")->where('project', '[0-9]+');
    // Route::get("/{project}/project-details", "Projects@details")->where('project', '[0-9]+');
    // Route::post("/{project}/project-details", "Projects@updateDescription")->where('project', '[0-9]+');
    // Route::put("/{project}/stop-all-timers", "Projects@stopAllTimers")->where('project', '[0-9]+');

    //dynamic load
    // Route::any("/{project}/{section}", "Projects@showDynamic")
    //     ->where(['project' => '[0-9]+', 'section' => 'details|comments|files|tasks|invoices|payments|timesheets|expenses|estimates|milestones|tickets|notes']);
});
// Route::resource('projects', 'Projects');

//TASKS
Route::group(['prefix' => 'tasks'], function () {
    Route::any("/", "API\Tasks@index");
    // Route::any("/timer/{id}/start", "Tasks@timerStart")->where('id', '[0-9]+');
    // Route::any("/timer/{id}/stop", "Tasks@timerStop")->where('id', '[0-9]+');
    // Route::any("/timer/{id}/stopall", "Tasks@timerStopAll")->where('id', '[0-9]+');
    // Route::post("/delete", "Tasks@destroy")->middleware(['demoModeCheck']);
    // Route::post("/{task}/toggle-status", "Tasks@toggleStatus")->where('task', '[0-9]+');
    // Route::post("/{task}/update-description", "Tasks@updateDescription")->where('task', '[0-9]+');
    // Route::post("/{task}/attach-files", "Tasks@attachFiles")->where('task', '[0-9]+');
    // Route::delete("/delete-attachment/{uniqueid}", "Tasks@deleteAttachment")->middleware(['demoModeCheck']);
    // Route::get("/download-attachment/{uniqueid}", "Tasks@downloadAttachment");
    // Route::post("/{task}/post-comment", "Tasks@storeComment")->where('task', '[0-9]+');
    // Route::delete("/delete-comment/{commentid}", "Tasks@deleteComment")->where('commentid', '[0-9]+');
    // Route::post("/{task}/update-title", "Tasks@updateTitle")->where('task', '[0-9]+');
    // Route::post("/{task}/add-checklist", "Tasks@storeChecklist")->where('task', '[0-9]+');
    // Route::post("/update-checklist/{checklistid}", "Tasks@updateChecklist")->where('checklistid', '[0-9]+');
    // Route::delete("/delete-checklist/{checklistid}", "Tasks@deleteChecklist")->where('checklistid', '[0-9]+');
    // Route::post("/toggle-checklist-status/{checklistid}", "Tasks@toggleChecklistStatus")->where('checklistid', '[0-9]+');
    // Route::post("/{task}/update-start-date", "Tasks@updateStartDate")->where('task', '[0-9]+');
    // Route::post("/{task}/update-due-date", "Tasks@updateDueDate")->where('task', '[0-9]+');
    // Route::post("/{task}/update-status", "Tasks@updateStatus")->where('task', '[0-9]+');
    // Route::post("/{task}/update-priority", "Tasks@updatePriority")->where('task', '[0-9]+');
    // Route::post("/{task}/update-visibility", "Tasks@updateVisibility")->where('task', '[0-9]+');
    // Route::post("/{task}/update-milestone", "Tasks@updateMilestone")->where('task', '[0-9]+');
    // Route::post("/{task}/update-assigned", "Tasks@updateAssigned")->where('task', '[0-9]+');
    // Route::post("/update-position", "Tasks@updatePosition");
    // Route::any("/v/{task}/{slug}", "Tasks@index")->where('task', '[0-9]+');
});
// Route::resource('tasks', 'Tasks');
