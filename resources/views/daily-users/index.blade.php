@extends('layouts.app')

@section('title', 'Pengunjung Harian')
@section('page-title', 'Pengunjung Harian')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0">Filter Data</h6>
                    <a href="{{ route('daily-users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Pengunjung Harian
                    </a>
                </div>
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Cari nama atau HP...">
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="date" class="form-control" 
                               value="{{ request('date') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
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
            <div class="card-body">
                @if($dailyUsers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Kontak</th>
                                    <th>Personal Trainer</th>
                                    <th>Tujuan</th>
                                    <th>Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyUsers as $user)
                                    <tr>
                                        <td>{{ $user->visit_date->format('d/m/Y') }}</td>
                                        <td><strong>{{ $user->name }}</strong></td>
                                        <td>
                                            <div>{{ $user->phone }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </td>
                                        <td>
                                            @if($user->personalTrainer)
                                                <span class="badge bg-info">{{ $user->personalTrainer->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($user->fitness_goals, 30) ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <div>Rp {{ number_format($user->amount_paid, 0, ',', '.') }}</div>
                                            <small class="text-muted">{{ ucfirst($user->payment_method) }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('daily-users.show', $user) }}" 
                                                   class="btn btn-outline-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('daily-users.destroy', $user) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            onclick="return confirm('Yakin hapus data ini?')" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $dailyUsers->links() }}
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-day fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada Pengunjung Harian</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection