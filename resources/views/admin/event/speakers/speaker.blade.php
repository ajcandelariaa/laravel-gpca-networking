@extends('admin.event.layouts.master')

@section('content')
    @livewire('speaker-details', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
        'speakerData' => $speakerData,
    ])
@endsection
