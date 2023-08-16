@extends('admin.event.layouts.master')

@section('content')
    @livewire('attendee-details', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
        'attendeeData' => $attendeeData,
    ])
@endsection
