<h2>Password Reset Request</h2>

<p>Hello {{ $user->name }},</p>

<p>You requested a password reset. Click the button below:</p>

<p>
    <a href="{{ $url }}"
       style="background:#000;color:#fff;padding:12px 20px;text-decoration:none;border-radius:6px;">
        Reset Password
    </a>
</p>

<p>If you did not request this, ignore this email.</p>
