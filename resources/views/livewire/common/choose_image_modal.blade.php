<div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center px-5 z-50">
    <div class="bg-white rounded-lg shadow-lg w-full h-5/6 grid grid-cols-12">

        <div class="col-span-9 h-full p-4 overflow-y-auto">
            @if (count($mediaFileList) == 0)
                <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
                    There are no files yet.
                </div>
            @else
                <div class="grid grid-cols-8 gap-4">
                    @foreach ($mediaFileList as $mediaFileIndex => $mediaFile)
                        @if (str_starts_with($mediaFile['file_type'], 'image/'))
                            @php
                                $isActive =
                                    $activeSelectedImage &&
                                    ($activeSelectedImage['id'] ?? null) === ($mediaFile['id'] ?? null);
                            @endphp

                            <div class="col-span-1 w-28 cursor-pointer"
                                wire:key="media-modal-{{ $mediaFile['id'] ?? $mediaFileIndex }}"
                                @if ($isActive) wire:click.prevent="unshowMediaFileDetails"
            @else
                wire:click.prevent="showMediaFileDetails({{ $mediaFileIndex }})" @endif
                                title="{{ $mediaFile['file_name'] }}">
                                <div
                                    class="w-28 h-28 border {{ $isActive ? 'border-primaryColor ring-2 ring-primaryColor/30' : 'border-gray-300 hover:border-primaryColor' }} bg-gray-100 flex items-center justify-center">
                                    <img src="{{ $mediaFile['file_url'] }}" alt="{{ $mediaFile['file_name'] }}"
                                        class="w-full h-full object-contain p-2" loading="lazy">
                                </div>

                                {{-- Filename under the thumb so browser Ctrl+F finds it --}}
                                <p class="mt-1 text-[11px] leading-tight text-gray-700 break-words text-center">
                                    {{ $mediaFile['file_name'] }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-span-3 p-4 border-l border-gray-200 flex flex-col justify-between">
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
                    <button wire:click.prevent="selectChooseImage" wire:key="selectChooseImage" type="button"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-5 rounded items-center text-sm cursor-pointer">Select
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
