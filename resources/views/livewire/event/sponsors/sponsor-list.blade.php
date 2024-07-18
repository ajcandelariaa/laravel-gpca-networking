<div>
    <h1 class="text-headingTextColor text-3xl font-bold">Sponsors</h1>

    <div class="flex gap-5 mt-5">
        <button type="button" wire:click.prevent="showAddSponsor" wire:key="showAddSponsor"
            class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-32 h-10">Add
            sponsor</button>

        <button type="button" wire:click.prevent="showAddSponsorType" wire:key="showAddSponsorType"
            class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-36 h-10">Add
            sponsor type</button>
    </div>

    
    @if (count($finalListOfSponsors) == 0)
        <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
            There are no sponsors yet.
        </div>
    @else
        <p class="mt-5">Total sponsors: {{ count($finalListOfSponsors) }}</p>

        <div class="shadow-lg my-5 bg-white rounded-md">
            <div
                class="grid grid-cols-12 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
                <div class="col-span-1">No.</div>
                <div class="col-span-2">Logo</div>
                <div class="col-span-2">Company Name</div>
                <div class="col-span-1">Category</div>
                <div class="col-span-1">Type</div>
                <div class="col-span-1">Website</div>
                <div class="col-span-2">Date time added</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @foreach ($finalListOfSponsors as $index => $finalListOfSponsor)
                <div
                    class="grid grid-cols-12 gap-10 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                    <div class="col-span-1">{{ $index + 1 }}</div>
                    <div class="col-span-2">
                        @if ($finalListOfSponsor['logo'] == null)
                            N/A
                        @else
                            <img src="{{ $finalListOfSponsor['logo'] }}" alt=""
                                class="mx-auto w-14">
                        @endif
                    </div>
                    <div class="col-span-2">{{ $finalListOfSponsor['name'] }}</div>
                    <div class="col-span-1">{{ $finalListOfSponsor['category'] }}</div>
                    <div class="col-span-1">{{ $finalListOfSponsor['type'] }}</div>
                    <div class="col-span-1">{{ $finalListOfSponsor['website'] ?? 'N/A' }}</div>
                    <div wire:click="showEditSponsorDateTime({{ $finalListOfSponsor['id'] }}, {{ $index }})"
                        class="text-blue-700 hover:underline col-span-2 cursor-pointer">
                        {{ $finalListOfSponsor['datetime_added'] }}</div>
                    <div class="col-span-1">
                        @if ($finalListOfSponsor['is_active'])
                            <button
                                wire:click="updateSponsorStatus({{ $index }})"
                                class="text-gray-700 bg-green-300 hover:bg-green-500 hover:text-white py-1 px-2 text-sm rounded-md">Active</button>
                        @else
                            <button
                                wire:click="updateSponsorStatus({{ $index }})"
                                class="text-gray-700 bg-red-300 hover:bg-red-500 hover:text-white py-1 px-2 text-sm rounded-md">Inactive</button>
                        @endif
                    </div>
                    <div class="col-span-1 flex gap-3 items-center justify-center">
                        <a href="{{ route('admin.event.sponsor.view', ['eventCategory' => $event->category, 'eventId' => $event->id, 'sponsorId' => $finalListOfSponsor['id']]) }}"
                            class="cursor-pointer hover:text-gray-600 text-gray-500">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <button wire:click="deleteSponsorConfirmation({{ $index }})"
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
    
    @if ($editSponsorDateTimeForm)
        @include('livewire.common.edit_datetime_form')
    @endif
    
    @if ($addSponsorForm)
        @include('livewire.event.sponsors.add_sponsor')
    @endif
</div>
