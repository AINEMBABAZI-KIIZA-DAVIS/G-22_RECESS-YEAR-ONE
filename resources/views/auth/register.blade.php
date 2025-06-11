<x-guest-layout>
    <div class="auth-card">
        <h2 class="mb-4 text-center text-xl font-semibold">{{ __('Create an Account') }}</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @include('auth.validation-errors', ['errors' => $errors])

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus />
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required />
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" class="form-control" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="form-label">{{ __('Register As') }}</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="supplier" {{ old('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="wholesaler" {{ old('role') == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">{{ __('Register') }}</button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}">{{ __('Already have an account? Login') }}</a>
            </div>
        </form>
    </div>
</x-guest-layout>
