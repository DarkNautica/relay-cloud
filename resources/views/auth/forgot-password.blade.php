<x-guest-layout>
    <p style="color:#94a3b8;font-size:14px;margin-bottom:20px;">
        Forgot your password? No problem. Enter your email and we'll send you a reset link.
    </p>

    @if(session('status'))
        <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#22c55e;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:14px;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn-primary">Send Reset Link</button>
    </form>

    <div class="auth-footer">
        <a href="{{ route('login') }}">Back to login</a>
    </div>
</x-guest-layout>
