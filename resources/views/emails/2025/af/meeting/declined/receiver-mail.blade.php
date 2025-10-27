<x-mail::message>

<img src="https://www.gpcaforum.com/wp-content/uploads/2025/10/AF-email-header.jpg" alt="app" width="600" style="margin-top:25px;display:block;max-width:100%;width:100%;height:auto;border:0;outline:none;text-decoration:none;">

<p class="normal" style="margin-top: 15px;">Hi {{ $details['receiverName'] }},</p>

<p class="normal" style="margin-top: 15px;">You have declined a meeting request from <strong>{{ $details['requesterName'] }}</strong> for the <strong>{{ $details['eventName'] }}</strong> networking event.</p>

@if($details['declinedReason'])
<p class="normal" style="margin-top: 15px;">Reason: "{{ $details['declinedReason'] }}"</p>
@endif

<p class="normal" style="margin-top: 15px;">We’ve notified the requester of your decision.</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>
