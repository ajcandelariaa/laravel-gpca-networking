<div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center px-5 z-50">
    <div class="bg-white rounded-lg shadow-lg w-full flex">

        <div class="w-3/4 p-4 overflow-auto">
            @if (count($mediaFileList) == 0)
                <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
                    There are no files yet.
                </div>
            @else
                <div class="grid grid-cols-5 gap-4">
                    @foreach ($mediaFileList as $mediaFileIndex => $mediaFile)
                        @if (str_starts_with($mediaFile['file_type'], 'image/'))
                            @if ($activeSelectedImage == $mediaFile)
                                <div class="col-span-1 flex items-center justify-center border border-primaryColor bg-gray-100 cursor-pointer hover:border-1"
                                    wire:click.prevent="unshowMediaFileDetails">
                                    <img src="{{ $mediaFile['file_url'] }}" class="w-full h-auto object-cover p-2">
                                </div>
                            @else
                                <div class="col-span-1 flex items-center justify-center border border-gray-300 bg-gray-100 cursor-pointer hover:border-1 hover:border-primaryColor"
                                    wire:click.prevent="showMediaFileDetails({{ $mediaFileIndex }})">
                                    <img src="{{ $mediaFile['file_url'] }}" class="w-full h-auto object-cover p-2">
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <div class="w-1/4 p-4 border-l border-gray-200 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-bold mb-4">Image Details</h2>
                @if ($activeSelectedImage != null)
                    <p>Link: {{ $activeSelectedImage['file_url'] }}</p>
                    <p>Directory: {{ $activeSelectedImage['file_directory'] }}</p>
                    <p>File name: {{ $activeSelectedImage['file_name'] }}</p>
                    <p>Size: {{ $activeSelectedImage['file_size'] }} kb</p>
                    <p>Dimension: {{ $activeSelectedImage['width'] }}x{{ $activeSelectedImage['height'] }} pixels</p>
                    <p>Date uploaded: {{ $activeSelectedImage['date_uploaded'] }}</p>
                @else
                    <div>
                        <p>Select an image to see details.</p>
                    </div>
                @endif
            </div>
            <div class="flex flex-col gap-2 mt-10">
                @if ($activeSelectedImage != null)
                    <button wire:click.prevent="selectChooseImage" wire:key="selectChooseImage" type="button" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-5 rounded items-center text-sm cursor-pointer">Select
                        logo</button>
                @else
                    <button type="button"
                        class="bg-gray-600 text-white font-medium py-2 px-5 rounded items-center text-sm cursor-not-allowed"
                        disabled>Select logo</button>
                @endif

                <button type="button" wire:click.prevent="cancelChooseImage"
                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-5 rounded items-center text-sm cursor-pointer">Cancel</button>
            </div>
        </div>
    </div>
</div>
