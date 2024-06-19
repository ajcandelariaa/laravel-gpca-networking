<div>
    <a href="{{ route('admin.event.media-partners.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of media partners</span>
    </a>

    <h1 class="text-headingTextColor text-3xl font-bold mt-5">Media partner details</h1>

    
    <div class="mt-5 relative">
        <div>
            <img src="{{ $mediaPartnerData['banner']['url'] }}" alt="" class="w-full relative">
            <button wire:click="showEditMediaPartnerAsset('Media partner banner')"
                class="absolute top-2 right-3 cursor-pointer z-20">
                <i
                    class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
            </button>
        </div>
        <div class="absolute -bottom-32 left-1/2 -translate-x-1/2">
            <div>
                <img src="{{ $mediaPartnerData['logo']['url'] }}"
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
        <p class="font-semibold text-xl">{{ $mediaPartnerData['name'] }}</p>
        <p class="font-semibold">{{ $mediaPartnerData['website'] }}</p>
    </div>

    <div class="mt-4">
        <hr>
    </div>


    <div class="mt-6">
        <div class="flex items-start gap-10">
            <div>
                <p><span class="font-semibold">Facebook:</span>
                    @if ($mediaPartnerData['facebook'] == '' || $mediaPartnerData['facebook'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['facebook'] }}
                    @endif
                </p>
                <p><span class="font-semibold">LinkedIn:</span>
                    @if ($mediaPartnerData['linkedin'] == '' || $mediaPartnerData['linkedin'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['linkedin'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Twitter:</span>
                    @if ($mediaPartnerData['twitter'] == '' || $mediaPartnerData['twitter'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['twitter'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Instagram:</span>
                    @if ($mediaPartnerData['instagram'] == '' || $mediaPartnerData['instagram'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['instagram'] }}
                    @endif
                </p>
            </div>
            <div>
                <p><span class="font-semibold">Country:</span>
                    @if ($mediaPartnerData['country'] == '' || $mediaPartnerData['country'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['country'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Contact Name:</span>
                    @if ($mediaPartnerData['contact_person_name'] == '' || $mediaPartnerData['contact_person_name'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['contact_person_name'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Email address:</span>
                    @if ($mediaPartnerData['email_address'] == '' || $mediaPartnerData['email_address'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['email_address'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Mobile Number:</span>
                    @if ($mediaPartnerData['mobile_number'] == '' || $mediaPartnerData['mobile_number'] == null)
                        N/A
                    @else
                        {{ $mediaPartnerData['mobile_number'] }}
                    @endif
                </p>
            </div>
        </div>

        <hr class="my-4">

        <p class="font-semibold">Company profile:</p>
        <p>
            @if ($mediaPartnerData['profile_html_text'] == null || $mediaPartnerData['profile_html_text'] == '')
                N/A
            @else
                {{ $mediaPartnerData['profile_html_text'] }}
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

    
    @if ($chooseImageModal)
        @include('livewire.common.choose_image_modal')
    @endif
    
    @if ($editMediaPartnerDetailsForm)
        @include('livewire.event.media-partners.edit_details')
    @endif
    
    @if ($editMediaPartnerAssetForm)
        @include('livewire.event.media-partners.edit_asset')
    @endif
</div>
