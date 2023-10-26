@extends('admin.event.layouts.master')

@section('content')
    @livewire('feature-list', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
    ])
@endsection
