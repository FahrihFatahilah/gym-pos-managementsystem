@extends('layouts.app')

@section('page-title', 'Laporan Membership')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-id-card me-2"></i>
                        Filter Laporan Membership
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.memberships') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('reports.export.memberships', request()->all()) }}" class="btn btn-success">
                                    <i class="fas fa-download"></i> Export PDF
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Data Membership
                    </h5>
                </div>
                <div class="card-body">
                    @if($memberships->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-mobile">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Tipe Membership</th>
                                        <th>Tanggal Pembelian</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($memberships as $membership)
                                    <tr>
                                        <td data-label="Member">
                                            <strong>{{ $membership->member->name }}</strong><br>
                                            <small class="text-muted">{{ $membership->member->phone }}</small>
                                        </td>
                                        <td data-label="Tipe">{{ $membership->type }}</td>
                                        <td data-label="Tanggal Pembelian">{{ $membership->created_at->format('d/m/Y H:i') }}</td>
                                        <td data-label="Mulai">{{ $membership->start_date->format('d/m/Y') }}</td>
                                        <td data-label="Selesai">{{ $membership->end_date->format('d/m/Y') }}</td>
                                        <td data-label="Harga">
                                            <strong class="text-success">Rp {{ number_format($membership->price, 0, ',', '.') }}</strong>
                                        </td>
                                        <td data-label="Status">
                                            <span class="badge bg-{{ $membership->status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($membership->status) }}
                                            </span>
                                        </td>
                                        <td data-label="Pembayaran">
                                            @if($membership->payments->count() > 0)
                                                @foreach($membership->payments as $payment)
                                                <div class="mb-1">
                                                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                    <small class="text-muted d-block">{{ $payment->payment_date->format('d/m/Y') }}</small>
                                                </div>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Belum ada pembayaran</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $memberships->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-id-card fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data membership</h5>
                            <p class="text-muted">Belum ada membership yang sesuai dengan filter yang dipilih.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection