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
                                <div class="input-group">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    <button type="button" class="btn btn-outline-info" id="checkHistory" style="transition: none;">
                                        <i class="fas fa-history"></i> Cek History
                                    </button>
                                </div>
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

                    <!-- History Modal -->
                    <div id="historyModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050;">
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 8px; width: 90%; max-width: 800px; max-height: 80vh; overflow-y: auto;">
                            <div style="padding: 20px; border-bottom: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center;">
                                <h5 style="margin: 0;">History Kunjungan</h5>
                                <button type="button" onclick="document.getElementById('historyModal').style.display='none'" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
                            </div>
                            <div id="historyContent" style="padding: 20px;">
                                <!-- Content loaded here -->
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
                                <label for="daily_price_type" class="form-label">Tipe Harga Harian <span class="text-danger">*</span></label>
                                <select class="form-select @error('daily_price_type') is-invalid @enderror" 
                                        id="daily_price_type" name="daily_price_type" required>
                                    <option value="">Pilih Tipe Harga</option>
                                    <option value="regular" {{ old('daily_price_type') == 'regular' ? 'selected' : '' }}>
                                        Reguler - Rp {{ number_format(App\Models\GymSetting::getSettings()->daily_price_regular, 0, ',', '.') }}
                                    </option>
                                    <option value="premium" {{ old('daily_price_type') == 'premium' ? 'selected' : '' }}>
                                        Premium - Rp {{ number_format(App\Models\GymSetting::getSettings()->daily_price_premium, 0, ',', '.') }}
                                    </option>
                                </select>
                                @error('daily_price_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
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
                        <span id="daily-price">Pilih tipe harga terlebih dahulu</span>
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
    const regularPrice = {{ App\Models\GymSetting::getSettings()->daily_price_regular }};
    const premiumPrice = {{ App\Models\GymSetting::getSettings()->daily_price_premium }};
    
    $('#daily_price_type').on('change', function() {
        const priceType = $(this).val();
        if (priceType === 'regular') {
            $('#daily-price').text('Rp ' + regularPrice.toLocaleString('id-ID'));
        } else if (priceType === 'premium') {
            $('#daily-price').text('Rp ' + premiumPrice.toLocaleString('id-ID'));
        } else {
            $('#daily-price').text('Pilih tipe harga terlebih dahulu');
        }
    });
    
    $('#personal_trainer_id').on('change', function() {
        const trainerId = $(this).val();
        if (trainerId) {
            $('#pt-cost').show();
        } else {
            $('#pt-cost').hide();
        }
    });
    
    // Check history functionality
    $('#checkHistory').on('click', function() {
        const phone = $('#phone').val();
        if (!phone) {
            alert('Masukkan nomor HP terlebih dahulu');
            return;
        }
        
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        
        $.ajax({
            url: '{{ route("daily-users.check-history") }}',
            method: 'POST',
            data: {
                phone: phone,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                let content = '<div class="row">';
                
                // Daily History
                content += '<div class="col-md-6"><h6>History Daily Gym</h6>';
                if (response.daily_history && response.daily_history.length > 0) {
                    content += '<table class="table table-sm">';
                    content += '<thead><tr><th>Tanggal</th><th>Bayar</th><th>PT</th></tr></thead><tbody>';
                    response.daily_history.forEach(function(item) {
                        const pt = item.personal_trainer ? item.personal_trainer.name : '-';
                        content += `<tr><td>${item.visit_date}</td><td>Rp ${parseInt(item.amount_paid).toLocaleString('id-ID')}</td><td>${pt}</td></tr>`;
                    });
                    content += '</tbody></table>';
                } else {
                    content += '<p class="text-muted">Belum ada history daily gym</p>';
                }
                content += '</div>';
                
                // Member History
                content += '<div class="col-md-6"><h6>History Membership</h6>';
                if (response.member_history && response.member_history.memberships && response.member_history.memberships.length > 0) {
                    content += `<p><strong>Nama:</strong> ${response.member_history.name}</p>`;
                    content += '<table class="table table-sm">';
                    content += '<thead><tr><th>Tipe</th><th>Mulai</th><th>Berakhir</th><th>Status</th></tr></thead><tbody>';
                    response.member_history.memberships.forEach(function(membership) {
                        const status = membership.status === 'active' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Expired</span>';
                        content += `<tr><td>${membership.type}</td><td>${membership.start_date}</td><td>${membership.end_date}</td><td>${status}</td></tr>`;
                    });
                    content += '</tbody></table>';
                    
                    if (response.member_history.name && !$('#name').val()) {
                        $('#name').val(response.member_history.name);
                    }
                } else {
                    content += '<p class="text-muted">Belum pernah jadi member</p>';
                }
                content += '</div></div>';
                
                $('#historyContent').html(content);
                document.getElementById('historyModal').style.display = 'block';
            },
            error: function() {
                alert('Gagal mengambil data history');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-history"></i> Cek History');
            }
        });
    });
});
</script>
@endpush
@endsection