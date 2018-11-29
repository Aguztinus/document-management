<?php

use Illuminate\Http\Request;

/*php
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

Route::apiResources([
    'user' => 'API\UserController',
    'unit' => 'API\UnitController',
    'document' => 'API\DocumentController'
]);

Route::get('profile', 'API\UserController@profile');
Route::get('findUser', 'API\UserController@search');
Route::get('allUnit', 'API\UnitController@allUnit');
Route::put('profile', 'API\UserController@updateProfile');

Route::get('getref/{id}', 'API\DocumentController@getref');

