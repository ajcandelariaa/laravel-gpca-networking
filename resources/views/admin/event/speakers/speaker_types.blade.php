@extends('admin.event.layouts.master')

@section('content')
    @livewire('speaker-type-list', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
    ])
@endsection
