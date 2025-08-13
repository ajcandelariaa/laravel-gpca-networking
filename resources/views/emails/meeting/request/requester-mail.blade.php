<x-mail::message>
<p class="normal">Hi {{ $details['requesterName'] }},</p>

<p class="normal" style="margin-top: 15px;">Your meeting request to <strong>{{ $details['receiverName'] }}</strong> has been successfully submitted via the <strong>{{ $details['eventName'] }}</strong> networking app.</p>

<p class="normal" style="margin-top: 15px;">Meeting Details:</p>
<ul class="list">
    <li style="margin-top: 5px;">Title: {{ $details['meetingTitle'] }}</li>
    <li style="margin-top: 5px;">Date: {{ $details['meetingDate'] }}</li>
    <li style="margin-top: 5px;">Time: {{ $details['meetingStartTime'] }} - {{ $details['meetingEndTime'] }}</li>
    <li style="margin-top: 5px;">Location: {{ $details['meetingLocation'] }}</li>
    <li style="margin-top: 5px;">Notes: {{ $details['meetingNotes'] ?? 'None' }}</li>
    
</ul>

<p class="normal" style="margin-top: 15px;">We’ve notified the other party. You will receive a notification once they respond to your request.</p>

<p class="normal" style="margin-top: 15px;">We look forward to helping you connect meaningfully during the event.</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>