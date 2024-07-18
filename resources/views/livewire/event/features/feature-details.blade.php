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

        <div class="flex gap-3 items-center mt-5">
            <p class="font-bold text-primaryColor">Full name:</p>
            <p>{{ $featureData['featureFullName'] }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Short name:</p>
            <p>{{ $featureData['featureShortName'] ?? 'N/A' }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Edition:</p>
            <p>{{ $featureData['featureEdition'] ?? 'N/A' }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Location:</p>
            <p>{{ $featureData['featureLocation'] ?? 'N/A' }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Date:</p>
            <p>{{ $featureData['featureFormattedDate'] }}</p>
        </div>

        <div class="flex gap-3 items-center">
            <p class="font-bold text-primaryColor">Link:</p>
            <p>{{ $featureData['featureLink'] ?? 'N/A' }}</p>
        </div>

        <hr class="my-4">

        <div>
            <p><span class="font-semibold">Primary BG Color:</span> {{ $featureData['featurePrimaryBgColor'] }}</p>
            <p><span class="font-semibold">Secondary BG Color:</span> {{ $featureData['featureSecondaryBgColor'] }}</p>
            <p><span class="font-semibold">Primary Text Color:</span> {{ $featureData['featurePrimaryTextColor'] }}</p>
            <p><span class="font-semibold">Secondary Text Color:</span> {{ $featureData['featureSecondaryTextColor'] }}</p>
        </div>

        <hr class="my-4">

        <p><span class="font-semibold">Published date time:</span>
            {{ $featureData['featureDateTimeAdded'] }}
        </p>

        <p><span class="font-semibold">Status:</span>
            {{ $featureData['featureStatus'] ? 'Active' : 'Inactive' }}
        </p>

        <hr class="my-4">

        <div>
            <p class="font-semibold">Description:</p>
            <p>
                @if ($featureData['featureDescriptionHTMLText'] == '' || $featureData['featureDescriptionHTMLText'] == null)
                    N/A
                @else
                    {{ $featureData['featureDescriptionHTMLText'] }}
                @endif
            </p>
        </div>
    </div>


    {{-- FEATURE ASSETS --}}
    <div class="border border-primaryColor rounded-2xl py-5 px-7 mt-10">
        <h1 class="text-headingTextColor text-3xl font-bold">Feature assets</h1>

        <div class="grid grid-cols-2 gap-x-14 mt-10 items-start">
            <div class="col-span-1">
                <div class="flex items-center flex-col">
                    <div class="relative">
                        <p class="text-center">Feature Logo</p>
                        <button wire:click="showEditFeatureAsset('Feature Logo')"
                            class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </div>
                    @if ($featureData['featureLogo']['url'])
                        <img src="{{ $featureData['featureLogo']['url'] }}" class="mt-3 w-80">
                        <button wire:click="deleteFeatureAsset('Feature Logo')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
                            Remove image
                        </button>
                    @else
                        N/A
                    @endif
                </div>
            </div>

            <div class="col-span-1 flex items-center flex-col">
                <div class="relative">
                    <p class="text-center">Feature Banner</p>
                    <button wire:click="showEditFeatureAsset('Feature Banner')"
                        class="absolute top-0 -right-6 cursor-pointer hover:text-yellow-600 text-yellow-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                @if ($featureData['featureBanner']['url'])
                    <img src="{{ $featureData['featureBanner']['url'] }}" class="mt-3 w-96">
                    <button wire:click="deleteFeatureAsset('Feature Banner')"class="cursor-pointer hover:bg-red-500 bg-red-400 text-white text-sm py-1 px-5 rounded-md mt-4">
                        Remove image
                    </button>
                @else
                    N/A
                @endif
            </div>
        </div>
    </div>

    
    @if ($chooseImageModal)
        @include('livewire.common.choose_image_modal')
    @endif

    @if ($editFeatureDetailsForm)
        @include('livewire.event.features.edit_details')
    @endif

    @if ($editFeatureAssetForm)
        @include('livewire.event.features.edit_asset')
    @endif
</div>
