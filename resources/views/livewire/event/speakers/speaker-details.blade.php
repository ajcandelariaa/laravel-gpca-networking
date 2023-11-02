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
        <p><span class="font-semibold">Published date time:</span> {{ $speakerData['speakerDateTimeAdded'] }}</p>
        <p><span class="font-semibold">Category:</span> {{ $speakerData['speakerCategoryName'] }}</p>
        <p><span class="font-semibold">Type:</span> {{ $speakerData['speakerTypeName'] }}</p>
        <p><span class="font-semibold">Status:</span> {{ $speakerData['speakerStatus'] ? 'Active' : 'Inactive' }}</p>

        <hr class="my-4">

        <div class="flex items-start gap-10">
            <div>
                <p><span class="font-semibold">Facebook:</span>
                    @if ($speakerData['speakerFacebook'] == '' || $speakerData['speakerFacebook'] == null)
                        N/A
                    @else
                        {{ $speakerData['speakerFacebook'] }}
                    @endif
                </p>
                <p><span class="font-semibold">LinkedIn:</span>
                    @if ($speakerData['speakerLinkedin'] == '' || $speakerData['speakerLinkedin'] == null)
                        N/A
                    @else
                        {{ $speakerData['speakerLinkedin'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Twitter:</span>
                    @if ($speakerData['speakerTwitter'] == '' || $speakerData['speakerTwitter'] == null)
                        N/A
                    @else
                        {{ $speakerData['speakerTwitter'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Instagram:</span>
                    @if ($speakerData['speakerInstagram'] == '' || $speakerData['speakerInstagram'] == null)
                        N/A
                    @else
                        {{ $speakerData['speakerInstagram'] }}
                    @endif
                </p>
            </div>

            <div>
                <p><span class="font-semibold">Country:</span>
                    @if ($speakerData['speakerCountry'] == '' || $speakerData['speakerCountry'] == null)
                        N/A
                    @else
                        {{ $speakerData['speakerCountry'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Email address:</span>
                    @if ($speakerData['speakerEmailAddress'] == '' || $speakerData['speakerEmailAddress'] == null)
                        N/A
                    @else
                        {{ $speakerData['speakerEmailAddress'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Mobile Number:</span>
                    @if ($speakerData['speakerMobileNumber'] == '' || $speakerData['speakerMobileNumber'] == null)
                        N/A
                    @else
                        {{ $speakerData['speakerMobileNumber'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Website:</span>
                    @if ($speakerData['speakerWebsite'] == '' || $speakerData['speakerWebsite'] == null)
                        N/A
                    @else
                        {{ $speakerData['speakerWebsite'] }}
                    @endif
                </p>
            </div>
        </div>


        <hr class="my-4">

        <p><span class="font-semibold">Biography: </span></p>
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
