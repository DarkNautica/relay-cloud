<x-guest-layout>
    <div style="text-align:center;margin-bottom:20px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:32px;height:32px;color:#8b5cf6;margin:0 auto 12px;">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
        <h2 style="font-size:18px;font-weight:700;margin-bottom:4px;">Two-Factor Authentication</h2>
        <p style="font-size:13px;color:#8b8a98;">Enter the 6-digit code from your authenticator app to continue.</p>
    </div>

    @if($errors->has('code'))
        <div class="alert-box" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.15);color:#ef4444;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;">
            {{ $errors->first('code') }}
        </div>
    @endif

    <form method="POST" action="{{ route('two-factor.challenge') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="code">Authentication Code</label>
            <input id="code" class="form-input" type="text" name="code" maxlength="6" pattern="[0-9]*" inputmode="numeric" autocomplete="one-time-code" autofocus required style="text-align:center;font-size:24px;font-family:'JetBrains Mono',monospace;letter-spacing:0.3em;">
        </div>
        <button type="submit" class="btn-submit">Verify</button>
    </form>

    <div style="margin-top:16px;text-align:center;font-size:12px;color:#4f4e5c;">
        Contact support if you've lost access to your authenticator app.
    </div>
</x-guest-layout>
