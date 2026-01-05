@extends('layouts.app')

@section('title', 'Kelola Stok')
@section('page-title', 'Kelola Stok')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-warehouse me-2"></i>
                    Kelola Stok Produk
                </h6>
                <a href="{{ route('stocks.history') }}" class="btn btn-info">
                    <i class="fas fa-history me-1"></i> History Stok
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Stok Saat Ini</th>
                                <th>Satuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->stock < 10 ? 'danger' : 'success' }} fs-6">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td>{{ $product->unit }}</td>
                                    <td>
                                        @if($product->stock < 10)
                                            <span class="badge bg-warning">Stok Rendah</span>
                                        @else
                                            <span class="badge bg-success">Stok Aman</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(auth()->user()->role === 'admin')
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                                data-bs-target="#updateStockModal" 
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}">
                                            <i class="fas fa-edit"></i> Update Stok
                                        </button>
                                        @else
                                        <span class="text-muted small">View Only</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data produk</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('stocks.update') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="productId">
                    
                    <div class="mb-3">
                        <label class="form-label">Produk</label>
                        <input type="text" class="form-control" id="productName" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipe <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="in">Stok Masuk</option>
                            <option value="out">Stok Keluar</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" name="reason" class="form-control" 
                               placeholder="Contoh: Pembelian, Rusak, dll" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#updateStockModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var productId = button.data('product-id');
    var productName = button.data('product-name');
    
    $('#productId').val(productId);
    $('#productName').val(productName);
});
</script>
@endpush