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
                        Add session
                    </div>

                    <div class="mt-5">
                        <div class="text-primaryColor">
                            Category <span class="text-red-500">*</span>
                        </div>
                        <div class="mt-2">
                            <select wire:model.lazy="category"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                <option value=""></option>
                                @foreach ($categoryChoices as $categoryChoice)
                                    <option value="{{ $categoryChoice['id'] }}">{{ $categoryChoice['value'] }}</option>
                                @endforeach
                            </select>

                            @error('category')
                                <div class="text-red-500 text-xs italic mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="text-primaryColor">
                            Session date<span class="text-red-500">*</span>
                        </div>
                        <div class="mt-2">
                            <input type="date" wire:model.lazy="session_date" placeholder="Select a date"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                            @error('session_date')
                                <div class="text-red-500 text-xs italic mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>


                    <div class="mt-5">
                        <div class="text-primaryColor">
                            Session Day <span class="text-red-500">*</span>
                        </div>
                        <div class="mt-2">
                            <input placeholder="Session Day" type="text" wire:model.lazy="session_day"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                            @error('session_day')
                                <div class="text-red-500 text-xs italic mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>


                    <div class="mt-5">
                        <div class="text-primaryColor">
                            Session Type
                        </div>
                        <div class="mt-2">
                            <input placeholder="Session Type" type="text" wire:model.lazy="session_type"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                        </div>
                    </div>


                    <div class="mt-5">
                        <div class="text-primaryColor">
                            Title <span class="text-red-500">*</span>
                        </div>
                        <div class="mt-2">
                            <input placeholder="Title" type="text" wire:model.lazy="title"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                            @error('title')
                                <div class="text-red-500 text-xs italic mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="text-primaryColor">
                            Start time<span class="text-red-500">*</span>
                        </div>
                        <div class="mt-2">
                            <input type="time" wire:model.lazy="start_time" placeholder="Select a time"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                            @error('start_time')
                                <div class="text-red-500 text-xs italic mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="text-primaryColor">
                            End time<span class="text-red-500">*</span>
                        </div>
                        <div class="mt-2">
                            <input type="time" wire:model.lazy="end_time" placeholder="Select a time"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                            @error('end_time')
                                <div class="text-red-500 text-xs italic mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:click.prevent="addSessionConfirmation">Add</button>
                    <button type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:click.prevent="cancelAddSession">Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>
