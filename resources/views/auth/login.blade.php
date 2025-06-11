<x-guest-layout>
    <div class="auth-card">
        <h2 class="mb-4 text-center text-xl font-semibold">{{ __('Sign in to your account') }}</h2>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" class="form-control" type="password" name="password" required />
            </div>

            <button type="submit" class="btn btn-primary w-100">{{ __('Login') }}</button>

            <div class="text-center mt-3">
                <a href="{{ route('register') }}">{{ __("Don't have an account? Register") }}</a>
            </div>
        </form>
    </div>
</x-guest-layout>
