<div>
    <a href="{{ route('admin.event.sponsors.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of sponsors</span>
    </a>

    <h1 class="text-headingTextColor text-3xl font-bold mt-5">Sponsor details</h1>

    
    <div class="mt-5 relative">
        <div>
            <img src="{{ $sponsorData['sponsorBanner'] }}" alt="" class="w-full relative">
            <button wire:click="showEditSponsorAsset('Sponsor banner')"
                class="absolute top-2 right-3 cursor-pointer z-20">
                <i
                    class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
            </button>
        </div>
        <div class="absolute -bottom-32 left-1/2 -translate-x-1/2">
            <div>
                <img src="{{ $sponsorData['sponsorLogo'] }}"
                    class="w-44 h-44 bg-gray-200 p-0.5 z-10 relative">
                <button wire:click="showEditSponsorAsset('Sponsor logo')"
                    class="absolute -top-5 -right-4 cursor-pointer z-20">
                    <i
                        class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="mt-36 text-center">
        <p class="font-semibold text-xl">{{ $sponsorData['sponsorName'] }}</p>
        <p class="font-semibold">{{ $sponsorData['sponsorWebsite'] }}</p>
    </div>

    <div class="mt-4">
        <hr>
    </div>

    <div class="mt-6">
        <p><span class="font-semibold">Category:</span> {{ $sponsorData['sponsorCategoryName'] }} sponsor</p>
        <p><span class="font-semibold">Type:</span> {{ $sponsorData['sponsorTypeName'] }} sponsor</p>
        
        <hr class="my-4">

        <div class="flex items-start gap-10">
            <div>
                <p><span class="font-semibold">Facebook:</span>
                    @if ($sponsorData['sponsorFacebook'] == '' || $sponsorData['sponsorFacebook'] == null)
                        N/A
                    @else
                        {{ $sponsorData['sponsorFacebook'] }}
                    @endif
                </p>
                <p><span class="font-semibold">LinkedIn:</span>
                    @if ($sponsorData['sponsorLinkedin'] == '' || $sponsorData['sponsorLinkedin'] == null)
                        N/A
                    @else
                        {{ $sponsorData['sponsorLinkedin'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Twitter:</span>
                    @if ($sponsorData['sponsorTwitter'] == '' || $sponsorData['sponsorTwitter'] == null)
                        N/A
                    @else
                        {{ $sponsorData['sponsorTwitter'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Instagram:</span>
                    @if ($sponsorData['sponsorInstagram'] == '' || $sponsorData['sponsorInstagram'] == null)
                        N/A
                    @else
                        {{ $sponsorData['sponsorInstagram'] }}
                    @endif
                </p>
            </div>
            <div>
                <p><span class="font-semibold">Country:</span>
                    @if ($sponsorData['sponsorCountry'] == '' || $sponsorData['sponsorCountry'] == null)
                        N/A
                    @else
                        {{ $sponsorData['sponsorCountry'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Contact Name:</span>
                    @if ($sponsorData['sponsorContactPersonName'] == '' || $sponsorData['sponsorContactPersonName'] == null)
                        N/A
                    @else
                        {{ $sponsorData['sponsorContactPersonName'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Email address:</span>
                    @if ($sponsorData['sponsorEmailAddress'] == '' || $sponsorData['sponsorEmailAddress'] == null)
                        N/A
                    @else
                        {{ $sponsorData['sponsorEmailAddress'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Mobile Number:</span>
                    @if ($sponsorData['sponsorMobileNumber'] == '' || $sponsorData['sponsorMobileNumber'] == null)
                        N/A
                    @else
                        {{ $sponsorData['sponsorMobileNumber'] }}
                    @endif
                </p>
            </div>
        </div>

        <hr class="my-4">

        <p class="font-semibold">Company profile:</p>
        <p>
            @if ($sponsorData['sponsorProfile'] == null || $sponsorData['sponsorProfile'] == "")
                N/A
            @else
                {{ $sponsorData['sponsorProfile'] }}
            @endif
        </p>
    </div>

    <div class="mt-4">
        <button wire:click="showEditSponsorDetails"
            class="bg-yellow-500 hover:bg-yellow-600 duration-200 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
            <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
            <span>Edit Profile</span>
        </button>
    </div>
    
    @if ($editSponsorDetailsForm)
        @include('livewire.event.sponsors.edit_details')
    @endif
    
    @if ($editSponsorAssetForm)
        @include('livewire.event.sponsors.edit_asset')
    @endif
</div>
