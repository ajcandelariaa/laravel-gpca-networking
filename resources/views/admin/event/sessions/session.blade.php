@extends('admin.event.layouts.master')

@section('content')
    @livewire('session-details', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
        'sessionData' => $sessionData,
    ])
@endsection
