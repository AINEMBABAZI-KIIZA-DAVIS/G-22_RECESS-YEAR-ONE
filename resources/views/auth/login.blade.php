<x-guest-layout>
    <div class="auth-card">
        <h2 class="text-center mb-4" style="color: #2b2d42; font-weight: 600;">{{ __('Welcome Back!') }}</h2>
        <p class="text-center text-muted mb-4">{{ __('Sign in to your bakery account') }}</p>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-4">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope me-2"></i>{{ __('Email Address') }}
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-envelope text-muted"></i>
                    </span>
                    <input id="email" 
                           class="form-control ps-3" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="Enter your email"
                           required 
                           autofocus />
                </div>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock me-2"></i>{{ __('Password') }}
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: #e76f51; font-size: 0.9rem;">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-key text-muted"></i>
                    </span>
                    <input id="password" 
                           class="form-control ps-3" 
                           type="password" 
                           name="password" 
                           placeholder="Enter your password"
                           required />
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    {{ __('Remember me') }}
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: #e76f51; border: none; font-weight: 600; letter-spacing: 0.5px;">
                {{ __('Sign In') }}
            </button>

            <div class="text-center mt-4">
                <p class="text-muted mb-0">{{ __("Don't have an account?" )}} 
                    <a href="{{ route('register') }}" style="color: #e76f51; font-weight: 500;">{{ __('Create an account') }}</a>
                </p>
            </div>
        </form>

        <!-- Social Login -->
        <div class="text-center mt-4">
            <div class="position-relative">
                <hr>
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted" style="font-size: 0.9rem;">{{ __('Or continue with') }}</span>
            </div>
            
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="#" class="btn btn-outline-secondary rounded-circle p-2" style="width: 42px; height: 42px;">
                    <i class="bi bi-google"></i>
                </a>
                <a href="#" class="btn btn-outline-secondary rounded-circle p-2" style="width: 42px; height: 42px;">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="#" class="btn btn-outline-secondary rounded-circle p-2" style="width: 42px; height: 42px;">
                    <i class="bi bi-apple"></i>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const passwordInput = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    </script>
    @endpush
</x-guest-layout>
