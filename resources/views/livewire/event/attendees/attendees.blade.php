<div>
    <h1 class="text-headingTextColor text-3xl font-bold">Attendees</h1>
    <div class="flex justify-between mt-5">
        @if ($addAttendeeForm)
            <button type="button" wire:click.prevent="cancelAddAttendee" wire:key="cancelAddAttendee" class="bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm w-40 h-10">Cancel</button>
        @else
            <div class="flex gap-3">
                <button type="button" wire:click.prevent="showAddAttendee" wire:key="showAddAttendee" class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-40 h-10">Add
                    attendees</button>
                <button class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-40 h-10">Import
                    attendees</button>
                <button class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-40 h-10">Export
                    data</button>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" wire:model.lazy="searchTerm"
                        class="border border-gray-300 bg-white rounded-md py-2 pl-10 pr-4 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        placeholder="Search...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                <div>
                    <button wire:click="search"
                        class="bg-primaryColor hover:bg-primaryColorHover text-white py-1 px-4 rounded-md">Search</button>
                </div>
            </div>
        @endif
    </div>


    @if ($addAttendeeForm)
        @include('livewire.event.attendees.add_attendee')
    @else
        @if (count($finalListOfAttendees) == 0)
            <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
                There are no attendees yet.
            </div>
        @else
            <div class="shadow-lg my-5 pt-5 bg-white rounded-md">
                <p>Total attendees: {{ count($finalListOfAttendees) }}</p>
                <div
                    class="grid grid-cols-7 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
                    <div class="col-span-1">Name</div>
                    <div class="col-span-1">Job title</div>
                    <div class="col-span-1">Company</div>
                    <div class="col-span-1">Email address</div>
                    <div class="col-span-1">Country</div>
                    <div class="col-span-1">Registration type</div>
                    <div class="col-span-1">Action</div>
                </div>
                @foreach ($finalListOfAttendees as $index => $finalListOfAttendee)
                    <div
                        class="grid grid-cols-7 gap-10 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                        <div class="col-span-1">{{ $finalListOfAttendee['name'] }}</div>
                        <div class="col-span-1">{{ $finalListOfAttendee['job_title'] }}</div>
                        <div class="col-span-1">{{ $finalListOfAttendee['company_name'] }}</div>
                        <div class="col-span-1">{{ $finalListOfAttendee['email_address'] }}</div>
                        <div class="col-span-1">{{ $finalListOfAttendee['country'] }}</div>
                        <div class="col-span-1">{{ $finalListOfAttendee['registration_type'] }}</div>
                        <div class="col-span-1">
                            <a href="{{ route('admin.event.attendee.view', ['eventCategory' => $event->category, 'eventId' => $event->id, 'attendeeId' => $finalListOfAttendee['id']]) }}"
                                class="cursor-pointer hover:text-gray-600 text-gray-500" target="_blank">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
