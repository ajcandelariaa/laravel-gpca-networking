@extends('admin.event.layouts.master')

@section('content')
    @livewire('attendees-list', ['eventId' => $eventId, 'eventCategory' => $eventCategory])
@endsection