@extends('admin.event.layouts.master')

@section('content')
    @livewire('event-details', ['eventData' => $eventData])
@endsection