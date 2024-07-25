@extends('admin.event.layouts.master')

@section('content')
    @livewire('notification-list', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
    ])
@endsection
