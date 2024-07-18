<div>
    <a href="{{ route('admin.event.sponsors.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of sponsors</span>
    </a>

    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-5">
        <div class="flex items-center justify-between">
            <h1 class="text-headingTextColor text-3xl font-bold">Sponsor details</h1>
            <div>
                <button wire:click="showEditSponsorDetails"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                    <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                    <span>Edit</span>
                </button>
            </div>
        </div>

        <div class="flex gap-3 items-center mt-3">
            <p class="font-bold text-primaryColor">Name: </p>
            <p>{{ $sponsorData['name'] }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Category: </p>
            <p>{{ $sponsorData['categoryName'] ?? 'N/A' }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Type: </p>
            <p>{{ $sponsorData['typeName'] ?? 'N/A' }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Website: </p>
            <p>{{ $sponsorData['website'] ?? 'N/A' }}</p>
        </div>

        <hr class="my-4">

        <div class="flex items-start gap-10">
            <div>
                <p><span class="font-semibold">Facebook:</span>
                    @if ($sponsorData['facebook'] == '' || $sponsorData['facebook'] == null)
                        N/A
                    @else
                        {{ $sponsorData['facebook'] }}
                    @endif
                </p>
                <p><span class="font-semibold">LinkedIn:</span>
                    @if ($sponsorData['linkedin'] == '' || $sponsorData['linkedin'] == null)
                        N/A
                    @else
                        {{ $sponsorData['linkedin'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Twitter:</span>
                    @if ($sponsorData['twitter'] == '' || $sponsorData['twitter'] == null)
                        N/A
                    @else
                        {{ $sponsorData['twitter'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Instagram:</span>
                    @if ($sponsorData['instagram'] == '' || $sponsorData['instagram'] == null)
                        N/A
                    @else
                        {{ $sponsorData['instagram'] }}
                    @endif
                </p>
            </div>
            <div>
                <p><span class="font-semibold">Country:</span>
                    @if ($sponsorData['country'] == '' || $sponsorData['country'] == null)
                        N/A
                    @else
                        {{ $sponsorData['country'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Contact Name:</span>
                    @if ($sponsorData['contact_person_name'] == '' || $sponsorData['contact_person_name'] == null)
                        N/A
                    @else
                        {{ $sponsorData['contact_person_name'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Email address:</span>
                    @if ($sponsorData['email_address'] == '' || $sponsorData['email_address'] == null)
                        N/A
                    @else
                        {{ $sponsorData['email_address'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Mobile Number:</span>
                    @if ($sponsorData['mobile_number'] == '' || $sponsorData['mobile_number'] == null)
                        N/A
                    @else
                        {{ $sponsorData['mobile_number'] }}
                    @endif
                </p>
            </div>
        </div>

        <hr class="my-4">

        <p><span class="font-semibold">Published date time:</span>
            {{ $sponsorData['datetime_added'] }}
        </p>

        <p><span class="font-semibold">Status:</span>
            {{ $sponsorData['is_active'] ? 'Active' : 'Inactive' }}
        </p>

        <hr class="my-4">

        <p class="font-semibold">Company profile:</p>
        <p>
            @if ($sponsorData['profile_html_text'] == null || $sponsorData['profile_html_text'] == '')
                N/A
            @else
                {{ $sponsorData['profile_html_text'] }}
            @endif
        </p>
    </div>

    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-10">
        <h1 class="text-headingTextColor text-3xl font-bold">Sponsor assets</h1>

        <div class="grid grid-cols-2 gap-x-14 mt-10 items-start">
            <div class="col-span-1">
                <div class="flex items-center flex-col">
                    <div class="relative">
                        <p class="text-center">Sponsor Logo</p>
                        <button wire:click="showEditSponsorAsset('Sponsor logo')"
                            class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    @if ($sponsorData['logo']['url'])
                        <img src="{{ $sponsorData['logo']['url'] }}" class="mt-3 w-80">
                        <button wire:click="deleteSponsorAsset('Sponsor logo')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
                            Remove image
                        </button>
                    @else
                        N/A
                    @endif
                </div>
            </div>

            <div class="col-span-1 flex items-center flex-col">
                <div class="relative">
                    <p class="text-center">Sponsor Banner</p>
                    <button wire:click="showEditSponsorAsset('Sponsor banner')"
                        class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                @if ($sponsorData['banner']['url'])
                    <img src="{{ $sponsorData['banner']['url'] }}" class="mt-3 w-96">
                    <button wire:click="deleteSponsorAsset('Sponsor banner')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
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

    @if ($editSponsorDetailsForm)
        @include('livewire.event.sponsors.edit_details')
    @endif
    
    @if ($editSponsorAssetForm)
        @include('livewire.event.sponsors.edit_asset')
    @endif
</div>
