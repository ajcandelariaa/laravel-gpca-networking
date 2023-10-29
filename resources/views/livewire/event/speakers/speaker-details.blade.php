<div>
    <a href="{{ route('admin.event.speakers.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of speakers</span>
    </a>

    <h1 class="text-headingTextColor text-3xl font-bold mt-5">Speaker details</h1>

    <div class="mt-5 relative">
        <div>
            <img src="{{ $speakerData['speakerCoverPhoto'] }}" alt="speaker banner" class="w-full relative">
            <button wire:click="showEditSpeakerAsset('Speaker Cover Photo')"
                class="absolute top-2 right-3 cursor-pointer z-20">
                <i
                    class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
            </button>
        </div>
        <div class="absolute -bottom-32 left-8">
            <div>
                <img src="{{ $speakerData['speakerPFP'] }}"
                    class="w-44 h-44 rounded-full  shadow-2xl bg-gray-200 p-0.5 z-10 relative">
                <button wire:click="showEditSpeakerAsset('Speaker PFP')"
                    class="absolute bottom-2 right-3 cursor-pointer z-20">
                    <i
                        class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="flex justify-between mt-4">
        <div class="ml-56">
            <p class="text-primaryColor font-bold text-2xl">{{ $speakerData['speakerSalutation'] }}
                {{ $speakerData['speakerFirstName'] }} {{ $speakerData['speakerMiddleName'] }}
                {{ $speakerData['speakerLastName'] }}</p>
            <p class="italic">{{ $speakerData['speakerJobTitle'] }} </p>
            <p class="font-semibold">{{ $speakerData['speakerCompanyName'] }} </p>
        </div>

        <div>
            <button wire:click="showEditSpeakerDetails"
                class="bg-yellow-500 hover:bg-yellow-600 duration-200 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                <span>Edit Profile</span>
            </button>
        </div>
    </div>


    <div class="mt-16">
        <hr>
    </div>

    <div class="mt-10">
        <p>Published date time: {{ $speakerData['speakerDateTimeAdded'] }}</p>
        <p>Category: {{ $speakerData['speakerCategoryName'] }}</p>
        <p>Type: {{ $speakerData['speakerTypeName'] }}</p>
        <p>Status: {{ $speakerData['speakerStatus'] ? 'Active' : 'Inactive' }}</p>
        <p>Biography: </p>
        <p>
            @if ($speakerData['speakerBiography'] == '' || $speakerData['speakerBiography'] == null)
                N/A
            @else
                {{ $speakerData['speakerBiography'] }}
            @endif
        </p>
    </div>

    <div class="mt-10">
        <p class="py-4 bg-gray-100 text-center font-semibold text-primaryColor text-xl">Sessions</p>
    </div>

    <div class="mt-10">

    </div>


    @if ($editSpeakerDetailsForm)
        @include('livewire.event.speakers.edit_details')
    @endif

    @if ($editSpeakerAssetForm)
        @include('livewire.event.speakers.edit_asset')
    @endif
</div>
