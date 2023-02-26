<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function() {
    Route::resource('filemanager', '\InnoSoft\FileManager\FileManagerController');
    Route::group(['prefix' => 'filemanager'], function() {
        Route::post('create-dir', '\InnoSoft\FileManager\FileManagerController@postCreateDir');
        Route::post('rename', '\InnoSoft\FileManager\FileManagerController@postRename');
        Route::post('upload', '\InnoSoft\FileManager\FileManagerController@postUpload');
        Route::post('save-photo-editor', '\InnoSoft\FileManager\FileManagerController@postSavePhotoEditor');
        Route::post('delete', '\InnoSoft\FileManager\FileManagerController@postDelete');
    });
});
