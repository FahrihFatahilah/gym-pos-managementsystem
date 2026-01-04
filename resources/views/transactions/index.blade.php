@extends('layouts.app')

@section('title', 'Transaksi POS')
@section('page-title', 'Transaksi POS')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date') }}" placeholder="Tanggal Mulai">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ request('end_date') }}" placeholder="Tanggal Akhir">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Cari kode transaksi...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
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
                <h6 class="m-0">Daftar Transaksi</h6>
            </div>
            <div class="card-body">
                @if($transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Transaksi</th>
                                    <th>Kasir</th>
                                    <th>Items</th>
                                    <th>Metode Bayar</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $transaction->transaction_code }}</span>
                                        </td>
                                        <td>{{ $transaction->user->name ?? 'System' }}</td>
                                        <td>
                                            <small>
                                                @foreach($transaction->details->take(2) as $detail)
                                                    {{ $detail->product->name }} ({{ $detail->quantity }}x)<br>
                                                @endforeach
                                                @if($transaction->details->count() > 2)
                                                    <em>+{{ $transaction->details->count() - 2 }} item lainnya</em>
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $transaction->getPaymentMethodLabel() }}</span>
                                        </td>
                                        <td class="text-end">
                                            <strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('transactions.show', $transaction) }}" 
                                                   class="btn btn-outline-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('transactions.receipt', $transaction) }}" 
                                                   class="btn btn-outline-success" title="Print Ulang" target="_blank">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} 
                            dari {{ $transactions->total() }} transaksi
                        </div>
                        {{ $transactions->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada transaksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection