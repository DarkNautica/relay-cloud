<x-guest-layout>
    <p style="color:#8b8a98;font-size:13px;margin-bottom:16px;">Verify your email by clicking the link we sent you.</p>
    @if(session('status') == 'verification-link-sent')
        <div class="alert-box alert-success-box">A new verification link has been sent.</div>
    @endif
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-submit" style="width:auto;padding:9px 18px;">Resend Email</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:#8b8a98;cursor:pointer;font-family:inherit;font-size:13px;">Log Out</button>
        </form>
    </div>
</x-guest-layout>
