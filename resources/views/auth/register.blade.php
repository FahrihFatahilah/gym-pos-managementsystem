@extends('layouts.auth')

@section('title', 'Register - Gym & POS System')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf
    
    <div class="text-center mb-4">
        <h5 class="text-dark mb-1">Daftar Akun Baru</h5>
        <p class="text-muted">Buat akun untuk mengakses sistem</p>
    </div>

    <!-- Name Field -->
    <div class="mb-3">
        <label for="name" class="form-label">
            <i class="fas fa-user me-2"></i>Nama Lengkap
        </label>
        <input id="name" type="text" 
               class="form-control @error('name') is-invalid @enderror" 
               name="name" value="{{ old('name') }}" 
               required autocomplete="name" autofocus
               placeholder="Masukkan nama lengkap">
        
        @error('name')
            <div class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <!-- Email Field -->
    <div class="mb-3">
        <label for="email" class="form-label">
            <i class="fas fa-envelope me-2"></i>Email
        </label>
        <input id="email" type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               name="email" value="{{ old('email') }}" 
               required autocomplete="email"
               placeholder="Masukkan email">
        
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
               name="password" required autocomplete="new-password"
               placeholder="Masukkan password">
        
        @error('password')
            <div class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <!-- Confirm Password Field -->
    <div class="mb-4">
        <label for="password-confirm" class="form-label">
            <i class="fas fa-lock me-2"></i>Konfirmasi Password
        </label>
        <input id="password-confirm" type="password" 
               class="form-control" name="password_confirmation" 
               required autocomplete="new-password"
               placeholder="Konfirmasi password">
    </div>

    <!-- Register Button -->
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-user-plus me-2"></i>
            Daftar
        </button>
    </div>

    <!-- Login Link -->
    <div class="text-center">
        <p class="mb-0">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-decoration-none">
                Masuk di sini
            </a>
        </p>
    </div>
</form>
@endsection