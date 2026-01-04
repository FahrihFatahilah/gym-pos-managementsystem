@extends('layouts.app')

@section('title', 'Pembukuan')
@section('page-title', 'Pembukuan')

@section('content')
<div class="row mb-4">
    <!-- Filter -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Summary -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h6>Pemasukan</h6>
                <h4>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h6>Pengeluaran</h6>
                <h4>Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h6>Laba Bersih</h6>
                <h4>Rp {{ number_format($netProfit, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h6>Margin</h6>
                <h4>{{ $totalIncome > 0 ? number_format(($netProfit / $totalIncome) * 100, 1) : 0 }}%</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Form Tambah Pengeluaran -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Tambah Pengeluaran</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('accounting.expense.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text" class="form-control" name="description" placeholder="Deskripsi" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control currency-input" name="amount" placeholder="Rp 0" required>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="category" required>
                            <option value="">Kategori</option>
                            <option value="operasional">Operasional</option>
                            <option value="listrik">Listrik</option>
                            <option value="gaji">Gaji</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="date" class="form-control" name="expense_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger">Simpan</button>
                        <button type="button" class="btn btn-secondary" id="resetForm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Daftar Pengeluaran -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0">Daftar Pengeluaran</h6>
                <button class="btn btn-sm btn-success" onclick="restoreAllExpenses()">
                    <i class="fas fa-undo"></i> Kembalikan Semua
                </button>
            </div>
            <div class="card-body">
                @if($expenses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                    <tr>
                                        <td>{{ $expense->expense_date->format('d/m') }}</td>
                                        <td>{{ $expense->description }}</td>
                                        <td><span class="badge bg-secondary">{{ $expense->category }}</span></td>
                                        <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteExpense({{ $expense->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada pengeluaran</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Format currency input
document.addEventListener('DOMContentLoaded', function() {
    const currencyInputs = document.querySelectorAll('.currency-input');
    
    currencyInputs.forEach(function(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
                e.target.value = 'Rp ' + value;
            } else {
                e.target.value = '';
            }
        });
        
        input.addEventListener('focus', function(e) {
            if (e.target.value === 'Rp 0') {
                e.target.value = '';
            }
        });
    });
    
    // Reset form button
    document.getElementById('resetForm').addEventListener('click', function() {
        // Reset all form fields
        document.querySelector('input[name="description"]').value = '';
        document.querySelector('input[name="amount"]').value = '';
        document.querySelector('select[name="category"]').value = '';
        document.querySelector('input[name="expense_date"]').value = '{{ date('Y-m-d') }}';
    });
});

// Delete expense function
function deleteExpense(expenseId) {
    if (confirm('Yakin ingin menghapus pengeluaran ini?')) {
        fetch(`/accounting/expense/${expenseId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus pengeluaran');
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan');
        });
    }
}

// Restore all expenses function
function restoreAllExpenses() {
    if (confirm('Kembalikan semua pengeluaran yang dihapus?')) {
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;
        
        fetch('/accounting/expenses/restore-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                start_date: startDate,
                end_date: endDate
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Gagal mengembalikan pengeluaran');
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan');
        });
    }
}
</script>
@endpush