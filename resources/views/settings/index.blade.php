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
