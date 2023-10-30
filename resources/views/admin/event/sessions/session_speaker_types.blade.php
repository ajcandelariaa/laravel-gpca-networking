@extends('admin.event.layouts.master')

@section('content')
    @livewire('session-speaker-type-list', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
        'sessionId' => $sessionId,
    ])
@endsection
