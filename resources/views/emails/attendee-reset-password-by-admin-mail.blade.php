<x-mail::message>
<p>Dear {{ $details['name'] }},</p>

<p>We hope this email finds you well. This is to inform you that your account's password on <strong>{{ $details['eventName'] }}</strong> has been reset by our administrator in response to recent security measures. Please find below the details for accessing your account:</p>

<span>Username: {{ $details['username'] }}</span>
<br>
<span>Temporary Password: {{ $details['newPassword'] }}</span>
<br>

<span><strong>Please Note:</strong></span>
<br>
<span>For security reasons, we recommend that you change the temporary password immediately after logging in. To do so, please follow these steps: </span>
<br>

<ol>
    <li>Open the GPCA Networking App.</li>
    <li>Choose {{ $details['eventName'] }}</li>
    <li>Enter your username and the temporary password provided above.</li>
    <li>Once logged in, navigate to your account settings.</li>
    <li>Choose the "Change Password" option and follow the prompts to set a new password of your choice.</li>
</ol>

<p>If you have any difficulties accessing your account or if you did not initiate this password reset, please contact Jhoanna Kilat at <a href="mailto:jhoanna@gpca.org.ae">jhoanna@gpca.org.ae</a> or call +971 4 451 0666 ext. 151. Your account's security is our top priority, and we are here to assist you with any concerns you may have.</p>

<p>Thank you for your prompt attention to this matter. We appreciate your cooperation in ensuring the security of your account.</p>

<p>Kind regards,</p>

<p>GPCA Team</p>
</x-mail::message>