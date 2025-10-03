<x-mail::message>

<p class="normal" style="margin-top: 15px;">Hi {{ $details['requesterName'] }},</p>

<p class="normal">You have successfully cancelled your meeting request with <strong>{{ $details['receiverName'] }}</strong>.</p>

@if($details['cancelledReason'])
<p class="normal">Reason: "{{ $details['cancelledReason'] }}"</p>
@endif

<p class="normal">They have been notified of the cancellation.</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>