<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function() { // laravel passport api
	Route::apiResources([
		'user' => 'API\UserController',
		'employee' => 'API\EmployeeController',


	]);
	Route::get('profile', 'API\UserController@profile');
	Route::get('findUser', 'API\UserController@search');


	Route::put('profile', 'API\UserController@updateProfile');


	Route::get('departments', 'API\EmployeeController@departments');
	Route::get('employeeTypes', 'API\EmployeeController@employeeTypes');
	Route::get('employmentTypes', 'API\EmployeeController@employmentTypes');
	Route::get('findEmployee', 'API\EmployeeController@search');
	Route::get('deleteEmployeeSchedule','API\EmployeeController@deleteSchedule');
	Route::get('employeeLogs','API\EmployeeController@loadEmployeeLogs');
	Route::get('employeeLogsPaginate','API\EmployeeController@getEmployeeLogsPage');
	Route::get('deleteLoggedSchedule','API\EmployeeController@deleteLoggedSchedule');

	Route::post('inputSchedules','API\EmployeeController@inputSchedules');
	Route::post('importExcel','API\EmployeeController@importExcel');


});

