<div>
    <a href="{{ route('admin.event.speakers.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of speakers</span>
    </a>

    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-5">
        <div class="flex items-center justify-between">
            <h1 class="text-headingTextColor text-3xl font-bold">Speaker details</h1>
            <div>
                <button wire:click="showEditSpeakerDetails"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                    <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                    <span>Edit</span>
                </button>
            </div>
        </div>

        <div class="flex gap-3 items-center mt-3">
            <p class="font-bold text-primaryColor">Category: </p>
            <p>{{ $speakerData['categoryName'] ?? 'N/A' }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Type: </p>
            <p>{{ $speakerData['typeName'] ?? 'N/A' }}</p>
        </div>

        <hr class="my-4">
        
        <p><span class="font-semibold">Name:</span>
            {{ $speakerData['salutation'] }} {{ $speakerData['first_name'] }} {{ $speakerData['middle_name'] }} {{ $speakerData['last_name'] }}
        </p>
        
        <p><span class="font-semibold">Job Title:</span>
            {{ $speakerData['job_title'] ?? 'N/A' }}
        </p>

        <p><span class="font-semibold">Company Name:</span>
            {{ $speakerData['company_name'] ?? 'N/A' }}
        </p>

        <hr class="my-4">

        <div class="flex items-start gap-10 mt-5">
            <div>
                <p><span class="font-semibold">Facebook:</span>
                    @if ($speakerData['facebook'] == '' || $speakerData['facebook'] == null)
                        N/A
                    @else
                        {{ $speakerData['facebook'] }}
                    @endif
                </p>
                <p><span class="font-semibold">LinkedIn:</span>
                    @if ($speakerData['linkedin'] == '' || $speakerData['linkedin'] == null)
                        N/A
                    @else
                        {{ $speakerData['linkedin'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Twitter:</span>
                    @if ($speakerData['twitter'] == '' || $speakerData['twitter'] == null)
                        N/A
                    @else
                        {{ $speakerData['twitter'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Instagram:</span>
                    @if ($speakerData['instagram'] == '' || $speakerData['instagram'] == null)
                        N/A
                    @else
                        {{ $speakerData['instagram'] }}
                    @endif
                </p>
            </div>
            <div>
                <p><span class="font-semibold">Country:</span>
                    @if ($speakerData['country'] == '' || $speakerData['country'] == null)
                        N/A
                    @else
                        {{ $speakerData['country'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Email address:</span>
                    @if ($speakerData['email_address'] == '' || $speakerData['email_address'] == null)
                        N/A
                    @else
                        {{ $speakerData['email_address'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Mobile Number:</span>
                    @if ($speakerData['mobile_number'] == '' || $speakerData['mobile_number'] == null)
                        N/A
                    @else
                        {{ $speakerData['mobile_number'] }}
                    @endif
                </p>
                <p><span class="font-semibold">Website:</span>
                    @if ($speakerData['website'] == '' || $speakerData['website'] == null)
                        N/A
                    @else
                        {{ $speakerData['website'] }}
                    @endif
                </p>
            </div>
        </div>

        <hr class="my-4">

        <p><span class="font-semibold">Published date time:</span>
            {{ $speakerData['datetime_added'] }}
        </p>

        <p><span class="font-semibold">Status:</span>
            {{ $speakerData['is_active'] ? 'Active' : 'Inactive' }}
        </p>

        <hr class="my-4">

        <p class="font-semibold">Biography:</p>
        <p>
            @if ($speakerData['biography_html_text'] == null || $speakerData['biography_html_text'] == '')
                N/A
            @else
                {{ $speakerData['biography_html_text'] }}
            @endif
        </p>
    </div>

    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-10">
        <h1 class="text-headingTextColor text-3xl font-bold">Speaker assets</h1>

        <div class="grid grid-cols-2 gap-x-14 mt-10 items-start">
            <div class="col-span-1">
                <div class="flex items-center flex-col">
                    <div class="relative">
                        <p class="text-center">Speaker PFP</p>
                        <button wire:click="showEditSpeakerAsset('Speaker PFP')"
                            class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    @if ($speakerData['pfp']['url'])
                        <img src="{{ $speakerData['pfp']['url'] }}" class="mt-3 w-80">
                        <button wire:click="deleteSpeakerAsset('Speaker PFP')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
                            Remove image
                        </button>
                    @else
                        N/A
                    @endif
                </div>
            </div>

            <div class="col-span-1 flex items-center flex-col">
                <div class="relative">
                    <p class="text-center">Speaker Cover Photo</p>
                    <button wire:click="showEditSpeakerAsset('Speaker Cover Photo')"
                        class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                @if ($speakerData['cover_photo']['url'])
                    <img src="{{ $speakerData['cover_photo']['url'] }}" class="mt-3 w-96">
                    <button wire:click="deleteSpeakerAsset('Speaker Cover Photo')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
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

    @if ($editSpeakerAssetForm)
        @include('livewire.event.speakers.edit_asset')
    @endif

    @if ($editSpeakerDetailsForm)
        @include('livewire.event.speakers.edit_details')
    @endif
</div>
