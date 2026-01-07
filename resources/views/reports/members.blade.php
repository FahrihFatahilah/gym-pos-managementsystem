@extends('layouts.app')

@section('title', 'Laporan Member')
@section('page-title', 'Laporan Member')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-users me-2"></i>
                    Laporan Member
                </h6>
                <div>
                    <a href="{{ route('reports.export.members', request()->query()) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('reports.members') }}">
                            <select class="form-select" name="status" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                        </form>
                    </div>
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('reports.members') }}">
                            <select class="form-select" name="payment_method" onchange="this.form.submit()">
                                <option value="">Semua Pembayaran</option>
                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            </select>
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        </form>
                    </div>
                </div>

                @if($members->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>No. HP</th>
                                    <th>Email</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Paket/Membership</th>
                                    <th>Sesi Tersisa</th>
                                    <th>Berakhir</th>
                                    <th>Total Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $member)
                                    <tr>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->phone }}</td>
                                        <td>{{ $member->email ?? '-' }}</td>
                                        <td>
                                            @if(isset($member->packet_id))
                                                <span class="badge bg-warning">PT Member</span>
                                            @elseif(isset($member->category) && $member->category === 'pt')
                                                <span class="badge bg-info">Membership with PT</span>
                                            @else
                                                <span class="badge bg-primary">Regular Member</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($member->status == 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($member->status == 'expired')
                                                <span class="badge bg-warning">Expired</span>
                                            @else
                                                <span class="badge bg-danger">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($member->packet_id))
                                                {{ $member->packet->name ?? '-' }}
                                            @elseif(isset($member->category))
                                                <span class="badge bg-info">{{ ucfirst($member->type) }} - {{ $member->getCategoryLabel() }}</span>
                                            @elseif($member->activeMembership)
                                                <span class="badge bg-info">{{ ucfirst($member->activeMembership->type) }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($member->sessions_remaining))
                                                {{ $member->sessions_remaining }}/{{ $member->total_sessions }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($member->end_date))
                                                {{ Carbon\Carbon::parse($member->end_date)->format('d/m/Y') }}
                                            @elseif(isset($member->category))
                                                {{ Carbon\Carbon::parse($member->end_date)->format('d/m/Y') }}
                                            @elseif($member->activeMembership)
                                                {{ Carbon\Carbon::parse($member->activeMembership->end_date)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($member->amount_paid))
                                                Rp {{ number_format($member->amount_paid, 0, ',', '.') }}
                                            @elseif(isset($member->price))
                                                Rp {{ number_format($member->price, 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($member->payments->sum('amount'), 0, ',', '.') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $members->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada data member</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection