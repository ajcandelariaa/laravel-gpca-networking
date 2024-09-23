<div class="container mx-auto my-5">
    <div class="flex justify-between items-center">
        <h1 class="text-headingTextColor text-3xl font-bold mt-5">Media library</h1>
        <button type="button" wire:click.prevent="showAddMedia" wire:key="showAddMedia"
            class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-32 h-10">Add
            Media</button>
    </div>



    @if (count($mediaFileList) == 0)
        <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
            There are no files yet.
        </div>
    @else
        <div class="grid grid-cols-10 gap-y-10 mt-5">
            @foreach ($mediaFileList as $mediaFileIndex => $mediaFile)
                @if (str_starts_with($mediaFile['file_type'], 'image/'))
                    <div class="col-span-1 w-36 h-36 flex items-center justify-center border border-gray-300 bg-gray-100 cursor-pointer hover:border-1 hover:border-primaryColor" wire:click.prevent="showMediaFileDetails({{ $mediaFileIndex }})" wire:key="showMediaFileDetails">
                        <img src="{{ $mediaFile['file_url'] }}" class="w-full h-full object-scale-down">
                    </div>
                @else
                    <div class="col-span-1 w-36 h-36 border border-gray-300 bg-gray-100 relative cursor-pointer hover:border-1 hover:border-primaryColor" wire:click.prevent="showMediaFileDetails({{ $mediaFileIndex }})" wire:key="showMediaFileDetails">
                        <div class="absolute top-6 left-9 z-10">
                            <img src="{{ asset('assets/icons/document.svg') }}" class="bg-cover p-2">
                        </div>
                        <p class="bg-white overflow-hidden w-full text-sm h-auto text-gray-600 p-1 absolute z-20 bottom-0 text-center">
                            {{ $mediaFile['file_name'] }}
                        </p>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
    
    @if ($showMediaModal)
        @include('livewire.home.media.media_details')
    @endif

    @if ($addMediaForm)
        @include('livewire.home.media.add_media')
    @endif
</div>
