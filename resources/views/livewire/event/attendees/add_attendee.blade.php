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
                        Add attendee
                    </div>

                    <div class="grid grid-cols-2 gap-5 mt-5">
                        <div class="col-span-1">
                            <div class="text-primaryColor">
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
                            <div class="text-primaryColor">
                                Company name <span class="text-red-500">*</span>
                            </div>
                            <div>
                                @if ($pass_type)
                                    @if ($event->category == 'AF')
                                        @if ($pass_type == 'fullMember')
                                            <select wire:model.lazy="company_name"
                                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                                @foreach ($members['data'] as $member)
                                                    @if ($member['type'] == 'full')
                                                        <option value="{{ $member['name'] }}">{{ $member['name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @elseif ($pass_type == 'member')
                                            <select wire:model.lazy="company_name"
                                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                                @foreach ($members['data'] as $member)
                                                    @if ($member['type'] == 'associate')
                                                        <option value="{{ $member['name'] }}">
                                                            {{ $member['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @else
                                            <input placeholder="Company name" type="text"
                                                wire:model.lazy="company_name"
                                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                        @endif
                                    @else
                                        @if ($pass_type == 'member')
                                            <select wire:model.lazy="company_name"
                                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                                @foreach ($members['data'] as $member)
                                                    <option value="{{ $member['name'] }}">{{ $member['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input placeholder="Company name" type="text"
                                                wire:model.lazy="company_name"
                                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                        @endif
                                    @endif
                                @else
                                    <input placeholder="Company name" type="text" wire:model.lazy="company_name"
                                        disabled
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
                            <div class="text-primaryColor">
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
                            <div class="text-primaryColor">
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
                            <div class="text-primaryColor">
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

                        <div class="col-span-1">
                            <div class="text-primaryColor">
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

                        <div class="col-span-1">
                            <div class="text-primaryColor">
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
                            <div class="text-primaryColor">
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

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse mt-5">
                        <button type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            wire:click.prevent="addAttendeeConfirmation">Add attendee</button>
                        <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            wire:click.prevent="resetAddAttendeeFields">Cancel</button>
                    </div>
                </div>
            </div>
    </form>
</div>
