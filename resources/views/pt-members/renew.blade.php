@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Perpanjang Member PT</h3>
                </div>
                
                <form action="{{ route('pt-members.process-renewal', $ptMember) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informasi Member</h5>
                                <p><strong>Nama:</strong> {{ $ptMember->name }}</p>
                                <p><strong>Phone:</strong> {{ $ptMember->phone }}</p>
                                <p><strong>Trainer:</strong> {{ $ptMember->personalTrainer->name }}</p>
                            </div>

                            <div class="col-md-6">
                                <h5>Paket Baru</h5>
                                
                                <div class="form-group">
                                    <label for="packet_id">Paket PT *</label>
                                    <select class="form-control @error('packet_id') is-invalid @enderror" 
                                            id="packet_id" name="packet_id" required>
                                        <option value="">Pilih Paket</option>
                                        @foreach($packets->groupBy('type') as $type => $typePackets)
                                            <optgroup label="{{ ucfirst($type) }}">
                                                @foreach($typePackets as $packet)
                                                    <option value="{{ $packet->id }}" 
                                                            data-price="{{ $packet->price }}"
                                                            data-sessions="{{ $packet->sessions }}"
                                                            data-duration="{{ $packet->duration_days }}">
                                                        {{ $packet->name }} - Rp {{ number_format($packet->price, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    @error('packet_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai *</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
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
                                        <option value="cash">Cash</option>
                                        <option value="qris">QRIS</option>
                                        <option value="transfer">Transfer</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="transaction_date">Tanggal Transaksi *</label>
                                    <input type="date" class="form-control @error('transaction_date') is-invalid @enderror" 
                                           id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                                    @error('transaction_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Perpanjang</button>
                        <a href="{{ route('pt-members.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function calculateEndDate() {
    const startDate = document.getElementById('start_date').value;
    const packetSelect = document.getElementById('packet_id');
    const selectedOption = packetSelect.options[packetSelect.selectedIndex];
    
    if (startDate && selectedOption.value) {
        const duration = parseInt(selectedOption.getAttribute('data-duration'));
        const start = new Date(startDate);
        const end = new Date(start);
        end.setDate(start.getDate() + duration - 1);
        
        const endDateString = end.toISOString().split('T')[0];
        document.getElementById('end_date').value = endDateString;
    }
}

document.getElementById('packet_id').addEventListener('change', calculateEndDate);
document.getElementById('start_date').addEventListener('change', calculateEndDate);
</script>
@endsection