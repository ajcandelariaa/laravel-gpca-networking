<div>
    <h1 class="text-headingTextColor text-3xl font-bold">Session Speakers Type</h1>

    <a href="{{ route('admin.event.session.view', ['eventCategory' => $event->category, 'eventId' => $event->id, 'sessionId' => $session->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm mt-5">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>Session details</span>
    </a>

    <div>
        <form>
            <div class="mt-5 grid grid-cols-4 gap-10">
                <div>
                    <div class="text-primaryColor">
                        Name <span class="text-red-500">*</span>
                    </div>
                    <div class="mt-2">
                        <input placeholder="Name" type="text" wire:model.lazy="name"
                            class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                        @error('name')
                            <div class="text-red-500 text-xs italic mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="text-primaryColor">
                        Description
                    </div>
                    <div class="mt-2">
                        <input placeholder="Description" type="text" wire:model.lazy="description"
                            class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                    </div>
                </div>

                <div>
                    <div class="text-primaryColor">
                        Text Color
                    </div>
                    <div class="mt-2">
                        <input placeholder="Text Color" type="text" wire:model.lazy="text_color"
                            class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                    </div>
                </div>

                <div>
                    <div class="text-primaryColor">
                        Background Color
                    </div>
                    <div class="mt-2">
                        <input placeholder="Background Color" type="text" wire:model.lazy="background_color"
                            class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                    </div>
                </div>
            </div>

            @if ($editState)
                <div class="mt-5 flex gap-5">
                    <button type="button"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-md py-1 px-10"
                        wire:click.prevent="editSpeakerType">Update</button>

                    <button type="button"
                        class="bg-red-500 hover:bg-red-600 text-white rounded-md py-1 px-10"
                        wire:click.prevent="cancelEditSpeakerType">Cancel</button>
                </div>
            @else
                <div class="mt-5">
                    <button type="button"
                        class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-md py-1 px-10"
                        wire:click.prevent="addSpeakerTypeConfirmation">Add</button>
                </div>
            @endif
        </form>
    </div>

    <div class="shadow-lg mt-10 mb-5 bg-white rounded-md">
        <div
            class="grid grid-cols-12 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
            <div class="col-span-1">No.</div>
            <div class="col-span-2">Name</div>
            <div class="col-span-2">Description</div>
            <div class="col-span-2">Text Color</div>
            <div class="col-span-2">Background Color</div>
            <div class="col-span-2">Date time added</div>
            <div class="col-span-1">Action</div>
        </div>

        @if (count($finalListOfSpeakerTypes) == 0)
            <div class="bg-red-400 text-white text-center py-3">
                There are no speaker types yet.
            </div>
        @else
            @foreach ($finalListOfSpeakerTypes as $index => $finalListOfSpeakerType)
                <div
                    class="grid grid-cols-12 gap-10 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                    <div class="col-span-1">{{ $index + 1 }}</div>
                    <div class="col-span-2">{{ $finalListOfSpeakerType['name'] }}</div>
                    <div class="col-span-2">
                        @if (($finalListOfSpeakerType['description'] == '') | ($finalListOfSpeakerType['description'] == null))
                            N/A
                        @else
                            {{ $finalListOfSpeakerType['description'] }}
                        @endif
                    </div>
                    <div class="col-span-2">
                        @if (($finalListOfSpeakerType['text_color'] == '') | ($finalListOfSpeakerType['text_color'] == null))
                            N/A
                        @else
                            {{ $finalListOfSpeakerType['text_color'] }}
                        @endif
                    </div>
                    <div class="col-span-2">
                        @if (($finalListOfSpeakerType['background_color'] == '') | ($finalListOfSpeakerType['background_color'] == null))
                            N/A
                        @else
                            {{ $finalListOfSpeakerType['background_color'] }}
                        @endif
                    </div>
                    <div wire:click="showEditSpeakerTypeDateTime({{ $finalListOfSpeakerType['id'] }}, {{ $index }})"
                        class="text-blue-700 hover:underline col-span-2 cursor-pointer">
                        {{ $finalListOfSpeakerType['datetime_added'] }}</div>
                    <div class="col-span-1">
                        <button wire:click="showEditForm({{ $index }})"
                            class="cursor-pointer hover:text-yellow-600 text-yellow-500">
                            <i class="fa-solid fa-edit"></i> Edit
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>


    @if ($editSpeakerTypeDateTimeForm)
        @include('livewire.common.edit_datetime_form')
    @endif
</div>
