@extends('layouts.app')

@section('title', 'Dashboard - Gym & POS System')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Member Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totalActiveMembers }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Penjualan Hari Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($todaySales, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Stok Minimum
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $lowStockProducts->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Member Akan Expired
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $expiringMembers }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-times fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sales Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-area me-2"></i>
                    Penjualan 7 Hari Terakhir
                </h6>
            </div>
            <div class="card-body">
                <canvas id="salesChart" width="100" height="40"></canvas>
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Produk Stok Minimum
                </h6>
            </div>
            <div class="card-body">
                @if($lowStockProducts->count() > 0)
                    @foreach($lowStockProducts as $product)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <div class="small font-weight-bold">{{ $product->name }}</div>
                                <div class="small text-muted">Stok: {{ $product->stock }} {{ $product->unit }}</div>
                            </div>
                            <div class="text-warning">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <p>Semua produk stok aman</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-receipt me-2"></i>
                    Transaksi Hari Ini
                </h6>
            </div>
            <div class="card-body">
                @if($todayTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode Transaksi</th>
                                    <th>Kasir</th>
                                    <th>Total</th>
                                    <th>Metode Bayar</th>
                                    <th>Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->transaction_code }}</td>
                                        <td>{{ $transaction->user->name }}</td>
                                        <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $transaction->getPaymentMethodLabel() }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->created_at->format('H:i') }}</td>
                                        <td>
                                            <a href="{{ route('pos.receipt', $transaction->id) }}" 
                                               class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="fas fa-receipt fa-3x mb-3"></i>
                        <p>Belum ada transaksi hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Chart
const ctx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($salesChart, 'date')) !!},
        datasets: [{
            label: 'Penjualan (Rp)',
            data: {!! json_encode(array_column($salesChart, 'sales')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endpush

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
</style>
@endpush