@extends('admin.event.layouts.master')

@section('content')
    @livewire('sponsor-type-list', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
    ])
@endsection
