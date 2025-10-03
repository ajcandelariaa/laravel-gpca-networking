<x-mail::message>

<p class="normal" style="margin-top: 15px;">Hi {{ $details['requesterName'] }},</p>

<p class="normal">Your meeting request to <strong>{{ $details['receiverName'] }}</strong> has expired due to no response before the proposed meeting time.</p>

<p class="normal">You may try sending a new meeting request if you're still interested in meeting.</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>
