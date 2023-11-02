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
        <p class="font-semibold">{{ $mediaPartnerData['mediaPartnerWebsite'] }}</p>
    </div>

    <div class="mt-4">
        <hr>
    </div>


    <div class="mt-6">
        <div class="flex items-start gap-10">
            <div>
                <p><span class="font-semibold">Facebook:</span>
                    @if ($mediaPartnerData['mediaPartnerFacebook'] == '' || $mediaPartnerData['mediaPartnerFacebook'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['mediaPartnerFacebook'] }}
                    @endif
                </p>
                <p><span class="font-semibold">LinkedIn:</span>
                    @if ($mediaPartnerData['mediaPartnerLinkedin'] == '' || $mediaPartnerData['mediaPartnerLinkedin'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['mediaPartnerLinkedin'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Twitter:</span>
                    @if ($mediaPartnerData['mediaPartnerTwitter'] == '' || $mediaPartnerData['mediaPartnerTwitter'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['mediaPartnerTwitter'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Instagram:</span>
                    @if ($mediaPartnerData['mediaPartnerInstagram'] == '' || $mediaPartnerData['mediaPartnerInstagram'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['mediaPartnerInstagram'] }}
                    @endif
                </p>
            </div>
            <div>
                <p><span class="font-semibold">Country:</span>
                    @if ($mediaPartnerData['mediaPartnerCountry'] == '' || $mediaPartnerData['mediaPartnerCountry'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['mediaPartnerCountry'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Contact Name:</span>
                    @if ($mediaPartnerData['mediaPartnerContactPersonName'] == '' || $mediaPartnerData['mediaPartnerContactPersonName'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['mediaPartnerContactPersonName'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Email address:</span>
                    @if ($mediaPartnerData['mediaPartnerEmailAddress'] == '' || $mediaPartnerData['mediaPartnerEmailAddress'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['mediaPartnerEmailAddress'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Mobile Number:</span>
                    @if ($mediaPartnerData['mediaPartnerMobileNumber'] == '' || $mediaPartnerData['mediaPartnerMobileNumber'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['mediaPartnerMobileNumber'] }}
                    @endif
                </p>
            </div>
        </div>

        <hr class="my-4">

        <p class="font-semibold">Company profile:</p>
        <p>
            @if ($mediaPartnerData['mediaPartnerProfile'] == null || $mediaPartnerData['mediaPartnerProfile'] == '')
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
