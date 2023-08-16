<div class="mt-5">
    <div class="grid grid-cols-3 gap-x-5">
        <div class="col-span-1">
            <div class="text-registrationPrimaryColor">
                Registration type <span class="text-red-500">*</span>
            </div>
            <div>
                <select wire:model.lazy="registration_type"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                    <option value=""></option>
                    @foreach ($registrationTypes['data'] as $registrationType)
                        <option value="{{ $registrationType }}">{{ $registrationType }}</option>
                    @endforeach
                </select>

                @error('registration_type')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-1">
            <div class="text-registrationPrimaryColor">
                Username <span class="text-red-500">*</span>
            </div>
            <div>
                <input placeholder="Username" type="text" wire:model.lazy="username"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                @error('username')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror

                @if ($usernameExistingError != null)
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $usernameExistingError }}
                    </div>
                @endif
            </div>
        </div>

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
    </div>

    <div class="mt-5 grid grid-cols-3 gap-x-5">
        <div class="col-span-1">
            <div class="text-registrationPrimaryColor">
                Pass type <span class="text-red-500">*</span>
            </div>
            <div>
                <select wire:model.lazy="pass_type"
                    class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                    <option value=""></option>
                    @if ($event->category == 'AF')
                        <option value="fullMember">Full member</option>
                    @endif
                    <option value="member">Member</option>
                    <option value="nonMember">Non-Member</option>
                </select>

                @error('pass_type')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-1">
            <div class="text-registrationPrimaryColor">
                Company name <span class="text-red-500">*</span>
            </div>
            <div>
                @if ($pass_type)
                    @if ($event->category == 'AF')
                        @if ($pass_type == 'fullMember')
                            <select wire:model.lazy="company_name"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                @foreach ($members['data'] as $member)
                                    @if ($member['type'] == "full")
                                        <option value="{{ $member['name'] }}">{{ $member['name'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        @elseif ($pass_type == 'member')
                            <select wire:model.lazy="company_name"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                @foreach ($members['data'] as $member)
                                    @if ($member['type'] == "associate")
                                        <option value="{{ $member['name'] }}">{{ $member['name'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        @else
                            <input placeholder="Company name" type="text" wire:model.lazy="company_name"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                        @endif
                    @else
                        @if ($pass_type == "member")
                            <select wire:model.lazy="company_name"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                @foreach ($members['data'] as $member)
                                    <option value="{{ $member['name'] }}">{{ $member['name'] }}</option>
                                @endforeach
                            </select>
                        @else 
                            <input placeholder="Company name" type="text" wire:model.lazy="company_name"
                            class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                        @endif
                    @endif
                @else
                    <input placeholder="Company name" type="text" wire:model.lazy="company_name" disabled
                        class="bg-gray-200 w-full py-1 px-3 rounded-md border border-gray-200 cursor-not-allowed">
                @endif

                @error('company_name')
                    <div class="text-red-500 text-xs italic mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-span-1">
            <div class="text-registrationPrimaryColor">
                Job title <span class="text-red-500">*</span>
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

    <div class="mt-5 grid grid-cols-11 gap-x-5">
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
        <div class="grid grid-cols-3 gap-x-5">
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

    <div class="mt-5">
        <div class="text-registrationPrimaryColor">
            Biography
        </div>
        <div>
            <textarea wire:model.lazy="biography" cols="30" rows="5" class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">{{ $biography }}</textarea>
        </div>
    </div>

    <div class="text-center mt-10 flex gap-5 place-content-center">
        <button wire:click.prevent="editAttendeeConfirmation"
            class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-40 h-10">
            <span>Update</span>
        </button>

        <button type="button" wire:click.prevent="cancelEditAttendee" wire:key="cancelEditAttendee" class="bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm w-40 h-10">Cancel</button>
    </div>
</div>
