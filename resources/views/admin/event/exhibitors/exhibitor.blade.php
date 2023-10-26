@extends('admin.event.layouts.master')

@section('content')
    @livewire('exhibitor-details', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
        'exhibitorData' => $exhibitorData,
    ])
@endsection
