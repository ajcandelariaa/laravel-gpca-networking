<x-mail::message>
<p class="normal">Dear {{ $details['name'] }},</p>

<p class="normal" style="margin-top: 15px;">Enhance your experience at the {{ $details['eventName'] }} by staying connected and engaged through our official networking app. The GPCA networking app allows you to easily connect with fellow attendees, access session details, schedule meetings, and stay updated on all event activities.</p>

<p class="normal" style="margin-top: 15px;"><strong>To access the app, here’s your login details:</strong></p>
<p class="normal">Username: {{ $details['username'] }}</p>
<p class="normal">Password: {{ $details['password'] }}</p>

<p class="normal" style="margin-top: 15px;">Please consider these login details strictly confidential and refrain from sharing them with others. Unauthorized sharing of this information may compromise your access to the event platform. We appreciate your cooperation in keeping these credentials secure.</p>

<p class="normal" style="margin-top: 15px;"><strong>Event Details:</strong></p>
<p class="normal">Event Name: {{ $details['eventName'] }}</p>
<p class="normal">Event Date: {{ $details['eventDate'] }}</p>
<p class="normal">Event Location: {{ $details['eventLocation'] }}</p>

<p class="normal" style="margin-top: 15px;"><strong>To get started:</strong></p>
<ol class="list">
    <li>Download the GPCA Events Networking app from Google Play Store or the Apple App Store</li>
    <li>Select {{ $details['eventName'] }} from the event list.</li>
    <li>Enter your username and the temporary password provided above.</li>
    <li>Once logged in, you will have access to all event sessions and features.</li>
</ol>

<p class="normal" style="margin-top: 15px;">Plan your participation in advance and start connecting with fellow attendees. Explore the networking app today to engage with industry peers, expand your professional network, and gain valuable insights from industry leaders. Make the most of the opportunities available at this year’s conference.</p>

<p class="normal" style="margin-top: 15px;">For any assistance and technical issues, please contact <a href="mailto:jhoanna@gpca.org.ae">jhoanna@gpca.org.ae</a></p>

<p class="normal" style="margin-top: 15px;">We are excited to welcome you to the <strong>{{ $details['eventName'] }}</strong> in <strong>Riyadh</strong> and ensure a rewarding and engaging conference experience.</p>

<p class="normal" style="margin-top: 15px;">Regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA Team</p>
</x-mail::message>