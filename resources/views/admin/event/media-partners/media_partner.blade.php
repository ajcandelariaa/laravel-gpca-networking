@extends('admin.event.layouts.master')

@section('content')
    @livewire('media-partner-details', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
        'mediaPartnerData' => $mediaPartnerData,
    ])
@endsection
