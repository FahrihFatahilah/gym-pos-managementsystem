@extends('layouts.app')

@section('title', 'Laporan PT Harian')

@section('page-title', 'Laporan PT Harian')

@section('page-actions')
<div class="d-flex gap-2">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
        <i class="fas fa-filter me-1"></i> Filter
    </button>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-dumbbell me-2"></i>
                    Laporan PT Harian
                    @if($isStaff)
                        <span class="badge bg-info ms-2">Hari Ini</span>
                    @else
                        ({{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }})
                    @endif
                </h5>
                <div class="text-end">
                    <h6 class="mb-0 text-success">
                        Total: Rp {{ number_format($totalPTSales, 0, ',', '.') }}
                    </h6>
                </div>
            </div>
            <div class="card-body">
                @if($ptMembers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Member</th>
                                    <th>Telepon</th>
                                    <th>Personal Trainer</th>
                                    <th>Paket</th>
                                    <th>Pembayaran</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ptMembers as $member)
                                <tr>
                                    <td>{{ $member->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <strong>{{ $member->name }}</strong>
                                    </td>
                                    <td>{{ $member->phone }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $member->personalTrainer->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($member->packet->type === 'individual') bg-primary
                                            @elseif($member->packet->type === 'couple') bg-success
                                            @elseif($member->packet->type === 'group') bg-warning
                                            @endif">
                                            {{ ucfirst($member->packet->type) }} PT
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $member->packet->sessions }} sesi</small>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($member->payment_method === 'cash') bg-success
                                            @elseif($member->payment_method === 'qris') bg-info
                                            @elseif($member->payment_method === 'transfer') bg-primary
                                            @endif">
                                            {{ strtoupper($member->payment_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            Rp {{ number_format($member->amount_paid, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $ptMembers->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-dumbbell fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada data PT member</h5>
                        <p class="text-muted">Belum ada member PT yang terdaftar pada periode ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('reports.pt-daily') }}">
                <div class="modal-body">
                    @if(!$isStaff)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="start_date" 
                                       value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="end_date" 
                                       value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label">Personal Trainer</label>
                        <select class="form-select" name="trainer_id">
                            <option value="">Semua Trainer</option>
                            @foreach($trainers as $trainer)
                                <option value="{{ $trainer->id }}" 
                                        {{ request('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                    {{ $trainer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select class="form-select" name="payment_method">
                            <option value="">Semua Metode</option>
                            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection