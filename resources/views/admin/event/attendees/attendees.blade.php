@extends('admin.event.layouts.master')

@section('content')
    @livewire('attendees', ['eventId' => $eventId, 'eventCategory' => $eventCategory])
@endsection