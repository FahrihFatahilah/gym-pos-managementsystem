@extends('layouts.app')

@section('title', 'Tambah Pengunjung Harian')
@section('page-title', 'Tambah Pengunjung Harian')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('daily-users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="personal_trainer_id" class="form-label">Personal Trainer</label>
                        <select class="form-select @error('personal_trainer_id') is-invalid @enderror" 
                                id="personal_trainer_id" name="personal_trainer_id">
                            <option value="">Pilih Personal Trainer (Opsional)</option>
                            @foreach(App\Models\PersonalTrainer::where('is_active', true)->get() as $trainer)
                                <option value="{{ $trainer->id }}" {{ old('personal_trainer_id') == $trainer->id ? 'selected' : '' }}>
                                    {{ $trainer->name }} - {{ $trainer->specialization }}
                                </option>
                            @endforeach
                        </select>
                        @error('personal_trainer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fitness_goals" class="form-label">Tujuan Fitness</label>
                        <textarea class="form-control @error('fitness_goals') is-invalid @enderror" 
                                  id="fitness_goals" name="fitness_goals" rows="3" 
                                  placeholder="Contoh: Menurunkan berat badan, membentuk otot, dll.">{{ old('fitness_goals') }}</textarea>
                        @error('fitness_goals')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="visit_date" class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('visit_date') is-invalid @enderror" 
                                       id="visit_date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required>
                                @error('visit_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required>
                                    <option value="">Pilih Metode</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                    <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <strong>Total Biaya:</strong> 
                        Rp {{ number_format(App\Models\GymSetting::getSettings()->membership_daily_price, 0, ',', '.') }}
                        <span id="pt-cost" style="display: none;"> + Rp <span id="pt-amount">0</span> (PT)</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('daily-users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#personal_trainer_id').on('change', function() {
        const trainerId = $(this).val();
        if (trainerId) {
            // Get trainer hourly rate (you might want to add this via AJAX)
            $('#pt-cost').show();
        } else {
            $('#pt-cost').hide();
        }
    });
});
</script>
@endpush
@endsection