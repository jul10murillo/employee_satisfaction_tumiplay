<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Employee\{
    IndexController,
    SearchController,
    AddFavoriteController,
    RemoveFavoriteController,
    GetFavoritesController,
};

Route::prefix('v1')->group(function () {
    Route::prefix('employees')->group(function () {
        Route::get('/', IndexController::class); 
        Route::get('/search', SearchController::class);
        Route::post('/favorite', AddFavoriteController::class);
        Route::delete('/favorite/{employee}', RemoveFavoriteController::class);
        Route::get('/favorites', GetFavoritesController::class);
    });
});