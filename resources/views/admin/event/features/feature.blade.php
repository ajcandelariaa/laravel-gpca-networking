@extends('admin.event.layouts.master')

@section('content')
    @livewire('feature-details', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
        'featureData' => $featureData,
    ])
@endsection
