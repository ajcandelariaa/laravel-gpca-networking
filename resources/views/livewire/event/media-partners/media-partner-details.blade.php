<div>
    <a href="{{ route('admin.event.media-partners.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of media partners</span>
    </a>

    <h1 class="text-headingTextColor text-3xl font-bold mt-5">Media partner details</h1>

    
    <div class="mt-5 relative">
        <div>
            <img src="{{ $mediaPartnerData['mediaPartnerBanner'] }}" alt="" class="w-full relative">
            <button wire:click="showEditMediaPartnerAsset('Media partner banner')"
                class="absolute top-2 right-3 cursor-pointer z-20">
                <i
                    class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
            </button>
        </div>
        <div class="absolute -bottom-32 left-1/2 -translate-x-1/2">
            <div>
                <img src="{{ $mediaPartnerData['mediaPartnerLogo'] }}"
                    class="w-44 h-44 bg-gray-200 p-0.5 z-10 relative">
                <button wire:click="showEditMediaPartnerAsset('Media partner logo')"
                    class="absolute -top-5 -right-4 cursor-pointer z-20">
                    <i
                        class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="mt-36 text-center">
        <p class="font-semibold text-xl">{{ $mediaPartnerData['mediaPartnerName'] }}</p>
        <p class="font-semibold">{{ $mediaPartnerData['mediaPartnerLink'] }}</p>

        <p class="">
            @if ($mediaPartnerData['mediaPartnerEmailAddress'] == null || $mediaPartnerData['mediaPartnerEmailAddress'] == "")
                N/A
            @else
                {{ $mediaPartnerData['mediaPartnerEmailAddress'] }}
            @endif
        </p>
        
        <p class="">
            @if ($mediaPartnerData['mediaPartnerMobileNumber'] == null || $mediaPartnerData['mediaPartnerMobileNumber'] == "")
                N/A
            @else
                {{ $mediaPartnerData['mediaPartnerMobileNumber'] }}
            @endif
        </p>
    </div>

    <div class="mt-4">
        <hr>
    </div>

    <div class="mt-6">
        <p class="font-semibold">Company profile:</p>
        <p class="mt-2">
            @if ($mediaPartnerData['mediaPartnerProfile'] == null || $mediaPartnerData['mediaPartnerProfile'] == "")
                N/A
            @else
                {{ $mediaPartnerData['mediaPartnerProfile'] }}
            @endif
        </p>
    </div>

    <div class="mt-4">
        <button wire:click="showEditMediaPartnerDetails"
            class="bg-yellow-500 hover:bg-yellow-600 duration-200 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
            <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
            <span>Edit Profile</span>
        </button>
    </div>
    
    @if ($editMediaPartnerDetailsForm)
        @include('livewire.event.media-partners.edit_details')
    @endif
    
    @if ($editMediaPartnerAssetForm)
        @include('livewire.event.media-partners.edit_asset')
    @endif
</div>
