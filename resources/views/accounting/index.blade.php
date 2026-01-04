@extends('layouts.app')

@section('title', 'Pembukuan')
@section('page-title', 'Pembukuan')

@section('content')
<div class="row">
    <!-- Filter -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Pemasukan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-arrow-up fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Total Pengeluaran
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-arrow-down fa-2x text-danger"></i>
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
                            Laba Bersih
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($netProfit, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-info"></i>
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
                            Margin (%)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totalIncome > 0 ? number_format(($netProfit / $totalIncome) * 100, 1) : 0 }}%
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-percentage fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Menu Pembukuan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('accounting.income') }}" class="btn btn-success btn-block">
                            <i class="fas fa-arrow-up me-2"></i>
                            Pemasukan
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('accounting.expense') }}" class="btn btn-danger btn-block">
                            <i class="fas fa-arrow-down me-2"></i>
                            Pengeluaran
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('accounting.profit-loss') }}" class="btn btn-info btn-block">
                            <i class="fas fa-chart-bar me-2"></i>
                            Laba Rugi
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('accounting.export.profit-loss', request()->query()) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-file-pdf me-2"></i>
                            Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Income Breakdown -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">Rincian Pemasukan</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Penjualan POS:</span>
                        <strong>Rp {{ number_format($posIncome, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Membership:</span>
                        <strong>Rp {{ number_format($membershipIncome, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total Pemasukan:</strong>
                    <strong class="text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-danger">Status Keuangan</h6>
            </div>
            <div class="card-body">
                @if($netProfit > 0)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Profit!</strong> Bisnis menghasilkan keuntungan sebesar 
                        <strong>Rp {{ number_format($netProfit, 0, ',', '.') }}</strong>
                    </div>
                @elseif($netProfit < 0)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Loss!</strong> Bisnis mengalami kerugian sebesar 
                        <strong>Rp {{ number_format(abs($netProfit), 0, ',', '.') }}</strong>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-minus-circle me-2"></i>
                        <strong>Break Even!</strong> Pemasukan sama dengan pengeluaran
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
</style>
@endpush