    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Struk - {{ $transaction->transaction_code }}</title>
        <style>
            @page {
                size: 58mm auto;
                margin: 0;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 9px;
                margin: 0;
                padding: 5mm;
                width: 58mm;
            }
            .receipt {
                width: 100%;
                margin: 0;
            }
            .header {
                text-align: center;
                margin-bottom: 8px;
                border-bottom: 1px solid #000;
                padding-bottom: 5px;
            }
            .header h1 {
                margin: 0;
                font-size: 12px;
                font-weight: bold;
            }
            .header p {
                margin: 2px 0;
                font-size: 8px;
            }
            .header img {
                max-height: 30px;
                margin-bottom: 3px;
            }
            .transaction-info {
                margin-bottom: 8px;
                font-size: 8px;
            }
            .transaction-info div {
                display: flex;
                justify-content: space-between;
                margin-bottom: 2px;
            }
            .items {
                margin-bottom: 8px;
            }
            .item {
                margin-bottom: 5px;
                padding-bottom: 3px;
                border-bottom: 1px dashed #ccc;
            }
            .item-name {
                font-weight: bold;
                font-size: 9px;
            }
            .item-details {
                display: flex;
                justify-content: space-between;
                font-size: 8px;
            }
            .total-section {
                border-top: 1px solid #000;
                padding-top: 5px;
                margin-top: 8px;
            }
            .total-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 3px;
                font-size: 8px;
            }
            .total-row.final {
                font-weight: bold;
                font-size: 10px;
                border-top: 1px solid #000;
                padding-top: 3px;
                margin-top: 3px;
            }
            .footer {
                text-align: center;
                margin-top: 8px;
                border-top: 1px dashed #ccc;
                padding-top: 5px;
                font-size: 7px;
            }
            .footer p {
                margin: 2px 0;
            }
        </style>
    </head>
    <body>
        <div class="receipt">
            <!-- Header -->
            <div class="header">
                @php
                    $gymSettings = App\Models\GymSetting::getSettings();
                @endphp
                @if($gymSettings->gym_logo)
                    <img src="{{ public_path('storage/' . $gymSettings->gym_logo) }}" alt="Logo">
                @endif
                <h1>{{ strtoupper($gymSettings->gym_name) }}</h1>
                <p>{{ $gymSettings->gym_address ?: 'Jl. Contoh No. 123, Jakarta' }}</p>
                <p>Telp: {{ $gymSettings->gym_phone ?: '(021) 1234-5678' }}</p>
            </div>

            <!-- Transaction Info -->
            <div class="transaction-info">
                <div>
                    <span>No. Transaksi:</span>
                    <span>{{ $transaction->transaction_code }}</span>
                </div>
                <div>
                    <span>Tanggal:</span>
                    <span>{{ formatTanggal($transaction->created_at, true) }}</span>
                </div>
                <div>
                    <span>Kasir:</span>
                    <span>{{ $transaction->user->name }}</span>
                </div>
                <div>
                    <span>Pembayaran:</span>
                    <span>{{ $transaction->getPaymentMethodLabel() }}</span>
                </div>
            </div>

            <!-- Items -->
            <div class="items">
                @foreach($transaction->details as $detail)
                    <div class="item">
                        <div class="item-name">{{ $detail->product->name }}</div>
                        <div class="item-details">
                            <span>{{ $detail->quantity }} x Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
                            <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>Pajak (0%):</span>
                    <span>Rp 0</span>
                </div>
                <div class="total-row final">
                    <span>TOTAL:</span>
                    <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                @if($gymSettings->receipt_footer)
                    @foreach(explode('\n', $gymSettings->receipt_footer) as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                @else
                    <p>Terima kasih atas kunjungan Anda!</p>
                    <p>Semoga sehat selalu</p>
                @endif
                <p>{{ formatTanggal(now(), true) }}</p>
            </div>
        </div>
    </body>
    </html>
