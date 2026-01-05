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
                                    <td>{{ formatTanggal($dailyUser->visit_date) }}</td>
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
                        <i class="fas fa-history me-2"></i>
                        History Kunjungan
                    </h5>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-outline-info btn-sm mb-3" id="loadHistory">
                        <i class="fas fa-search"></i> Lihat History
                    </button>
                    <div id="historyContent">
                        <!-- History will be loaded here -->
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Informasi Waktu
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>Didaftarkan:</strong><br>
                    {{ formatTanggal($dailyUser->created_at, true) }}</p>
                    
                    @if($dailyUser->updated_at != $dailyUser->created_at)
                    <p><strong>Terakhir Diupdate:</strong><br>
                    {{ formatTanggal($dailyUser->updated_at, true) }}</p>
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

@push('scripts')
<script>
$(document).ready(function() {
    $('#loadHistory').on('click', function() {
        const phone = '{{ $dailyUser->phone }}';
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
                let content = '';
                
                // Daily History
                content += '<h6 class="text-primary">Daily Gym</h6>';
                if (response.daily_history && response.daily_history.length > 0) {
                    content += '<div class="table-responsive"><table class="table table-sm">';
                    content += '<thead><tr><th>Tanggal</th><th>Bayar</th></tr></thead><tbody>';
                    response.daily_history.slice(0, 5).forEach(function(item) {
                        const date = new Date(item.visit_date);
                        const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                        const formatted = date.getDate() + ' ' + bulan[date.getMonth()] + ' ' + date.getFullYear();
                        content += `<tr><td>${formatted}</td><td>Rp ${parseInt(item.amount_paid).toLocaleString('id-ID')}</td></tr>`;
                    });
                    content += '</tbody></table></div>';
                    if (response.daily_history.length > 5) {
                        content += `<small class="text-muted">Dan ${response.daily_history.length - 5} kunjungan lainnya</small>`;
                    }
                } else {
                    content += '<p class="text-muted small">Belum ada history daily gym</p>';
                }
                
                // Member History
                content += '<h6 class="text-success mt-3">Membership</h6>';
                if (response.member_history && response.member_history.memberships && response.member_history.memberships.length > 0) {
                    content += `<p class="small"><strong>Nama Member:</strong> ${response.member_history.name}</p>`;
                    content += '<div class="table-responsive"><table class="table table-sm">';
                    content += '<thead><tr><th>Tipe</th><th>Status</th></tr></thead><tbody>';
                    response.member_history.memberships.slice(0, 3).forEach(function(membership) {
                        const status = membership.status === 'active' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Expired</span>';
                        content += `<tr><td>${membership.type}</td><td>${status}</td></tr>`;
                    });
                    content += '</tbody></table></div>';
                } else {
                    content += '<p class="text-muted small">Belum pernah jadi member</p>';
                }
                
                $('#historyContent').html(content);
            },
            error: function() {
                $('#historyContent').html('<div class="alert alert-danger">Gagal memuat history</div>');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-search"></i> Lihat History');
            }
        });
    });
});
</script>
@endpush