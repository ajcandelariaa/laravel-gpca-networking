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
        <p class="font-semibold">{{ $sponsorData['sponsorLink'] }}</p>

        <p class="">
            @if ($sponsorData['sponsorEmailAddress'] == null || $sponsorData['sponsorEmailAddress'] == "")
                N/A
            @else
                {{ $sponsorData['sponsorEmailAddress'] }}
            @endif
        </p>
        
        <p class="">
            @if ($sponsorData['sponsorMobileNumber'] == null || $sponsorData['sponsorMobileNumber'] == "")
                N/A
            @else
                {{ $sponsorData['sponsorMobileNumber'] }}
            @endif
        </p>
    </div>

    <div class="mt-4">
        <hr>
    </div>

    <div class="mt-6">
        <p><span class="font-semibold">Category:</span> {{ $sponsorData['sponsorCategoryName'] }} sponsor</p>
        <p><span class="font-semibold">Type:</span> {{ $sponsorData['sponsorTypeName'] }} sponsor</p>
        <p class="font-semibold">Company profile:</p>
        <p class="mt-2">
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
