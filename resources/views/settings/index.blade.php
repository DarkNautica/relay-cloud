@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Settings</h1>
        <p class="page-sub">Manage your account and security.</p>
    </div>
</div>

<!-- Profile -->
<div class="card card-pad" style="margin-bottom:16px;max-width:600px;">
    <div style="font-size:14px;font-weight:600;margin-bottom:16px;">Profile</div>
    <form method="POST" action="{{ route('settings.profile') }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label" for="name">Name</label>
            <input type="text" name="name" id="name" class="form-input" value="{{ Auth::user()->name }}" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" class="form-input" value="{{ Auth::user()->email }}" disabled style="opacity:0.6;">
            <div style="font-size:12px;color:var(--text-tertiary);margin-top:4px;">Contact support to change your email address.</div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Save Profile</button>
    </form>
</div>

<!-- Password -->
<div class="card card-pad" style="margin-bottom:16px;max-width:600px;">
    <div style="font-size:14px;font-weight:600;margin-bottom:16px;">Password</div>
    <form method="POST" action="{{ route('settings.password') }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label" for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">New Password</label>
            <input type="password" name="password" id="password" class="form-input" required>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Update Password</button>
    </form>
</div>

<!-- Two-Factor Authentication -->
<div class="card card-pad" style="margin-bottom:16px;max-width:600px;{{ Auth::user()->two_factor_enabled ? '' : 'border-left:3px solid var(--warning);' }}">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
        <div style="font-size:14px;font-weight:600;">Two-Factor Authentication</div>
        @if(Auth::user()->two_factor_enabled)
            <span class="badge badge-active"><span class="badge-dot"></span> Enabled</span>
        @endif
    </div>
    @if(Auth::user()->two_factor_enabled)
        <p style="font-size:13px;color:var(--text-secondary);margin-bottom:8px;">Two-factor authentication is active. Your account is protected.</p>
        @if(Auth::user()->two_factor_confirmed_at)
            <p style="font-size:12px;color:var(--text-tertiary);margin-bottom:16px;">Enabled on {{ Auth::user()->two_factor_confirmed_at->format('F j, Y') }}</p>
        @endif
        <form method="POST" action="{{ route('two-factor.disable') }}">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Disable Two-Factor Authentication</button>
        </form>
    @else
        <div style="display:flex;align-items:flex-start;gap:14px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:24px;height:24px;color:var(--warning);flex-shrink:0;margin-top:2px;">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <div>
                <p style="font-size:13px;color:var(--text-secondary);margin-bottom:6px;">Add an extra layer of security to your account. When enabled, you'll need to enter a code from your authenticator app each time you sign in.</p>
                <p style="font-size:12px;color:var(--text-secondary);margin-bottom:14px;font-weight:500;">Recommended &mdash; protect your account and credentials.</p>
                <form method="POST" action="{{ route('two-factor.enable') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Enable Two-Factor Authentication</button>
                </form>
            </div>
        </div>
    @endif
</div>

<!-- Danger Zone -->
<div class="danger-zone" style="max-width:600px;">
    <h3>Delete Account</h3>
    <p>Permanently delete your account, all projects, and all data. This cannot be undone.</p>
    <form method="POST" action="{{ route('settings.delete') }}" onsubmit="return document.getElementById('confirm-delete').value === 'DELETE'">
        @csrf @method('DELETE')
        <div class="form-group">
            <label class="form-label" for="confirm-delete">Type <strong>DELETE</strong> to confirm</label>
            <input type="text" name="confirm" id="confirm-delete" class="form-input" placeholder="DELETE" required style="max-width:200px;">
            @error('confirm')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-danger btn-sm">Delete Account</button>
    </form>
</div>
@endsection
