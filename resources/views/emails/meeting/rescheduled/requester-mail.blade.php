<x-mail::message>
<img src="http://gpca.org.ae/conferences/anc/wp-content/uploads/2025/09/ANC-banner_540x118-px.jpg">

<p class="normal" style="margin-top: 15px;">Hi {{ $details['requesterName'] }},</p>

<p class="normal">You have successfully rescheduled your meeting with <strong>{{ $details['receiverName'] }}</strong>.</p>

<p class="normal">Updated Meeting Details:</p>
<ul class="list">
    <li>Title: {{ $details['meetingTitle'] }}</li>
    <li>New Date: {{ $details['meetingDate'] }}</li>
    <li>New Time: {{ $details['meetingStartTime'] }} - {{ $details['meetingEndTime'] }}</li>
    <li>Location: {{ $details['meetingLocation'] }}</li>
</ul>

<p class="normal">They have been notified and will respond shortly.</p>

<p class="normal" style="margin-top: 15px;">Best regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA </p>
</x-mail::message>