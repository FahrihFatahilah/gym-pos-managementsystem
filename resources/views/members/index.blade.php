@extends('layouts.app')

@section('title', 'Kelola Member')
@section('page-title', 'Kelola Member')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-users me-2"></i>
                    Daftar Member
                </h6>
                <a href="{{ route('members.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Member
                </a>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Cari berdasarkan nama atau nomor HP..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Id Member</th>
                                <th>Nama</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $member)
                                <tr>
                                    <td>{{ $member->member_code ?: 'Belum ada' }}</td>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->phone }}</td>
                                    <td>{{ $member->email ?: '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($member->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(auth()->user()->role === 'admin')
                                            <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus member ini? Data tidak dapat dikembalikan!')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data member</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $members->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
