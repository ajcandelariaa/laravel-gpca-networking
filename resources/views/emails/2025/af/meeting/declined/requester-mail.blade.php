<x-mail::message>

<img src="https://www.gpcaforum.com/wp-content/uploads/2025/10/AF-email-header-v2.jpg" alt="app" width="600" style="margin-top:25px;display:block;max-width:100%;width:100%;height:auto;border:0;outline:none;text-decoration:none;">

<p class="normal" style="margin-top: 15px;">Hi {{ $details['requesterName'] }},</p>

<p class="normal" style="margin-top: 15px;">Your meeting request to <strong>{{ $details['receiverName'] }}</strong> has been declined.</p>

@if($details['declinedReason'])
<p class="normal">Reason: "{{ $details['declinedReason'] }}"</p>
@endif

<p class="normal">You may consider proposing a new meeting at a different time or with another attendee.</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>