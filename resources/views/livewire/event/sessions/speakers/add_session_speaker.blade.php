<div class="fixed z-10 inset-0 overflow-y-auto">
    <form>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="text-primaryColor italic font-bold text-xl">
                        Add session speaker
                    </div>

                    <div class="mt-5">
                        <div class="text-primaryColor">
                            Speaker type
                        </div>
                        <div class="mt-2">
                            <select wire:model.lazy="session_speaker_type_id"
                                class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                                <option value=""></option>
                                @foreach ($speakerTypeChoices as $speakerTypeChoice)
                                    <option value="{{ $speakerTypeChoice['speakerTypeId'] }}">
                                        {{ $speakerTypeChoice['speakerTypeName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="text-primaryColor">
                            Please select speakers <span class="text-red-500">*</span>
                        </div>
                        <div>
                            @if (count($speakerChoices) > 0)
                                <div class="mt-2 flex flex-col gap-2">
                                    @foreach ($speakerChoices as $speakerChoice)
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" wire:model.lazy="speaker_ids"
                                                value="{{ $speakerChoice['speakerId'] }}"
                                                id="{{ $speakerChoice['speakerId'] }}">
                                            <label for="{{ $speakerChoice['speakerId'] }}">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ $speakerChoice['speakerPFP'] }}" class="w-10 h-10 object-cover">
                                                    <p>{{ $speakerChoice['speakerName'] }}</p>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-red-400 text-white text-center py-1 mt-2 rounded-md">
                                    There are no speakers yet.
                                </div>
                            @endif


                            @error('speaker_ids')
                                <div class="text-red-500 text-xs italic mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>


                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:click.prevent="addSessionSpeakerConfirmation">Add</button>
                    <button type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        wire:click.prevent="cancelAddSessionSpeaker">Cancel</button>
                </div>
            </div>
        </div>
    </form>
</div>
