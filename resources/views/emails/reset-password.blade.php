<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="margin:0;padding:0;background-color:#0c0c0e;font-family:'Inter',Helvetica,Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0c0c0e;padding:40px 20px;">
<tr><td align="center">
<table width="480" cellpadding="0" cellspacing="0" style="background-color:#111115;border:1px solid #27272f;border-radius:12px;overflow:hidden;">
    <tr><td style="padding:32px 32px 24px;text-align:center;">
        <div style="font-size:20px;font-weight:700;color:#f1f0f5;margin-bottom:4px;">
            &#9889; Relay Cloud
        </div>
    </td></tr>
    <tr><td style="padding:0 32px 24px;">
        <h1 style="font-size:22px;font-weight:700;color:#f1f0f5;margin:0 0 12px;text-align:center;">Reset your password</h1>
        <p style="font-size:14px;line-height:1.6;color:#8b8a98;margin:0 0 24px;text-align:center;">
            Click the button below to set a new password for your Relay Cloud account.
        </p>
        <table width="100%" cellpadding="0" cellspacing="0"><tr><td align="center">
            <a href="{{ $url }}" style="display:inline-block;padding:12px 32px;background-color:#7c3aed;color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;border-radius:8px;">
                Reset Password
            </a>
        </td></tr></table>
        <p style="font-size:12px;color:#4f4e5c;margin:20px 0 0;text-align:center;">
            This link expires in 60 minutes. If you didn't request a password reset, no action is needed.
        </p>
    </td></tr>
    <tr><td style="padding:16px 32px;border-top:1px solid #27272f;">
        <p style="font-size:11px;color:#4f4e5c;margin:0;text-align:center;line-height:1.6;">
            If the button doesn't work, copy and paste this URL:<br>
            <span style="color:#8b8a98;word-break:break-all;">{{ $url }}</span>
        </p>
    </td></tr>
</table>
</td></tr>
</table>
</body>
</html>
