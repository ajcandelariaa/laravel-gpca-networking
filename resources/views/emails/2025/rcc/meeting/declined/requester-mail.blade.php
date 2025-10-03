<x-mail::message>

<p class="normal" style="margin-top: 15px;">Hi {{ $details['requesterName'] }},</p>

<p class="normal" style="margin-top: 15px;">Your meeting request to <strong>{{ $details['receiverName'] }}</strong> has been declined.</p>

@if($details['declinedReason'])
<p class="normal">Reason: "{{ $details['declinedReason'] }}"</p>
@endif

<p class="normal">You may consider proposing a new meeting at a different time or with another attendee.</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>