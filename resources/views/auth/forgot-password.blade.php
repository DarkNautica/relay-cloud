<x-guest-layout>
    <p style="color:#8b8a98;font-size:13px;margin-bottom:16px;">Enter your email and we'll send a reset link.</p>
    @if(session('status'))
        <div class="alert-box alert-success-box">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn-submit">Send Reset Link</button>
    </form>
    <div class="auth-footer"><a href="{{ route('login') }}">Back to login</a></div>
</x-guest-layout>
