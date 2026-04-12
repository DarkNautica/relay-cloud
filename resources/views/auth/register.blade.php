<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="name">Name</label>
            <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password">
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn-primary">Create Account</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Log in</a>
    </div>
</x-guest-layout>
