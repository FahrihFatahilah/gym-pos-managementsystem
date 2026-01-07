@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Member PT</h3>
                </div>
                
                <form action="{{ route('pt-members.update', $ptMember) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <h5>Informasi Personal</h5>
                                
                                <div class="form-group">
                                    <label for="name">Nama Lengkap *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $ptMember->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">Nomor Telepon *</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $ptMember->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $ptMember->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address">Alamat</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3">{{ old('address', $ptMember->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Package Information (Read Only) -->
                            <div class="col-md-6">
                                <h5>Informasi Paket PT</h5>
                                
                                <div class="form-group">
                                    <label>Personal Trainer</label>
                                    <input type="text" class="form-control" value="{{ $ptMember->personalTrainer->name }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Paket</label>
                                    <input type="text" class="form-control" 
                                           value="{{ ucfirst($ptMember->packet->type) }} - {{ $ptMember->packet->name }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Periode</label>
                                    <input type="text" class="form-control" 
                                           value="{{ formatTanggal($ptMember->start_date) }} - {{ formatTanggal($ptMember->end_date) }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="sessions_remaining">Sesi Tersisa *</label>
                                    <input type="number" class="form-control @error('sessions_remaining') is-invalid @enderror" 
                                           id="sessions_remaining" name="sessions_remaining" 
                                           value="{{ old('sessions_remaining', $ptMember->sessions_remaining) }}" 
                                           min="0" max="{{ $ptMember->total_sessions }}" required>
                                    <small class="form-text text-muted">
                                        Total sesi: {{ $ptMember->total_sessions }}
                                    </small>
                                    @error('sessions_remaining')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="notes">Catatan</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3">{{ old('notes', $ptMember->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Current Status Display -->
                                <div class="alert alert-info">
                                    <h6>Status Saat Ini:</h6>
                                    <p><strong>Status:</strong> 
                                        @if($ptMember->status === 'active')
                                            <span class="badge badge-success">Aktif</span>
                                        @elseif($ptMember->status === 'expired')
                                            <span class="badge badge-warning">Expired</span>
                                        @else
                                            <span class="badge badge-secondary">Selesai</span>
                                        @endif
                                    </p>
                                    <p><strong>Sesi:</strong> {{ $ptMember->sessions_remaining }}/{{ $ptMember->total_sessions }}</p>
                                    <p><strong>Harga:</strong> {{ $ptMember->formatted_amount }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('pt-members.show', $ptMember) }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection