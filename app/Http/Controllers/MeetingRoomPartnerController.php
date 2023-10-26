<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\MeetingRoomPartner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MeetingRoomPartnerController extends Controller
{
    public function eventMeetingRoomPartnersView($eventCategory, $eventId){
        return view('admin.event.meeting-room-partners.meeting_room_partners', [
            "pageTitle" => "Meeting room partners",
            "eventName" => "14th GPCA Supply Chain Conference",
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventMeetingRoomPartnerView($eventCategory, $eventId, $meetingRoomPartnerId){
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $meetingRoomPartner = MeetingRoomPartner::where('id', $meetingRoomPartnerId)->first();

        if($meetingRoomPartner){
            if($meetingRoomPartner->logo){
                $meetingRoomPartnerLogo = Storage::url($meetingRoomPartner->logo);
                $meetingRoomPartnerLogoDefault = false;
            } else {
                $meetingRoomPartnerLogo = asset('assets/images/logo-placeholder.jpg');
                $meetingRoomPartnerLogoDefault = true;
            }
    
            if($meetingRoomPartner->banner){
                $meetingRoomPartnerBanner = Storage::url($meetingRoomPartner->banner);
                $meetingRoomPartnerBannerDefault = false;
            } else {
                $meetingRoomPartnerBanner = asset('assets/images/banner-placeholder.jpg');
                $meetingRoomPartnerBannerDefault = true;
            }
    
            $meetingRoomPartnerData = [
                "meetingRoomPartnerId" => $meetingRoomPartner->id,
                "meetingRoomPartnerName" => $meetingRoomPartner->name,
                "meetingRoomPartnerLocation" => $meetingRoomPartner->location,
                "meetingRoomPartnerEmailAddress" => $meetingRoomPartner->email_address,
                "meetingRoomPartnerMobileNumber" => $meetingRoomPartner->mobile_number,
                "meetingRoomPartnerLink" => $meetingRoomPartner->link,
                "meetingRoomPartnerProfile" => $meetingRoomPartner->profile,
                "meetingRoomPartnerLogo" => $meetingRoomPartnerLogo,
                "meetingRoomPartnerLogoDefault" => $meetingRoomPartnerLogoDefault,
                "meetingRoomPartnerBanner" => $meetingRoomPartnerBanner,
                "meetingRoomPartnerBannerDefault" => $meetingRoomPartnerBannerDefault,
                "meetingRoomPartnerStatus" => $meetingRoomPartner->active,
                "meetingRoomPartnerDateTimeAdded" => Carbon::parse($meetingRoomPartner->datetime_added)->format('M j, Y g:i A'),
            ];
            
            return view('admin.event.meeting-room-partners.meeting_room_partner', [
                "pageTitle" => "Meeting Room Partner",
                "eventName" => $event->name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "meetingRoomPartnerData" => $meetingRoomPartnerData,
            ]);
        } else {
            abort(404, 'Data not found'); 
        }
    }
}
