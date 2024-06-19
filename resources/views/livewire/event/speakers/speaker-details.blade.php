<div>
    <a href="{{ route('admin.event.speakers.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of speakers</span>
    </a>

    <h1 class="text-headingTextColor text-3xl font-bold mt-5">Speaker details</h1>

    <div class="mt-5 relative">
        <div>
            <img src="{{ $speakerData['cover_photo']['url'] }}" alt="speaker banner" class="w-full relative">
            <button wire:click="showEditSpeakerAsset('Speaker Cover Photo')"
                class="absolute top-2 right-3 cursor-pointer z-20">
                <i
                    class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
            </button>
        </div>
        <div class="absolute -bottom-32 left-8">
            <div>
                <img src="{{ $speakerData['pfp']['url'] }}"
                    class="w-44 h-44 rounded-full  shadow-2xl bg-gray-200 p-0.5 z-10 relative">
                <button wire:click="showEditSpeakerAsset('Speaker PFP')"
                    class="absolute bottom-2 right-3 cursor-pointer z-20">
                    <i
                        class="fa-solid fa-pen bg-yellow-500 hover:bg-yellow-600 duration-200 text-gray-100 rounded-full p-3"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="flex justify-between mt-4">
        <div class="ml-56">
            <p class="text-primaryColor font-bold text-2xl">{{ $speakerData['salutation'] }}
                {{ $speakerData['first_name'] }} {{ $speakerData['middle_name'] }}
                {{ $speakerData['last_name'] }}</p>
            <p class="italic">{{ $speakerData['job_title'] }} </p>
            <p class="font-semibold">{{ $speakerData['company_name'] }} </p>
        </div>

        <div>
            <button wire:click="showEditSpeakerDetails"
                class="bg-yellow-500 hover:bg-yellow-600 duration-200 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                <span>Edit Profile</span>
            </button>
        </div>
    </div>


    <div class="mt-16">
        <hr>
    </div>

    <div class="mt-10">
        <p><span class="font-semibold">Published date time:</span> {{ $speakerData['datetime_added'] }}</p>
        <p><span class="font-semibold">Category:</span> {{ $speakerData['categoryName'] }}</p>
        <p><span class="font-semibold">Type:</span> {{ $speakerData['typeName'] }}</p>
        <p><span class="font-semibold">Status:</span> {{ $speakerData['is_active'] ? 'Active' : 'Inactive' }}</p>

        <hr class="my-4">

        <div class="flex items-start gap-10">
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

        <p><span class="font-semibold">Biography: </span></p>
        <p>
            @if ($speakerData['biography_html_text'] == '' || $speakerData['biography_html_text'] == null)
                N/A
            @else
                {{ $speakerData['biography_html_text'] }}
            @endif
        </p>
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
