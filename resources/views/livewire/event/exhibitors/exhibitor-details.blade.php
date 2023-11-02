<div>
    <a href="{{ route('admin.event.exhibitors.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of exhibitors</span>
    </a>

    <h1 class="text-headingTextColor text-3xl font-bold mt-5">Exhibitor details</h1>

    
    <div class="mt-5 relative">
        <div>
            <img src="{{ $exhibitorData['exhibitorBanner'] }}" alt="" class="w-full relative">
            <button wire:click="showEditExhibitorAsset('Exhibitor banner')"
                class="absolute top-2 right-3 cursor-pointer z-20">
                <i
                    class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
            </button>
        </div>
        <div class="absolute -bottom-32 left-1/2 -translate-x-1/2">
            <div>
                <img src="{{ $exhibitorData['exhibitorLogo'] }}"
                    class="w-44 h-44 bg-gray-200 p-0.5 z-10 relative">
                <button wire:click="showEditExhibitorAsset('Exhibitor logo')"
                    class="absolute -top-5 -right-4 cursor-pointer z-20">
                    <i
                        class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="mt-36 text-center">
        <p class="font-semibold text-xl">{{ $exhibitorData['exhibitorName'] }}</p>
        <p class="font-semibold">{{ $exhibitorData['exhibitorWebsite'] }}</p>
    </div>

    <div class="mt-4">
        <hr>
    </div>

    
    <div class="mt-6">
        <p><span class="font-semibold">Stand number:</span> {{ $exhibitorData['exhibitorStandNumber'] }}</p>
        
        <hr class="my-4">

        <div class="flex items-start gap-10">
            <div>
                <p><span class="font-semibold">Facebook:</span>
                    @if ($exhibitorData['exhibitorFacebook'] == '' || $exhibitorData['exhibitorFacebook'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['exhibitorFacebook'] }}
                    @endif
                </p>
                <p><span class="font-semibold">LinkedIn:</span>
                    @if ($exhibitorData['exhibitorLinkedin'] == '' || $exhibitorData['exhibitorLinkedin'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['exhibitorLinkedin'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Twitter:</span>
                    @if ($exhibitorData['exhibitorTwitter'] == '' || $exhibitorData['exhibitorTwitter'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['exhibitorTwitter'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Instagram:</span>
                    @if ($exhibitorData['exhibitorInstagram'] == '' || $exhibitorData['exhibitorInstagram'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['exhibitorInstagram'] }}
                    @endif
                </p>
            </div>
            <div>
                <p><span class="font-semibold">Country:</span>
                    @if ($exhibitorData['exhibitorCountry'] == '' || $exhibitorData['exhibitorCountry'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['exhibitorCountry'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Contact Name:</span>
                    @if ($exhibitorData['exhibitorContactPersonName'] == '' || $exhibitorData['exhibitorContactPersonName'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['exhibitorContactPersonName'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Email address:</span>
                    @if ($exhibitorData['exhibitorEmailAddress'] == '' || $exhibitorData['exhibitorEmailAddress'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['exhibitorEmailAddress'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Mobile Number:</span>
                    @if ($exhibitorData['exhibitorMobileNumber'] == '' || $exhibitorData['exhibitorMobileNumber'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['exhibitorMobileNumber'] }}
                    @endif
                </p>
            </div>
        </div>

        <hr class="my-4">

        <p class="font-semibold">Company profile:</p>
        <p>
            @if ($exhibitorData['exhibitorProfile'] == null || $exhibitorData['exhibitorProfile'] == "")
                N/A
            @else
                {{ $exhibitorData['exhibitorProfile'] }}
            @endif
        </p>
    </div>

    <div class="mt-4">
        <button wire:click="showEditExhibitorDetails"
            class="bg-yellow-500 hover:bg-yellow-600 duration-200 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
            <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
            <span>Edit Profile</span>
        </button>
    </div>
    
    @if ($editExhibitorDetailsForm)
        @include('livewire.event.exhibitors.edit_details')
    @endif
    
    @if ($editExhibitorAssetForm)
        @include('livewire.event.exhibitors.edit_asset')
    @endif
</div>
