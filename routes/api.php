<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Employee\{
    IndexController,
    SearchController,
};

Route::prefix('v1')->group(function () {
    Route::prefix('employees')->group(function () {
        Route::get('/', IndexController::class); 
        Route::get('/search', SearchController::class);
    });
});