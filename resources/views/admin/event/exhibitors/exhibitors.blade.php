@extends('admin.event.layouts.master')

@section('content')
    @livewire('exhibitor-list', [
        'eventId' => $eventId,
        'eventCategory' => $eventCategory,
    ])
@endsection
