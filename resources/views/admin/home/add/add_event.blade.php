@extends('admin.home.layouts.master')

@section('content')
    <div class="container mx-auto my-4">
        <a href="{{ route('admin.events.view') }}"
            class="bg-red-500 hover:bg-red-700 text-white font-medium py-2 px-5 rounded-md inline-flex items-center text-sm">
            <span class="mr-2"><i class="fa-sharp fa-solid fa-arrow-left"></i></span>
            <span>Cancel</span>
        </a>

        <div class="shadow-lg bg-white rounded-md container mx-auto mt-5 mb-10">
            <form id="add_form" class="add-event-form" action="{{ route('admin.add.event.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="p-5">
                    @include('admin.home.add.event_details')
                    @include('admin.home.add.event_assets')
    
                    <div class="text-center mt-10">
                        <button id="add_btn" type="submit" 
                            class="bg-primaryColor hover:bg-primaryColorHover text-white font-medium py-2 px-10 rounded inline-flex items-center text-sm cursor-pointer">Publish</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection