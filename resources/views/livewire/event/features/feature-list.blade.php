<div>
    <h1 class="text-headingTextColor text-3xl font-bold">Features</h1>

    <div class="flex justify-between mt-5">
        <button type="button" wire:click.prevent="showAddFeature" wire:key="showAddFeature"
            class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-28 h-10">Add
            feature</button>
    </div>


    @if (count($finalListOfFeatures) == 0)
        <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
            There are no features yet.
        </div>
    @else
        <div class="shadow-lg my-5 bg-white rounded-md">
            <div
                class="grid grid-cols-12 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
                <div class="col-span-1">No.</div>
                <div class="col-span-2">Full Name</div>
                <div class="col-span-2">Short Name</div>
                <div class="col-span-2">Feature Date</div>
                <div class="col-span-2">Date time added</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-1">Action</div>
            </div>
            @foreach ($finalListOfFeatures as $index => $finalListOfFeature)
                <div
                    class="grid grid-cols-12 gap-11 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                    <div class="col-span-1">{{ $index + 1 }}</div>
                    <div class="col-span-2">{{ $finalListOfFeature['full_name'] }}</div>
                    <div class="col-span-2">{{ $finalListOfFeature['short_name'] }}</div>
                    <div class="col-span-2">{{ $finalListOfFeature['date'] }}</div>
                    <div wire:click="showEditFeatureDateTime({{ $finalListOfFeature['id'] }}, {{ $index }})"
                        class="text-blue-700 hover:underline col-span-2 cursor-pointer">
                        {{ $finalListOfFeature['datetime_added'] }}</div>
                    <div class="col-span-2">
                        @if ($finalListOfFeature['is_active'])
                            <button
                                wire:click="updateFeatureStatus({{ $index }})"
                                class="text-gray-700 bg-green-300 hover:bg-green-500 hover:text-white py-1 px-2 text-sm rounded-md">Active</button>
                        @else
                            <button
                                wire:click="updateFeatureStatus({{ $index }})"
                                class="text-gray-700 bg-red-300 hover:bg-red-500 hover:text-white py-1 px-2 text-sm rounded-md">Inactive</button>
                        @endif
                    </div>
                    <div class="col-span-1 flex gap-3 items-center justify-center">
                        <a href="{{ route('admin.event.feature.view', ['eventCategory' => $event->category, 'eventId' => $event->id, 'featureId' => $finalListOfFeature['id']]) }}"
                            class="cursor-pointer hover:text-gray-600 text-gray-500">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <button wire:click="deleteFeatureConfirmation({{ $index }})"
                            class="cursor-pointer hover:text-red-600 text-red-500 text-sm ">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($editFeatureDateTimeForm)
        @include('livewire.common.edit_datetime_form')
    @endif

    @if ($addFeatureForm)
        @include('livewire.event.features.add_feature')
    @endif
</div>
