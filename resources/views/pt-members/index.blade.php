@extends('layouts.app')

@section('page-title', 'Member Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="page-title">
                            <i class="fas fa-users gradient-icon"></i>
                            Member Saya
                        </h2>
                        <p class="page-subtitle">Kelola dan pantau perkembangan member yang Anda latih</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="stats-cards">
                            <div class="stat-item">
                                <div class="stat-number">{{ $members->total() }}</div>
                                <div class="stat-label">Total Member</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card members-card">
                <div class="card-header gradient-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Daftar Member
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($members->count() > 0)
                        <div class="members-grid">
                            @foreach($members as $member)
                            <div class="member-card" onclick="window.location='{{ route('pt-members.show', $member) }}'">
                                <div class="member-card-header">
                                    <div class="member-avatar-small">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="member-status-indicator status-{{ $member->status }}"></div>
                                </div>
                                
                                <div class="member-card-body">
                                    <h6 class="member-card-name">{{ $member->name }}</h6>
                                    
                                    <div class="member-card-info">
                                        @if($member->phone)
                                        <div class="info-row">
                                            <i class="fas fa-phone"></i>
                                            <span>{{ $member->phone }}</span>
                                        </div>
                                        @endif
                                        
                                        @if($member->email)
                                        <div class="info-row">
                                            <i class="fas fa-envelope"></i>
                                            <span>{{ Str::limit($member->email, 20) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="membership-info">
                                        @if($member->activeMembership)
                                            <div class="membership-active">
                                                <i class="fas fa-calendar-check"></i>
                                                <span>Berakhir {{ $member->activeMembership->end_date->format('d/m/Y') }}</span>
                                            </div>
                                        @else
                                            <div class="membership-inactive">
                                                <i class="fas fa-calendar-times"></i>
                                                <span>Tidak ada membership aktif</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="member-card-footer">
                                    <span class="status-badge status-{{ $member->status }}">
                                        <i class="fas fa-{{ $member->status === 'active' ? 'check-circle' : 'times-circle' }}"></i>
                                        {{ ucfirst($member->status) }}
                                    </span>
                                    <div class="card-actions">
                                        <button class="btn-action" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="pagination-wrapper">
                            {{ $members->links() }}
                        </div>
                    @else
                        <div class="empty-state-large">
                            <div class="empty-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4>Belum Ada Member</h4>
                            <p>Anda belum memiliki member yang ditugaskan. Hubungi admin untuk menugaskan member kepada Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    padding: 2rem;
    border-radius: 12px;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.gradient-icon {
    color: rgba(255, 255, 255, 0.9);
    font-size: 2.5rem;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.stats-cards {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.stat-item {
    background: rgba(255, 255, 255, 0.15);
    padding: 1rem 1.5rem;
    border-radius: 8px;
    text-align: center;
    backdrop-filter: blur(10px);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
    margin-top: 0.25rem;
}

.members-card {
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border-radius: 12px;
    overflow: hidden;
}

.gradient-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--info-color) 100%);
    color: white;
    border: none;
    padding: 1.5rem;
}

.members-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
}

.member-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.2s ease;
    cursor: pointer;
    overflow: hidden;
    border: 1px solid var(--gray-200);
}

.member-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    border-color: var(--primary-color);
}

.member-card-header {
    background: var(--gray-50);
    padding: 1.5rem;
    position: relative;
    text-align: center;
}

.member-avatar-small {
    width: 60px;
    height: 60px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
}

.member-status-indicator {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.member-status-indicator.status-active {
    background: var(--success-color);
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

.member-status-indicator.status-expired {
    background: var(--danger-color);
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
}

.member-card-body {
    padding: 1.5rem;
}

.member-card-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 1rem;
    text-align: center;
}

.member-card-info {
    margin-bottom: 1rem;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: var(--gray-600);
}

.info-row i {
    width: 16px;
    color: var(--gray-500);
}

.membership-info {
    padding: 0.75rem;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.membership-active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 600;
}

.membership-inactive {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 600;
}

.member-card-footer {
    padding: 1rem 1.5rem;
    background: var(--gray-50);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid var(--gray-200);
}

.status-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 16px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.status-active {
    background: var(--success-color);
    color: white;
}

.status-badge.status-expired {
    background: var(--danger-color);
    color: white;
}

.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.btn-action:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
}

.empty-state-large {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--gray-500);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}

.empty-state-large h4 {
    color: var(--gray-700);
    margin-bottom: 1rem;
}

.pagination-wrapper {
    padding: 1.5rem;
    border-top: 1px solid var(--gray-200);
    background: var(--gray-50);
}

@media (max-width: 768px) {
    .page-header {
        text-align: center;
    }
    
    .page-title {
        font-size: 1.8rem;
        justify-content: center;
    }
    
    .stats-cards {
        justify-content: center;
        margin-top: 1rem;
    }
    
    .members-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
    }
}
</style>
@endsection