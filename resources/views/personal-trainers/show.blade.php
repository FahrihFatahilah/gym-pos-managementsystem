@extends('layouts.app')

@section('title', 'Detail Personal Trainer')
@section('page-title', 'Detail Personal Trainer')

@section('page-actions')
<a href="{{ route('personal-trainers.edit', $personalTrainer) }}" class="btn btn-warning">
    <i class="fas fa-edit"></i> Edit
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Informasi Personal Trainer</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $personalTrainer->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>HP:</strong></td>
                        <td>{{ $personalTrainer->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $personalTrainer->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tarif/Jam:</strong></td>
                        <td>Rp {{ number_format($personalTrainer->hourly_rate, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($personalTrainer->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
                
                @if($personalTrainer->specialization)
                    <div class="mt-3">
                        <strong>Spesialisasi:</strong>
                        <p class="mt-2">{{ $personalTrainer->specialization }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Member yang Dilatih ({{ $personalTrainer->members->count() }})</h6>
            </div>
            <div class="card-body">
                @if($personalTrainer->members->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama Member</th>
                                    <th>Kontak</th>
                                    <th>Status</th>
                                    <th>Tujuan Fitness</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($personalTrainer->members as $member)
                                    <tr>
                                        <td>
                                            <strong>{{ $member->name }}</strong>
                                        </td>
                                        <td>
                                            <div>{{ $member->phone }}</div>
                                            <small class="text-muted">{{ $member->email }}</small>
                                        </td>
                                        <td>
                                            @if($member->status == 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Expired</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $member->fitness_goals ?? '-' }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Belum ada member yang dilatih</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection