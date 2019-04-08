<?php
Route::group(['namespace' => 'Kanamania\FileManager\Http\Controllers', 'middleware' => ['web']], function() {
    Route::get('f/{file}', 'FileManagerController@get')->name('kfm.get');
    Route::post('f/up', 'FileManagerController@upload')->name('kfm.upload');
    Route::post('f/{file}/x', 'FileManagerController@delete')->name('kfm.delete');
    Route::get('f/{file}/{x}/{y}', 'FileManagerController@thumbnail')->name('kfm.thumb');
});