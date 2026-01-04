@extends('layouts.app')

@section('title', 'Pengaturan Gym - Gym & POS System')
@section('page-title', 'Pengaturan Gym')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-cog me-2"></i>
                    Pengaturan Informasi Gym
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Dasar
                            </h5>
                            
                            <div class="mb-3">
                                <label for="gym_name" class="form-label">Nama Gym <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('gym_name') is-invalid @enderror" 
                                       id="gym_name" name="gym_name" value="{{ old('gym_name', $settings->gym_name) }}" required>
                                @error('gym_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gym_address" class="form-label">Alamat Gym</label>
                                <textarea class="form-control @error('gym_address') is-invalid @enderror" 
                                          id="gym_address" name="gym_address" rows="3">{{ old('gym_address', $settings->gym_address) }}</textarea>
                                @error('gym_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gym_phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control @error('gym_phone') is-invalid @enderror" 
                                       id="gym_phone" name="gym_phone" value="{{ old('gym_phone', $settings->gym_phone) }}">
                                @error('gym_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gym_email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('gym_email') is-invalid @enderror" 
                                       id="gym_email" name="gym_email" value="{{ old('gym_email', $settings->gym_email) }}">
                                @error('gym_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gym_website" class="form-label">Website</label>
                                <input type="url" class="form-control @error('gym_website') is-invalid @enderror" 
                                       id="gym_website" name="gym_website" value="{{ old('gym_website', $settings->gym_website) }}"
                                       placeholder="https://example.com">
                                @error('gym_website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gym_description" class="form-label">Deskripsi Gym</label>
                                <textarea class="form-control @error('gym_description') is-invalid @enderror" 
                                          id="gym_description" name="gym_description" rows="3">{{ old('gym_description', $settings->gym_description) }}</textarea>
                                @error('gym_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Logo & Settings -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary">
                                <i class="fas fa-image me-2"></i>
                                Logo & Pengaturan
                            </h5>
                            
                            <!-- Logo Upload -->
                            <div class="mb-3">
                                <label for="gym_logo" class="form-label">Logo Gym</label>
                                <div class="text-center mb-3">
                                    @if($settings->gym_logo)
                                        <img src="{{ asset('storage/' . $settings->gym_logo) }}" alt="Logo Gym" 
                                             class="img-thumbnail mb-2" style="max-height: 150px;" id="logoPreview">
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeLogo">
                                                <i class="fas fa-trash me-1"></i> Hapus Logo
                                            </button>
                                        </div>
                                    @else
                                        <div class="border rounded p-4 mb-2" style="height: 150px; display: flex; align-items: center; justify-content: center;">
                                            <div class="text-muted">
                                                <i class="fas fa-image fa-3x mb-2"></i>
                                                <p>Belum ada logo</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('gym_logo') is-invalid @enderror" 
                                       id="gym_logo" name="gym_logo" accept="image/*">
                                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB</div>
                                @error('gym_logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Favicon Upload -->
                            <div class="mb-3">
                                <label for="gym_favicon" class="form-label">Icon Website (Favicon)</label>
                                <div class="text-center mb-3">
                                    @if($settings->gym_favicon)
                                        <img src="{{ asset('storage/' . $settings->gym_favicon) }}" alt="Favicon" 
                                             class="img-thumbnail mb-2" style="max-height: 32px; width: 32px;" id="faviconPreview">
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeFavicon">
                                                <i class="fas fa-trash me-1"></i> Hapus Icon
                                            </button>
                                        </div>
                                    @else
                                        <div class="border rounded p-2 mb-2" style="height: 50px; display: flex; align-items: center; justify-content: center;">
                                            <div class="text-muted">
                                                <i class="fas fa-globe fa-2x"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" class="form-control @error('gym_favicon') is-invalid @enderror" 
                                       id="gym_favicon" name="gym_favicon" accept="image/*">
                                <div class="form-text">Format: ICO, PNG (16x16 atau 32x32 pixel). Maksimal 1MB</div>
                                @error('gym_favicon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Receipt Footer -->
                            <div class="mb-3">
                                <label for="receipt_footer" class="form-label">Footer Struk</label>
                                <textarea class="form-control @error('receipt_footer') is-invalid @enderror" 
                                          id="receipt_footer" name="receipt_footer" rows="3" 
                                          placeholder="Terima kasih atas kunjungan Anda!">{{ old('receipt_footer', $settings->receipt_footer) }}</textarea>
                                <div class="form-text">Teks yang akan muncul di bagian bawah struk</div>
                                @error('receipt_footer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Membership Prices -->
                            <h6 class="mb-3 text-secondary">
                                <i class="fas fa-money-bill me-2"></i>
                                Harga Membership
                            </h6>
                            
                            <div class="mb-3">
                                <label for="membership_daily_price" class="form-label">Harga Harian <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('membership_daily_price') is-invalid @enderror" 
                                           id="membership_daily_price" name="membership_daily_price" 
                                           value="{{ old('membership_daily_price', $settings->membership_daily_price) }}" 
                                           min="0" step="1000" required>
                                </div>
                                @error('membership_daily_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="membership_monthly_price" class="form-label">Harga Bulanan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('membership_monthly_price') is-invalid @enderror" 
                                           id="membership_monthly_price" name="membership_monthly_price" 
                                           value="{{ old('membership_monthly_price', $settings->membership_monthly_price) }}" 
                                           min="0" step="1000" required>
                                </div>
                                @error('membership_monthly_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="membership_yearly_price" class="form-label">Harga Tahunan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('membership_yearly_price') is-invalid @enderror" 
                                           id="membership_yearly_price" name="membership_yearly_price" 
                                           value="{{ old('membership_yearly_price', $settings->membership_yearly_price) }}" 
                                           min="0" step="1000" required>
                                </div>
                                @error('membership_yearly_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- System Settings -->
                            <h6 class="mb-3 text-secondary">
                                <i class="fas fa-cogs me-2"></i>
                                Pengaturan Sistem
                            </h6>
                            
                            <div class="mb-3">
                                <label for="currency" class="form-label">Mata Uang <span class="text-danger">*</span></label>
                                <select class="form-select @error('currency') is-invalid @enderror" 
                                        id="currency" name="currency" required>
                                    <option value="IDR" {{ old('currency', $settings->currency) == 'IDR' ? 'selected' : '' }}>
                                        Indonesian Rupiah (IDR)
                                    </option>
                                    <option value="USD" {{ old('currency', $settings->currency) == 'USD' ? 'selected' : '' }}>
                                        US Dollar (USD)
                                    </option>
                                    <option value="EUR" {{ old('currency', $settings->currency) == 'EUR' ? 'selected' : '' }}>
                                        Euro (EUR)
                                    </option>
                                </select>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="timezone" class="form-label">Zona Waktu <span class="text-danger">*</span></label>
                                <select class="form-select @error('timezone') is-invalid @enderror" 
                                        id="timezone" name="timezone" required>
                                    <option value="Asia/Jakarta" {{ old('timezone', $settings->timezone) == 'Asia/Jakarta' ? 'selected' : '' }}>
                                        Asia/Jakarta (WIB)
                                    </option>
                                    <option value="Asia/Makassar" {{ old('timezone', $settings->timezone) == 'Asia/Makassar' ? 'selected' : '' }}>
                                        Asia/Makassar (WITA)
                                    </option>
                                    <option value="Asia/Jayapura" {{ old('timezone', $settings->timezone) == 'Asia/Jayapura' ? 'selected' : '' }}>
                                        Asia/Jayapura (WIT)
                                    </option>
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Simpan Pengaturan
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
    // Preview logo before upload
    $('#gym_logo').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#logoPreview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });

    // Preview favicon before upload
    $('#gym_favicon').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#faviconPreview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove logo
    $('#removeLogo').on('click', function() {
        Swal.fire({
            title: 'Hapus Logo?',
            text: 'Logo akan dihapus dari sistem',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("settings.remove-logo") }}',
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus logo', 'error');
                    }
                });
            }
        });
    });

    // Remove favicon
    $('#removeFavicon').on('click', function() {
        Swal.fire({
            title: 'Hapus Icon?',
            text: 'Icon website akan dihapus dari sistem',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("settings.remove-favicon") }}',
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus icon', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush