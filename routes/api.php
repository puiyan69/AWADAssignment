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

Route::middleware('jwt.auth')->group(function() {
    Route::apiResource('appointments', 'AppointmentController');
    Route::apiResource('doctors', 'DoctorController');
    Route::apiResource('patients', 'PatientController');
});

Route::middleware('api')->namespace('Auth')->prefix('auth')->group(function() {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::middleware(['jwt.auth', 'can:manager-user'])-> group(function(){
    Route::apiResource('doctors', 'DoctorController')->only([
        'store',
        'update',
    ]);

    Route::apiResource('patients', 'PatientController')->only([
        'store',
        'update',
    ]);


});

Route::middleware(['jwt.auth', 'can:view-appointments'])-> group(function(){
    Route::apiResource('doctors', 'DoctorController')->only([
        'index',
        'show',
    ]);

    Route::apiResource('patients', 'PatientController')->only([
        'index',
        'show',
    ]);

    Route::apiResource('appointments', 'AppointmentController')->only([
        'index',
        'show',
    ]);
});

Route::middleware(['jwt.auth', 'can:manager-appointment'])-> group(function(){
    Route::apiResource('appointments', 'AppointmentController')->only([
        'store',
        'update'
    ]);
});