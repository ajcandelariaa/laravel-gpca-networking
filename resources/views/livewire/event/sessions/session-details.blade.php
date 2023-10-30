<div>
    <a href="{{ route('admin.event.sessions.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of sessions</span>
    </a>

    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-5">
        <div class="flex items-center justify-between">
            <h1 class="text-headingTextColor text-3xl font-bold">Session Details</h1>
            <div>
                <button wire:click="showEditSessionDetails"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                    <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                    <span>Edit</span>
                </button>
            </div>
        </div>

        <p class="text-xl font-semibold mt-5">{{ $sessionData['sessionTitle'] }}</p>

        <div class="flex gap-3 items-center mt-2 text-primaryColor">
            <i class="fa-solid fa-list-check"></i>
            <p>{{ $sessionData['sessionCategoryName'] }}</p>
        </div>

        <div class="flex gap-3 items-center mt-2 text-primaryColor">
            <i class="fa-solid fa-calendar-days"></i>
            <p>{{ $sessionData['sessionDateName'] }} ({{ $sessionData['sessionDay'] }})</p>
        </div>

        <div class="flex gap-3 items-center mt-2 text-primaryColor">
            <i class="fa-regular fa-clock"></i>
            <p>{{ $sessionData['sessionStartTime'] }} - {{ $sessionData['sessionEndTime'] }}</p>
        </div>

        <div class="flex gap-3 items-center mt-2 text-primaryColor">
            <i class="fa-solid fa-location-dot"></i>
            <p>
                @if ($sessionData['sessionLocation'] == null || $sessionData['sessionLocation'] == '')
                    N/A
                @else
                    {{ $sessionData['sessionLocation'] }}
                @endif
            </p>
        </div>

        <div class="my-4">
            <hr>
        </div>

        <p> Session type:
            @if ($sessionData['sessionType'] == null || $sessionData['sessionType'] == '')
                N/A
            @else
                {{ $sessionData['sessionType'] }}
            @endif
        </p>

        <p> Session description:
            @if ($sessionData['sessionType'] == null || $sessionData['sessionType'] == '')
                N/A
            @else
                {{ $sessionData['sessionType'] }}
            @endif
        </p>
    </div>


    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-10">
        <div class="flex items-center justify-between">
            <h1 class="text-headingTextColor text-3xl font-bold">Session Speakers</h1>
            <div class="flex gap-5">
                <button type="button" wire:click.prevent="showAddSpeaker" wire:key="showAddSpeaker"
                    class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-32 h-10">Add
                    speaker</button>

                <button type="button" wire:click.prevent="showAddSpeakerType" wire:key="showAddSpeakerType"
                    class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-36 h-10">Add
                    speaker type</button>
            </div>
        </div>

        <div class="mt-5">
            @if (count($sessionData['finalSessionSpeakerGroup']) > 0)
                <div class="flex flex-col gap-5">
                    @foreach ($sessionData['finalSessionSpeakerGroup'] as $speakerGroupIndex => $speakerGroup)
                        <div>
                            <p class="text-xl font-semibold mb-2">
                                @if ($speakerGroup['sessionSpeakerTypeName'] == null)
                                    Others
                                @else
                                    {{ $speakerGroup['sessionSpeakerTypeName'] }}
                                @endif
                            </p>
                            <div class="flex flex-col gap-2">
                                @foreach ($speakerGroup['speakers'] as $speakerIndex => $speaker)
                                <div class="flex items-cpenter gap-3">
                                    <img src="{{ $speaker['speakerPFP'] }}" class="w-20 h-20 object-cover">
                                    <div class="flex flex-col justify-center">
                                        <p class="text-lg">{{ $speaker['speakerName'] }}</p>
                                        <div class="flex flex-col items-start">
                                            <a href="{{ route('admin.event.speaker.view', ['eventCategory' => $event->category, 'eventId' => $event->id, 'speakerId' => $speaker['speakerId']]) }}" target="_blank" class="text-gray-700 hover:underline cursor-pointer text-sm">
                                                <i class="fa-solid fa-eye"></i>
                                                View
                                            </a>
                                            <p wire:click.prevent="removeSessionSpeaker('{{ $speakerGroupIndex }}', '{{ $speakerIndex }}', '{{ $speaker['sessionSpeakerId'] }}')" class="text-red-400 hover:underline cursor-pointer text-sm">
                                                <i class="fa-solid fa-trash"></i>
                                                Remove
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
                    There are no session speakers yet.
                </div>
            @endif
        </div>
    </div>


    @if ($addSessionSpeakerForm)
        @include('livewire.event.sessions.speakers.add_session_speaker')
    @endif

    @if ($editSessionDetailsForm)
        @include('livewire.event.sessions.edit_details')
    @endif


</div>
