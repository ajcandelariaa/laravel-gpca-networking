@extends('admin.event.layouts.master')

@section('content')
    @livewire('meeting-room-partner-details', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
        'meetingRoomPartnerData' => $meetingRoomPartnerData,
    ])
@endsection
