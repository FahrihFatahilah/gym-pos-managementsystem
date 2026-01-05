@extends('layouts.app')

@section('title', 'Detail Member')
@section('page-title', 'Detail Member')

@section('content')
<div class="container-fluid">
    <!-- Member Profile Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card member-profile-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <div class="member-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3 class="member-name mb-2">{{ $member->name ?? 'Data tidak tersedia' }}</h3>
                            <div class="member-info">
                                <span class="info-item">
                                    <i class="fas fa-phone text-primary"></i>
                                    {{ $member->phone ?? 'Tidak ada' }}
                                </span>
                                <span class="info-item">
                                    <i class="fas fa-envelope text-info"></i>
                                    {{ $member->email ?? 'Tidak ada' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="member-status mb-2">
                                <span class="status-badge status-{{ $member->status ?? 'active' }}">
                                    <i class="fas fa-{{ ($member->status ?? 'active') === 'active' ? 'check-circle' : 'times-circle' }}"></i>
                                    {{ ucfirst($member->status ?? 'active') }}
                                </span>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt"></i>
                                Bergabung {{ formatTanggal($member->created_at) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Member Details -->
        <div class="col-lg-8 mb-4">
            <div class="card info-card">
                <div class="card-header gradient-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Informasi Detail
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Alamat
                                </label>
                                <div class="info-value">{{ $member->address ?? 'Belum diisi' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-bullseye"></i>
                                    Tujuan Fitness
                                </label>
                                <div class="info-value">{{ $member->fitness_goals ?? 'Belum diisi' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-dumbbell"></i>
                                    Personal Trainer
                                </label>
                                <div class="info-value">
                                    @if($member->personalTrainer)
                                        <span class="badge bg-info">{{ $member->personalTrainer->name }}</span>
                                    @else
                                        Tidak ada
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <label class="info-label">
                                    <i class="fas fa-clock"></i>
                                    Terakhir Update
                                </label>
                                <div class="info-value">{{ formatTanggal($member->updated_at, true) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Membership Statistics -->
            <div class="card mt-4">
                <div class="card-header gradient-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Statistik Membership
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-number">{{ $member->memberships->count() }}</div>
                                <div class="stat-label">Total Membership</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-number">{{ $member->payments->where('status', 'completed')->count() }}</div>
                                <div class="stat-label">Pembayaran Selesai</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-number">Rp {{ number_format($member->payments->where('status', 'completed')->sum('amount'), 0, ',', '.') }}</div>
                                <div class="stat-label">Total Pembayaran</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-number">{{ $member->memberships->where('status', 'active')->count() }}</div>
                                <div class="stat-label">Membership Aktif</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Membership History -->
        <div class="col-lg-4 mb-4">
            <div class="card membership-card">
                <div class="card-header gradient-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Riwayat Membership
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($member->memberships->count() > 0)
                        <div class="membership-timeline">
                            @foreach($member->memberships->sortByDesc('created_at') as $membership)
                            <div class="timeline-item {{ $membership->status === 'active' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="membership-type">{{ $membership->type }}</h6>
                                        <span class="membership-status status-{{ $membership->status }}">
                                            {{ ucfirst($membership->status) }}
                                        </span>
                                    </div>
                                    <div class="membership-period">
                                        <i class="fas fa-calendar"></i>
                                        {{ formatTanggal($membership->start_date) }} - {{ formatTanggal($membership->end_date) }}
                                    </div>
                                    <div class="membership-price">
                                        <i class="fas fa-tag"></i>
                                        Rp {{ number_format($membership->price, 0, ',', '.') }}
                                    </div>
                                    @if($membership->payments->count() > 0)
                                        <div class="payment-info">
                                            <i class="fas fa-credit-card"></i>
                                            {{ $membership->payments->count() }} pembayaran
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Belum ada riwayat membership</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="action-buttons">
                <a href="{{ route('members.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Daftar Member
                </a>
                <a href="{{ route('members.edit', $member->id ?? 1) }}" class="btn btn-edit">
                    <i class="fas fa-edit"></i>
                    Edit Member
                </a>
                @if($member->status !== 'active')
                <a href="{{ route('members.renew', $member->id ?? 1) }}" class="btn btn-renew">
                    <i class="fas fa-refresh"></i>
                    Perpanjang Membership
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.member-profile-card {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
}

.member-avatar {
    font-size: 4rem;
    color: rgba(255, 255, 255, 0.9);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.member-name {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.member-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.95rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: var(--success-color);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.status-expired {
    background: var(--danger-color);
    color: white;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.gradient-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--info-color) 100%);
    color: white;
    border: none;
}

.info-card, .membership-card {
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border-radius: 12px;
    overflow: hidden;
}

.info-group {
    padding: 1.5rem;
    background: var(--gray-50);
    border-radius: 8px;
    transition: transform 0.2s ease;
    border-left: 4px solid var(--primary-color);
}

.info-group:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.info-value {
    font-size: 1.1rem;
    color: var(--gray-800);
    font-weight: 500;
}

.stat-box {
    padding: 1rem;
    background: var(--white);
    border-radius: 8px;
    margin-bottom: 1rem;
    border: 1px solid var(--gray-200);
    transition: all 0.2s ease;
}

.stat-box:hover {
    border-color: var(--primary-color);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.1);
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
}

.stat-label {
    font-size: 0.85rem;
    color: var(--gray-600);
    margin-top: 0.25rem;
}

.membership-timeline {
    padding: 1rem;
}

.timeline-item {
    position: relative;
    padding-left: 2rem;
    padding-bottom: 1.5rem;
    border-left: 2px solid var(--gray-200);
}

.timeline-item.active {
    border-left-color: var(--success-color);
}

.timeline-item:last-child {
    border-left: none;
}

.timeline-marker {
    position: absolute;
    left: -6px;
    top: 0;
    width: 12px;
    height: 12px;
    background: var(--gray-300);
    border-radius: 50%;
    border: 2px solid white;
}

.timeline-item.active .timeline-marker {
    background: var(--success-color);
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}

.timeline-content {
    background: var(--gray-50);
    padding: 1rem;
    border-radius: 6px;
    border-left: 3px solid var(--gray-200);
}

.timeline-item.active .timeline-content {
    border-left-color: var(--success-color);
    background: rgba(16, 185, 129, 0.05);
}

.membership-type {
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
}

.membership-status {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.membership-period, .membership-price, .payment-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--gray-600);
    margin-bottom: 0.25rem;
}

.membership-price {
    color: var(--success-color);
    font-weight: 600;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--gray-500);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.action-buttons {
    text-align: center;
    margin-top: 2rem;
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-back {
    background: var(--secondary-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(100, 116, 139, 0.2);
}

.btn-edit {
    background: var(--warning-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
}

.btn-renew {
    background: var(--success-color);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
}

.btn-back:hover, .btn-edit:hover, .btn-renew:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    color: white;
}

@media (max-width: 768px) {
    .member-name {
        font-size: 1.5rem;
    }
    
    .member-info {
        margin-top: 1rem;
    }
    
    .info-group {
        margin-bottom: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: center;
    }
}
</style>
@endsection