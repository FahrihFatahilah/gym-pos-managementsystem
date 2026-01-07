@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Paket</h3>
                </div>
                
                <form action="{{ route('packets.update', $packet) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Paket *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $packet->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="type">Tipe Paket *</label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required onchange="toggleDurationField()">
                                        <option value="">Pilih Tipe</option>
                                        <option value="individual" {{ old('type', $packet->type) == 'individual' ? 'selected' : '' }}>Individual PT</option>
                                        <option value="couple" {{ old('type', $packet->type) == 'couple' ? 'selected' : '' }}>Couple PT</option>
                                        <option value="group" {{ old('type', $packet->type) == 'group' ? 'selected' : '' }}>Group PT</option>
                                        <option value="daily" {{ old('type', $packet->type) == 'daily' ? 'selected' : '' }}>Daily Pass</option>
                                        <option value="membership" {{ old('type', $packet->type) == 'membership' ? 'selected' : '' }}>Membership</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" id="duration-field" style="display: none;">
                                    <label for="duration_days">Durasi (Hari) *</label>
                                    <input type="number" class="form-control @error('duration_days') is-invalid @enderror" 
                                           id="duration_days" name="duration_days" value="{{ old('duration_days', $packet->duration_days) }}" min="1">
                                    @error('duration_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" id="sessions-field" style="display: none;">
                                    <label for="sessions">Jumlah Sesi *</label>
                                    <input type="number" class="form-control @error('sessions') is-invalid @enderror" 
                                           id="sessions" name="sessions" value="{{ old('sessions', $packet->sessions) }}" min="1">
                                    @error('sessions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" id="membership-duration-field" style="display: none;">
                                    <label for="membership_months">Durasi Membership *</label>
                                    <select class="form-control @error('membership_months') is-invalid @enderror" 
                                            id="membership_months" name="membership_months" onchange="calculateMembershipEndDate()">
                                        <option value="">Pilih Durasi</option>
                                        <option value="1" {{ old('membership_months', $packet->membership_months ?? '') == '1' ? 'selected' : '' }}>1 Bulan</option>
                                        <option value="3" {{ old('membership_months', $packet->membership_months ?? '') == '3' ? 'selected' : '' }}>3 Bulan</option>
                                        <option value="6" {{ old('membership_months', $packet->membership_months ?? '') == '6' ? 'selected' : '' }}>6 Bulan</option>
                                        <option value="12" {{ old('membership_months', $packet->membership_months ?? '') == '12' ? 'selected' : '' }}>12 Bulan</option>
                                    </select>
                                    @error('membership_months')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" id="membership-dates-field" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="start_date">Tanggal Mulai *</label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                                   id="start_date" name="start_date" value="{{ old('start_date', $packet->start_date ?? date('Y-m-d')) }}" onchange="calculateMembershipEndDate()">
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="end_date">Tanggal Selesai *</label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                                   id="end_date" name="end_date" value="{{ old('end_date', $packet->end_date ?? '') }}" readonly>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="price">Harga *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price', $packet->price) }}" min="0" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Keterangan</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4">{{ old('description', $packet->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $packet->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('packets.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDurationField() {
    const typeSelect = document.getElementById('type');
    const durationField = document.getElementById('duration-field');
    const sessionsField = document.getElementById('sessions-field');
    const durationMinutesField = document.getElementById('duration-minutes-field');
    const membershipDurationField = document.getElementById('membership-duration-field');
    const membershipDatesField = document.getElementById('membership-dates-field');
    const durationInput = document.getElementById('duration_days');
    const sessionsInput = document.getElementById('sessions');
    const durationMinutesInput = document.getElementById('duration_minutes');
    const membershipMonthsInput = document.getElementById('membership_months');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    const ptTypes = ['individual', 'couple', 'group'];
    const membershipTypes = ['membership'];
    
    if (ptTypes.includes(typeSelect.value)) {
        durationField.style.display = 'block';
        sessionsField.style.display = 'block';
        durationMinutesField.style.display = 'block';
        membershipDurationField.style.display = 'none';
        membershipDatesField.style.display = 'none';
        durationInput.disabled = false;
        sessionsInput.disabled = false;
        durationMinutesInput.disabled = false;
        membershipMonthsInput.disabled = true;
        startDateInput.disabled = true;
        endDateInput.disabled = true;
    } else if (membershipTypes.includes(typeSelect.value)) {
        durationField.style.display = 'none';
        sessionsField.style.display = 'none';
        durationMinutesField.style.display = 'none';
        membershipDurationField.style.display = 'block';
        membershipDatesField.style.display = 'block';
        durationInput.disabled = true;
        sessionsInput.disabled = true;
        durationMinutesInput.disabled = true;
        membershipMonthsInput.disabled = false;
        startDateInput.disabled = false;
        endDateInput.disabled = false;
        durationInput.value = 30;
        sessionsInput.value = 0;
        durationMinutesInput.value = 0;
    } else {
        durationField.style.display = 'none';
        sessionsField.style.display = 'none';
        durationMinutesField.style.display = 'none';
        membershipDurationField.style.display = 'none';
        membershipDatesField.style.display = 'none';
        durationInput.disabled = true;
        sessionsInput.disabled = true;
        durationMinutesInput.disabled = true;
        membershipMonthsInput.disabled = true;
        startDateInput.disabled = true;
        endDateInput.disabled = true;
        durationInput.value = 30;
        sessionsInput.value = 0;
        durationMinutesInput.value = 0;
    }
}

function calculateMembershipEndDate() {
    const startDate = document.getElementById('start_date').value;
    const months = document.getElementById('membership_months').value;
    
    if (startDate && months) {
        const start = new Date(startDate);
        const end = new Date(start);
        end.setMonth(start.getMonth() + parseInt(months));
        end.setDate(end.getDate() - 1);
        
        const endDateString = end.toISOString().split('T')[0];
        document.getElementById('end_date').value = endDateString;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleDurationField();
});
</script>
@endsection