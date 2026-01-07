@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.sales') ? 'active' : '' }}" 
                   href="{{ route('reports.sales') }}">
                    <i class="fas fa-chart-line me-2"></i>Penjualan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.memberships') ? 'active' : '' }}" 
                   href="{{ route('reports.memberships') }}">
                    <i class="fas fa-id-card me-2"></i>Membership
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.members') ? 'active' : '' }}" 
                   href="{{ route('reports.members') }}">
                    <i class="fas fa-users me-2"></i>Member
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.stocks') ? 'active' : '' }}" 
                   href="{{ route('reports.stocks') }}">
                    <i class="fas fa-warehouse me-2"></i>Stok
                </a>
            </li>
        </ul>
        
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line me-2"></i>
                    Laporan Penjualan
                </h6>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" 
                                   value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" 
                                   value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Metode Bayar</label>
                            <select name="payment_method" class="form-control">
                                <option value="">Semua</option>
                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-download me-1"></i> Export
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('reports.export.sales', array_merge(request()->query(), ['format' => 'pdf'])) }}">
                                            <i class="fas fa-file-pdf me-2 text-danger"></i> PDF
                                        </a></li>
                                        <li><a class="dropdown-item" href="{{ route('reports.export.sales', array_merge(request()->query(), ['format' => 'excel'])) }}">
                                            <i class="fas fa-file-excel me-2 text-success"></i> Excel
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Summary -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6>Total Keseluruhan</h6>
                                        <h4>Rp {{ number_format($totalSales, 0, ',', '.') }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-money-bill-wave fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6>Penjualan POS</h6>
                                        <h4>Rp {{ number_format($totalPosSales, 0, ',', '.') }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-cash-register fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6>Membership</h6>
                                        <h4>Rp {{ number_format($totalMembershipSales, 0, ',', '.') }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-id-card fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6>Pengunjung Harians</h6>
                                        <h4>Rp {{ number_format($totalDailyUserSales, 0, ',', '.') }}</h4>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-calendar-day fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- POS Transactions Table -->
                <h5 class="mb-3">
                    <i class="fas fa-cash-register me-2"></i>
                    Transaksi POS
                </h5>
                <div class="table-responsive mb-5">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Transaksi</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Metode Bayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $transaction->transaction_code }}</td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $transaction->getPaymentMethodLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data transaksi POS</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $transactions->links() }}

                <!-- Membership Payments Table -->
                <h5 class="mb-3">
                    <i class="fas fa-id-card me-2"></i>
                    Pembayaran Membership
                </h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Member</th>
                                <th>Tipe Membership</th>
                                <th>Jumlah</th>
                                <th>Metode Bayar</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($membershipPayments as $payment)
                                <tr>
                                    <td>{{ Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $payment->member->name }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ ucfirst($payment->membership->type ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data pembayaran membership</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $membershipPayments->appends(request()->query())->links() }}

                <!-- Pengunjung Harians Table -->
                <h5 class="mb-3">
                    <i class="fas fa-calendar-day me-2"></i>
                    Pengunjung Harians
                </h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Kontak</th>
                                <th>Personal Trainer</th>
                                <th>Jumlah</th>
                                <th>Metode Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyUsers as $user)
                                <tr>
                                    <td>{{ $user->visit_date->format('d/m/Y') }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        <div>{{ $user->phone }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </td>
                                    <td>
                                        @if($user->personalTrainer)
                                            <span class="badge bg-info">{{ $user->personalTrainer->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($user->amount_paid, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($user->payment_method) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data Pengunjung Harians</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $dailyUsers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection