@extends('layouts.app')

@section('title', 'Perpanjang Membership')
@section('page-title', 'Perpanjang Membership')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-redo me-2"></i>
                    Perpanjang Membership
                </h6>
            </div>
            <div class="card-body">
                <!-- Member Info -->
                <div class="alert alert-info">
                    <h6><i class="fas fa-user me-2"></i>Informasi Member</h6>
                    <p class="mb-1"><strong>Nama:</strong> {{ $member->name }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $member->phone }}</p>
                    <p class="mb-0"><strong>Status:</strong> 
                        <span class="badge bg-danger">Expired</span>
                    </p>
                </div>

                <form action="{{ route('members.process-renewal', $member) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="membership_type" class="form-label">Tipe Membership <span class="text-danger">*</span></label>
                        <select class="form-select @error('membership_type') is-invalid @enderror" 
                                id="membership_type" name="membership_type" required>
                            <option value="">Pilih Tipe Membership</option>
                            <option value="daily" {{ old('membership_type') == 'daily' ? 'selected' : '' }}>Harian - Rp 25.000</option>
                            <option value="monthly" {{ old('membership_type') == 'monthly' ? 'selected' : '' }}>Bulanan - Rp 150.000</option>
                            <option value="yearly" {{ old('membership_type') == 'yearly' ? 'selected' : '' }}>Tahunan - Rp 1.500.000</option>
                            <option value="custom" {{ old('membership_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('membership_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Berakhir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}" required readonly>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="custom_price_field" style="display: none;">
                        <label for="membership_price" class="form-label">Harga Custom <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('membership_price') is-invalid @enderror" 
                                   id="membership_price" name="membership_price" value="{{ old('membership_price') }}" 
                                   min="0" step="1000">
                        </div>
                        @error('membership_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" 
                                id="payment_method" name="payment_method" required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('members.expired') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-redo me-1"></i> Perpanjang Membership
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Get last membership end date for renewal
    const lastMembership = @json($member->memberships->sortByDesc('end_date')->first());
    
    // Set start date to day after last membership ended, or today if no previous membership
    if (lastMembership && lastMembership.end_date) {
        const lastEndDate = new Date(lastMembership.end_date);
        lastEndDate.setDate(lastEndDate.getDate() + 1);
        const nextStartDate = lastEndDate.toISOString().split('T')[0];
        $('#start_date').val(nextStartDate);
    }
    
    // Auto calculate end date based on membership type
    $('#membership_type, #start_date').on('change', function() {
        const type = $('#membership_type').val();
        const startDate = $('#start_date').val();
        
        if (type && startDate) {
            const start = new Date(startDate);
            let endDate;
            
            if (type === 'daily') {
                // Daily membership is only for the selected day
                endDate = new Date(start);
                $('#custom_price_field').hide();
                $('#membership_price').removeAttr('required');
            } else if (type === 'monthly') {
                // Add 1 month, handle month overflow properly
                endDate = new Date(start);
                endDate.setMonth(start.getMonth() + 1);
                
                // Handle cases where the day doesn't exist in the target month
                // e.g., Jan 31 + 1 month should be Feb 28/29, not Mar 3
                if (endDate.getDate() !== start.getDate()) {
                    endDate.setDate(0); // Set to last day of previous month
                }
                
                $('#custom_price_field').hide();
                $('#membership_price').removeAttr('required');
            } else if (type === 'yearly') {
                // Add 1 year, handle leap year properly
                endDate = new Date(start);
                endDate.setFullYear(start.getFullYear() + 1);
                
                // Handle leap year case (Feb 29 -> Feb 28)
                if (start.getMonth() === 1 && start.getDate() === 29 && !isLeapYear(endDate.getFullYear())) {
                    endDate.setDate(28);
                }
                
                $('#custom_price_field').hide();
                $('#membership_price').removeAttr('required');
            } else if (type === 'custom') {
                // Default to 1 month for custom
                endDate = new Date(start);
                endDate.setMonth(start.getMonth() + 1);
                
                if (endDate.getDate() !== start.getDate()) {
                    endDate.setDate(0);
                }
                
                $('#custom_price_field').show();
                $('#membership_price').attr('required', true);
            }
            
            if (endDate) {
                const formattedDate = endDate.toISOString().split('T')[0];
                $('#end_date').val(formattedDate);
            }
        }
    });
    
    // Helper function to check leap year
    function isLeapYear(year) {
        return (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
    }
    
    // Show/hide custom price field
    $('#membership_type').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#custom_price_field').show();
            $('#membership_price').attr('required', true);
        } else {
            $('#custom_price_field').hide();
            $('#membership_price').removeAttr('required');
        }
    });
    
    // Trigger calculation if values are already set
    if ($('#start_date').val() && $('#membership_type').val()) {
        $('#membership_type').trigger('change');
    }
});
</script>
@endpush