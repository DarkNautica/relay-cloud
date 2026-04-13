<x-guest-layout>
    <div style="text-align:center;margin-bottom:20px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:36px;height:36px;margin:0 auto 12px;display:block;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        <div style="font-size:18px;font-weight:700;margin-bottom:4px;">Verify your email address</div>
        <div style="font-size:13px;color:#8b8a98;">We sent a verification link to</div>
        <div style="font-size:14px;font-weight:600;color:#f1f0f5;margin-top:4px;">{{ Auth::user()->email }}</div>
    </div>

    <p style="color:#8b8a98;font-size:13px;margin-bottom:18px;text-align:center;line-height:1.6;">
        Click the link in your email to verify your account. If you didn't receive it, click below to resend.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert-box alert-success-box" style="margin-bottom:16px;">A new verification link has been sent to your email address.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom:12px;">
        @csrf
        <button type="submit" class="btn-submit">Resend Verification Email</button>
    </form>

    <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;color:#4f4e5c;">
        <span>Wrong email? <a href="{{ route('settings') }}" style="color:#8b5cf6;">Update in Settings</a></span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:#8b8a98;cursor:pointer;font-family:inherit;font-size:12px;">Log Out</button>
        </form>
    </div>
</x-guest-layout>
