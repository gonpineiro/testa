<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
// use App\Http\Controllers\Api\ProductController;

Route::group(['middleware' => ['role:writer']], function () {
    Route::get('get_user', [ApiController::class, 'get_user']);

    //Route::resource('producto', ProductController::class);
});
