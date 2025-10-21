<div>
    <a href="{{ route('admin.event.meeting-room-partners.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of meeting room partners</span>
    </a>


    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-5">
        <div class="flex items-center justify-between">
            <h1 class="text-headingTextColor text-3xl font-bold">Meeting room partner details</h1>
            <div>
                <button wire:click="showEditMeetingRoomPartnerDetails"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                    <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                    <span>Edit</span>
                </button>
            </div>
        </div>

        <div class="flex gap-3 items-center mt-3">
            <p class="font-bold text-primaryColor">Name: </p>
            <p>{{ $meetingRoomPartnerData['name'] }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Location: </p>
            <p>{{ $meetingRoomPartnerData['location'] ?? 'N/A' }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Website: </p>
            <p>{{ $meetingRoomPartnerData['website'] ?? 'N/A' }}</p>
        </div>

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

        <p><span class="font-semibold">Floorplan Link:</span>
            {{ $meetingRoomPartnerData['floorplan_link'] }}
        </p>

        <hr class="my-4">

        <p><span class="font-semibold">Published date time:</span>
            {{ $meetingRoomPartnerData['datetime_added'] }}
        </p>

        <p><span class="font-semibold">Status:</span>
            {{ $meetingRoomPartnerData['is_active'] ? 'Active' : 'Inactive' }}
        </p>

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



    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-10">
        <h1 class="text-headingTextColor text-3xl font-bold">Meeting room partner assets</h1>

        <div class="grid grid-cols-2 gap-x-14 mt-10 items-start">
            <div class="col-span-1">
                <div class="flex items-center flex-col">
                    <div class="relative">
                        <p class="text-center">Meeting room partner Logo</p>
                        <button wire:click="showEditMeetingRoomPartnerAsset('Meeting room partner logo')"
                            class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    @if ($meetingRoomPartnerData['logo']['url'])
                        <img src="{{ $meetingRoomPartnerData['logo']['url'] }}" class="mt-3 w-80">
                        <button wire:click="deleteMeetingRoomPartnerAsset('Meeting room partner logo')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
                            Remove image
                        </button>
                    @else
                        N/A
                    @endif
                </div>
            </div>

            <div class="col-span-1 flex items-center flex-col">
                <div class="relative">
                    <p class="text-center">Meeting room partner Banner</p>
                    <button wire:click="showEditMeetingRoomPartnerAsset('Meeting room partner banner')"
                        class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                @if ($meetingRoomPartnerData['banner']['url'])
                    <img src="{{ $meetingRoomPartnerData['banner']['url'] }}" class="mt-3 w-96">
                    <button wire:click="deleteMeetingRoomPartnerAsset('Meeting room partner banner')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
                        Remove image
                    </button>
                @else
                    N/A
                @endif
            </div>
        </div>
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
