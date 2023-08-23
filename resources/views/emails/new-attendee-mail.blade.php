<x-mail::message>
<p>Dear {{ $details['name'] }},</p>

<p>We are thrilled to welcome you to <strong>{{ $details['eventName'] }}</strong>! Get ready for an incredible networking opportunity at the GPCA event. As you prepare to join us, we want to provide you with the information you need to access the event platform.</p>

<span><strong>Here are your login details:</strong></span>
<br>
<span>Username: {{ $details['username'] }}</span>
<br>
<span>Password: {{ $details['password'] }}</span>
<br>

<p>Please keep this information confidential and do not share it with anyone. These credentials will grant you access to the event platform, where you can explore the schedule, join sessions, and engage with other attendees.</p>

<span><strong>Event Details:</strong></span>
<br>
<span>Event Name: {{ $details['eventName'] }}</span>
<br>
<span>Event Date: {{ $details['eventDate'] }}</span>
<br>
<span>Event Location: {{ $details['eventLocation'] }}</span>
<br>

<span><strong>To get started:</strong></span>
<ol>
    <li>Open the GPCA Networking App.</li>
    <li>Choose {{ $details['eventName'] }}</li>
    <li>Enter your username and the temporary password provided above.</li>
    <li>Once logged in, you'll have access to all the event sessions and features.</li>
</ol>

<p>The GPCA networking event promises to be a fantastic occasion for you to connect with peers, engage in insightful discussions, and expand your professional network.</p>

<p>If you encounter any technical issues, please contact Jhoanna Kilat at <a href="mailto:jhoanna@gpca.org.ae">jhoanna@gpca.org.ae</a> or call +971 4 451 0666 ext. 151.</p>

<p>We encourage you to explore the event agenda and plan your sessions ahead of time to make the most of your experience. This is a great opportunity to learn, connect, and engage with fellow attendees who share your interests.</p>

<p>Thank you for choosing to be a part of <strong>{{ $details['eventName'] }}</strong>. We're excited to have you join us for the GPCA networking event and look forward to your active participation.</p>

<p>Kind regards,</p>

<p>GPCA Team</p>
</x-mail::message>