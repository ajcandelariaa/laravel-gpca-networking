<div class="fixed z-10 inset-0 overflow-y-auto">
    <form>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="text-primaryColor italic font-bold text-xl">
                        Edit Event details
                    </div>

                    <div class="mt-5">
                        <div class="space-y-2 col-span-2 grid grid-cols-2 gap-5 items-start">
                            <div class="col-span-1 mt-2">
                                <div class="text-primaryColor">
                                    Event Category <span class="text-red-500">*</span>
                                </div>
                                <div class="mt-2">
                                    <select wire:model.lazy="category"
                                        class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                        <option value="">Please select...</option>
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

                            <div class="col-span-1">
                                <div class="text-primaryColor">
                                    Event Edition <span class="text-red-500">*</span>
                                </div>
                                <div class="mt-2">
                                    <input placeholder="14" type="text" wire:model.lazy="edition"
                                        class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                    @error('edition')
                                        <div class="text-red-500 text-xs italic mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="space-y-2 col-span-2 grid grid-cols-2 gap-5 items-start">
                            <div class="col-span-2 mt-2">
                                <div class="text-primaryColor">
                                    Event Full Name <span class="text-red-500">*</span>
                                </div>
                                <div class="mt-2">
                                    <input placeholder="14th GPCA Supply Chain" type="text"
                                        wire:model.lazy="full_name"
                                        class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                    @error('full_name')
                                        <div class="text-red-500 text-xs italic mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ROW 2 --}}
                        <div class="space-y-2 col-span-2 grid grid-cols-2 gap-5 items-start mt-5">
                            <div class="col-span-1 mt-2">
                                <div class="text-primaryColor">
                                    Event Short Name <span class="text-red-500">*</span>
                                </div>
                                <div class="mt-2">
                                    <input placeholder="GPCA Supply Chain" type="text" wire:model.lazy="short_name"
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
                                    Event Location <span class="text-red-500">*</span>
                                </div>
                                <div class="mt-2">
                                    <input placeholder="Le Méridien Al Khobar, Saudi Arabia" type="text"
                                        wire:model.lazy="location"
                                        class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                                    @error('location')
                                        <div class="text-red-500 text-xs italic mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 col-span-2 grid grid-cols-2 gap-5 items-start mt-5">
                            <div class="col-span-1 mt-2">
                                <div class="text-primaryColor">
                                    Event Full Link <span class="text-red-500">*</span>
                                </div>
                                <div class="mt-2">
                                    <input placeholder="https://www.gpcasupplychain.com/" type="text"
                                        wire:model.lazy="event_full_link"
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
                                    <input placeholder="www.gpcasupplychain.com" type="text"
                                        wire:model.lazy="event_short_link"
                                        class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                                    @error('event_short_link')
                                        <div class="text-red-500 text-xs italic mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 col-span-2 grid grid-cols-2 gap-5 items-start mt-5">
                            <div class="col-span-1 mt-2">
                                <div class="text-primaryColor">
                                    Event Start Date <span class="text-red-500">*</span>
                                </div>
                                <div class="mt-2">
                                    <input type="date" wire:model.lazy="event_start_date" placeholder="Select a date"
                                        class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                                    @error('event_start_date')
                                        <div class="text-red-500 text-xs italic mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-span-1">
                                <div class="text-primaryColor">
                                    Event End Date <span class="text-red-500">*</span>
                                </div>
                                <div class="mt-2">
                                    <input type="date" wire:model.lazy="event_end_date" placeholder="Select a date"
                                        class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                                    @error('event_end_date')
                                        <div class="text-red-500 text-xs italic mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="text-primaryColor">
                                Timezone <span class="text-red-500">*</span>
                            </div>
                            <div class="mt-2">
                                <select wire:model.lazy="timezone"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                    @foreach ($timezoneChoices as $timezoneChoice)
                                        <option value="{{ $timezoneChoice }}">{{ $timezoneChoice }}</option>
                                    @endforeach
                                </select>

                                @error('timezone')
                                    <div class="text-red-500 text-xs italic mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse mt-5">
                        <button type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            wire:click.prevent="editEventDetailsConfirmation">Update</button>
                        <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            wire:click.prevent="resetEditEventDetailsFields">Cancel</button>
                    </div>
                </div>
            </div>
    </form>
</div>
