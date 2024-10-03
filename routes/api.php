<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group( function() {
    Route::post('auth/register', \App\http\Controllers\Api\Auth\ResgisterControler::class);
    Route::post('auth/login', \App\Http\Controllers\Api\Auth\LoginControler::class);
    Route::resource('home',\App\Http\Controllers\Api\HomeControler::class)->except(['edit', 'create',]);


     // Route yang hanya bisa diakses dengan token
        Route::middleware('auth:sanctum')->group(function () {
        //Route untuk Logout user
        Route::post('auth/logout', \App\Http\Controllers\Api\Auth\LogoutControler::class);
        Route::resource('category',\App\Http\Controllers\Api\CategoryControler::class)->except(['edit', 'create']);
        Route::resource('product',\App\Http\Controllers\Api\ProductControler::class)->except(['edit', 'create']);
    });

});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
