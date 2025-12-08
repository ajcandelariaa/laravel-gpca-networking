<div>
    <h1 class="text-headingTextColor text-3xl font-bold">Notifications</h1>
    
    <!-- Floating Add Session Button -->
    <div class="fixed bottom-0 left-6 mb-5 z-50">
        <button type="button" wire:click.prevent="showAddNotification"
            class="w-14 h-14 rounded-full bg-primaryColor hover:bg-primaryColorHover text-white shadow-lg flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primaryColor">
            <i class="fa-solid fa-plus text-xl"></i>
        </button>
    </div>

    <div class="flex justify-between mt-5">
        <button type="button" wire:click.prevent="showAddNotification" wire:key="showAddNotification"
            class="bg-primaryColor hover:bg-primaryColorHover text-white rounded-lg text-sm w-36 h-10">Add
            notification</button>
    </div>

    @if (count($finalListOfNotifications) == 0)
        <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md">
            There are no notifications yet.
        </div>
    @else
        <p class="mt-5">Total notifications: {{ count($finalListOfNotifications) }}</p>

        <div class="shadow-lg my-5 bg-white rounded-md">
            <div
                class="grid grid-cols-12 pt-2 pb-2 mt-3 text-center items-center gap-10 text-sm text-white bg-primaryColor rounded-tl-md rounded-tr-md">
                <div class="col-span-1">No.</div>
                <div class="col-span-1">Date</div>
                <div class="col-span-1">Time</div>
                <div class="col-span-1">Type</div>
                <div class="col-span-2">Title</div>
                <div class="col-span-1">Subtitle</div>
                <div class="col-span-3">Message</div>
                <div class="col-span-1">Sent</div>
                <div class="col-span-1">Action</div>
            </div>
            @foreach ($finalListOfNotifications as $index => $finalListOfNotification)
                <div
                    class="grid grid-cols-12 gap-10 pt-2 pb-2 mb-1 text-center items-center text-sm {{ $index % 2 == 0 ? 'bg-registrationInputFieldsBGColor' : 'bg-registrationCardBGColor' }}">
                    <div class="col-span-1">{{ $index + 1 }}</div>
                    <div class="col-span-1">{{ $finalListOfNotification['send_date'] }}</div>
                    <div class="col-span-1">{{ $finalListOfNotification['send_time'] }}</div>
                    <div class="col-span-1">{{ $finalListOfNotification['type'] }}</div>
                    <div class="col-span-2">{{ $finalListOfNotification['title'] }}</div>
                    <div class="col-span-1">{{ $finalListOfNotification['subtitle'] ?? 'N/A' }}</div>
                    <div class="col-span-3 text-xs">{{ $finalListOfNotification['message'] ?? 'N/A' }}</div>
                    <div class="col-span-1">
                        @if ($finalListOfNotification['is_sent'])
                            <div
                                class="text-gray-700 bg-green-300 py-1 px-1 text-sm rounded-md">
                                Yes
                            </div>
                        @else
                            <div
                                class="text-gray-700 bg-red-300 py-1 px-1 text-sm rounded-md">
                                No
                            </div>
                        @endif
                    </div>

                    <div class="col-span-1 flex gap-3 items-center justify-center">
                        <button wire:click="showEditNotification({{ $index }})"
                            class="cursor-pointer hover:text-yellow-600 text-yellow-500 text-sm ">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button wire:click="deleteNotificationConfirmation({{ $index }})"
                            class="cursor-pointer hover:text-red-600 text-red-500 text-sm ">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($addNotificationForm)
        @include('livewire.event.notifications.add_notification')
    @endif

    @if ($editNotificationForm)
        @include('livewire.event.notifications.edit_notification')
    @endif
</div>
