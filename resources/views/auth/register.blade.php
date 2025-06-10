<x-guest-layout>
    <h2 class="auth-card-title mb-4">{{ __('Register') }}</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
        </div>

        <!-- Role -->
        <div class="mb-3">
            <label for="role" class="form-label">{{ __('Role') }}</label>
            <select id="role" name="role" class="form-select" required>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>{{ __('Supplier') }}</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                <option value="wholesaler" {{ old('role') == 'wholesaler' ? 'selected' : '' }}>{{ __('Wholesaler') }}</option>
                {{-- Add more roles as needed --}}
            </select>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary-gradient">
                {{ __('Register') }}
            </button>
        </div>

        <a class="auth-link mt-3" href="{{ route('login') }}">
            {{ __('Already registered?') }}
        </a>
    </form>
</x-guest-layout>
