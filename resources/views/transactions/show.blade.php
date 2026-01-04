@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0">Detail Transaksi</h6>
                <div>
                    <a href="{{ route('transactions.receipt', $transaction) }}" 
                       class="btn btn-success btn-sm" target="_blank">
                        <i class="fas fa-print"></i> Print Ulang
                    </a>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Transaction Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Kode Transaksi:</strong></td>
                                <td>{{ $transaction->transaction_code }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kasir:</strong></td>
                                <td>{{ $transaction->user->name ?? 'System' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Metode Bayar:</strong></td>
                                <td><span class="badge bg-secondary">{{ $transaction->getPaymentMethodLabel() }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Total Items:</strong></td>
                                <td>{{ $transaction->details->sum('quantity') }} item</td>
                            </tr>
                            <tr>
                                <td><strong>Total Bayar:</strong></td>
                                <td><strong class="text-success">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Items -->
                <h6 class="mb-3">Item Transaksi</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->details as $detail)
                                <tr>
                                    <td>{{ $detail->product->name }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-dark">
                                <th colspan="3" class="text-end">TOTAL:</th>
                                <th class="text-end">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Statistik Transaksi</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-primary mb-1">{{ $transaction->details->count() }}</h5>
                            <small class="text-muted">Jenis Produk</small>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="border rounded p-3">
                            <h5 class="text-success mb-1">{{ $transaction->details->sum('quantity') }}</h5>
                            <small class="text-muted">Total Quantity</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="border rounded p-3">
                            <h5 class="text-info mb-1">Rp {{ number_format($transaction->total_amount / $transaction->details->sum('quantity'), 0, ',', '.') }}</h5>
                            <small class="text-muted">Rata-rata per Item</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection