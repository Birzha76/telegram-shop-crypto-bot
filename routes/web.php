<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TelegramUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::prefix('/admin')->name('admin.')->middleware('admin')->group(function() {
    Route::get('/home', [MainController::class, 'index'])->name('home');
    Route::resource('categories', CategoryController::class)->middleware('admin.super');
    Route::resource('products', ProductController::class)->middleware('admin.super');
    Route::resource('users', UserController::class)->middleware('admin.super');
    Route::resource('tg-users', TelegramUserController::class)->middleware('admin.super');
});

Route::name('user.')->group(function(){

    Route::get('/login', function () {
        if (Auth::check()) {
            return redirect(route('admin.home'));
        }
        return view('login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'login'])->name('login-form');

    Route::get('/logout', function() {
        Auth::logout();
        return redirect(route('user.login'));
    })->name('logout');
});

Route::get('/', function () {
    return view('welcome');
});

Route::any('/hook-88-1', [MainController::class, 'hook']);
Route::get('/fix', [MainController::class, 'fix']);
