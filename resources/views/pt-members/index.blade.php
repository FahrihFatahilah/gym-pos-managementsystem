@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Member PT</h3>
                    <a href="{{ route('pt-members.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Member PT
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama atau telepon..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            @if(auth()->user()->role === 'admin')
                            <div class="col-md-3">
                                <select name="trainer_id" class="form-control">
                                    <option value="">Semua Trainer</option>
                                    @foreach($trainers as $trainer)
                                        <option value="{{ $trainer->id }}" {{ request('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                            {{ $trainer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary">Filter</button>
                                <a href="{{ route('pt-members.index') }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Members Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Telepon</th>
                                    <th>Trainer</th>
                                    <th>Paket</th>
                                    <th>Sesi</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ptMembers as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->phone }}</td>
                                    <td>{{ $member->personalTrainer->name }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($member->packet->type) {
                                                'individual' => 'bg-primary text-white',
                                                'couple' => 'bg-success text-white', 
                                                'group' => 'bg-warning text-dark',
                                                default => 'bg-info text-white'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($member->packet->type) }}</span>
                                        {{ $member->packet->name }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $member->sessions_remaining > 0 ? 'bg-success' : 'bg-danger' }} text-white">
                                            {{ $member->sessions_remaining }}/{{ $member->total_sessions }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ formatTanggal($member->start_date) }} - {{ formatTanggal($member->end_date) }}
                                    </td>
                                    <td>
                                        @if($member->status === 'active')
                                            <span class="badge bg-success text-white">Aktif</span>
                                        @elseif($member->status === 'expired')
                                            <span class="badge bg-warning text-dark">Expired</span>
                                        @else
                                            <span class="badge bg-secondary text-white">Selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('pt-members.show', $member) }}" class="btn btn-xs btn-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($member->status === 'active' && $member->sessions_remaining > 0)
                                                <form action="{{ route('pt-members.use-session', $member) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-warning" onclick="return confirm('Gunakan 1 sesi?')" title="Gunakan Sesi">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($member->status === 'completed' || $member->sessions_remaining <= 0)
                                                <a href="{{ route('pt-members.renew', $member) }}" class="btn btn-xs btn-success" title="Perpanjang">
                                                    <i class="fas fa-redo"></i>
                                                </a>
                                            @endif
                                            @if($member->packet->type === 'group')
                                                <a href="{{ route('pt-members.add-member', $member) }}" class="btn btn-xs" style="background-color: #6f42c1; color: white;" title="Tambah Member">
                                                    <i class="fas fa-user-plus"></i>
                                                </a>
                                            @endif
                                            @can('update', $member)
                                                <a href="{{ route('pt-members.edit', $member) }}" class="btn btn-xs btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @if(auth()->user()->role === 'admin')
                                                <form action="{{ route('pt-members.destroy', $member) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Hapus member ini?')" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data member PT</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    {{ $ptMembers->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection