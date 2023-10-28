@extends('admin.event.layouts.master')

@section('content')
    @livewire('sponsor-details', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
        'sponsorData' => $sponsorData,
    ])
@endsection
