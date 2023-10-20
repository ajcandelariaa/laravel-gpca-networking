@extends('admin.event.layouts.master')

@section('content')
    @livewire('media-partner-list', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
    ])
@endsection
