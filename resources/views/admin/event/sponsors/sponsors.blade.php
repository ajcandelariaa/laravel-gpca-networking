@extends('admin.event.layouts.master')

@section('content')
    @livewire('sponsor-list', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
    ])
@endsection
