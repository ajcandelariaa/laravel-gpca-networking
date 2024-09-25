@extends('admin.event.layouts.master')

@section('content')
    @livewire('manage-welcome-email-notification', ['eventId' => $eventId, 'eventCategory' => $eventCategory])
@endsection