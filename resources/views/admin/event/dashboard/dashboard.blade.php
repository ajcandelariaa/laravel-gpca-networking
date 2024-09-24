@extends('admin.event.layouts.master')

@section('content')
    <h1 class="text-headingTextColor text-2xl font-bold">Dashboard</h1>

    <div class="grid grid-cols-1 gap-4 text-center mt-10">
        <div class="bg-blue-500 p-4 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-white">Total attendees</h3>
            <p class="text-4xl font-bold text-white">{{ $finalData['totalAttendees'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 text-center mt-10">
        <div class="bg-blue-500 p-4 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-white">Total speakers</h3>
            <p class="text-4xl font-bold text-white">{{ $finalData['totalSpeakers'] }}</p>
        </div>
        <div class="bg-blue-500 p-4 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-white">Total sessions</h3>
            <p class="text-4xl font-bold text-white">{{ $finalData['totalSessions'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 text-center mt-10">
        <div class="bg-teal-500 p-4 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-white">Total sponsors</h3>
            <p class="text-4xl font-bold text-white">{{ $finalData['totalSponsors'] }}</p>
        </div>
        <div class="bg-teal-500 p-4 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-white">Total exhibitors</h3>
            <p class="text-4xl font-bold text-white">{{ $finalData['totalExhibitors'] }}</p>
        </div>
        <div class="bg-teal-500 p-4 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-white">Total meeting room partners</h3>
            <p class="text-4xl font-bold text-white">{{ $finalData['totalMrps'] }}</p>
        </div>
        <div class="bg-teal-500 p-4 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-white">Total media partners</h3>
            <p class="text-4xl font-bold text-white">{{ $finalData['totalMps'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 text-center mt-10">
        <div class="bg-green-500 p-4 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-white">Total conversations</h3>
            <p class="text-4xl font-bold text-white">{{ $finalData['totalConversations'] }}</p>
        </div>
        <div class="bg-green-500 p-4 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-white">Tota chats initiated</h3>
            <p class="text-4xl font-bold text-white">{{ $finalData['totalChats'] }}</p>
        </div>
        <div class="bg-green-500 p-4 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-white">Tota logins</h3>
            <p class="text-4xl font-bold text-white">{{ $finalData['totalLogins'] }}</p>
        </div>
    </div>
@endsection
