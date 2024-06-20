<?php

use App\Http\Controllers\AttendeesController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExhibitorController;
use App\Http\Controllers\MediaPartnerController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\SponsorController;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api.check.secret.code'], function () {
    Route::prefix('{api_code}')->group(function () {
        Route::get('/event', [EventController::class, 'apiEventsList']);
        Route::group(['middleware' => 'api.check.event.exists'], function () {
            Route::prefix('event/{eventCategory}/{eventId}')->group(function () {
                Route::post('/login', [AttendeesController::class, 'apiAttendeeLogin']);

                Route::middleware("auth:sanctum")->group(function () {
                    Route::group(['middleware' => 'api.check.attendee.exists'], function () {
                        Route::prefix('attendee/{attendeeId}')->group(function () {
                            Route::get('/logout', [AttendeesController::class, 'apiAttendeeLogout']);
                            
                            Route::get('/homepage', [EventController::class, 'apiEventHomepage']);

                            Route::get('/speaker', [SpeakerController::class, 'apiSpeakersList']);
                            Route::get('/sponsor', [SponsorController::class, 'getListOfSponsors']);

                            Route::get('/exhibitor', [ExhibitorController::class, 'getListOfExhibitors']);
                            Route::get('/media-partner', [MediaPartnerController::class, 'getListOfMediaPartners']);
                        });
                    });
                });
            });
        });
    });
});


Route::fallback(function () {
    return response()->json([
        'status' => 404,
        'message' => "Page not found",
    ], 404);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
