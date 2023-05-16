<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $pageTitle }}</title>

    {{-- FONT AWESOME LINK --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/event/master.css') }}">

    {{-- VITE --}}
    @vite('resources/css/app.css')

    {{-- LIVEWIRE --}}
    @livewireStyles()
</head>

<body>
    <div class="flex">
        <div class="bg-primaryColor h-full min-h-screen p-5 pt-8 duration-300 sidebar-full" id="sidebar">
            <div class="flex justify-center">
                <img src="{{ asset('assets/images/gpca-networking-logo-inverted.png') }}" class="duration-300 sidebar-full-image" alt="logo" id="sidebar-image">
            </div>
            <div class="mt-10 text-white flex flex-col gap-3" id="main-navigation">
                <a href="#" class="flex items-center gap-5 p-2 hover:bg-sideBarBGColorHover rounded-md duration-500">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <p>Dashboard</p>
                </a>
                <a href="#" class="flex items-center gap-5 p-2 hover:bg-sideBarBGColorHover rounded-md duration-500">
                    <i class="fa-solid fa-calendar-days  w-5 text-center"></i>
                    <p>Event</p>
                </a>
                <a href="#" class="flex items-center gap-5 p-2 hover:bg-sideBarBGColorHover rounded-md duration-500">
                    <i class="fa-solid fa-people-group w-5 text-center"></i>
                    <p>Attendees</p>
                </a>
                <a href="#" class="flex items-center gap-5 p-2 hover:bg-sideBarBGColorHover rounded-md duration-500">
                    <i class="fa-solid fa-microphone w-5 text-center"></i>
                    <p>Speakers</p>
                </a>
                <a href="#" class="flex items-center gap-5 p-2 hover:bg-sideBarBGColorHover rounded-md duration-500">
                    <i class="fa-regular fa-newspaper w-5 text-center"></i>
                    <p>Agenda</p>
                </a>
                <a href="#" class="flex items-center gap-5 p-2 hover:bg-sideBarBGColorHover rounded-md duration-500">
                    <i class="fa-solid fa-handshake w-5 text-center"></i>
                    <p>Sponsors</p>
                </a>
                <a href="#" class="flex items-center gap-5 p-2 hover:bg-sideBarBGColorHover rounded-md duration-500">
                    <i class="fa-solid fa-building-user w-5 text-center"></i>
                    <p>Exhibitors</p>
                </a>
                <a href="#" class="flex items-center gap-5 p-2 hover:bg-sideBarBGColorHover rounded-md duration-500">
                    <i class="fa-solid fa-users-between-lines w-5 text-center"></i>
                    <p>Meeting room partners</p>
                </a>
                <a href="#" class="flex items-center gap-5 p-2 hover:bg-sideBarBGColorHover rounded-md duration-500">
                    <i class="fa-solid fa-photo-film w-5 text-center"></i>
                    <p>Media partners</p>
                </a>
                <a href="#" class="flex items-center gap-5 p-2 hover:bg-sideBarBGColorHover rounded-md duration-500">
                    <i class="fa-solid fa-location-dot w-5 text-center"></i>
                    <p>Venue</p>
                </a>
                <a href="#" class="flex items-center gap-5 p-2 hover:bg-sideBarBGColorHover rounded-md duration-500">
                    <i class="fa-solid fa-map-location-dot w-5 text-center"></i>
                    <p>Floor plan</p>
                </a>
            </div>
        </div>
        <div class="flex-auto">
            <div class="bg-headerBGColor text-white flex items-center justify-between py-4 px-7">
                <div class="flex items-center gap-5">
                    <i class="fa-solid fa-bars cursor-pointer" onclick="menuButtonClicked()"></i>
                    <p>Admin Panel - 14th GPCA Supply Chain conference</p>
                </div>
                <div class="flex gap-5">
                    <a href="{{ route('admin.main-dashboard.view') }}" class="hover:underline">Home</a>
                    <a href="{{ route('admin.logout') }}" class="hover:underline">Logout</a>
                </div>
            </div>
            <div class="p-7">
                @yield('content')
            </div>
        </div>
    </div>

    @livewireScripts()
    <script src="{{ asset('js/main/master.js') }}"></script>
</body>

</html>
