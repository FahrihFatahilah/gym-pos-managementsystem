@extends('layouts.auth')

@section('title', 'Login - Gym & POS System')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf
    
    <div class="text-center mb-4">
        <h5 class="text-dark mb-1">Selamat Datang!</h5>
        <p class="text-muted">Silakan login untuk melanjutkan</p>
    </div>

    <!-- Email Field -->
    <div class="mb-3">
        <label for="email" class="form-label">
            <i class="fas fa-envelope me-2"></i>Email
        </label>
        <input id="email" type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               name="email" value="{{ old('email') }}" 
               required autocomplete="email" autofocus
               placeholder="Masukkan email Anda">
        
        @error('email')
            <div class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <!-- Password Field -->
    <div class="mb-3">
        <label for="password" class="form-label">
            <i class="fas fa-lock me-2"></i>Password
        </label>
        <input id="password" type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               name="password" required autocomplete="current-password"
               placeholder="Masukkan password Anda">
        
        @error('password')
            <div class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <!-- Remember Me -->
    <div class="mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                   {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                Ingat saya
            </label>
        </div>
    </div>

    <!-- Login Button -->
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i>
            Masuk
        </button>
    </div>


</form>

<!-- Quick Login Buttons for Demo -->

<script>
function quickLogin(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
    document.querySelector('form').submit();
}
</script>
@endsection