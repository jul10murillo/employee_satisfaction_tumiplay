<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Employee\{
    IndexController,
    SearchController,
    GetFavoritesController,
    AddFavoriteController,
    RemoveFavoriteController
};

Route::prefix('v1')->group(function () {
    Route::prefix('employees')->group(function () {
        Route::get('/', IndexController::class); 
    });
});