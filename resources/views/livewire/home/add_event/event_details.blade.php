<div class="mt-5 grid grid-cols-2 gap-y-5 gap-x-5 items-start">
    <div class="text-primaryColor font-medium text-xl col-span-2">
        Event details
    </div>

    <div class="space-y-2 col-span-2 grid grid-cols-6 gap-5 items-start">
        <div class="col-span-1 mt-2">
            <div class="text-primaryColor">
                Event Category <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <select wire:model.lazy="category"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                    <option value=""></option>
                    @foreach ($eventCategories as $eventCategory => $code)
                        <option value="{{ $eventCategory }}">{{ $eventCategory }}</option>
                    @endforeach
                </select>

                @error('category')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-2">
            <div class="text-primaryColor">
                Event Full Name <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input wire:model.lazy="full_name" placeholder="14th GPCA Supply Chain" type="text"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                @error('full_name')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-2">
            <div class="text-primaryColor">
                Event Short Name <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input wire:model.lazy="short_name" placeholder="GPCA Supply Chain" type="text"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                @error('short_name')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-1">
            <div class="text-primaryColor">
                Event Edition <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input wire:model.lazy="edition" placeholder="14" type="text"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                @error('edition')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>

    <div class="space-y-2 col-span-2 grid grid-cols-3 gap-5 items-start">
        <div class="col-span-1 mt-2">
            <div class="text-primaryColor">
                Event Location <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input wire:model.lazy="location" placeholder="Le Méridien Al Khobar, Saudi Arabia" type="text"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                @error('location')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-1">
            <div class="text-primaryColor">
                Event Full Link <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input wire:model.lazy="event_full_link" placeholder="https://www.gpcasupplychain.com/" type="text"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                @error('event_full_link')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-1">
            <div class="text-primaryColor">
                Event Short Link <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input wire:model.lazy="event_short_link" placeholder="www.gpcasupplychain.com" type="text"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                @error('event_short_link')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>

    <div class="space-y-2">
        <div class="text-primaryColor">
            Event Start Date <span class="text-red-500">*</span>
        </div>
        <div class="mt-2">
            <input wire:model.lazy="event_start_date" type="date" placeholder="Select a date"
                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

            @error('event_start_date')
                <div class="text-red-500 text-xs italic mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="space-y-2">
        <div class="text-primaryColor">
            Event End Date <span class="text-red-500">*</span>
        </div>
        <div class="mt-2">
            <input wire:model.lazy="event_end_date" type="date" placeholder="Select a date"
                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

            @error('event_end_date')
                <div class="text-red-500 text-xs italic mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>


    <div class="space-y-2 col-span-2 grid grid-cols-4 gap-5 items-start">
        <div class="col-span-1 mt-2">
            <div class="text-primaryColor">
                Primary Background Color <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input wire:model.lazy="primary_bg_color" placeholder="#000000" type="text"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                @error('primary_bg_color')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-1">
            <div class="text-primaryColor">
                Secondary Background Color <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input wire:model.lazy="secondary_bg_color" placeholder="#000000" type="text"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                @error('secondary_bg_color')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-1">
            <div class="text-primaryColor">
                Primary Text Color <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input wire:model.lazy="primary_text_color" placeholder="#000000" type="text"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                @error('primary_text_color')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-1">
            <div class="text-primaryColor">
                Secondary Text Color <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input wire:model.lazy="secondary_text_color" placeholder="#000000" type="text"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                @error('secondary_text_color')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>

    <div class="space-y-2 col-span-2">
        <div class="col-span-2">
            <div class="text-primaryColor">
                Event Logo <span class="text-red-500">*</span>
            </div>

            <div class="mt-2">
                <div class="flex flex-nowrap gap-5 items-center">
                    <input wire:model.lazy="event_logo_placeholder_text" placeholder="test.jpg" type="text"
                        class="bg-registrationInputFieldsBGColor w-1/2 py-1 px-3 outline-primaryColor rounded-md border border-gray-200"
                        disabled>
                    <button type="button" wire:click.prevent="chooseEventLogo"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-5 rounded items-center text-sm cursor-pointer">Choose
                        logo</button>
                </div>
                @error('event_logo_placeholder_text')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>

    @if ($chooseImageModal)
        @include('livewire.common.choose_image_modal')
    @endif
</div>
