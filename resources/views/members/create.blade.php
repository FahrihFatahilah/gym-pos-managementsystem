@extends('layouts.app')

@section('title', 'Tambah Member')
@section('page-title', 'Tambah Member')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Form Tambah Member</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('members.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                                  placeholder="Contoh: Menurunkan berat badan, membentuk otot, meningkatkan stamina, dll.">{{ old('fitness_goals') }}</textarea>
                        @error('fitness_goals')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-id-card me-2"></i>
                        Paket Membership
                    </h6>

                    <div class="mb-3">
                        <label for="packet_id" class="form-label">Paket Membership <span class="text-danger">*</span></label>
                        <select class="form-select @error('packet_id') is-invalid @enderror" 
                                id="packet_id" name="packet_id" required>
                            <option value="">Pilih Paket</option>
                            @foreach($packets->groupBy('type') as $type => $typePackets)
                                <optgroup label="{{ $type === 'daily' ? 'Harian / Day Pass' : 'Membership' }}">
                                    @foreach($typePackets as $packet)
                                        <option value="{{ $packet->id }}" 
                                                data-price="{{ $packet->price }}"
                                                data-duration="{{ $packet->duration_days }}"
                                                {{ old('packet_id') == $packet->id ? 'selected' : '' }}>
                                            {{ $packet->name }} - {{ $packet->formatted_price }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('packet_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Berakhir</label>
                                <input type="text" class="form-control" id="end_date_display" readonly>
                                <small class="text-muted">Otomatis dihitung berdasarkan paket</small>
                            </div>
                        </div>
                    </div>

                    <div id="packet-info" class="alert alert-info" style="display: none;">
                        <h6>Detail Paket:</h6>
                        <div id="packet-details"></div>
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" 
                                id="payment_method" name="payment_method" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('members.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto calculate end date based on packet selection
    $('#packet_id, #start_date').on('change', function() {
        const packetId = $('#packet_id').val();
        const startDate = $('#start_date').val();
        
        if (packetId && startDate) {
            const selectedOption = $('#packet_id option:selected');
            const duration = selectedOption.data('duration');
            const price = selectedOption.data('price');
            
            if (duration && startDate) {
                const start = new Date(startDate);
                const endDate = new Date(start);
                endDate.setDate(start.getDate() + duration);
                
                const formattedEndDate = endDate.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                
                $('#end_date_display').val(formattedEndDate);
                
                // Show packet info
                $('#packet-details').html(`
                    <p><strong>Harga:</strong> Rp ${parseInt(price).toLocaleString('id-ID')}</p>
                    <p><strong>Durasi:</strong> ${duration} hari</p>
                    <p><strong>Berakhir:</strong> ${formattedEndDate}</p>
                `);
                $('#packet-info').show();
            }
        } else {
            $('#end_date_display').val('');
            $('#packet-info').hide();
        }
    });
    
    // Trigger calculation if values already selected
    if ($('#packet_id').val() && $('#start_date').val()) {
        $('#packet_id').trigger('change');
    }
});
</script>
@endpush