<div>
    <h1 class="text-headingTextColor text-3xl font-bold">Exhibitors</h1>

    <div class="flex justify-between mt-5">
        <button type="button" wire:click.prevent="showAddExhibitor" wire:key="showAddExhibitor"
            class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-52 h-10">Add
            exhibitor</button>
    </div>

    @if (count($finalListOfExhibitors) == 0)
        <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
            There are no exhibitors yet.
        </div>
    @else
        <p class="mt-5">Total exhibitors: {{ count($finalListOfExhibitors) }}</p>

        <div class="shadow-lg my-5 bg-white rounded-md">
            <div
                class="grid grid-cols-11 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
                <div class="col-span-1">No.</div>
                <div class="col-span-2">Logo</div>
                <div class="col-span-2">Company Name</div>
                <div class="col-span-1">Link</div>
                <div class="col-span-1">Stand No.</div>
                <div class="col-span-2">Date time added</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @foreach ($finalListOfExhibitors as $index => $finalListOfExhibitor)
                <div
                    class="grid grid-cols-11 gap-10 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                    <div class="col-span-1">{{ $index + 1 }}</div>
                    <div class="col-span-2">
                        @if ($finalListOfExhibitor['logo'] == null)
                            N/A
                        @else
                            <img src="{{ Storage::url($finalListOfExhibitor['logo']) }}" alt=""
                                class="mx-auto w-14">
                        @endif
                    </div>
                    <div class="col-span-2">{{ $finalListOfExhibitor['name'] }}</div>
                    <div class="col-span-1">{{ $finalListOfExhibitor['link'] }}</div>
                    <div class="col-span-1">{{ $finalListOfExhibitor['stand_number'] }}</div>
                    <div wire:click="showEditExhibitorDateTime({{ $finalListOfExhibitor['id'] }}, {{ $index }})"
                        class="text-blue-700 hover:underline col-span-2 cursor-pointer">
                        {{ $finalListOfExhibitor['datetime_added'] }}</div>
                    <div class="col-span-1">
                        @if ($finalListOfExhibitor['active'])
                            <button
                                wire:click="updateExhibitorStatus({{ $index }}, {{ $finalListOfExhibitor['id'] }}, true)"
                                class="text-gray-700 bg-green-300 hover:bg-green-500 hover:text-white py-1 px-2 text-sm rounded-md">Active</button>
                        @else
                            <button
                                wire:click="updateExhibitorStatus({{ $index }}, {{ $finalListOfExhibitor['id'] }}, false)"
                                class="text-gray-700 bg-red-300 hover:bg-red-500 hover:text-white py-1 px-2 text-sm rounded-md">Inactive</button>
                        @endif
                    </div>
                    <div class="col-span-1">
                        <a href="{{ route('admin.event.exhibitor.view', ['eventCategory' => $event->category, 'eventId' => $event->id, 'exhibitorId' => $finalListOfExhibitor['id']]) }}"
                            class="cursor-pointer hover:text-gray-600 text-gray-500">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($editExhibitorDateTimeForm)
        @include('livewire.event.exhibitors.edit_datetime')
    @endif

    @if ($addExhibitorForm)
        @include('livewire.event.exhibitors.add_mrp')
    @endif
</div>
