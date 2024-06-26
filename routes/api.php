<?php

use App\Http\Controllers\AttendeesController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExhibitorController;
use App\Http\Controllers\MediaPartnerController;
use App\Http\Controllers\MeetingRoomPartnerController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\SponsorController;
use Illuminate\Http\Request;
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

                            Route::prefix('speaker')->group(function () {
                                // Route::get('/', [SpeakerController::class, 'apiEventSpeakers']);
                                Route::get('/{speakerId}', [SpeakerController::class, 'apiEventSpeakerDetail']);
                                Route::post('/mark-as-favorite', [SpeakerController::class, 'apiEventSpeakerMarkAsFavorite']);
                            });

                            Route::prefix('session')->group(function () {
                                // Route::get('/', [SessionController::class, 'apiEventSessions']);
                                Route::get('/{sessionId}', [SessionController::class, 'apiEventSessionDetail']);
                                Route::post('/mark-as-favorite', [SessionController::class, 'apiEventSessionMarkAsFavorite']);
                            });

                            Route::prefix('sponsor')->group(function () {
                                // Route::get('/', [SponsorController::class, 'apiEventSponsors']);
                                Route::get('/{sponsorId}', [SponsorController::class, 'apiEventSponsorDetail']);
                                Route::post('/mark-as-favorite', [SponsorController::class, 'apiEventSponsorMarkAsFavorite']);
                            });

                            Route::prefix('exhibitor')->group(function () {
                                // Route::get('/', [ExhibitorController::class, 'apiEventExhibitors']);
                                Route::get('/{exhibitorId}', [ExhibitorController::class, 'apiEventExhibitorDetail']);
                                Route::post('/mark-as-favorite', [ExhibitorController::class, 'apiEventExhibitorMarkAsFavorite']);
                            });

                            Route::prefix('meeting-room-partner')->group(function () {
                                // Route::get('/', [MeetingRoomPartnerController::class, 'apiEventMeetingRoomPartners']);
                                Route::get('/{meetingRoomPartnerId}', [MeetingRoomPartnerController::class, 'apiEventMeetingRoomPartnerDetail']);
                                Route::post('/mark-as-favorite', [MeetingRoomPartnerController::class, 'apiEventMeetingRoomPartnerMarkAsFavorite']);
                            });

                            Route::prefix('media-partner')->group(function () {
                                // Route::get('/media-partner', [MediaPartnerController::class, 'apiEventMediaPartners']);
                                Route::get('/{mediaPartnerId}', [MediaPartnerController::class, 'apiEventMediaPartnerDetail']);
                                Route::post('/mark-as-favorite', [MediaPartnerController::class, 'apiEventMediaPartnerMarkAsFavorite']);
                            });
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
