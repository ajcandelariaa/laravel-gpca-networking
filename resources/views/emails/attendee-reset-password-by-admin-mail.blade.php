<x-mail::message>
<p class="normal">Dear {{ $details['name'] }},</p>

<p class="normal" style="margin-top: 15px;">We hope this email finds you well. This is to inform you that your account's password on <strong>{{ $details['eventName'] }}</strong> has been reset by our administrator in response to recent security measures. Please find below the details for accessing your account:</p>

<p class="normal" style="margin-top: 15px;">Username: {{ $details['username'] }}</p>
<p class="normal">Temporary Password: {{ $details['newPassword'] }}</p>

<p class="normal" style="margin-top: 15px;"><strong>Please Note:</strong></p>
<p class="normal">For security reasons, we recommend that you change the temporary password immediately after logging in. To do so, please follow these steps:</p>
<ol class="list">
    <li style="margin-top: 5px;">Open the GPCA Networking App.</li>
    <li>Choose {{ $details['eventName'] }}</li>
    <li>Enter your username and the temporary password provided above.</li>
    <li>Once logged in, navigate to your account settings.</li>
    <li>Choose the "Change Password" option and follow the prompts to set a new password of your choice.</li>
</ol>

<p class="normal" style="margin-top: 15px;">If you have any difficulties accessing your account or if you did not initiate this password reset, please contact Jhoanna Kilat at <a href="mailto:jhoanna@gpca.org.ae">jhoanna@gpca.org.ae</a> or call +971 4 451 0666 ext. 151. Your account's security is our top priority, and we are here to assist you with any concerns you may have.</p>

<p class="normal" style="margin-top: 15px;">Thank you for your prompt attention to this matter. We appreciate your cooperation in ensuring the security of your account.</p>

<p class="normal" style="margin-top: 15px;">Kind regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA Team</p>
</x-mail::message>