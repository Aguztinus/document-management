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
    'author' => 'API\DocumentAutorController',
    'unit' => 'API\UnitController',
    'document' => 'API\DocumentController',
    'documenttype' => 'API\DocumentTypeController',
    'documentnum' => 'API\DocumentNumController',
]);

Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');
Route::get('findUser', 'API\UserController@search');
Route::get('getUserHis', 'API\UserController@getuserHistory');
Route::get('getuserunit/{id}', 'API\UserController@getuserunit');

Route::get('allUnit', 'API\UnitController@allUnit');
Route::get('findUnit', 'API\UnitController@search');
Route::get('findDocType', 'API\DocumentTypeController@search');
Route::get('allDocTypes', 'API\DocumentTypeController@allDocTypes');
Route::get('allDocNum', 'API\DocumentNumController@allDocNum');
Route::get('allAuthor', 'API\DocumentAutorController@allAuthor');

Route::get('findDochome', 'API\DocumentController@searchDochome');
Route::get('mydocument', 'API\DocumentController@mydocument');
Route::get('findDoc', 'API\DocumentController@searchDoc');
Route::get('sortDoc', 'API\DocumentController@sortDoc');
Route::get('filterDoc', 'API\DocumentController@filterDoc');
Route::get('getref/{id}', 'API\DocumentController@getref');
Route::get('gethistory/{id}', 'API\DocumentController@gethistory');
Route::get('getdocumentref/{id}', 'API\DocumentController@getdocumentref');
Route::get('download/{id}', 'API\DocumentController@download');
Route::delete('deletefile/{id}', 'API\DocumentController@deletefile');


Route::post('upload', 'API\DocumentController@upload')->name('upload');

