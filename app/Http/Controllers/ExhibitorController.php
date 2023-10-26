<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Exhibitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExhibitorController extends Controller
{
    public function eventExhibitorsView($eventCategory, $eventId)
    {
        return view('admin.event.exhibitors.exhibitors', [
            "pageTitle" => "Exhibitors",
            "eventName" => "14th GPCA Supply Chain Conference",
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventExhibitorView($eventCategory, $eventId, $exhibitorId)
    {
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $exhibitor = Exhibitor::where('id', $exhibitorId)->first();

        if ($exhibitor) {
            if ($exhibitor->logo) {
                $exhibitorLogo = Storage::url($exhibitor->logo);
                $exhibitorLogoDefault = false;
            } else {
                $exhibitorLogo = asset('assets/images/logo-placeholder.jpg');
                $exhibitorLogoDefault = true;
            }

            if ($exhibitor->banner) {
                $exhibitorBanner = Storage::url($exhibitor->banner);
                $exhibitorBannerDefault = false;
            } else {
                $exhibitorBanner = asset('assets/images/banner-placeholder.jpg');
                $exhibitorBannerDefault = true;
            }

            $exhibitorData = [
                "exhibitorId" => $exhibitor->id,
                "exhibitorName" => $exhibitor->name,
                "exhibitorStandNumber" => $exhibitor->stand_number,
                "exhibitorEmailAddress" => $exhibitor->email_address,
                "exhibitorMobileNumber" => $exhibitor->mobile_number,
                "exhibitorLink" => $exhibitor->link,
                "exhibitorProfile" => $exhibitor->profile,
                "exhibitorLogo" => $exhibitorLogo,
                "exhibitorLogoDefault" => $exhibitorLogoDefault,
                "exhibitorBanner" => $exhibitorBanner,
                "exhibitorBannerDefault" => $exhibitorBannerDefault,
                "exhibitorStatus" => $exhibitor->active,
                "exhibitorDateTimeAdded" => Carbon::parse($exhibitor->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.exhibitors.exhibitor', [
                "pageTitle" => "Exhibitor",
                "eventName" => $event->name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "exhibitorData" => $exhibitorData,
            ]);
        } else {
            abort(404, 'Data not found');
        }
    }
}
