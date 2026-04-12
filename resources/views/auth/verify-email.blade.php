<x-guest-layout>
    <p style="color:#94a3b8;font-size:14px;margin-bottom:20px;">
        Thanks for signing up! Please verify your email address by clicking the link we just sent you.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#22c55e;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:14px;">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div style="display:flex;align-items:center;justify-content:space-between;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary" style="width:auto;">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-family:inherit;font-size:14px;">Log Out</button>
        </form>
    </div>
</x-guest-layout>
