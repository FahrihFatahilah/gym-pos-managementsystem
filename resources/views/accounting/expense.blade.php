@extends('layouts.app')

@section('title', 'Pengeluaran')
@section('page-title', 'Pengeluaran')

@section('content')
<div class="row">
    <!-- Form Tambah Pengeluaran -->
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Pengeluaran
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('accounting.expense.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" 
                               id="description" name="description" value="{{ old('description') }}" required>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" value="{{ old('amount') }}" min="0" step="1000" required>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" 
                                id="category" name="category" required>
                            <option value="">Pilih Kategori</option>
                            <option value="operasional" {{ old('category') == 'operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="listrik" {{ old('category') == 'listrik' ? 'selected' : '' }}>Listrik</option>
                            <option value="air" {{ old('category') == 'air' ? 'selected' : '' }}>Air</option>
                            <option value="internet" {{ old('category') == 'internet' ? 'selected' : '' }}>Internet</option>
                            <option value="gaji" {{ old('category') == 'gaji' ? 'selected' : '' }}>Gaji</option>
                            <option value="peralatan" {{ old('category') == 'peralatan' ? 'selected' : '' }}>Peralatan</option>
                            <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="expense_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('expense_date') is-invalid @enderror" 
                               id="expense_date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Daftar Pengeluaran -->
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-list me-2"></i>
                    Daftar Pengeluaran
                </h6>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('accounting.expense') }}">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="date" class="form-control" name="start_date" 
                                           value="{{ request('start_date') }}" placeholder="Tanggal Mulai">
                                </div>
                                <div class="col-md-5">
                                    <input type="date" class="form-control" name="end_date" 
                                           value="{{ request('end_date') }}" placeholder="Tanggal Akhir">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if($expenses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                    <tr>
                                        <td>{{ formatTanggal($expense->expense_date) }}</td>
                                        <td>
                                            <strong>{{ $expense->description }}</strong>
                                            @if($expense->notes)
                                                <br><small class="text-muted">{{ $expense->notes }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($expense->category) }}</span>
                                        </td>
                                        <td>
                                            <strong class="text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>{{ $expense->user->name ?? 'System' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-warning">
                                    <td colspan="3"><strong>Total Pengeluaran:</strong></td>
                                    <td><strong class="text-danger">Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $expenses->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada data pengeluaran</h5>
                        <p class="text-muted">Tambahkan pengeluaran pertama menggunakan form di sebelah kiri</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection