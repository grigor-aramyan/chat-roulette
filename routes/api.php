<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::get('pair/find', 'FindPairController')->middleware('auth:api');
Route::post('mode/update', 'UpdateUserModeController')->middleware('auth:api');

Route::apiResource('messages', 'MessageController')->except([
    'index', 'show'
])->middleware('auth:api');
Route::get('messages/fetch/{pairing_user_id}', 'MessageController@fetch')->middleware('auth:api');