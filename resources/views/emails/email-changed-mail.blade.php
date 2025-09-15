<x-mail::message>

<img src="https://gpca.org.ae/conferences/anc/wp-content/uploads/2025/09/ANC-banner_540x118-px-v2.jpg" alt="app" width="600" style="margin-top:25px;display:block;max-width:100%;width:100%;height:auto;border:0;outline:none;text-decoration:none;">

<p class="normal" style="margin-top: 15px;">Dear {{ $details['name'] }},</p>

<p class="normal" style="margin-top: 15px;">We hope this email finds you well. This is to inform you that your email address associated with the <strong>{{ $details['eventName'] }}</strong> account has been successfully changed. Please find below the updated details for accessing your account:</p>

<p class="normal" style="margin-top: 15px;"><strong>New Email Address:</strong> {{ $details['new_email_address'] }}</p>

<p class="normal" style="margin-top: 15px;">If you did not initiate this email address change, please contact Jhoanna Kilat immediately at <a href="mailto:jhoanna@gpca.org.ae">jhoanna@gpca.org.ae</a> or call +971 4 451 0666 ext. 151. Your account's security is our top priority, and we are here to assist you with any concerns you may have.</p>

<p class="normal" style="margin-top: 15px;">Thank you for your attention to this matter. We appreciate your cooperation in ensuring the security of your account.</p>

<p class="normal" style="margin-top: 15px;">Kind regards,</p>
<p class="normal" style="margin-top: 5px;">GPCA Team</p>
</x-mail::message>