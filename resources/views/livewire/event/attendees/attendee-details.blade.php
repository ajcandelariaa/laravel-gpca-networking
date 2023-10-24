<div>
    @if ($editAttendeeForm)
        <h1 class="text-headingTextColor text-3xl font-bold">Edit Attendee</h1>
        @include('livewire.event.attendees.edit_attendee')
    @else
        <h1 class="text-headingTextColor text-3xl font-bold">Attendee details</h1>
        <div class="grid grid-cols-attendeeDetailGrid gap-14">
            <div class="mt-5">
                <div class="relative">
                    <img src="{{ $attendeeData['attendeePFP'] }}" class="w-80 h-80">
                    <div class="absolute -bottom-4 -right-3 cursor-pointer" wire:click.prevent="showUpdatePFPAttendee" wire:key="showUpdatePFPAttendee">
                        <i class="fa-solid fa-pen bg-primaryColor text-white rounded-full p-3"></i>
                    </div>
                </div>

                <div class="mt-10">
                    <p class="font-bold text-2xl">Biography</p>
                    @if ($attendeeData['attendeeBiography'] == "" || $attendeeData['attendeeBiography'] == null)
                        <p class="mt-3 text-sm text-gray-700">N/A</p>
                    @else
                        <p class="mt-3 text-sm text-gray-700">{{ $attendeeData['attendeeBiography'] }}</p>
                    @endif
                </div>
            </div>

            <div class="mt-5">
                <div>
                    <p class="text-primaryColor font-bold text-3xl">{{ $attendeeData['attendeeSalutation'] }}
                        {{ $attendeeData['attendeeFirstName'] }} {{ $attendeeData['attendeeMiddleName'] }}
                        {{ $attendeeData['attendeeLastName'] }}</p>
                    <p class="mt-2 italic text-lg">{{ $attendeeData['attendeeJobTitle'] }}</p>
                    <p class="font-bold text-lg">{{ $attendeeData['attendeeCompany'] }}</p>
                </div>

                <hr class="my-6">

                <div class="grid grid-cols-attendeeDetailGrid2 gap-y-2 items-center">
                    <p class="font-bold">Username:</p>
                    <p>{{ $attendeeData['attendeeUsername'] }}</p>

                    <p class="font-bold">Email address:</p>
                    <p>{{ $attendeeData['attendeeEmail'] }}</p>

                    <p class="font-bold">Mobile number:</p>
                    <p>{{ $attendeeData['attendeeMobileNumber'] }}</p>

                    <p class="font-bold">Landline number:</p>
                    @if ($attendeeData['attendeeLandlineNumber'] == "" || $attendeeData['attendeeLandlineNumber'] == null)
                        <p class="mt-3 text-sm text-gray-700">N/A</p>
                    @else
                        <p class="mt-3 text-sm text-gray-700">{{ $attendeeData['attendeeLandlineNumber'] }}</p>
                    @endif

                    <p class="font-bold">Country:</p>
                    <p>{{ $attendeeData['attendeeCountry'] }}</p>

                    <p class="font-bold">Badge number:</p>
                    <p>{{ $attendeeData['attendeeBadgeNumber'] }}</p>

                    <p class="font-bold">Pass type:</p>
                    @if ($attendeeData['attendeePassType'] == 'fullMember')
                        <p>Full Member</p>
                    @elseif ($attendeeData['attendeePassType'] == 'member')
                        <p>Member</p>
                    @else
                        <p>Non-Member</p>
                    @endif

                    <p class="font-bold">Registration type:</p>
                    <p>{{ $attendeeData['attendeeRegistrationType'] }}</p>

                    <p class="font-bold">Joined:</p>
                    <p>{{ $attendeeData['attendeeAddedDateTime'] }}</p>

                    <p class="font-bold">Last password changed:</p>
                    <p>{{ $attendeeData['attendeeLastPasswordChangeDateTime'] }}</p>
                </div>

                <div class="mt-5 flex gap-7">
                    <button wire:click.prevent="showEditAttendee" wire:key="showEditAttendee"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                        <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                        <span>Edit profile</span>
                    </button>


                    <button wire:click.prevent="showResetPasswordAttendee" wire:key="showResetPasswordAttendee"
                        class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                        <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                        <span>Reset password</span>
                    </button>
                </div>
            </div>
        </div>

        @if ($resetPasswordForm)
            @include('livewire.event.attendees.attendee_reset_password')
        @endif

        @if ($editAttendeePFPForm)
            @include('livewire.event.attendees.edit_pfp')
        @endif
    @endif
</div>
