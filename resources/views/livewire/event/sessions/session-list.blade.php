<div>
    <h1 class="text-headingTextColor text-3xl font-bold">Session</h1>

    <div class="flex justify-between mt-5">
        <button type="button" wire:click.prevent="showAddSession" wire:key="showAddSession"
            class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-32 h-10">Add
            session</button>
    </div>

    @if (count($finalListOfSessions) == 0)
        <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
            There are no sessions yet.
        </div>
    @else
        <div class="shadow-lg my-5 bg-white rounded-md">
            <div
                class="grid grid-cols-12 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
                <div class="col-span-1">No.</div>
                <div class="col-span-2">Category</div>
                <div class="col-span-2">Date</div>
                <div class="col-span-1">Day</div>
                <div class="col-span-2">Title</div>
                <div class="col-span-2">Timings</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @foreach ($finalListOfSessions as $index => $finalListOfSession)
                <div
                    class="grid grid-cols-12 gap-10 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                    <div class="col-span-1">{{ $index + 1 }}</div>
                    <div class="col-span-2">{{ $finalListOfSession['category'] }}</div>
                    <div class="col-span-2">{{ $finalListOfSession['session_date'] }}</div>
                    <div class="col-span-1">{{ $finalListOfSession['session_day'] }}</div>
                    <div class="col-span-2">{{ $finalListOfSession['title'] }}</div>
                    <div class="col-span-2">{{ $finalListOfSession['timings'] }}</div>
                    <div class="col-span-1">
                        @if ($finalListOfSession['active'])
                            <button
                                wire:click="updateSessionStatus({{ $index }}, {{ $finalListOfSession['id'] }}, true)"
                                class="text-gray-700 bg-green-300 hover:bg-green-500 hover:text-white py-1 px-2 text-sm rounded-md">Active</button>
                        @else
                            <button
                                wire:click="updateSessionStatus({{ $index }}, {{ $finalListOfSession['id'] }}, false)"
                                class="text-gray-700 bg-red-300 hover:bg-red-500 hover:text-white py-1 px-2 text-sm rounded-md">Inactive</button>
                        @endif
                    </div>
                    <div class="col-span-1">
                        <a href="{{ route('admin.event.session.view', ['eventCategory' => $event->category, 'eventId' => $event->id, 'sessionId' => $finalListOfSession['id']]) }}" class="cursor-pointer hover:text-gray-600 text-gray-500">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    
    @if ($addSessionForm)
        @include('livewire.event.sessions.add_session')
    @endif
</div>
