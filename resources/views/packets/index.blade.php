@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Master Paket</h3>
                    <a href="{{ route('packets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Paket
                    </a>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Tipe</th>
                                    <th>Harga</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($packets as $packet)
                                <tr>
                                    <td>{{ $packet->name }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($packet->type) {
                                                'individual' => 'bg-primary text-white',
                                                'couple' => 'bg-success text-white', 
                                                'group' => 'bg-warning text-dark',
                                                'daily' => 'bg-info text-white',
                                                'membership' => 'bg-secondary text-white',
                                                default => 'bg-info text-white'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($packet->type) }}</span>
                                    </td>
                                    <td>{{ $packet->formatted_price }}</td>
                                    <td>{{ $packet->description }}</td>
                                    <td>
                                        @if($packet->is_active)
                                            <span class="badge bg-success text-white">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary text-white">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('packets.edit', $packet) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('packets.destroy', $packet) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus paket ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data paket</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $packets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection