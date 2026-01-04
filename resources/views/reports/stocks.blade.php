@extends('layouts.app')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-warehouse me-2"></i>
                    Laporan Stok
                </h6>
                <div>
                    <a href="{{ route('reports.export.stocks', request()->query()) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('reports.stocks') }}">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="low_stock" value="1" 
                                       {{ request('low_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label">
                                    Tampilkan hanya stok minimum (< 10)
                                </label>
                            </div>
                        </form>
                    </div>
                </div>

                @if($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>SKU</th>
                                    <th>Nama Produk</th>
                                    <th>Stok</th>
                                    <th>Unit</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr class="{{ $product->stock < 10 ? 'table-warning' : '' }}">
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <strong>{{ $product->stock }}</strong>
                                            @if($product->stock < 10)
                                                <span class="badge bg-warning ms-1">LOW</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->unit }}</td>
                                        <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-warehouse fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada data produk</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection