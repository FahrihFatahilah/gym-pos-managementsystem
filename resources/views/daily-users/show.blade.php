@extends('layouts.app')

@section('page-title', 'Detail Pengunjung Harian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        Informasi Pengunjung
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td>{{ $dailyUser->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon:</strong></td>
                                    <td>{{ $dailyUser->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $dailyUser->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Kunjungan:</strong></td>
                                    <td>{{ $dailyUser->visit_date->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Personal Trainer:</strong></td>
                                    <td>{{ $dailyUser->personalTrainer->name ?? 'Tidak ada' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tujuan Fitness:</strong></td>
                                    <td>{{ $dailyUser->fitness_goals ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Bayar:</strong></td>
                                    <td><strong class="text-success">Rp {{ number_format($dailyUser->amount_paid, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Metode Pembayaran:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $dailyUser->payment_method === 'cash' ? 'success' : ($dailyUser->payment_method === 'qris' ? 'info' : 'primary') }}">
                                            {{ strtoupper($dailyUser->payment_method) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Informasi Waktu
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>Didaftarkan:</strong><br>
                    {{ $dailyUser->created_at->format('d/m/Y H:i') }}</p>
                    
                    @if($dailyUser->updated_at != $dailyUser->created_at)
                    <p><strong>Terakhir Diupdate:</strong><br>
                    {{ $dailyUser->updated_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-12">
            <a href="{{ route('daily-users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            
            <form action="{{ route('daily-users.destroy', $dailyUser) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus data ini?')">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection