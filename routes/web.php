<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AttendeesController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExhibitorController;
use App\Http\Controllers\FloorPlanController;
use App\Http\Controllers\MediaPartnerController;
use App\Http\Controllers\MeetingRoomPartnerController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\VenueController;
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
        Route::get('/event/add', [EventController::class, 'addEventView'])->name('admin.add.event.view');
        Route::post('/event/add', [EventController::class, 'addEvent'])->name('admin.add.event.post');

        Route::group(['middleware' => 'check.event.exists'], function () {
            Route::prefix('event/{eventCategory}/{eventId}')->group(function () {
                Route::get('/dashboard', [EventController::class, 'eventDashboardView'])->name('admin.event.dashboard.view');
                Route::get('/details', [EventController::class, 'eventDetailsView'])->name('admin.event.details.view');
                Route::prefix('attendees')->group(function(){
                    Route::get('/', [AttendeesController::class, 'eventAttendeesView'])->name('admin.event.attendees.view');
                    Route::get('/{attendeeId}', [AttendeesController::class, 'eventAttendeeView'])->name('admin.event.attendee.view');
                });
                Route::get('/speakers', [SpeakerController::class, 'eventSpeakersView'])->name('admin.event.speakers.view');
                Route::get('/agenda', [AgendaController::class, 'eventAgendaView'])->name('admin.event.agenda.view');
                Route::get('/sponsors', [SponsorController::class, 'eventSponsorsView'])->name('admin.event.sponsors.view');
                Route::get('/exhibitors', [ExhibitorController::class, 'eventExhibitorsView'])->name('admin.event.exhibitors.view');
                Route::get('/meeting-room-partners', [MeetingRoomPartnerController::class, 'eventMeetingRoomPartnerView'])->name('admin.event.meeting-room-partners.view');
                Route::get('/media-partners', [MediaPartnerController::class, 'eventMediaPartnerView'])->name('admin.event.media-partners.view');
                Route::get('/venue', [VenueController::class, 'eventVenueView'])->name('admin.event.venue.view');
                Route::get('/floor-plan', [FloorPlanController::class, 'eventFloorPlanView'])->name('admin.event.floor-plan.view');
            });
        });
    });

    Route::get('/login', [AdminController::class, 'loginView'])->name('admin.login.view');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');
});