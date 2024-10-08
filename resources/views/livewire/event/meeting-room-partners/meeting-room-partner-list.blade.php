<div>
    <h1 class="text-headingTextColor text-3xl font-bold">Meeting room partners</h1>

    <div class="flex justify-between mt-5">
        <button type="button" wire:click.prevent="showAddMeetingRoomPartner" wire:key="showAddMeetingRoomPartner"
            class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-52 h-10">Add
            meeting room partner</button>
    </div>

    @if (count($finalListOfMeetingRoomPartners) == 0)
        <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
            There are no meeting room partners yet.
        </div>
    @else
        <p class="mt-5">Total meeting room partners: {{ count($finalListOfMeetingRoomPartners) }}</p>

        <div class="shadow-lg my-5 bg-white rounded-md">
            <div
                class="grid grid-cols-11 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
                <div class="col-span-1">No.</div>
                <div class="col-span-2">Logo</div>
                <div class="col-span-2">Company Name</div>
                <div class="col-span-1">Website</div>
                <div class="col-span-1">Location</div>
                <div class="col-span-2">Date time added</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @foreach ($finalListOfMeetingRoomPartners as $index => $finalListOfMeetingRoomPartner)
                <div
                    class="grid grid-cols-11 gap-10 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                    <div class="col-span-1">{{ $index + 1 }}</div>
                    <div class="col-span-2">
                        @if ($finalListOfMeetingRoomPartner['logo'] == null)
                            N/A
                        @else
                            <img src="{{ $finalListOfMeetingRoomPartner['logo'] }}" alt=""
                                class="mx-auto w-14">
                        @endif
                    </div>
                    <div class="col-span-2">{{ $finalListOfMeetingRoomPartner['name'] }}</div>
                    <div class="col-span-1">{{ $finalListOfMeetingRoomPartner['website'] ?? 'N/A' }}</div>
                    <div class="col-span-1">{{ $finalListOfMeetingRoomPartner['location'] ?? 'N/A' }}</div>
                    <div wire:click="showEditMeetingRoomPartnerDateTime({{ $finalListOfMeetingRoomPartner['id'] }}, {{ $index }})"
                        class="text-blue-700 hover:underline col-span-2 cursor-pointer">
                        {{ $finalListOfMeetingRoomPartner['datetime_added'] }}</div>
                    <div class="col-span-1">
                        @if ($finalListOfMeetingRoomPartner['is_active'])
                            <button
                                wire:click="updateMeetingRoomPartnerStatus({{ $index }})"
                                class="text-gray-700 bg-green-300 hover:bg-green-500 hover:text-white py-1 px-2 text-sm rounded-md">Active</button>
                        @else
                            <button
                                wire:click="updateMeetingRoomPartnerStatus({{ $index }})"
                                class="text-gray-700 bg-red-300 hover:bg-red-500 hover:text-white py-1 px-2 text-sm rounded-md">Inactive</button>
                        @endif
                    </div>
                    <div class="col-span-1 flex gap-3 items-center justify-center">
                        <a href="{{ route('admin.event.meeting-room-partner.view', ['eventCategory' => $event->category, 'eventId' => $event->id, 'meetingRoomPartnerId' => $finalListOfMeetingRoomPartner['id']]) }}"
                            class="cursor-pointer hover:text-gray-600 text-gray-500">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <button wire:click="deleteMeetingRoomPartnerConfirmation({{ $index }})"
                            class="cursor-pointer hover:text-red-600 text-red-500 text-sm ">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($chooseImageModal)
        @include('livewire.common.choose_image_modal')
    @endif

    @if ($editMeetingRoomPartnerDateTimeForm)
        @include('livewire.common.edit_datetime_form')
    @endif

    @if ($addMeetingRoomPartnerForm)
        @include('livewire.event.meeting-room-partners.add_mrp')
    @endif
</div>
