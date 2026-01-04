@extends('layouts.app')

@section('title', 'Personal Trainer')
@section('page-title', 'Personal Trainer')

@section('page-actions')
<a href="{{ route('personal-trainers.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Tambah PT
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($trainers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kontak</th>
                                    <th>Spesialisasi</th>
                                    <th>Tarif/Jam</th>
                                    <th>Member</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trainers as $trainer)
                                    <tr>
                                        <td>
                                            <strong>{{ $trainer->name }}</strong>
                                        </td>
                                        <td>
                                            <div>{{ $trainer->phone }}</div>
                                            <small class="text-muted">{{ $trainer->email }}</small>
                                        </td>
                                        <td>{{ $trainer->specialization ?? '-' }}</td>
                                        <td>Rp {{ number_format($trainer->hourly_rate, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $trainer->members_count }} member</span>
                                        </td>
                                        <td>
                                            @if($trainer->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('personal-trainers.show', $trainer) }}" 
                                                   class="btn btn-outline-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('personal-trainers.edit', $trainer) }}" 
                                                   class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('personal-trainers.destroy', $trainer) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            onclick="return confirm('Yakin hapus PT ini?')" title="Hapus">
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
                    
                    {{ $trainers->links() }}
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-dumbbell fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada Personal Trainer</p>
                        <a href="{{ route('personal-trainers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah PT Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection