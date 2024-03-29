<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\MediaPartner;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MediaPartnerController extends Controller
{
    public function eventMediaPartnersView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');

        return view('admin.event.media-partners.media_partners', [
            "pageTitle" => "Media partners",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventMediaPartnerView($eventCategory, $eventId, $mediaPartnerId){
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $mediaPartner = MediaPartner::where('id', $mediaPartnerId)->first();

        if($mediaPartner){
            if($mediaPartner->logo){
                $mediaPartnerLogo = Storage::url($mediaPartner->logo);
                $mediaPartnerLogoDefault = false;
            } else {
                $mediaPartnerLogo = asset('assets/images/logo-placeholder.jpg');
                $mediaPartnerLogoDefault = true;
            }
    
            if($mediaPartner->banner){
                $mediaPartnerBanner = Storage::url($mediaPartner->banner);
                $mediaPartnerBannerDefault = false;
            } else {
                $mediaPartnerBanner = asset('assets/images/banner-placeholder.jpg');
                $mediaPartnerBannerDefault = true;
            }
    
            $mediaPartnerData = [
                "mediaPartnerId" => $mediaPartner->id,
                "mediaPartnerName" => $mediaPartner->name,
                "mediaPartnerProfile" => $mediaPartner->profile,

                "mediaPartnerLogo" => $mediaPartnerLogo,
                "mediaPartnerLogoDefault" => $mediaPartnerLogoDefault,
                "mediaPartnerBanner" => $mediaPartnerBanner,
                "mediaPartnerBannerDefault" => $mediaPartnerBannerDefault,

                "mediaPartnerCountry" => $mediaPartner->country,
                "mediaPartnerContactPersonName" => $mediaPartner->contact_person_name,
                "mediaPartnerEmailAddress" => $mediaPartner->email_address,
                "mediaPartnerMobileNumber" => $mediaPartner->mobile_number,
                "mediaPartnerWebsite" => $mediaPartner->website,
                "mediaPartnerFacebook" => $mediaPartner->facebook,
                "mediaPartnerLinkedin" => $mediaPartner->linkedin,
                "mediaPartnerTwitter" => $mediaPartner->twitter,
                "mediaPartnerInstagram" => $mediaPartner->instagram,

                "mediaPartnerStatus" => $mediaPartner->active,
                "mediaPartnerDateTimeAdded" => Carbon::parse($mediaPartner->datetime_added)->format('M j, Y g:i A'),
            ];
            
            return view('admin.event.media-partners.media_partner', [
                "pageTitle" => "Media Partner",
                "eventName" => $event->name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "mediaPartnerData" => $mediaPartnerData,
            ]);
        } else {
            abort(404, 'Data not found'); 
        }
    }

    public function getListOfMediaPartners() {
        return response()->json();
    }
}
