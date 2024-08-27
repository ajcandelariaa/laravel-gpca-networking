<div>
    <h1 class="text-headingTextColor text-3xl font-bold">Confirmed delegates</h1>

    @if (count($attendeesFromApi) == 0)
        <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
            There are no confirmed attendees from API yet.
        </div>
    @else
        <p class="mt-5">Total confirmed attendees: {{ count($attendeesFromApi) }}</p>
        <p class="mt-5">Total added attendees: {{ $totalCountAdded }}</p>
        <p class="mt-5">Total not added attendees: {{ $totalCountNotAdded }}</p>

        <div class="shadow-lg my-5 bg-white rounded-md">
            <div
                class="grid grid-cols-12 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
                <div class="col-span-1">Transaction ID</div>
                <div class="col-span-1">Invoice number</div>
                <div class="col-span-3">Name</div>
                <div class="col-span-1">Pass type</div>
                <div class="col-span-1">Company</div>
                <div class="col-span-1">Job title</div>
                <div class="col-span-1">Email address</div>
                <div class="col-span-1">Registration type</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @foreach ($attendeesFromApi as $index => $attendeeFromApi)
                <div
                    class="grid grid-cols-12 gap-10 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                    <div class="col-span-1 break-words">{{ $attendeeFromApi['delegateTransactionId'] }}</div>
                    <div class="col-span-1 break-words">{{ $attendeeFromApi['delegateInvoiceNumber'] }}</div>
                    <div class="col-span-3 break-words">{{ $attendeeFromApi['delegateSalutation'] }} {{ $attendeeFromApi['delegateFName'] }} {{ $attendeeFromApi['delegateMName'] }}  {{ $attendeeFromApi['delegateLName'] }}</div>
                    <div class="col-span-1 break-words">{{ $attendeeFromApi['delegatePassType'] }}</div>
                    <div class="col-span-1 break-words">{{ $attendeeFromApi['delegateCompany'] }}</div>
                    <div class="col-span-1 break-words">{{ $attendeeFromApi['delegateJobTitle'] }}</div>
                    <div class="col-span-1 break-words">{{ $attendeeFromApi['delegateEmailAddress'] }}</div>
                    <div class="col-span-1 break-words">{{ $attendeeFromApi['delegateBadgeType'] }}</div>
                    <div class="col-span-1 break-words">{{ $attendeeFromApi['delegateIsAdded'] ? 'Added' : 'Not yet added' }}</div>
                    <div class="col-span-1 break-words">
                        @if($attendeeFromApi['delegateIsAdded'])
                            <button class="cursor-not-allowed bg-gray-400 py-1 px-6 rounded-md" disabled>Added</button>
                        @else 
                            <button class="cursor-pointer bg-primaryColor text-white py-1 px-6 rounded-md" wire:click.prevent="{{ "addAttendeeConfirmation($index)" }}">Add</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
