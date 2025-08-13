<x-mail::message>
<p class="normal">Hi {{ $details['receiverName'] }},</p>

<p class="normal">The meeting request from <strong>{{ $details['requesterName'] }}</strong> has been cancelled.</p>

@if($details['cancelledReason'])
<p class="normal">Reason: "{{ $details['cancelledReason'] }}"</p>
@endif

<p class="normal">We apologize for any inconvenience this may cause.</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>
