@extends('admin.event.layouts.master')

@section('content')
    @livewire('meeting-room-partner-list', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
    ])
@endsection
