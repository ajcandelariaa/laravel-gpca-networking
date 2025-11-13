<div>
    @if ($editAttendeeForm)
        <h1 class="text-headingTextColor text-3xl font-bold">Edit Attendee</h1>
        @include('livewire.event.attendees.edit_attendee')
    @else
        <a href="{{ route('admin.event.attendees.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
            class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
            <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
            <span>List of attendees</span>
        </a>

        <h1 class="text-headingTextColor text-3xl font-bold mt-5">Attendee details</h1>
        <div class="grid grid-cols-attendeeDetailGrid gap-14">
            <div class="mt-5">
                <div class="relative">
                    <img src="{{ $attendeeData['pfp']['url'] ?? asset('assets/images/pfp-placeholder.jpg') }}"
                        class="w-80 h-80">
                    <div class="absolute -bottom-4 -right-3 cursor-pointer" wire:click.prevent="showUpdatePFPAttendee"
                        wire:key="showUpdatePFPAttendee">
                        <i class="fa-solid fa-pen bg-primaryColor text-white rounded-full p-3"></i>
                    </div>
                </div>

                <div class="mt-10">
                    <p class="font-bold text-2xl">Biography</p>
                    @if ($attendeeData['biography'] == '' || $attendeeData['biography'] == null)
                        <p class="mt-3 text-sm text-gray-700">N/A</p>
                    @else
                        <p class="mt-3 text-sm text-gray-700">{{ $attendeeData['biography'] }}</p>
                    @endif
                </div>
            </div>

            <div class="mt-5">
                <div>
                    <p class="text-primaryColor font-bold text-3xl">{{ $attendeeData['salutation'] }}
                        {{ $attendeeData['first_name'] }} {{ $attendeeData['middle_name'] }}
                        {{ $attendeeData['last_name'] }}</p>
                    <p class="mt-2 italic text-lg">{{ $attendeeData['job_title'] }}</p>
                    <p class="font-bold text-lg">{{ $attendeeData['company_name'] }}</p>
                </div>

                <hr class="my-6">

                <div class="flex gap-20">
                    <div class="grid grid-cols-attendeeDetailGrid2 gap-y-1 items-center">
                        <p class="font-bold">Badge number:</p>
                        <p>{{ $attendeeData['badge_number'] }}</p>

                        <p class="font-bold">Registration type:</p>
                        <p>{{ $attendeeData['registration_type'] }}</p>

                        <p class="font-bold">Pass type:</p>
                        @if ($attendeeData['pass_type'] == 'fullMember')
                            <p>Full Member</p>
                        @elseif ($attendeeData['pass_type'] == 'member')
                            <p>Member</p>
                        @else
                            <p>Non-Member</p>
                        @endif

                        <p class="font-bold">Company country:</p>
                        <p>{{ $attendeeData['company_country'] }}</p>

                        <p class="font-bold">Company phone number:</p>
                        @if ($attendeeData['company_phone_number'] == '' || $attendeeData['company_phone_number'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['company_phone_number'] }}</p>
                        @endif

                        <p class="font-bold">Username:</p>
                        <p>{{ $attendeeData['username'] }}</p>

                        <p class="font-bold">Email address:</p>
                        <p>{{ $attendeeData['email_address'] }}</p>

                        <p class="font-bold">Mobile number:</p>
                        <p>{{ $attendeeData['mobile_number'] ?? 'N/A' }}</p>

                        <p class="font-bold">Interests:</p>
                        <p>{{ $attendeeData['interests'] ?? 'N/A' }}</p>

                        <p class="font-bold">Active:</p>
                        <p>
                            @if ($attendeeData['is_active'])
                                Yes
                            @else
                                No
                            @endif
                        </p>

                        <p class="font-bold">Joined:</p>
                        <p>{{ $attendeeData['joined_date_time'] }}</p>
                        

                        <p class="font-bold">Account Activated:</p>
                        <p>{{ $attendeeData['password_set_datetime'] }}</p>
                    </div>

                    <div class="grid grid-cols-attendeeDetailGrid3 gap-y-1 items-center">
                        <p class="font-bold">Gender:</p>
                        @if ($attendeeData['gender'] == '' || $attendeeData['gender'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['gender'] }}</p>
                        @endif

                        <p class="font-bold">Birthdate:</p>
                        @if ($attendeeData['birthdate'] == '' || $attendeeData['birthdate'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['birthdate'] }}</p>
                        @endif

                        <p class="font-bold">Country:</p>
                        @if ($attendeeData['country'] == '' || $attendeeData['country'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['country'] }}</p>
                        @endif

                        <p class="font-bold">City:</p>
                        @if ($attendeeData['city'] == '' || $attendeeData['city'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['city'] }}</p>
                        @endif

                        <p class="font-bold">Address:</p>
                        @if ($attendeeData['address'] == '' || $attendeeData['address'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['address'] }}</p>
                        @endif

                        <p class="font-bold">Nationality:</p>
                        @if ($attendeeData['nationality'] == '' || $attendeeData['nationality'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['nationality'] }}</p>
                        @endif

                        <p class="font-bold">Website:</p>
                        @if ($attendeeData['website'] == '' || $attendeeData['website'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['website'] }}</p>
                        @endif

                        <p class="font-bold">Facebook:</p>
                        @if ($attendeeData['facebook'] == '' || $attendeeData['facebook'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['facebook'] }}</p>
                        @endif

                        <p class="font-bold">Linkedin:</p>
                        @if ($attendeeData['linkedin'] == '' || $attendeeData['linkedin'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['linkedin'] }}</p>
                        @endif

                        <p class="font-bold">Twitter:</p>
                        @if ($attendeeData['twitter'] == '' || $attendeeData['twitter'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['twitter'] }}</p>
                        @endif

                        <p class="font-bold">Instagram:</p>
                        @if ($attendeeData['instagram'] == '' || $attendeeData['instagram'] == null)
                            <p class="text-sm text-gray-700">N/A</p>
                        @else
                            <p class="text-sm text-gray-700">{{ $attendeeData['instagram'] }}</p>
                        @endif
                    </div>
                </div>

                <div class="mt-5 flex gap-5">
                    <button wire:click.prevent="showEditAttendee" wire:key="showEditAttendee"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                        <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                        <span>Edit profile</span>
                    </button>
                    @if ($attendeeData['password_set_datetime'] == null)
                        <button wire:click.prevent="activateAccount" wire:key="activateAccount"
                            class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                            <span>Activate account</span>
                        </button>
                    @else
                        <button disabled
                            class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm cursor-not-allowed">
                            <span>Activate account</span>
                        </button>
                    @endif
                    <button wire:click.prevent="deleteAccountConfirmation" wire:key="deleteAccountConfirmation"
                        class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                        <span class="mr-2"><i class="fa-solid fa-trash"></i></span>
                        <span>Delete account</span>
                    </button>
                </div>

                <hr class="my-6">

                <p class="text-xl font-semibold text-primaryColor">Password resets</p>
                <div class="shadow-lg my-5 bg-white rounded-md w-1/2">
                    <div
                        class="grid grid-cols-3 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
                        <div class="col-span-1">No.</div>
                        <div class="col-span-1">Changed by</div>
                        <div class="col-span-1">Date</div>
                    </div>

                    @if (count($attendeeData['attendeePasswordResetDetails']) == 0)
                        <div class="bg-red-400 text-white text-center mt-1 py-1">
                            Attendee does not reset his/her password yet.
                        </div>
                    @else
                        @foreach ($attendeeData['attendeePasswordResetDetails'] as $index => $passwordResetDetail)
                            <div
                                class="grid grid-cols-3 gap-10 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                                <div class="col-span-1">{{ $index + 1 }}</div>
                                <div class="col-span-1">{{ $passwordResetDetail['changed_by'] }}</div>
                                <div class="col-span-1">{{ $passwordResetDetail['datetime'] }}</div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="mt-10">
                    <button wire:click.prevent="showResetPasswordAttendee" wire:key="showResetPasswordAttendee"
                        class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                        <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                        <span>Reset password</span>
                    </button>
                </div>
            </div>
        </div>

        @if ($chooseImageModal)
            @include('livewire.common.choose_image_modal')
        @endif

        @if ($resetPasswordForm)
            @include('livewire.event.attendees.attendee_reset_password')
        @endif

        @if ($editAttendeePFPForm)
            @include('livewire.event.attendees.edit_pfp')
        @endif
    @endif
</div>
