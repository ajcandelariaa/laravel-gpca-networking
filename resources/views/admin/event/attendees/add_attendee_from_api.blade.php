@extends('admin.event.layouts.master')

@section('content')
    @livewire('add-attendee-from-api', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
    ])
@endsection
