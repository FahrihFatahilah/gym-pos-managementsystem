@extends('layouts.app')

@section('title', 'History Stok')
@section('page-title', 'History Stok')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>
                    History Stok
                </h6>
                <a href="{{ route('stocks.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('stocks.history') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Cari produk..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('stocks.history') }}">
                            <select class="form-select" name="type" onchange="this.form.submit()">
                                <option value="">Semua Tipe</option>
                                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Masuk</option>
                                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Keluar</option>
                            </select>
                        </form>
                    </div>
                </div>

                @if($histories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Alasan</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($histories as $history)
                                    <tr>
                                        <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <strong>{{ $history->product->name }}</strong><br>
                                            <small class="text-muted">{{ $history->product->sku }}</small>
                                        </td>
                                        <td>
                                            @if($history->type == 'in')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-arrow-up me-1"></i>
                                                    {{ $history->getTypeLabel() }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-arrow-down me-1"></i>
                                                    {{ $history->getTypeLabel() }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $history->quantity }}</strong> {{ $history->product->unit }}
                                        </td>
                                        <td>{{ $history->reason }}</td>
                                        <td>{{ $history->user->name ?? 'System' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $histories->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada history stok</h5>
                        <p class="text-muted">History perubahan stok akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection