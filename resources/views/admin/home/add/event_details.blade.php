<div class="mt-5 grid grid-cols-2 gap-y-5 gap-x-5 items-start">
    <div class="text-primaryColor font-medium text-xl col-span-2">
        Event details
    </div>

    <div class="space-y-2 col-span-2 grid grid-cols-5 gap-5 items-start">

        <div class="col-span-1 mt-2">
            <div class="text-primaryColor">
                Event Category <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <select required name="category"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                    <option value="" disabled selected hidden>Please select...</option>
                    @foreach ($eventCategories as $eventCategory => $code)
                        <option value="{{ $eventCategory }}" @if (old('category') == $eventCategory) selected @endif>
                            {{ $eventCategory }}</option>
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
                Event Name <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input placeholder="14th GPCA Supply Chain" type="text" name="name" value="{{ old('name') }}"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                @error('name')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-2">
            <div class="text-primaryColor">
                Event Location <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input placeholder="Le Méridien Al Khobar, Saudi Arabia" type="text" name="location"
                    value="{{ old('location') }}"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                @error('location')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>

    {{-- ROW 2 --}}
    <div class="space-y-2 col-span-2">
        <div class="text-primaryColor">
            Event Description <span class="text-red-500">*</span>
        </div>
        <div class="mt-2">
            <textarea name="description" rows="3" placeholder="Type a description here..."
                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">{{ old('description') }}</textarea>

            @error('description')
                <div class="text-red-500 text-xs italic mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="space-y-2 col-span-2 grid grid-cols-2 gap-5 items-start">
        <div class="col-span-1 mt-2">
            <div class="text-primaryColor">
                Event Full Link <span class="text-red-500">*</span>
            </div>
            <div class="mt-2">
                <input placeholder="https://www.gpcasupplychain.com/" type="text" name="event_full_link"
                    value="{{ old('event_full_link') }}"
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
                <input placeholder="www.gpcasupplychain.com" type="text" name="event_short_link" value="{{ old('event_short_link') }}"
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
            <input type="date" name="event_start_date" placeholder="Select a date"
                value="{{ old('event_start_date') }}"
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
            <input type="date" name="event_end_date" placeholder="Select a date" value="{{ old('event_end_date') }}"
                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

            @error('event_end_date')
                <div class="text-red-500 text-xs italic mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
