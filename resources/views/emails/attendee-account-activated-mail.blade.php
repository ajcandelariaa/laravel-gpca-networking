<x-mail::message>
<p class="normal">Dear {{ $details['name'] }},</p>

<p class="normal" style="margin-top: 15px;">
Your account for <strong>{{ $details['eventName'] }}</strong> has been successfully activated.
</p>

<p class="normal" style="margin-top: 15px;">
You can now log in to the GPCA Events Networking app using your email address and the password you created.
</p>

<p class="normal" style="margin-top: 15px;">
If you encounter any issues accessing your account, please contact our support team at
<a href="mailto:jhoanna@gpca.org.ae">jhoanna@gpca.org.ae</a> or call +971 4 451 0666 ext. 151.
</p>

<p class="normal" style="margin-top: 15px;">We look forward to seeing you at the event!</p>

<p class="normal" style="margin-top: 15px;">Kind regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA Team</p>
</x-mail::message>
