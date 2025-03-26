<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\isLogin;
use Illuminate\Support\Facades\Route;

//Route::get('{any?}', function () {
//    return view('application');
//})->where('any', '.*')
//    ->middleware('auth');


Route::middleware(isLogin::class)->group(function() {
    Route::get('/', function () {
        return view('application');
    });
    Route::get('/posts', function () {
        return view('application');
    });

    Route::post('/get-posts', [PostController::class, 'getPosts'])->name('posts.get');
    Route::post('/add-post', [PostController::class, 'addPost'])->name('posts.add-post');
    Route::put('/update-post/{id}', [PostController::class, 'updatePost'])->name('posts.update-post');
});

Route::get('/login', function () {
    return view('application');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    return view('application');
})->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
