<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('posts', PostController::class);
Route::get('categories', [PostController::class, 'getCategories']);
