<div>
    <a href="{{ route('admin.event.exhibitors.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of exhibitors</span>
    </a>

    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-5">
        <div class="flex items-center justify-between">
            <h1 class="text-headingTextColor text-3xl font-bold">Exhibitor details</h1>
            <div>
                <button wire:click="showEditExhibitorDetails"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                    <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                    <span>Edit</span>
                </button>
            </div>
        </div>

        <div class="flex gap-3 items-center mt-3">
            <p class="font-bold text-primaryColor">Name: </p>
            <p>{{ $exhibitorData['name'] }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Stand number: </p>
            <p>{{ $exhibitorData['stand_number'] ?? 'N/A' }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Website: </p>
            <p>{{ $exhibitorData['website'] ?? 'N/A' }}</p>
        </div>

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

        <p><span class="font-semibold">Floorplan Link:</span>
            {{ $exhibitorData['floorplan_link'] }}
        </p>

        <hr class="my-4">

        <p><span class="font-semibold">Published date time:</span>
            {{ $exhibitorData['datetime_added'] }}
        </p>

        <p><span class="font-semibold">Status:</span>
            {{ $exhibitorData['is_active'] ? 'Active' : 'Inactive' }}
        </p>

        <hr class="my-4">

        <p class="font-semibold">Company profile:</p>
        <p>
            @if ($exhibitorData['profile_html_text'] == null || $exhibitorData['profile_html_text'] == '')
                N/A
            @else
                {{ $exhibitorData['profile_html_text'] }}
            @endif
        </p>
    </div>

    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-10">
        <h1 class="text-headingTextColor text-3xl font-bold">Exhibitor assets</h1>

        <div class="grid grid-cols-2 gap-x-14 mt-10 items-start">
            <div class="col-span-1">
                <div class="flex items-center flex-col">
                    <div class="relative">
                        <p class="text-center">Exhibitor Logo</p>
                        <button wire:click="showEditExhibitorAsset('Exhibitor logo')"
                            class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    @if ($exhibitorData['logo']['url'])
                        <img src="{{ $exhibitorData['logo']['url'] }}" class="mt-3 w-80">
                        <button wire:click="deleteExhibitorAsset('Exhibitor logo')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
                            Remove image
                        </button>
                    @else
                        N/A
                    @endif
                </div>
            </div>

            <div class="col-span-1 flex items-center flex-col">
                <div class="relative">
                    <p class="text-center">Exhibitor Banner</p>
                    <button wire:click="showEditExhibitorAsset('Exhibitor banner')"
                        class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                @if ($exhibitorData['banner']['url'])
                    <img src="{{ $exhibitorData['banner']['url'] }}" class="mt-3 w-96">
                    <button wire:click="deleteExhibitorAsset('Exhibitor banner')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
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

    @if ($editExhibitorDetailsForm)
        @include('livewire.event.exhibitors.edit_details')
    @endif

    @if ($editExhibitorAssetForm)
        @include('livewire.event.exhibitors.edit_asset')
    @endif
</div>
