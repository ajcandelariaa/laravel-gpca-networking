<x-mail::message>

<p class="normal" style="margin-top: 15px;">Hi {{ $details['receiverName'] }},</p>

<p class="normal" style="margin-top: 15px;">You have declined a meeting request from <strong>{{ $details['requesterName'] }}</strong> for the <strong>{{ $details['eventName'] }}</strong> networking event.</p>

@if($details['declinedReason'])
<p class="normal" style="margin-top: 15px;">Reason: "{{ $details['declinedReason'] }}"</p>
@endif

<p class="normal" style="margin-top: 15px;">We’ve notified the requester of your decision.</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>
