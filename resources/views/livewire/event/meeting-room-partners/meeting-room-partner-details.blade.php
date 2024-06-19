<div>
    <a href="{{ route('admin.event.meeting-room-partners.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of meeting room partners</span>
    </a>

    <h1 class="text-headingTextColor text-3xl font-bold mt-5">Meeting room partner details</h1>


    <div class="mt-5 relative">
        <div>
            <img src="{{ $meetingRoomPartnerData['banner']['url'] }}" alt="" class="w-full relative">
            <button wire:click="showEditMeetingRoomPartnerAsset('Meeting room partner banner')"
                class="absolute top-2 right-3 cursor-pointer z-20">
                <i
                    class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
            </button>
        </div>
        <div class="absolute -bottom-32 left-1/2 -translate-x-1/2">
            <div>
                <img src="{{ $meetingRoomPartnerData['logo']['url'] }}"
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
        <p class="font-semibold text-xl">{{ $meetingRoomPartnerData['name'] }}</p>
        <p class="font-semibold">{{ $meetingRoomPartnerData['website'] ?? 'N/A' }}</p>
    </div>

    <div class="mt-4">
        <hr>
    </div>


    <div class="mt-6">
        <p><span class="font-semibold">Location:</span> {{ $meetingRoomPartnerData['location'] ?? 'N/A' }}</p>

        <hr class="my-4">

        <div class="flex items-start gap-10">
            <div>
                <p><span class="font-semibold">Facebook:</span>
                    @if ($meetingRoomPartnerData['facebook'] == '' || $meetingRoomPartnerData['facebook'] == null)
                        N/A
                    @else
                        {{ $meetingRoomPartnerData['facebook'] }}
                    @endif
                </p>
                <p><span class="font-semibold">LinkedIn:</span>
                    @if ($meetingRoomPartnerData['linkedin'] == '' || $meetingRoomPartnerData['linkedin'] == null)
                        N/A
                    @else
                        {{ $meetingRoomPartnerData['linkedin'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Twitter:</span>
                    @if ($meetingRoomPartnerData['twitter'] == '' || $meetingRoomPartnerData['twitter'] == null)
                        N/A
                    @else
                        {{ $meetingRoomPartnerData['twitter'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Instagram:</span>
                    @if ($meetingRoomPartnerData['instagram'] == '' || $meetingRoomPartnerData['instagram'] == null)
                        N/A
                    @else
                        {{ $meetingRoomPartnerData['instagram'] }}
                    @endif
                </p>
            </div>
            <div>
                <p><span class="font-semibold">Country:</span>
                    @if ($meetingRoomPartnerData['country'] == '' || $meetingRoomPartnerData['country'] == null)
                        N/A
                    @else
                        {{ $meetingRoomPartnerData['country'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Contact Name:</span>
                    @if ($meetingRoomPartnerData['contact_person_name'] == '' || $meetingRoomPartnerData['contact_person_name'] == null)
                        N/A
                    @else
                        {{ $meetingRoomPartnerData['contact_person_name'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Email address:</span>
                    @if ($meetingRoomPartnerData['email_address'] == '' || $meetingRoomPartnerData['email_address'] == null)
                        N/A
                    @else
                        {{ $meetingRoomPartnerData['email_address'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Mobile Number:</span>
                    @if ($meetingRoomPartnerData['mobile_number'] == '' || $meetingRoomPartnerData['mobile_number'] == null)
                        N/A
                    @else
                        {{ $meetingRoomPartnerData['mobile_number'] }}
                    @endif
                </p>
            </div>
        </div>

        <hr class="my-4">

        <p class="font-semibold">Company profile:</p>
        <p>
            @if ($meetingRoomPartnerData['profile_html_text'] == null || $meetingRoomPartnerData['profile_html_text'] == '')
                N/A
            @else
                {{ $meetingRoomPartnerData['profile_html_text'] }}
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

    
    @if ($chooseImageModal)
        @include('livewire.common.choose_image_modal')
    @endif

    @if ($editMeetingRoomPartnerDetailsForm)
        @include('livewire.event.meeting-room-partners.edit_details')
    @endif

    @if ($editMeetingRoomPartnerAssetForm)
        @include('livewire.event.meeting-room-partners.edit_asset')
    @endif
</div>
