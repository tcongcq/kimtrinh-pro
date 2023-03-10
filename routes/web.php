<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::group(['prefix' => config('cms.backend_prefix')], function(){
        Route::get('profile', '\App\Http\Controllers\Admin\DashboardController@getProfile');
        Route::post('profile', '\App\Http\Controllers\Admin\DashboardController@postProfile');

        Route::resource('filemanager', '\InnoSoft\FileManager\FileManagerController');
        Route::group(['prefix' => 'filemanager'], function() {
            Route::post('create-dir', '\InnoSoft\FileManager\FileManagerController@postCreateDir');
            Route::post('rename', '\InnoSoft\FileManager\FileManagerController@postRename');
            Route::post('upload', '\InnoSoft\FileManager\FileManagerController@postUpload');
            Route::post('save-photo-editor', '\InnoSoft\FileManager\FileManagerController@postSavePhotoEditor');
            Route::post('delete', '\InnoSoft\FileManager\FileManagerController@postDelete');
        });
        Route::any('{paths?}', "\InnoSoft\CMS\CMSController@route")->where('paths', '([A-Za-z0-9\-\/]+)');
    });

    Route::get('/', function () {
        return redirect()->to(config('cms.backend_prefix'));
    });
});