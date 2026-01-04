@extends('layouts.app')

@section('title', 'Point of Sale - Gym & POS System')
@section('page-title', 'Point of Sale')

@section('content')
<div class="row">
    <!-- Product List -->
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-box me-2"></i>
                            Daftar Produk
                        </h6>
                    </div>
                    <div class="col-auto">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchProduct" 
                                   placeholder="Cari produk...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="productList">
                    @foreach($products as $product)
                        <div class="col-md-4 col-sm-6 mb-3 product-item" 
                             data-name="{{ strtolower($product->name) }}">
                            <div class="card product-card h-100" 
                                 data-id="{{ $product->id }}"
                                 data-name="{{ $product->name }}"
                                 data-price="{{ $product->price }}"
                                 data-stock="{{ $product->stock }}"
                                 style="cursor: pointer;">
                                <div class="card-body text-center">
                                    <div class="mb-2">
                                        <i class="fas fa-bottle-water fa-3x text-primary"></i>
                                    </div>
                                    <h6 class="card-title">{{ $product->name }}</h6>
                                    <p class="card-text">
                                        <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong><br>
                                        <small class="text-muted">Stok: {{ $product->stock }} {{ $product->unit }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Cart -->
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Keranjang Belanja
                </h6>
            </div>
            <div class="card-body">
                <div id="cartItems">
                    <div class="text-center text-muted py-4" id="emptyCart">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                        <p>Keranjang kosong</p>
                    </div>
                </div>
                
                <hr>
                
                <!-- Discount & Tax -->
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Diskon (%)</label>
                        <input type="number" class="form-control" id="discountPercent" 
                               min="0" max="100" value="0" step="0.1">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Pajak (%)</label>
                        <input type="number" class="form-control" id="taxPercent" 
                               min="0" max="100" value="0" step="0.1">
                    </div>
                </div>
                
                <!-- Calculation Summary -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <span id="subtotalAmount">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between text-danger">
                        <span>Diskon:</span>
                        <span id="discountAmount">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between text-warning">
                        <span>Pajak:</span>
                        <span id="taxAmount">Rp 0</span>
                    </div>
                </div>
                
                <hr>
                
                <!-- Total -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Total:</h5>
                    <h5 class="mb-0 text-success" id="totalAmount">Rp 0</h5>
                </div>
                
                <!-- Payment Method -->
                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select class="form-select" id="paymentMethod">
                        <option value="cash">Tunai</option>
                        <option value="qris">QRIS</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button class="btn btn-success btn-lg" id="processPayment" disabled>
                        <i class="fas fa-credit-card me-2"></i>
                        Proses Pembayaran
                    </button>
                    <button class="btn btn-outline-danger" id="clearCart">
                        <i class="fas fa-trash me-2"></i>
                        Kosongkan Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let cart = [];
let subtotal = 0;
let discountPercent = 0;
let taxPercent = 0;
let discountAmount = 0;
let taxAmount = 0;
let total = 0;

$(document).ready(function() {
    // Search products
    $('#searchProduct').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.product-item').each(function() {
            const productName = $(this).data('name');
            if (productName.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Add to cart
    $('.product-card').on('click', function() {
        const productId = $(this).data('id');
        const productName = $(this).data('name');
        const productPrice = $(this).data('price');
        const productStock = $(this).data('stock');

        if (productStock <= 0) {
            Swal.fire('Error', 'Stok produk habis!', 'error');
            return;
        }

        addToCart(productId, productName, productPrice, productStock);
    });

    // Process payment
    $('#processPayment').on('click', function() {
        if (cart.length === 0) {
            Swal.fire('Error', 'Keranjang kosong!', 'error');
            return;
        }

        const paymentMethod = $('#paymentMethod').val();
        
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            text: `Total: Rp ${total.toLocaleString('id-ID')}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Proses',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                processTransaction(paymentMethod);
            }
        });
    });

    // Clear cart
    $('#clearCart').on('click', function() {
        if (cart.length === 0) return;
        
        Swal.fire({
            title: 'Kosongkan Keranjang?',
            text: 'Semua item akan dihapus dari keranjang',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kosongkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                clearCart();
            }
        });
    });
    
    // Discount and tax calculation
    $('#discountPercent, #taxPercent').on('input', function() {
        calculateTotal();
    });
});

function addToCart(productId, productName, productPrice, productStock) {
    const existingItem = cart.find(item => item.product_id === productId);
    
    if (existingItem) {
        if (existingItem.quantity >= productStock) {
            Swal.fire('Error', 'Jumlah melebihi stok yang tersedia!', 'error');
            return;
        }
        existingItem.quantity++;
    } else {
        cart.push({
            product_id: productId,
            name: productName,
            price: productPrice,
            quantity: 1,
            stock: productStock
        });
    }
    
    updateCartDisplay();
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.product_id !== productId);
    updateCartDisplay();
}

function updateQuantity(productId, quantity) {
    const item = cart.find(item => item.product_id === productId);
    if (item) {
        if (quantity <= 0) {
            removeFromCart(productId);
        } else if (quantity <= item.stock) {
            item.quantity = quantity;
            updateCartDisplay();
        } else {
            Swal.fire('Error', 'Jumlah melebihi stok yang tersedia!', 'error');
        }
    }
}

function updateCartDisplay() {
    const cartContainer = $('#cartItems');
    const emptyCart = $('#emptyCart');
    
    if (cart.length === 0) {
        emptyCart.show();
        $('#processPayment').prop('disabled', true);
        subtotal = 0;
    } else {
        emptyCart.hide();
        $('#processPayment').prop('disabled', false);
        
        let cartHtml = '';
        subtotal = 0;
        
        cart.forEach(item => {
            const itemSubtotal = item.price * item.quantity;
            subtotal += itemSubtotal;
            
            cartHtml += `
                <div class="cart-item mb-3 p-2 border rounded">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${item.name}</h6>
                            <small class="text-muted">Rp ${item.price.toLocaleString('id-ID')}</small>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.product_id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="input-group input-group-sm" style="width: 120px;">
                            <button class="btn btn-outline-secondary" type="button" 
                                    onclick="updateQuantity(${item.product_id}, ${item.quantity - 1})">-</button>
                            <input type="text" class="form-control text-center" value="${item.quantity}" readonly>
                            <button class="btn btn-outline-secondary" type="button" 
                                    onclick="updateQuantity(${item.product_id}, ${item.quantity + 1})">+</button>
                        </div>
                        <strong>Rp ${itemSubtotal.toLocaleString('id-ID')}</strong>
                    </div>
                </div>
            `;
        });
        
        cartContainer.html(cartHtml);
    }
    
    calculateTotal();
}

function calculateTotal() {
    discountPercent = parseFloat($('#discountPercent').val()) || 0;
    taxPercent = parseFloat($('#taxPercent').val()) || 0;
    
    discountAmount = (subtotal * discountPercent) / 100;
    const afterDiscount = subtotal - discountAmount;
    taxAmount = (afterDiscount * taxPercent) / 100;
    total = afterDiscount + taxAmount;
    
    $('#subtotalAmount').text(`Rp ${subtotal.toLocaleString('id-ID')}`);
    $('#discountAmount').text(`-Rp ${discountAmount.toLocaleString('id-ID')}`);
    $('#taxAmount').text(`Rp ${taxAmount.toLocaleString('id-ID')}`);
    $('#totalAmount').text(`Rp ${total.toLocaleString('id-ID')}`);
}

function clearCart() {
    cart = [];
    $('#discountPercent').val(0);
    $('#taxPercent').val(0);
    updateCartDisplay();
}

function processTransaction(paymentMethod) {
    const transactionData = {
        items: cart,
        payment_method: paymentMethod,
        subtotal_amount: subtotal,
        discount_percent: discountPercent,
        discount_amount: discountAmount,
        tax_percent: taxPercent,
        tax_amount: taxAmount,
        total_amount: total,
        _token: $('meta[name="csrf-token"]').attr('content')
    };
    
    $.ajax({
        url: '{{ route("pos.transaction") }}',
        method: 'POST',
        data: transactionData,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Transaksi Berhasil!',
                    text: `Kode: ${response.transaction_code}`,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Cetak Struk',
                    cancelButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open(`{{ url('pos/receipt') }}/${response.transaction_id}`, '_blank');
                    }
                });
                
                clearCart();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            Swal.fire('Error', response.message || 'Terjadi kesalahan', 'error');
        }
    });
}
</script>
@endpush

@push('styles')
<style>
.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.cart-item {
    background-color: #f8f9fa;
}

.input-group-sm .form-control {
    font-size: 0.875rem;
}
</style>
@endpush