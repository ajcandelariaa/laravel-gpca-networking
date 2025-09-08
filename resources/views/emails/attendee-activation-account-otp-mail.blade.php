<x-mail::message>
<p class="normal">Dear {{ $details['name'] }},</p>

<p class="normal" style="margin-top: 15px;">Welcome to <strong>{{ $details['eventName'] }}</strong>!</p>

<p class="normal" style="margin-top: 15px;">To activate your account, please use the following One-Time Password (OTP):</p>

<p class="normal" style="margin-top: 15px;"><strong>OTP:</strong> {{ $details['otp'] }}</p>

<p class="normal" style="margin-top: 15px;">This OTP is valid for 10 minutes. Please enter this OTP in the app to activate your account. If you did not request account activation, please disregard this email. If you continue to receive such emails or have any concerns, please contact Jhoanna Kilat immediately at <a href="mailto:jhoanna@gpca.org.ae">jhoanna@gpca.org.ae</a> or call +971 4 451 0666 ext. 151.</p>

<p class="normal" style="margin-top: 15px;">Thank you for your attention to this matter. We appreciate your cooperation in ensuring the security of your account.</p>

<p class="normal" style="margin-top: 15px;">Kind regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA Team</p>
</x-mail::message>
