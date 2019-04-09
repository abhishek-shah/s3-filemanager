<?php
/**
 * Created by PhpStorm.
 * User: sanjogkaskar
 * Date: 3/28/19
 * Time: 4:28 PM
 */


Route::group(['namespace' => 'Hnrtech\Filemanager\Http\Controllers'], function (){

    Route::post('/delete_file', 'FileManagerController@delete');
    Route::post('/filemanager/upload', 'FileManagerController@upload');
    Route::post('/filemanager/addfolder', 'FileManagerController@addfolder');
    Route::get('/filemanager/{path?}', 'FileManagerController@index');
});

