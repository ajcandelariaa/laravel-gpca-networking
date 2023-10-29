@extends('admin.event.layouts.master')

@section('content')
    @livewire('session-list', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
    ])
@endsection
