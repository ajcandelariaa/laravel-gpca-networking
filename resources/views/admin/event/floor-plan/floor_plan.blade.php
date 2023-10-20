@extends('admin.event.layouts.master')

@section('content')
    @livewire('floor-plan-list', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
    ])
@endsection
