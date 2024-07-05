<x-mail::message>
<p class="normal">Dear {{ $details['name'] }},</p>

<p class="normal" style="margin-top: 15px;">We are thrilled to welcome you to <strong>{{ $details['eventName'] }}</strong>! Get ready for an incredible networking opportunity at the GPCA event. As you prepare to join us, we want to provide you with the information you need to access the event platform.</p>

<p class="normal" style="margin-top: 15px;"><strong>Here are your login details:</strong></p>
<p class="normal">Username: {{ $details['username'] }}</p>
<p class="normal">Password: {{ $details['password'] }}</p>

<p class="normal" style="margin-top: 15px;">Please keep this information confidential and do not share it with anyone. These credentials will grant you access to the event platform, where you can explore the schedule, join sessions, and engage with other attendees.</p>

<p class="normal" style="margin-top: 15px;"><strong>Event Details:</strong></p>
<p class="normal">Event Name: {{ $details['eventName'] }}</p>
<p class="normal">Event Date: {{ $details['eventDate'] }}</p>
<p class="normal">Event Location: {{ $details['eventLocation'] }}</p>

<p class="normal" style="margin-top: 15px;"><strong>To get started:</strong></p>
<ol class="list">
    <li>Open the GPCA Networking App.</li>
    <li>Choose {{ $details['eventName'] }}</li>
    <li>Enter your username and the temporary password provided above.</li>
    <li>Once logged in, you'll have access to all the event sessions and features.</li>
</ol>

<p class="normal" style="margin-top: 15px;">The GPCA networking event promises to be a fantastic occasion for you to connect with peers, engage in insightful discussions, and expand your professional network.</p>

<p class="normal" style="margin-top: 15px;">If you encounter any technical issues, please contact Jhoanna Kilat at <a href="mailto:jhoanna@gpca.org.ae">jhoanna@gpca.org.ae</a> or call +971 4 451 0666 ext. 151.</p>

<p class="normal" style="margin-top: 15px;">We encourage you to explore the event agenda and plan your sessions ahead of time to make the most of your experience. This is a great opportunity to learn, connect, and engage with fellow attendees who share your interests.</p>

<p class="normal" style="margin-top: 15px;">Thank you for choosing to be a part of <strong>{{ $details['eventName'] }}</strong>. We're excited to have you join us for the GPCA networking event and look forward to your active participation.</p>

<p class="normal" style="margin-top: 15px;">Kind regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA Team</p>
</x-mail::message>