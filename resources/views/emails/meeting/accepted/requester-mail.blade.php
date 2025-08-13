<x-mail::message>
<p class="normal">Hi {{ $details['requesterName'] }},</p>

<p class="normal" style="margin-top: 15px;">Your meeting request to <strong>{{ $details['receiverName'] }}</strong> has been accepted via the <strong>{{ $details['eventName'] }}</strong> networking app.</p>

<p class="normal">Meeting Details:</p>
<ul class="list">
    <li>Title: {{ $details['meetingTitle'] }}</li>
    <li>Date: {{ $details['meetingDate'] }}</li>
    <li>Time: {{ $details['meetingStartTime'] }} - {{ $details['meetingEndTime'] }}</li>
    <li>Location: {{ $details['meetingLocation'] }}</li>
</ul>

<p class="normal" style="margin-top: 15px;">We hope your meeting goes well and leads to meaningful connections.</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>
