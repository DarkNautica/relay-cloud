<x-guest-layout>
    <p style="color:#8b8a98;font-size:13px;margin-bottom:16px;">Please confirm your password before continuing.</p>
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password">
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn-submit">Confirm</button>
    </form>
</x-guest-layout>
