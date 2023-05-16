<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->group(function (){
    Route::middleware(['isAdmin'])->group(function () {
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('/dashboard', [EventController::class, 'mainDashboardView'])->name('admin.main-dashboard.view');
        Route::get('/event', [EventController::class, 'eventsView'])->name('admin.events.view');
        Route::prefix('event/{eventCategory}/{eventId}')->group(function () {
            Route::get('/dashboard', [EventController::class, 'eventDashboardView'])->name('admin.event-dashboard.view');
        });
    });

    Route::get('/login', [AdminController::class, 'loginView'])->name('admin.login.view');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');
});