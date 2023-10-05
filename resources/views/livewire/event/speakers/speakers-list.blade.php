<div>
    <h1 class="text-headingTextColor text-3xl font-bold">Speakers Management</h1>

    <div class="flex justify-between mt-5">
        <button type="button" wire:click.prevent="showAddSpeaker" wire:key="showAddSpeaker"
            class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-40 h-10">Add
            speaker</button>
    </div>

    @if (count($finalListOfSpeakers) == 0)
        <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
            There are no speakers yet.
        </div>
    @else
        <p class="mt-5">Total speakers: {{ count($finalListOfSpeakers) }}</p>
        <div class="shadow-lg my-5 bg-white rounded-md">
            <div
                class="grid grid-cols-11 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
                <div class="col-span-1">No.</div>
                <div class="col-span-2">Name</div>
                <div class="col-span-2">Company</div>
                <div class="col-span-2">Job title</div>
                <div class="col-span-2">Date time added</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @foreach ($finalListOfSpeakers as $index => $finalListOfSpeaker)
                <div class="grid grid-cols-11 gap-10 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                    <div class="col-span-1">{{ $index + 1 }}</div>
                    <div class="col-span-2">{{ $finalListOfSpeaker['name'] }}</div>
                    <div class="col-span-2">{{ $finalListOfSpeaker['company_name'] }}</div>
                    <div class="col-span-2">{{ $finalListOfSpeaker['job_title'] }}</div>
                    <div wire:click="showEditSpeakerDateTime({{ $finalListOfSpeaker['id'] }}, {{ $index }})" class="text-blue-700 hover:underline col-span-2 cursor-pointer">{{ $finalListOfSpeaker['datetime_added'] }}</div>
                    <div class="col-span-1">
                        @if ($finalListOfSpeaker['active'])
                            <button wire:click="updateSpeakerStatus({{ $index }}, {{ $finalListOfSpeaker['id'] }}, true)"
                                class="text-gray-700 bg-green-300 hover:bg-green-500 hover:text-white py-1 px-2 text-sm rounded-md">Active</button>
                        @else
                            <button wire:click="updateSpeakerStatus({{ $index }}, {{ $finalListOfSpeaker['id'] }}, false)"
                                class="text-gray-700 bg-red-300 hover:bg-red-500 hover:text-white py-1 px-2 text-sm rounded-md">Inactive</button>
                        @endif
                    </div>
                    <div class="col-span-1">
                        <a href="{{ route('admin.event.speaker.view', ['eventCategory' => $event->category, 'eventId' => $event->id, 'speakerId' => $finalListOfSpeaker['id']]) }}"
                            class="cursor-pointer hover:text-gray-600 text-gray-500">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($editSpeakerDateTimeForm)
        @include('livewire.event.speakers.edit_datetime')
    @endif

    @if ($addSpeakerForm)
        @include('livewire.event.speakers.add_speaker')
    @endif
</div>
