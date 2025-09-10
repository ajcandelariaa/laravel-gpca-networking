<x-mail::message>
<p class="normal">Dear {{ $details['name'] }},</p>

<p class="normal" style="margin-top: 15px;">Enhance your experience at the 15<sup>th</sup> GPCA Agri-Nutrients Conference by staying connected and engaged through our official networking app. The GPCA Networking App enables you to easily connect with fellow attendees, access session details, explore participating companies, and stay updated on all event activities.</p>

<p class="normal" style="margin-top: 15px;"><strong>Event Details:</strong></p>
<p class="normal">Event Name: {{ $details['eventName'] }}</p>
<p class="normal">Event Date: 29 September - 1 October 2025</p>
<p class="normal">Event Location: {{ $details['eventLocation'] }}</p>

<p class="normal" style="margin-top: 15px;"><strong>How to activate your account:</strong></p>
<ol class="list">
    <li>Download the GPCA Events Networking app:
        <ul>
            <li>iOS - <a href="https://apps.apple.com/us/app/gpca-events-networking/id6639614793" target="_blank">www.apps.apple.com</a></li>
            <li>Android - <a href="https://play.google.com/store/apps/details?id=com.gpcanetworking2.app" target="_blank">www.play.google.com</a></li>
        </ul>
    </li>
    <li>Select 15<sup>th</sup> GPCA Agri-Nutrients Conference from the event list.</li>
    <li>Tap <strong>Activate your account</strong>, then enter your email: <strong>{{ $details['email_address'] }}</strong>.</li>
    <li>We’ll email you a 6-digit OTP. Enter it in the app and set your new password.</li>
    <li>Then it will redirect you to the login screen, enter your email and password you created.</li>
    <li>Once logged in, you will have access to all event sessions and features.</li>
</ol>

<img src="https://gpca.org.ae/conferences/anc/wp-content/uploads/2025/09/anc-welcome-email-banner.png" alt="app" width="600" style="margin-top:25px;display:block;max-width:100%;width:100%;height:auto;border:0;outline:none;text-decoration:none;">

<p class="normal" style="margin-top: 15px;">Plan your participation in advance and start connecting with fellow attendees. Explore the networking app today to engage with industry peers, expand your professional network, and gain valuable insights from industry leaders. Make the most of the opportunities available at this year’s conference.</p>

<p class="normal" style="margin-top: 15px;">For any assistance and technical issues, please contact <a href="mailto:jhoanna@gpca.org.ae">jhoanna@gpca.org.ae</a></p>

<p class="normal" style="margin-top: 15px;">We are excited to welcome you to the <strong>15<sup>th</sup> GPCA Agri-Nutrients Conference</strong> in <strong>Abu Dhabi, UAE</strong> and ensure a rewarding and engaging conference experience.</p>

<p class="normal" style="margin-top: 15px;">Regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA Team</p>
</x-mail::message>