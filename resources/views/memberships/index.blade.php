@extends('layouts.app')

@section('title', 'Kelola Membership')
@section('page-title', 'Kelola Membership')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-id-card me-2"></i>
                    Daftar Membership
                </h6>
                <a href="{{ route('memberships.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Membership
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Tipe</th>
                                <th>Kategori</th>
                                <th>Mulai</th>
                                <th>Berakhir</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($memberships as $membership)
                                <tr>
                                    <td>{{ $membership->member->name }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $membership->getTypeLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $membership->category === 'pt' ? 'warning' : 'secondary' }}">
                                            {{ $membership->getCategoryLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ formatTanggal($membership->start_date) }}</td>
                                    <td>{{ formatTanggal($membership->end_date) }}</td>
                                    <td>Rp {{ number_format($membership->price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $membership->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($membership->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('memberships.show', $membership) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('memberships.edit', $membership) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data membership</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $memberships->links() }}
            </div>
        </div>
    </div>
</div>
@endsection