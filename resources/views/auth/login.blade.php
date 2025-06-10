<x-guest-layout>
    <h2 class="auth-card-title mb-4">{{ __('Login') }}</h2>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <div class="input-group">
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" />
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()" tabindex="-1">
                    <span id="togglePasswordIcon" class="fa fa-eye"></span>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <script>
            function togglePassword() {
                const pwd = document.getElementById('password');
                const icon = document.getElementById('togglePasswordIcon');
                if (pwd.type === 'password') {
                    pwd.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    pwd.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        </script>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary-gradient">
                {{ __('Log in') }}
            </button>
        </div>

        @if (Route::has('password.request'))
            <a class="auth-link mt-3" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
        @endif
        
        @if (Route::has('register'))
            <a class="auth-link mt-2" href="{{ route('register') }}">
                {{ __("Don't have an account? Register") }}
            </a>
        @endif
    </form>
</x-guest-layout>
