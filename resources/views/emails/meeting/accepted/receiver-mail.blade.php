<x-mail::message>
<p class="normal">Hi {{ $details['receiverName'] }},</p>

<p class="normal" style="margin-top: 15px;">You have accepted a meeting request from <strong>{{ $details['requesterName'] }}</strong> for the <strong>{{ $details['eventName'] }}</strong> networking event.</p>

<p class="normal" style="margin-top: 15px;">Meeting Details:</p>
<ul class="list">
    <li>Title: {{ $details['meetingTitle'] }}</li>
    <li>Date: {{ $details['meetingDate'] }}</li>
    <li>Time: {{ $details['meetingStartTime'] }} - {{ $details['meetingEndTime'] }}</li>
    <li>Location: {{ $details['meetingLocation'] }}</li>
</ul>

<p class="normal" style="margin-top: 15px;">We've notified the requester about your response. Looking forward to your meeting!</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>
