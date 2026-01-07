@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Member PT</h3>
                </div>
                
                <form action="{{ route('pt-members.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <h5>Informasi Personal</h5>
                                
                                <div class="form-group">
                                    <label for="name">Nama Lengkap *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">Nomor Telepon *</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Alamat</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- PT Package Information -->
                            <div class="col-md-6">
                                <h5>Informasi Paket PT</h5>
                                
                                <div class="form-group">
                                    <label for="personal_trainer_id">Personal Trainer *</label>
                                    <select class="form-control @error('personal_trainer_id') is-invalid @enderror" 
                                            id="personal_trainer_id" name="personal_trainer_id" required>
                                        <option value="">Pilih Trainer</option>
                                        @foreach($trainers as $trainer)
                                            <option value="{{ $trainer->id }}" {{ old('personal_trainer_id') == $trainer->id ? 'selected' : '' }}>
                                                {{ $trainer->name }} - {{ $trainer->specialization }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('personal_trainer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="packet_id">Paket PT *</label>
                                    <select class="form-control @error('packet_id') is-invalid @enderror" 
                                            id="packet_id" name="packet_id" required onchange="updatePackageInfo()">
                                        <option value="">Pilih Paket</option>
                                        @if($packets->count() > 0)
                                            @foreach($packets->groupBy('type') as $type => $typePackets)
                                                <optgroup label="{{ ucfirst($type) }}">
                                                    @foreach($typePackets as $packet)
                                                        <option value="{{ $packet->id }}" 
                                                                data-price="{{ $packet->price }}"
                                                                data-sessions="{{ $packet->sessions }}"
                                                                data-duration="{{ $packet->duration_days }}"
                                                                {{ old('packet_id') == $packet->id ? 'selected' : '' }}>
                                                            {{ $packet->name }} - Rp {{ number_format($packet->price, 0, ',', '.') }} 
                                                            ({{ $packet->sessions }} sesi, {{ $packet->duration_days }} hari)
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @else
                                            <option disabled>Tidak ada paket tersedia</option>
                                        @endif
                                    </select>
                                    @error('packet_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai *</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required onchange="calculateEndDate()">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="end_date">Tanggal Selesai *</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date') }}" required readonly>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="payment_method">Metode Pembayaran *</label>
                                    <select class="form-control @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" name="payment_method" required>
                                        <option value="">Pilih Metode</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                        <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="notes">Catatan</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Package Info Display -->
                                <div id="package-info" class="alert alert-info" style="display: none;">
                                    <h6>Detail Paket:</h6>
                                    <div id="package-details"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('pt-members.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updatePackageInfo() {
    const selectedOption = document.getElementById('packet_id').options[document.getElementById('packet_id').selectedIndex];
    const packageInfo = document.getElementById('package-info');
    const packageDetails = document.getElementById('package-details');
    
    if (selectedOption.value) {
        const price = selectedOption.getAttribute('data-price');
        const sessions = selectedOption.getAttribute('data-sessions');
        const duration = selectedOption.getAttribute('data-duration');
        
        packageDetails.innerHTML = `
            <p><strong>Harga:</strong> Rp ${parseInt(price).toLocaleString('id-ID')}</p>
            <p><strong>Jumlah Sesi:</strong> ${sessions} sesi</p>
            <p><strong>Masa Berlaku:</strong> ${duration} hari</p>
        `;
        packageInfo.style.display = 'block';
        
        // Calculate end date
        calculateEndDate();
    } else {
        packageInfo.style.display = 'none';
        document.getElementById('end_date').value = '';
    }
}

function calculateEndDate() {
    const startDate = document.getElementById('start_date').value;
    const packetSelect = document.getElementById('packet_id');
    const selectedOption = packetSelect.options[packetSelect.selectedIndex];
    
    if (startDate && selectedOption.value) {
        const duration = parseInt(selectedOption.getAttribute('data-duration'));
        const start = new Date(startDate);
        const end = new Date(start);
        end.setDate(start.getDate() + duration - 1); // -1 karena hari mulai dihitung
        
        const endDateString = end.toISOString().split('T')[0];
        document.getElementById('end_date').value = endDateString;
    }
}

// Legacy function for backward compatibility
document.getElementById('packet_id').addEventListener('change', updatePackageInfo);
</script>
@endsection