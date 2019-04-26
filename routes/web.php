<?php

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('home');
    });
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/download/{id}', 'HomeController@download')->name('download');
    Route::get('invoice', function(){
        return view('invoice');
    });
    
    Route::get('/{vue_capture?}', function() {
        return view('home');
     })->where('vue_capture', '[\/\w\.-]*');

});

