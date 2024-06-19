<div>
    <a href="{{ route('admin.event.sponsors.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of sponsors</span>
    </a>

    <h1 class="text-headingTextColor text-3xl font-bold mt-5">Sponsor details</h1>

    
    <div class="mt-5 relative">
        <div>
            <img src="{{ $sponsorData['banner']['url'] }}" alt="" class="w-full relative">
            <button wire:click="showEditSponsorAsset('Sponsor banner')"
                class="absolute top-2 right-3 cursor-pointer z-20">
                <i
                    class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
            </button>
        </div>
        <div class="absolute -bottom-32 left-1/2 -translate-x-1/2">
            <div>
                <img src="{{ $sponsorData['logo']['url'] }}"
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
        <p class="font-semibold text-xl">{{ $sponsorData['name'] }}</p>
        <p class="font-semibold">{{ $sponsorData['website'] ?? 'N/A' }}</p>
    </div>

    <div class="mt-4">
        <hr>
    </div>

    <div class="mt-6">
        <p><span class="font-semibold">Category:</span> {{ $sponsorData['categoryName'] }} sponsor</p>
        <p><span class="font-semibold">Type:</span> {{ $sponsorData['typeName'] }} sponsor</p>
        
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

        <p class="font-semibold">Company profile:</p>
        <p>
            @if ($sponsorData['profile_html_text'] == null || $sponsorData['profile_html_text'] == "")
                N/A
            @else
                {{ $sponsorData['profile_html_text'] }}
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
