<div class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center px-5 z-50">
    <div class="bg-white rounded-lg shadow-lg w-3/4 flex">
        <div class="w-3/4 p-4 overflow-auto">
            <div class="text-primaryColor italic font-bold text-xl">
                Session days
            </div>

            @if (count($session_days) == 0)
                <div class="bg-red-400 text-white text-center py-2 mt-3 rounded-md">
                    There are no session days yet.
                </div>
            @else
                <div>
                    <div
                        class="grid grid-cols-6 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
                        <div class="col-span-1">No.</div>
                        <div class="col-span-2">Day</div>
                        <div class="col-span-2">Description</div>
                        <div class="col-span-1">Action</div>
                    </div>

                    @foreach ($session_days as $index => $session_day)
                        <div
                            class="grid grid-cols-6 gap-10 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                            <div class="col-span-1">{{ $index + 1 }}</div>
                            <div class="col-span-2">{{ $session_day['session_day'] }}</div>
                            <div class="col-span-2">{{ $session_day['description'] ?? 'N/A' }}</div>
                            <div class="col-span-1">
                                <button wire:click="deleteSessionDay({{ $index }})"
                                    class="cursor-pointer hover:text-red-600 text-red-500 text-sm ">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="w-1/4 p-4 border-l border-gray-200 ">
            <form>
                <div class="text-primaryColor italic font-bold text-xl">
                    Add session day
                </div>

                <div class="mt-5">
                    <div class="text-primaryColor">
                        Session day<span class="text-red-500">*</span>
                    </div>
                    <div class="mt-2">
                        <input type="text" wire:model.lazy="add_session_day" placeholder="Day 1"
                            class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">

                        @error('add_session_day')
                            <div class="text-red-500 text-xs italic mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mt-2">
                    <div class="text-primaryColor">
                        Description
                    </div>
                    <div class="mt-2">
                        <input type="text" wire:model.lazy="add_session_day_desc"
                            class="bg-registrationInputFieldsBGColor w-full py-1 px-3 outline-primaryColor rounded-md border border-gray-200">
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 w-full rounded items-center text-sm cursor-pointer"
                        wire:click.prevent="addSessionDay">Add</button>
                    <button type="button"
                        class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 w-full rounded items-center text-sm cursor-pointer"
                        wire:click.prevent="resetAddSessionDayFields">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
