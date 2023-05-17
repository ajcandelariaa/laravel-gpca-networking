@extends('admin.home.layouts.master')

@section('content')
    <div class="container mx-auto my-4">
        <a href="{{ route('admin.events.view') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
            <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
            <span>Manage Event</span>
        </a>
    </div>
@endsection