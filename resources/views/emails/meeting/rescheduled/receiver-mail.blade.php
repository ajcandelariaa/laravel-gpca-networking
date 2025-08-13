<x-mail::message>
<p class="normal">Hi {{ $details['receiverName'] }},</p>

<p class="normal">A meeting request from <strong>{{ $details['requesterName'] }}</strong> has been rescheduled in the <strong>{{ $details['eventName'] }}</strong> networking app.</p>

<p class="normal">Updated Meeting Details:</p>
<ul class="list">
    <li>Title: {{ $details['meetingTitle'] }}</li>
    <li>New Date: {{ $details['meetingDate'] }}</li>
    <li>New Time: {{ $details['meetingStartTime'] }} - {{ $details['meetingEndTime'] }}</li>
    <li>Location: {{ $details['meetingLocation'] }}</li>
</ul>

@if($details['isAttendee'])
<p class="normal" style="margin-top: 15px;">To respond to the rescheduled request, please open the networking app and accept or decline the request.</p>
@else
<p class="normal" style="margin-top: 15px;">To respond to the rescheduled request, click the button below.</p>
<x-mail::button :url="$details['meetingRespondLink']" color="registration">
Respond to Meeting Request
</x-mail::button>
@endif

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>