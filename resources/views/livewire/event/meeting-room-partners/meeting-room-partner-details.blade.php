<div>
    <a href="{{ route('admin.event.meeting-room-partners.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of meeting room partners</span>
    </a>

    <h1 class="text-headingTextColor text-3xl font-bold mt-5">Meeting room partner details</h1>

    
    <div class="mt-5 relative">
        <div>
            <img src="{{ $meetingRoomPartnerData['meetingRoomPartnerBanner'] }}" alt="" class="w-full relative">
            <button wire:click="showEditMeetingRoomPartnerAsset('Meeting room partner banner')"
                class="absolute top-2 right-3 cursor-pointer z-20">
                <i
                    class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
            </button>
        </div>
        <div class="absolute -bottom-32 left-1/2 -translate-x-1/2">
            <div>
                <img src="{{ $meetingRoomPartnerData['meetingRoomPartnerLogo'] }}"
                    class="w-44 h-44 bg-gray-200 p-0.5 z-10 relative">
                <button wire:click="showEditMeetingRoomPartnerAsset('Meeting room partner logo')"
                    class="absolute -top-5 -right-4 cursor-pointer z-20">
                    <i
                        class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="mt-36 text-center">
        <p class="font-semibold text-xl">{{ $meetingRoomPartnerData['meetingRoomPartnerName'] }}</p>
        <p class="font-semibold">{{ $meetingRoomPartnerData['meetingRoomPartnerLink'] }}</p>
        <p>{{ $meetingRoomPartnerData['meetingRoomPartnerLocation'] }}</p>

        <p class="">
            @if ($meetingRoomPartnerData['meetingRoomPartnerEmailAddress'] == null || $meetingRoomPartnerData['meetingRoomPartnerEmailAddress'] == "")
                N/A
            @else
                {{ $meetingRoomPartnerData['meetingRoomPartnerEmailAddress'] }}
            @endif
        </p>
        
        <p class="">
            @if ($meetingRoomPartnerData['meetingRoomPartnerMobileNumber'] == null || $meetingRoomPartnerData['meetingRoomPartnerMobileNumber'] == "")
                N/A
            @else
                {{ $meetingRoomPartnerData['meetingRoomPartnerMobileNumber'] }}
            @endif
        </p>
    </div>

    <div class="mt-4">
        <hr>
    </div>

    <div class="mt-6">
        <p class="font-semibold">Company profile:</p>
        <p class="mt-2">
            @if ($meetingRoomPartnerData['meetingRoomPartnerProfile'] == null || $meetingRoomPartnerData['meetingRoomPartnerProfile'] == "")
                N/A
            @else
                {{ $meetingRoomPartnerData['meetingRoomPartnerProfile'] }}
            @endif
        </p>
    </div>

    <div class="mt-4">
        <button wire:click="showEditMeetingRoomPartnerDetails"
            class="bg-yellow-500 hover:bg-yellow-600 duration-200 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
            <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
            <span>Edit Profile</span>
        </button>
    </div>
    
    @if ($editMeetingRoomPartnerDetailsForm)
        @include('livewire.event.meeting-room-partners.edit_details')
    @endif
    
    @if ($editMeetingRoomPartnerAssetForm)
        @include('livewire.event.meeting-room-partners.edit_asset')
    @endif
</div>
