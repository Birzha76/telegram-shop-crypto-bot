<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

Route::prefix('/admin')->name('admin.')->middleware('admin')->group(function() {

    Route::get('/home', [MainController::class, 'index'])->name('home');
//    Route::get('/stats', [StatsController::class, 'index'])->name('stats');
//    Route::get('/stats-user', [StatsController::class, 'perUser'])->name('stats.user')->middleware('admin.super');
//    Route::get('/stats-user-week', [StatsController::class, 'userWeek'])->name('stats.user.week');
//    Route::post('/stats-user', [StatsController::class, 'searchPerUser'])->name('stats.user.search')->middleware('admin.super');
//    Route::get('/settings', [SettingController::class, 'edit'])->name('settings')->middleware('admin.super');
//    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('admin.super');
    Route::resource('users', UserController::class)->middleware('admin.super');
//    Route::resource('domains', DomainController::class)->middleware('admin.super');
//    Route::resource('links', LinkController::class);
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
