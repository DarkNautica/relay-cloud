@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Set up two-factor authentication</h1>
        <p class="page-sub">Scan the QR code and enter the confirmation code to enable 2FA.</p>
    </div>
</div>

<div class="card card-pad" style="max-width:600px;">
    <div style="margin-bottom:24px;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
            <span style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:var(--accent-glow);color:var(--accent-light);font-size:12px;font-weight:700;border:1px solid rgba(124,58,237,0.2);">1</span>
            <span style="font-size:14px;font-weight:600;">Scan this QR code with your authenticator app</span>
        </div>
        <p style="font-size:13px;color:var(--text-secondary);margin-bottom:16px;">Use Google Authenticator, Authy, 1Password, or any TOTP-compatible app.</p>
        <div style="background:white;padding:16px;border-radius:12px;display:inline-block;">
            {!! $qrCodeSvg !!}
        </div>
        <div style="margin-top:12px;">
            <p style="font-size:12px;color:var(--text-tertiary);margin-bottom:4px;">Can't scan? Enter this key manually:</p>
            <code style="font-family:var(--font-mono);font-size:13px;background:var(--bg-base);padding:6px 12px;border-radius:6px;border:1px solid var(--border);color:var(--accent-light);letter-spacing:0.05em;">{{ $secret }}</code>
        </div>
    </div>

    <div style="border-top:1px solid var(--border);padding-top:24px;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
            <span style="width:24px;height:24px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:var(--accent-glow);color:var(--accent-light);font-size:12px;font-weight:700;border:1px solid rgba(124,58,237,0.2);">2</span>
            <span style="font-size:14px;font-weight:600;">Enter the 6-digit code from your app to confirm setup</span>
        </div>

        @if($errors->has('code'))
            <div class="alert alert-error" style="margin-bottom:16px;">{{ $errors->first('code') }}</div>
        @endif

        <form method="POST" action="{{ route('two-factor.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="code">Verification Code</label>
                <input type="text" name="code" id="code" class="form-input" maxlength="6" pattern="[0-9]*" inputmode="numeric" autocomplete="one-time-code" autofocus required style="max-width:200px;text-align:center;font-size:20px;font-family:var(--font-mono);letter-spacing:0.3em;">
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                <button type="submit" class="btn btn-primary btn-sm">Confirm and Enable</button>
                <a href="{{ route('settings') }}" style="font-size:13px;color:var(--text-secondary);">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
