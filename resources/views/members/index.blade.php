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
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
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
                {{ $members->links() }}
            </div>
        </div>
    </div>
</div>
@endsection