@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Two-Factor Authentication</h1>
        <p class="page-sub">Manage your account security.</p>
    </div>
</div>

<div class="card card-pad" style="max-width:600px;">
    @if($user->two_factor_enabled)
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
            <div style="font-size:14px;font-weight:600;">Two-Factor Authentication</div>
            <span class="badge badge-active"><span class="badge-dot"></span> Enabled</span>
        </div>
        <p style="font-size:13px;color:var(--text-secondary);margin-bottom:8px;">Two-factor authentication is active. Your account is protected.</p>
        @if($user->two_factor_confirmed_at)
            <p style="font-size:12px;color:var(--text-tertiary);margin-bottom:20px;">Enabled on {{ $user->two_factor_confirmed_at->format('F j, Y') }}</p>
        @endif
        <form method="POST" action="{{ route('two-factor.disable') }}">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Disable Two-Factor Authentication</button>
        </form>
    @else
        <div style="display:flex;align-items:flex-start;gap:14px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:28px;height:28px;color:var(--accent-light);flex-shrink:0;margin-top:2px;">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <div>
                <div style="font-size:14px;font-weight:600;margin-bottom:6px;">Two-Factor Authentication</div>
                <p style="font-size:13px;color:var(--text-secondary);margin-bottom:16px;">Add an extra layer of security to your account. When enabled, you'll need to enter a code from your authenticator app each time you sign in.</p>
                <form method="POST" action="{{ route('two-factor.enable') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Enable Two-Factor Authentication</button>
                </form>
            </div>
        </div>
    @endif
</div>

<div style="margin-top:16px;">
    <a href="{{ route('settings') }}" style="font-size:13px;color:var(--accent-light);">&larr; Back to Settings</a>
</div>
@endsection
