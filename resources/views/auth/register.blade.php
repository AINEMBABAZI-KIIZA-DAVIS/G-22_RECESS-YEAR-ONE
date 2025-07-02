<x-guest-layout>
    <div class="auth-card">
        <h2 class="text-center mb-2" style="color: #2b2d42; font-weight: 600;">{{ __('Join Premium Bakery') }}</h2>
        <p class="text-center text-muted mb-4">{{ __('Create your account to get started') }}</p>

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

        <form method="POST" action="{{ route('register') }}" class="mt-4">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">
                    <i class="bi bi-person me-2"></i>{{ __('Full Name') }}
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input id="name" 
                           class="form-control ps-3" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="Enter your full name"
                           required 
                           autofocus />
                </div>
            </div>

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
                           required />
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-2"></i>{{ __('Password') }}
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-key text-muted"></i>
                    </span>
                    <input id="password" 
                           class="form-control ps-3" 
                           type="password" 
                           name="password" 
                           placeholder="Create a password"
                           required />
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <small class="form-text text-muted">Use 8 or more characters with a mix of letters, numbers & symbols</small>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">
                    <i class="bi bi-shield-lock me-2"></i>{{ __('Confirm Password') }}
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-shield-lock text-muted"></i>
                    </span>
                    <input id="password_confirmation" 
                           class="form-control ps-3" 
                           type="password" 
                           name="password_confirmation" 
                           placeholder="Confirm your password"
                           required />
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="role" class="form-label">
                    <i class="bi bi-person-badge me-2"></i>{{ __('I am a') }}
                </label>
                <div class="d-flex flex-column gap-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="role_supplier" value="supplier" {{ old('role') == 'supplier' ? 'checked' : 'checked' }}>
                        <label class="form-check-label d-flex align-items-center" for="role_supplier">
                            <i class="bi bi-truck me-2"></i>
                            <div>
                                <div class="fw-semibold">Supplier</div>
                                <small class="text-muted">Sell your bakery ingredients</small>
                            </div>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="role_wholesaler" value="wholesaler" {{ old('role') == 'wholesaler' ? 'checked' : '' }}>
                        <label class="form-check-label d-flex align-items-center" for="role_wholesaler">
                            <i class="bi bi-shop me-2"></i>
                            <div>
                                <div class="fw-semibold">Wholesaler</div>
                                <small class="text-muted">Buy in bulk for your business</small>
                            </div>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="role" id="role_admin" value="admin" {{ old('role') == 'admin' ? 'checked' : '' }}>
                        <label class="form-check-label d-flex align-items-center" for="role_admin">
                            <i class="bi bi-shield-lock me-2"></i>
                            <div>
                                <div class="fw-semibold">Administrator</div>
                                <small class="text-muted">Manage the platform</small>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label" for="terms">
                    I agree to the <a href="#" class="text-decoration-none" style="color: #e76f51;">Terms of Service</a> and 
                    <a href="#" class="text-decoration-none" style="color: #e76f51;">Privacy Policy</a>
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 mb-3" style="background-color: #e76f51; border: none; font-weight: 600; letter-spacing: 0.5px;">
                {{ __('Create Account') }}
            </button>

            <div class="text-center">
                <p class="text-muted mb-0">{{ __('Already have an account?') }} 
                    <a href="{{ route('login') }}" style="color: #e76f51; font-weight: 500;">{{ __('Sign In') }}</a>
                </p>
            </div>
        </form>

        <!-- Social Signup -->
        <div class="text-center mt-4">
            <div class="position-relative">
                <hr>
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted" style="font-size: 0.9rem;">{{ __('Or sign up with') }}</span>
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
        // Toggle password visibility for both password fields
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

        // Password strength indicator (simplified example)
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                // Password strength logic can be added here
            });
        }
    </script>
    @endpush
</x-guest-layout>
