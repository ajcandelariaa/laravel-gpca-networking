@extends('admin.home.layouts.master')

@section('content')
    <div class="container mx-auto my-4">
        <a href="{{ route('admin.add-event.view') }}"
            class="bg-primaryColor hover:bg-primaryColorHover text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
            <span class="mr-2"><i class="fas fa-plus"></i></span>
            <span>Add Event</span>
        </a>

        @if ($events->isNotEmpty())
            1
        @else
            <div class="bg-red-400 text-white text-center py-3 mt-5 rounded-md container mx-auto">
                There are no events yet.
            </div>
        @endif
    </div>
@endsection