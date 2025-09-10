<x-mail::message>
<img src="http://gpca.org.ae/conferences/anc/wp-content/uploads/2025/09/ANC-banner_540x118-px.jpg">

<p class="normal" style="margin-top: 15px;">Hi {{ $details['receiverName'] }},</p>

<p class="normal">The meeting request from <strong>{{ $details['requesterName'] }}</strong> has been cancelled.</p>

@if($details['cancelledReason'])
<p class="normal">Reason: "{{ $details['cancelledReason'] }}"</p>
@endif

<p class="normal">We apologize for any inconvenience this may cause.</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>
