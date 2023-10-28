<div>
    <a href="{{ route('admin.event.features.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of features</span>
    </a>


    {{-- FEATURE DETAILS --}}
    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-5">
        <div class="flex items-center justify-between">
            <h1 class="text-headingTextColor text-3xl font-bold">Feature Details</h1> 
            <div>
                <button wire:click="showEditFeatureDetails"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
                    <span class="mr-2"><i class="fa-solid fa-file-pen"></i></span>
                    <span>Edit</span>
                </button>
            </div>
        </div>

        <div class="flex items-center gap-2 mt-5">
            <p class="font-bold text-2xl">{{ $featureData['featureName'] }}</p> 
            <p>({{ $featureData['featureShortName'] }})</p>
        </div>

        <div class="flex gap-3 items-center mt-3 text-primaryColor">
            <i class="fa-solid fa-location-dot"></i>
            <p>{{ $featureData['featureLocation'] }}</p>
        </div>

        <div class="flex gap-3 items-center mt-2 text-primaryColor">
            <i class="fa-solid fa-calendar-days"></i>
            <p>{{ $featureData['featureFormattedDate'] }}
            </p>
        </div>

        <div class="flex gap-3 items-center mt-2 text-primaryColor">
            <i class="fa-solid fa-link"></i>
            <p>{{ $featureData['featureLink'] }}</p>
        </div>

        <div class="mt-5">
            <hr>
        </div>

        <div class="mt-3">
            <p class="font-semibold">Tagline:</p>
            <p class="ml-4">
                @if ($featureData['featureTagline'] == '' || $featureData['featureTagline'] == null)
                    N/A
                @else
                    {{ $featureData['featureTagline'] }}
                @endif
            </p>
        </div>

        <div class="mt-3">
            <p class="font-semibold">Short description:</p>
            <p class="ml-4">
                @if ($featureData['featureShortDescription'] == '' || $featureData['featureShortDescription'] == null)
                    N/A
                @else
                    {{ $featureData['featureShortDescription'] }}
                @endif
            </p>
        </div>

        <div class="mt-5">
            <p class="font-semibold">Long description:</p>
            <p class="ml-4">
                @if ($featureData['featureLongDescription'] == '' || $featureData['featureLongDescription'] == null)
                    N/A
                @else
                    {{ $featureData['featureLongDescription'] }}
                @endif
            </p>
        </div>
    </div>



    {{-- FEATURE ASSETS --}}
    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-10">
        <h1 class="text-headingTextColor text-3xl font-bold">Feature Assets</h1>

        <div class="grid grid-cols-3 gap-x-10 mt-10 items-start">
            <div class="flex items-center flex-col">
                <div class="relative -ml-5">
                    <p class="text-center">Feature Logo</p>
                    <button wire:click="showEditFeatureAsset('Feature Logo')"
                        class="absolute top-0 -right-14 cursor-pointer hover:text-yellow-600 text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                </div>
                <img src="{{ $featureData['featureLogo'] }}" class="mt-3 w-60">
            </div>
            <div class="flex items-center flex-col">
                <div class="relative -ml-5">
                    <p class="text-center">Feature Banner</p>
                    <button wire:click="showEditFeatureAsset('Feature Banner')"
                        class="absolute top-0 -right-14 cursor-pointer hover:text-yellow-600 text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                </div>
                <img src="{{ $featureData['featureBanner'] }}" class="mt-3 w-96">
            </div>
            <div class="flex items-center flex-col">
                <div class="relative -ml-5">
                    <p class="text-center">Feature Image</p>
                    <button wire:click="showEditFeatureAsset('Feature Image')"
                        class="absolute top-0 -right-14 cursor-pointer hover:text-yellow-600 text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                </div>
                <img src="{{ $featureData['featureImage'] }}" class="mt-3 w-60">
            </div>
        </div>
    </div>

    @if ($editFeatureDetailsForm)
        @include('livewire.event.features.edit_details')
    @endif

    @if ($editFeatureAssetForm)
        @include('livewire.event.features.edit_asset')
    @endif 
</div>
