<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\BlogPostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::post('me', [AuthController::class, 'me']);

    Route::get('/list-blogs', [BlogPostController::class, 'listBlogs']);
    Route::get('/show-blog/{id}', [BlogPostController::class, 'showBlog']);
    Route::post('/add-blog-post', [BlogPostController::class, 'addBlog']);
    Route::put('/update-blog-post/{id}', [BlogPostController::class, 'updateBlog']);
    Route::delete('/delete-blog-post/{id}', [BlogPostController::class, 'deleteBlog']);

});
