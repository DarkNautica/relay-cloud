<x-guest-layout>
    @if(session('status'))
        <div class="alert-box alert-success-box">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password">
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-row">
            <label><input type="checkbox" name="remember"> Remember me</label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>
        <button type="submit" class="btn-submit">Log in</button>
    </form>
    <div class="auth-footer">Don't have an account? <a href="{{ route('register') }}">Sign up</a></div>
</x-guest-layout>
