<?php

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('home');
    });
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('invoice', function(){
        return view('invoice');
    });
    
    Route::get('{path}',"HomeController@index")->where( 'path', '([A-z\d-\/_.]+)?' );
    
});

