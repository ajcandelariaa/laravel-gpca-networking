<div class="mt-5">
    <div class="grid grid-cols-11 gap-x-5">
        <div class="col-span-2">
            <div class="text-registrationPrimaryColor">
                Salutation
            </div>
            <div>
                <select wire:model.lazy="salutation"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                    <option value=""></option>
                    @foreach ($salutations as $salutation)
                        <option value="{{ $salutation }}">{{ $salutation }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-span-3">
            <div class="text-registrationPrimaryColor">
                First name <span class="text-red-500">*</span>
            </div>
            <div>
                <input placeholder="First name" type="text" wire:model.lazy="first_name"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                @error('first_name')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-3">
            <div class="text-registrationPrimaryColor">
                Middle name
            </div>
            <div>
                <input placeholder="Middle name" type="text" wire:model.lazy="middle_name"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
            </div>
        </div>

        <div class="col-span-3">
            <div class="text-registrationPrimaryColor">
                Last name <span class="text-red-500">*</span>
            </div>
            <div>
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


    <div class="mt-5">
        <div class="grid grid-cols-4 gap-x-5">
            <div class="col-span-1">
                <div class="text-registrationPrimaryColor">
                    Email address <span class="text-red-500">*</span>
                </div>
                <div>
                    <input placeholder="Email address" type="text" wire:model.lazy="email_address"
                        class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                    @error('email_address')
                        <div class="text-red-500 text-xs italic mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    @if ($emailExistingError != null)
                        <div class="text-red-500 text-xs italic mt-1">
                            {{ $emailExistingError }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-span-1">
                <div class="text-registrationPrimaryColor">
                    Mobile number <span class="text-red-500">*</span>
                </div>
                <div>
                    <input placeholder="xxxxxxx" type="text" wire:model.lazy="mobile_number"
                        class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                    @error('mobile_number')
                        <div class="text-red-500 text-xs italic mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="col-span-1">
                <div class="text-registrationPrimaryColor">
                    Landline number
                </div>
                <div>
                    <input placeholder="xxxxxxx" type="text" wire:model.lazy="landline_number"
                        class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                    @error('landline_number')
                        <div class="text-red-500 text-xs italic mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            

            <div class="col-span-1">
                <div class="text-registrationPrimaryColor">
                    Country <span class="text-red-500">*</span>
                </div>
                <div>
                    <select wire:model.lazy="country"
                        class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                        <option value=""></option>
                        @foreach ($countries as $country)
                            <option value="{{ $country }}">
                                {{ $country }}
                            </option>
                        @endforeach
                    </select>

                    @error('country')
                        <div class="text-red-500 text-xs italic mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>


    <div class="mt-5 grid grid-cols-3 gap-x-5">
        <div class="col-span-1">
            <div class="text-registrationPrimaryColor">
                Pass type
            </div>
            <div>
                <select wire:model.lazy="pass_type"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                    <option value=""></option>
                    <option value="fullMember">Full member</option>
                    <option value="member">Member</option>
                    <option value="nonMember">Non-Member</option>
                </select>

                @error('country')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-1">
            <div class="text-registrationPrimaryColor">
                Company name
            </div>
            <div>
                <input placeholder="Company name" type="text" wire:model.lazy="company_name"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                @error('company_name')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-1">
            <div class="text-registrationPrimaryColor">
                Job title
            </div>
            <div>
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
</div>
