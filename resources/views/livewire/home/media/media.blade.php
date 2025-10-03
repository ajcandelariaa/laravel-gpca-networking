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
        <div class="grid grid-cols-10 gap-4 mt-5">
            @foreach ($mediaFileList as $mediaFileIndex => $mediaFile)
                <div class="col-span-1 w-36 cursor-pointer"
                    wire:click.prevent="showMediaFileDetails({{ $mediaFileIndex }})"
                    wire:key="media-{{ $mediaFile['id'] ?? $mediaFileIndex }}">
                    @if (str_starts_with($mediaFile['file_type'], 'image/'))
                        <div
                            class="w-36 h-36 flex items-center justify-center border border-gray-300 bg-gray-100 hover:border-primaryColor">
                            <img src="{{ $mediaFile['file_url'] }}" alt="{{ $mediaFile['file_name'] }}"
                                class="w-full h-full object-contain">
                        </div>
                    @else
                        <div
                            class="w-36 h-36 border border-gray-300 bg-gray-100 relative flex items-center justify-center hover:border-primaryColor">
                            <img src="{{ asset('assets/icons/document.svg') }}" alt="document" class="w-10 h-10">
                        </div>
                    @endif

                    <p class="mt-1 text-[11px] leading-tight text-gray-700 break-words text-center">
                        {{ $mediaFile['file_name'] }}
                    </p>
                </div>
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
