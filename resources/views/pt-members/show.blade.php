@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detail Member PT</h3>
                    <div>
                        @if($ptMember->status === 'active' && $ptMember->sessions_remaining > 0)
                            <form action="{{ route('pt-members.use-session', $ptMember) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Gunakan 1 sesi?')">
                                    <i class="fas fa-minus"></i> Gunakan Sesi
                                </button>
                            </form>
                        @endif
                        @can('update', $ptMember)
                            <a href="{{ route('pt-members.edit', $ptMember) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endcan
                        <a href="{{ route('pt-members.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <h5>Informasi Personal</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Nama</strong></td>
                                    <td>: {{ $ptMember->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon</strong></td>
                                    <td>: {{ $ptMember->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>: {{ $ptMember->email ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td>: {{ $ptMember->address ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>: 
                                        @if($ptMember->status === 'active')
                                            <span class="badge bg-success text-white">Aktif</span>
                                        @elseif($ptMember->status === 'expired')
                                            <span class="badge bg-warning text-dark">Expired</span>
                                        @else
                                            <span class="badge bg-secondary text-white">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Package Information -->
                        <div class="col-md-6">
                            <h5>Informasi Paket PT</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Trainer</strong></td>
                                    <td>: {{ $ptMember->personalTrainer->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Paket</strong></td>
                                    <td>: 
                                        @php
                                            $badgeClass = match($ptMember->packet->type) {
                                                'individual' => 'bg-primary text-white',
                                                'couple' => 'bg-success text-white', 
                                                'group' => 'bg-warning text-dark',
                                                default => 'bg-info text-white'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($ptMember->packet->type) }}</span>
                                        {{ $ptMember->packet->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Harga</strong></td>
                                    <td>: {{ $ptMember->formatted_amount }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Periode</strong></td>
                                    <td>: {{ formatTanggal($ptMember->start_date) }} - {{ formatTanggal($ptMember->end_date) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sesi</strong></td>
                                    <td>: 
                                        <span class="badge {{ $ptMember->sessions_remaining > 0 ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                            {{ $ptMember->sessions_remaining }}/{{ $ptMember->total_sessions }}
                                        </span>
                                        @if($ptMember->sessions_remaining > 0)
                                            ({{ $ptMember->sessions_remaining }} sesi tersisa)
                                        @else
                                            (Sesi habis)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Metode Bayar</strong></td>
                                    <td>: {{ strtoupper($ptMember->payment_method) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($ptMember->packet->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Keterangan Paket</h5>
                            <div class="alert alert-info">
                                {{ $ptMember->packet->description }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($ptMember->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Catatan</h5>
                            <div class="alert alert-secondary">
                                {{ $ptMember->notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Progress Bar -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Progress Sesi</h5>
                            @php
                                $usedSessions = $ptMember->total_sessions - $ptMember->sessions_remaining;
                                $progressPercentage = $ptMember->total_sessions > 0 ? ($usedSessions / $ptMember->total_sessions) * 100 : 0;
                            @endphp
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $progressPercentage }}%" 
                                     aria-valuenow="{{ $usedSessions }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $ptMember->total_sessions }}">
                                    {{ $usedSessions }}/{{ $ptMember->total_sessions }}
                                </div>
                            </div>
                            <small class="text-muted">
                                {{ $usedSessions }} sesi telah digunakan dari {{ $ptMember->total_sessions }} sesi total
                            </small>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Informasi Tambahan</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="20%"><strong>Durasi per Sesi</strong></td>
                                    <td>: {{ $ptMember->packet->duration_minutes }} menit</td>
                                </tr>
                                <tr>
                                    <td><strong>Diproses oleh</strong></td>
                                    <td>: {{ $ptMember->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Daftar</strong></td>
                                    <td>: {{ formatTanggal($ptMember->created_at) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection