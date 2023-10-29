<div>
    <a href="{{ route('admin.event.sessions.view', ['eventCategory' => $event->category, 'eventId' => $event->id]) }}"
        class="bg-red-500 hover:bg-red-400 text-white font-medium py-2 px-5 rounded inline-flex items-center text-sm">
        <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
        <span>List of sessions</span>
    </a>

    <h1 class="text-headingTextColor text-3xl font-bold mt-5">Session details</h1>
</div>
