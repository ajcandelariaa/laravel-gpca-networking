<?php

use App\Http\Controllers\AttendeesController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExhibitorController;
use App\Http\Controllers\MediaPartnerController;
use App\Http\Controllers\MeetingRoomPartnerController;
use App\Http\Controllers\NotificationController;
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
                Route::post('/forgot-password/send-otp', [AttendeesController::class, 'apiForgotPasswordSendOtp']);
                Route::post('/forgot-password/verify-otp', [AttendeesController::class, 'apiForgotPasswordVerifyOtp']);
                Route::post('/forgot-password/reset', [AttendeesController::class, 'apiForgotPasswordReset']);

                Route::middleware("auth:sanctum")->group(function () {
                    Route::group(['middleware' => 'api.check.attendee.exists'], function () {
                        Route::prefix('attendee/{attendeeId}')->group(function () {
                            Route::post('/logout', [AttendeesController::class, 'apiAttendeeLogout']);
                            Route::get('/homepage', [EventController::class, 'apiEventHomepage']);

                            Route::get('test-notification', function(){
                                sendPushNotification("dVBMR1aRMk19u0knEM_OSZ:APA91bGYK7iPJExgCG8GxKrBlmGtzkbhxNFi3kT5J3FgKXXSCPG1ThkA9h0Nye9PpuVjRWgQVzRDUrWhS9EJrgnsevaw1jpfA6L5Z3ecBgMCx6kGnoIh5FOs33kTI8aYUP7fQ-clWDLK", "Test message", "GPCA events", null);
                            });

                            Route::prefix('profile')->group(function () {
                                Route::get('/', [AttendeesController::class, 'apiAttendeeProfile']);
                                Route::post('/edit-details', [AttendeesController::class, 'apiAttendeeEditProfileDetails']);
                                Route::post('/edit-username-email', [AttendeesController::class, 'apiAttendeeEditProfileUsernameEmail']);
                                Route::post('/edit-password', [AttendeesController::class, 'apiAttendeeEditProfilePassword']);
                                Route::post('/edit-pfp', [AttendeesController::class, 'apiAttendeeEditPfp']);
                            });

                            Route::get('/attendees', [AttendeesController::class, 'apiAttendeesList']);

                            Route::get('/v2/attendees', [AttendeesController::class, 'apiAttendeesListv2']);

                            Route::prefix('/attendees/contact')->group(function () {
                                Route::get('/', [AttendeesController::class, 'apiAttendeesContact']);
                                Route::post('/add', [AttendeesController::class, 'apiAttendeesAddToContact']);
                                Route::post('/remove', [AttendeesController::class, 'apiAttendeesRemoveFromContact']);
                            });

                            Route::prefix('conversation')->group(function () {
                                Route::get('/', [ConversationController::class, 'apiConversationsList']);
                                Route::get('/{conversationId}', [ConversationController::class, 'apiConversationMessages']);
                                Route::post('/send-message', [ConversationController::class, 'apiConversationSendMessage']);
                            });

                            Route::get('/favorites', [AttendeesController::class, 'apiAttendeeFavorites']);

                            Route::prefix('speaker')->group(function () {
                                Route::get('/', [SpeakerController::class, 'apiEventSpeakers']);
                                Route::get('/{speakerId}', [SpeakerController::class, 'apiEventSpeakerDetail']);
                                Route::post('/mark-as-favorite', [SpeakerController::class, 'apiEventSpeakerMarkAsFavorite']);
                            });

                            Route::prefix('session')->group(function () {
                                Route::get('/', [SessionController::class, 'apiEventSessions']);
                                Route::get('/{sessionId}', [SessionController::class, 'apiEventSessionDetail']);
                                Route::post('/mark-as-favorite', [SessionController::class, 'apiEventSessionMarkAsFavorite']);
                            });

                            Route::prefix('sponsor')->group(function () {
                                Route::get('/', [SponsorController::class, 'apiEventSponsors']);
                                Route::get('/{sponsorId}', [SponsorController::class, 'apiEventSponsorDetail']);
                                Route::post('/mark-as-favorite', [SponsorController::class, 'apiEventSponsorMarkAsFavorite']);
                            });

                            Route::prefix('exhibitor')->group(function () {
                                Route::get('/', [ExhibitorController::class, 'apiEventExhibitors']);
                                Route::get('/{exhibitorId}', [ExhibitorController::class, 'apiEventExhibitorDetail']);
                                Route::post('/mark-as-favorite', [ExhibitorController::class, 'apiEventExhibitorMarkAsFavorite']);
                            });

                            Route::prefix('meeting-room-partner')->group(function () {
                                Route::get('/', [MeetingRoomPartnerController::class, 'apiEventMeetingRoomPartners']);
                                Route::get('/{meetingRoomPartnerId}', [MeetingRoomPartnerController::class, 'apiEventMeetingRoomPartnerDetail']);
                                Route::post('/mark-as-favorite', [MeetingRoomPartnerController::class, 'apiEventMeetingRoomPartnerMarkAsFavorite']);
                            });

                            Route::prefix('media-partner')->group(function () {
                                Route::get('/', [MediaPartnerController::class, 'apiEventMediaPartners']);
                                Route::get('/{mediaPartnerId}', [MediaPartnerController::class, 'apiEventMediaPartnerDetail']);
                                Route::post('/mark-as-favorite', [MediaPartnerController::class, 'apiEventMediaPartnerMarkAsFavorite']);
                            });

                            Route::prefix('notification')->group(function () {
                                Route::get('/', [NotificationController::class, 'apiEventNofications']);
                                Route::post('/mark-as-read', [NotificationController::class, 'apiEventNotificationMarkAsRead']);
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