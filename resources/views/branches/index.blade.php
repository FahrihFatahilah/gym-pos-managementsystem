@extends('layouts.app')

@section('title', 'Kelola Cabang')
@section('page-title', 'Kelola Cabang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-building me-2"></i>
                    Daftar Cabang
                </h6>
                <a href="{{ route('branches.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Cabang
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Cabang</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branches as $branch)
                                <tr>
                                    <td><strong>{{ $branch->code }}</strong></td>
                                    <td>{{ $branch->name }}</td>
                                    <td>{{ Str::limit($branch->address, 50) }}</td>
                                    <td>{{ $branch->phone ?: '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $branch->is_active ? 'success' : 'danger' }}">
                                            {{ $branch->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('branches.show', $branch) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('branches.edit', $branch) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('branches.destroy', $branch) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Yakin hapus cabang ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data cabang</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{ $branches->links() }}
            </div>
        </div>
    </div>
</div>
@endsection