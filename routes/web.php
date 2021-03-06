<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TelegramUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\CashController;

Route::prefix('/admin')->name('admin.')->middleware('admin')->group(function() {
    Route::get('/home', [MainController::class, 'index'])->name('home');
    Route::resource('categories', CategoryController::class)->middleware('admin.super');

    Route::get('/products/excel', [ProductController::class, 'excel'])->middleware('admin.super')->name('products.excel');
    Route::post('/products/excel', [ProductController::class, 'importFromExcel'])->middleware('admin.super')->name('products.excel-upload');
    Route::resource('products', ProductController::class)->middleware('admin.super');

    Route::resource('checks', CheckController::class)->middleware('admin.super');
    Route::resource('users', UserController::class)->middleware('admin.super');
    Route::resource('tg-users', TelegramUserController::class)->middleware('admin.super');
    Route::resource('settings', SettingController::class)->middleware('admin.super');

    Route::get('/cash', [CashController::class, 'edit'])->middleware('admin.super')->name('cash.index');
    Route::post('/cash', [CashController::class, 'update'])->middleware('admin.super')->name('cash.update');
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
