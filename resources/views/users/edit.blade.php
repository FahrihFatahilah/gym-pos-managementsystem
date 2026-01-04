@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit me-2"></i>
                    Form Edit User
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Administrator
                                    </option>
                                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>
                                        Staff/Kasir
                                    </option>
                                    <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>
                                        Owner
                                    </option>
                                    <option value="pt" {{ old('role', $user->role) == 'pt' ? 'selected' : '' }}>
                                        Personal Trainer
                                    </option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="branch_id" class="form-label">Cabang</label>
                                <select class="form-select @error('branch_id') is-invalid @enderror" 
                                        id="branch_id" name="branch_id">
                                    <option value="">Pilih Cabang</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" 
                                                {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }} ({{ $branch->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3" id="pt-field" style="display: none;">
                                <label for="personal_trainer_id" class="form-label">Personal Trainer <span class="text-danger">*</span></label>
                                <select class="form-select @error('personal_trainer_id') is-invalid @enderror" 
                                        id="personal_trainer_id" name="personal_trainer_id">
                                    <option value="">Pilih Personal Trainer</option>
                                    @foreach($personalTrainers as $trainer)
                                        <option value="{{ $trainer->id }}" {{ old('personal_trainer_id', $user->personal_trainer_id) == $trainer->id ? 'selected' : '' }}>
                                            {{ $trainer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('personal_trainer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <hr>
                    <h6 class="mb-3">
                        <i class="fas fa-key me-2"></i>
                        Pengaturan Akses Menu
                    </h6>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Admin memiliki akses penuh ke semua menu. 
                        Untuk role lain, centang menu yang ingin diberikan akses.
                    </div>

                    <div class="row">
                        @foreach($availablePermissions as $permission => $label)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="permissions[{{ $permission }}]" 
                                           id="permission_{{ $permission }}"
                                           value="1"
                                           {{ $user->hasPermission($permission) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission_{{ $permission }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePTField() {
    const roleSelect = document.getElementById('role');
    const ptField = document.getElementById('pt-field');
    const ptSelect = document.getElementById('personal_trainer_id');
    
    if (roleSelect.value === 'pt') {
        ptField.style.display = 'block';
        ptSelect.required = true;
    } else {
        ptField.style.display = 'none';
        ptSelect.required = false;
    }
}

document.getElementById('role').addEventListener('change', function() {
    const role = this.value;
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="permissions"]');
    
    togglePTField();
    
    if (role === 'admin') {
        checkboxes.forEach(cb => {
            cb.checked = true;
            cb.disabled = true;
        });
    } else {
        checkboxes.forEach(cb => {
            cb.disabled = false;
        });
        
        // Set default permissions based on role
        const defaults = {
            'staff': ['dashboard', 'members', 'memberships', 'products', 'pos', 'stocks'],
            'owner': ['dashboard', 'reports'],
            'pt': ['dashboard', 'my_members']
        };
        
        if (defaults[role]) {
            checkboxes.forEach(cb => {
                const permission = cb.name.match(/permissions\[(.+)\]/)[1];
                cb.checked = defaults[role].includes(permission);
            });
        }
    }
});

// Trigger on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePTField();
    document.getElementById('role').dispatchEvent(new Event('change'));
});
</script>
@endsection