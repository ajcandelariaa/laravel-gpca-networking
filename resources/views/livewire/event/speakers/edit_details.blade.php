<div class="fixed z-20 inset-0 overflow-y-auto">
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
                        Edit speaker
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-x-5">
                        <div>
                            <div class="text-primaryColor">
                                Category <span class="text-red-500">*</span>
                            </div>
                            <div class="mt-2">
                                <select wire:model.lazy="feature_id"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                    <option value=""></option>
                                    @foreach ($categoryChoices as $categoryChoice)
                                        <option value="{{ $categoryChoice['id'] }}">{{ $categoryChoice['value'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('feature_id')
                                    <div class="text-red-500 text-xs italic mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="text-primaryColor">
                                Type <span class="text-red-500">*</span>
                            </div>
                            <div class="mt-2">
                                <select wire:model.lazy="speaker_type_id"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                    <option value=""></option>
                                    @foreach ($typeChoices as $typeChoice)
                                        <option value="{{ $typeChoice['id'] }}">{{ $typeChoice['value'] }}</option>
                                    @endforeach
                                </select>

                                @error('speaker_type_id')
                                    <div class="text-red-500 text-xs italic mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="mt-7 my-5">

                    <div class="grid grid-cols-2 gap-x-5">
                        <div>
                            <div class="text-primaryColor">
                                Salutation
                            </div>
                            <div class="mt-2">
                                <select wire:model.lazy="salutation"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                    <option value=""></option>
                                    @foreach ($salutations as $salutation)
                                        <option value="{{ $salutation }}">{{ $salutation }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div>
                            <div class="text-primaryColor">
                                First name <span class="text-red-500">*</span>
                            </div>
                            <div class="mt-2">
                                <input placeholder="First name" type="text" wire:model.lazy="first_name"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                                @error('first_name')
                                    <div class="text-red-500 text-xs italic mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="mt-5 grid grid-cols-2 gap-x-5">
                        <div>
                            <div class="text-primaryColor">
                                Middle name
                            </div>
                            <div class="mt-2">
                                <input placeholder="Middle name" type="text" wire:model.lazy="middle_name"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                            </div>
                        </div>


                        <div>
                            <div class="text-primaryColor">
                                Last name <span class="text-red-500">*</span>
                            </div>
                            <div class="mt-2">
                                <input placeholder="Last name" type="text" wire:model.lazy="last_name"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                                @error('last_name')
                                    <div class="text-red-500 text-xs italic mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>



                    <div class="mt-5 grid grid-cols-2 gap-x-5">
                        <div>
                            <div class="text-primaryColor">
                                Company name <span class="text-red-500">*</span>
                            </div>
                            <div class="mt-2">
                                <input placeholder="Company name" type="text" wire:model.lazy="company_name"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                                @error('company_name')
                                    <div class="text-red-500 text-xs italic mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>


                        <div>
                            <div class="text-primaryColor">
                                Job title <span class="text-red-500">*</span>
                            </div>
                            <div class="mt-2">
                                <input placeholder="Job title" type="text" wire:model.lazy="job_title"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                                @error('job_title')
                                    <div class="text-red-500 text-xs italic mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="mt-7 my-5">

                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-1">
                            <div class="text-primaryColor">
                                Country
                            </div>
                            <div>
                                <input placeholder="UAE" type="text" wire:model.lazy="country"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="text-primaryColor">
                                Email address
                            </div>
                            <div>
                                <input placeholder="user@gmail.com" type="email" wire:model.lazy="email_address"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="text-primaryColor">
                                Mobile number
                            </div>
                            <div>
                                <input placeholder="xxxxxxxxxx" type="texxt" wire:model.lazy="mobile_number"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="text-primaryColor">
                                Website
                            </div>
                            <div>
                                <input placeholder="" type="text" wire:model.lazy="website"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="text-primaryColor">
                                Facebook
                            </div>
                            <div>
                                <input placeholder="" type="text" wire:model.lazy="facebook"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="text-primaryColor">
                                Linkedin
                            </div>
                            <div>
                                <input placeholder="" type="text" wire:model.lazy="linkedin"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="text-primaryColor">
                                Twitter
                            </div>
                            <div>
                                <input placeholder="" type="text" wire:model.lazy="twitter"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                            </div>
                        </div>

                        <div class="col-span-1">
                            <div class="text-primaryColor">
                                Instagram
                            </div>
                            <div>
                                <input placeholder="" type="text" wire:model.lazy="instagram"
                                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                            </div>
                        </div>
                    </div>

                    <hr class="mt-7 my-5">

                    <div>
                        <div class="text-primaryColor">
                            Bio
                        </div>
                        <div class="mt-2">
                            <textarea wire:model.lazy="biography_html_text" cols="30" rows="10"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">{{ $biography_html_text }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:click.prevent="editSpeakerDetailsConfirmation">Update</button>
                    <button type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:click.prevent="resetEditSpeakerDetailsFields">Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>
