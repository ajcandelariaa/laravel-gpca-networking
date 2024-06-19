<div>
    <a href="{{ route('admin.event.exhibitors.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of exhibitors</span>
    </a>

    <h1 class="text-headingTextColor text-3xl font-bold mt-5">Exhibitor details</h1>

    
    <div class="mt-5 relative">
        <div>
            <img src="{{ $exhibitorData['banner']['url'] }}" alt="" class="w-full relative">
            <button wire:click="showEditExhibitorAsset('Exhibitor banner')"
                class="absolute top-2 right-3 cursor-pointer z-20">
                <i
                    class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
            </button>
        </div>
        <div class="absolute -bottom-32 left-1/2 -translate-x-1/2">
            <div>
                <img src="{{ $exhibitorData['logo']['url'] }}"
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
        <p class="font-semibold text-xl">{{ $exhibitorData['name'] }}</p>
        <p class="font-semibold">{{ $exhibitorData['website'] ?? 'N/A' }}</p>
    </div>

    <div class="mt-4">
        <hr>
    </div>

    
    <div class="mt-6">
        <p><span class="font-semibold">Stand number:</span> {{ $exhibitorData['stand_number'] ?? 'N/A' }}</p>
        
        <hr class="my-4">

        <div class="flex items-start gap-10">
            <div>
                <p><span class="font-semibold">Facebook:</span>
                    @if ($exhibitorData['facebook'] == '' || $exhibitorData['facebook'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['facebook'] }}
                    @endif
                </p>
                <p><span class="font-semibold">LinkedIn:</span>
                    @if ($exhibitorData['linkedin'] == '' || $exhibitorData['linkedin'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['linkedin'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Twitter:</span>
                    @if ($exhibitorData['twitter'] == '' || $exhibitorData['twitter'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['twitter'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Instagram:</span>
                    @if ($exhibitorData['instagram'] == '' || $exhibitorData['instagram'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['instagram'] }}
                    @endif
                </p>
            </div>
            <div>
                <p><span class="font-semibold">Country:</span>
                    @if ($exhibitorData['country'] == '' || $exhibitorData['country'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['country'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Contact Name:</span>
                    @if ($exhibitorData['contact_person_name'] == '' || $exhibitorData['contact_person_name'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['contact_person_name'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Email address:</span>
                    @if ($exhibitorData['email_address'] == '' || $exhibitorData['email_address'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['email_address'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Mobile Number:</span>
                    @if ($exhibitorData['mobile_number'] == '' || $exhibitorData['mobile_number'] == null)
                        N/A
                    @else
                        {{ $exhibitorData['mobile_number'] }}
                    @endif
                </p>
            </div>
        </div>

        <hr class="my-4">

        <p class="font-semibold">Company profile:</p>
        <p>
            @if ($exhibitorData['profile_html_text'] == null || $exhibitorData['profile_html_text'] == "")
                N/A
            @else
                {{ $exhibitorData['profile_html_text'] }}
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
    
    @if ($chooseImageModal)
        @include('livewire.common.choose_image_modal')
    @endif

    @if ($editExhibitorDetailsForm)
        @include('livewire.event.exhibitors.edit_details')
    @endif
    
    @if ($editExhibitorAssetForm)
        @include('livewire.event.exhibitors.edit_asset')
    @endif
</div>
