@extends('admin.event.layouts.master')

@section('content')
    @livewire('speakers-list', ['eventId' => $eventId, 'eventCategory' => $eventCategory])
@endsection