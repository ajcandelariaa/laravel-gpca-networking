<div>
    <a href="{{ route('admin.event.media-partners.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of media partners</span>
    </a>

    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-5">
        <div class="flex items-center justify-between">
            <h1 class="text-headingTextColor text-3xl font-bold">Media partner details</h1>
            <div>
                <button wire:click="showEditMediaPartnerDetails"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                    <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                    <span>Edit</span>
                </button>
            </div>
        </div>

        <div class="flex gap-3 items-center mt-3">
            <p class="font-bold text-primaryColor">Name: </p>
            <p>{{ $mediaPartnerData['name'] }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Website: </p>
            <p>{{ $mediaPartnerData['website'] ?? 'N/A' }}</p>
        </div>

        <hr class="my-4">

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

        <p><span class="font-semibold">Published date time:</span>
            {{ $mediaPartnerData['datetime_added'] }}
        </p>

        <p><span class="font-semibold">Status:</span>
            {{ $mediaPartnerData['is_active'] ? 'Active' : 'Inactive' }}
        </p>

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

    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-10">
        <h1 class="text-headingTextColor text-3xl font-bold">Media partner assets</h1>

        <div class="grid grid-cols-2 gap-x-14 mt-10 items-start">
            <div class="col-span-1">
                <div class="flex items-center flex-col">
                    <div class="relative">
                        <p class="text-center">Media partner Logo</p>
                        <button wire:click="showEditMediaPartnerAsset('Media partner logo')"
                            class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    @if ($mediaPartnerData['logo']['url'])
                        <img src="{{ $mediaPartnerData['logo']['url'] }}" class="mt-3 w-80">
                        <button wire:click="deleteMediaPartnerAsset('Media partner logo')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
                            Remove image
                        </button>
                    @else
                        N/A
                    @endif
                </div>
            </div>

            <div class="col-span-1 flex items-center flex-col">
                <div class="relative">
                    <p class="text-center">Media partner Banner</p>
                    <button wire:click="showEditMediaPartnerAsset('Media partner banner')"
                        class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                @if ($mediaPartnerData['banner']['url'])
                    <img src="{{ $mediaPartnerData['banner']['url'] }}" class="mt-3 w-96">
                    <button wire:click="deleteMediaPartnerAsset('Media partner banner')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
                        Remove image
                    </button>
                @else
                    N/A
                @endif
            </div>
        </div>
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
