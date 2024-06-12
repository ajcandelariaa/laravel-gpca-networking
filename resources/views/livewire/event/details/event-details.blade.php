<div>
    <div class="flex gap-3">
        @if ($eventData['eventDetails']['is_visible_in_the_app'])
            <button wire:click.prevent="toggleVisibilityInTheApp" class="bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm w-40 h-10">Hide
                in the app</button>
        @else
            <button wire:click.prevent="toggleVisibilityInTheApp" class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-40 h-10">Show
                in the app</button>
        @endif

        
        @if ($eventData['eventDetails']['is_accessible_in_the_app'])
            <button wire:click.prevent="toggleAccessibilityInTheApp" class="bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm w-40 h-10">Disable
                Access</button>
        @else
            <button wire:click.prevent="toggleAccessibilityInTheApp" class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-40 h-10">Enable access</button>
        @endif
    </div>

    {{-- EVENT DETAILS --}}
    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-5">
        <div class="flex items-center justify-between">
            <h1 class="text-headingTextColor text-3xl font-bold">Event Details</h1>
            <div>
                <button wire:click="showEditEventDetails"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                    <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                    <span>Edit</span>
                </button>
            </div>
        </div>

        <div class="flex items-center gap-2 mt-5">
            <p class="font-bold text-2xl">{{ $eventData['eventDetails']['full_name'] }}</p>
            <p>({{ $eventData['eventDetails']['edition'] }} - {{ $eventData['eventDetails']['short_name'] }})</p>
            <p class="text-primaryColor rounded-full border border-primaryColor px-4 font-bold text-sm">
                {{ $eventData['eventDetails']['category'] }}</p>
        </div>

        <div class="flex gap-3 items-center mt-3 text-primaryColor">
            <i class="fa-solid fa-location-dot"></i>
            <p>{{ $eventData['eventDetails']['location'] }}</p>
        </div>

        <div class="flex gap-3 items-center mt-2 text-primaryColor">
            <i class="fa-solid fa-calendar-days"></i>
            <p>{{ $eventData['eventDetails']['finalEventStartDate'] . ' - ' . $eventData['eventDetails']['finalEventEndDate'] }}
            </p>
        </div>

        <div class="flex gap-3 items-center mt-2 text-primaryColor">
            <i class="fa-solid fa-link"></i>
            <p>{{ $eventData['eventDetails']['event_full_link'] }}</p>
        </div>

        <div class="flex gap-3 items-center mt-2 text-primaryColor">
            <i class="fa-solid fa-link"></i>
            <p>{{ $eventData['eventDetails']['event_short_link'] }}</p>
        </div>

        <div class="mt-5">
            {{ $eventData['eventDetails']['description_html_text'] }}
        </div>
    </div>


    {{-- EVENT COLORS --}}
    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-5">
        <div class="flex items-center justify-between">
            <h1 class="text-headingTextColor text-3xl font-bold">Event Colors</h1>
            <div>
                <button wire:click="showEditEventColors"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                    <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                    <span>Edit</span>
                </button>
            </div>
        </div>

        <div class="flex gap-3 items-center mt-3">
            <p class="font-bold text-primaryColor">Primary Background Color: </p>
            <p>{{ $eventData['eventColors']['primary_bg_color'] }}</p>
        </div>

        <div class="flex gap-3 items-center mt-3">
            <p class="font-bold text-primaryColor">Secondary Background Color: </p>
            <p>{{ $eventData['eventColors']['secondary_bg_color'] }}</p>
        </div>

        <div class="flex gap-3 items-center mt-3">
            <p class="font-bold text-primaryColor">Primary Text Color: </p>
            <p>{{ $eventData['eventColors']['primary_text_color'] }}</p>
        </div>

        <div class="flex gap-3 items-center mt-3">
            <p class="font-bold text-primaryColor">Secondary Text Color: </p>
            <p>{{ $eventData['eventColors']['secondary_text_color'] }}</p>
        </div>
    </div>


    {{-- EVENT ASSETS --}}
    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-10">
        <h1 class="text-headingTextColor text-3xl font-bold">Event Assets</h1>

        <div class="grid grid-cols-3 gap-x-14 mt-10 items-start">
            <div class="col-span-2">
                <div class="grid grid-cols-3 gap-x-10">
                    <div class="flex items-center flex-col">
                        <div class="relative">
                            <p class="text-center">Event Logo</p>
                            <button wire:click="showEditEventAsset('Event Logo')"
                                class="absolute top-0 -right-14 cursor-pointer hover:text-yellow-600 text-yellow-500">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                        </div>
                        <img src="{{ $eventData['eventAssets']['event_logo']['url'] }}" class="mt-3">
                    </div>
                    <div class="flex items-center flex-col">
                        <div class="relative">
                            <p class="text-center">Event Logo inverted</p>
                            <button wire:click="showEditEventAsset('Event Logo inverted')"
                                class="absolute top-0 -right-14 cursor-pointer hover:text-yellow-600 text-yellow-500">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                        </div>
                        <img src="{{ $eventData['eventAssets']['event_logo_inverted']['url'] }}" class="mt-3">
                    </div>
                    <div class="flex items-center flex-col">
                        <div class="relative">
                            <p class="text-center">App Sponsor logo</p>
                            <button wire:click="showEditEventAsset('App Sponsor logo')"
                                class="absolute top-0 -right-14 cursor-pointer hover:text-yellow-600 text-yellow-500">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                        </div>
                        <img src="{{ $eventData['eventAssets']['app_sponsor_logo']['url'] }}" class="mt-3">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-10 mt-10">
                    <div class="flex items-center flex-col">
                        <div class="relative">
                            <p class="text-center">Event Banner</p>
                            <button wire:click="showEditEventAsset('Event Banner')"
                                class="absolute top-0 -right-14 cursor-pointer hover:text-yellow-600 text-yellow-500">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                        </div>
                        <img src="{{ $eventData['eventAssets']['event_banner']['url'] }}" class="mt-3">
                    </div>
                    <div class="flex items-center flex-col">
                        <div class="relative">
                            <p class="text-center">App Sponsor banner</p>
                            <button wire:click="showEditEventAsset('App Sponsor banner')"
                                class="absolute top-0 -right-14 cursor-pointer hover:text-yellow-600 text-yellow-500">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                        </div>
                        <img src="{{ $eventData['eventAssets']['app_sponsor_banner']['url'] }}" class="mt-3">
                    </div>
                </div>
            </div>

            <div class="col-span-1 flex items-center flex-col">
                <div class="relative">
                    <p class="text-center">Event splash screen</p>
                    <button wire:click="showEditEventAsset('Event splash screen')"
                        class="absolute top-0 -right-14 cursor-pointer hover:text-yellow-600 text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                </div>
                <img src="{{ $eventData['eventAssets']['event_splash_screen']['url'] }}" class="mt-3 ">
            </div>
        </div>
    </div>

    @if ($chooseImageModal)
        @include('livewire.common.choose_image_modal')
    @endif

    @if ($editEventDetailsForm)
        @include('livewire.event.details.edit_details')
    @endif

    @if ($editEventColorsForm)
        @include('livewire.event.details.edit_colors')
    @endif

    @if ($editEventAssetForm)
        @include('livewire.event.details.edit_asset')
    @endif
</div>
