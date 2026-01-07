<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px;
            background: #2563eb;
            color: white;
            border-radius: 8px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .logo {
            max-height: 40px;
            margin-bottom: 10px;
        }
        
        .info {
            margin-bottom: 20px;
            background: #f8fafc;
            padding: 12px;
            border-radius: 6px;
            border-left: 4px solid #2563eb;
        }
        
        .info table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info td {
            padding: 6px 8px;
            font-weight: 500;
        }
        
        .info .value {
            color: #2563eb;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        th {
            background: #1e40af;
            color: white;
            padding: 10px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }
        
        td {
            border: 1px solid #e5e7eb;
            padding: 8px 6px;
            font-size: 10px;
        }
        
        tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total {
            font-weight: bold;
            background: #10b981 !important;
            color: white;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        @php
            $gymSettings = App\Models\GymSetting::getSettings();
        @endphp
        @if($gymSettings->gym_logo)
            <img src="{{ public_path('storage/' . $gymSettings->gym_logo) }}" alt="Logo" class="logo">
        @endif
        <h1>Laporan Penjualan</h1>
        <p>{{ $gymSettings->gym_name }}</p>
        <p>Periode: {{ Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td><strong>Total Transaksi POS:</strong></td>
                <td class="value">{{ $transactions->count() }} transaksi</td>
                <td><strong>Total POS:</strong></td>
                <td class="text-right value">Rp {{ number_format($totalPosSales, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Membership:</strong></td>
                <td class="value">{{ $membershipPayments->count() }} pembayaran</td>
                <td><strong>Total Membership:</strong></td>
                <td class="text-right value">Rp {{ number_format($totalMembershipSales, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Daily Users:</strong></td>
                <td class="value">{{ $dailyUsers->count() }} pengunjung</td>
                <td><strong>Total Daily:</strong></td>
                <td class="text-right value">Rp {{ number_format($totalDailyUserSales, 0, ',', '.') }}</td>
            </tr>
            <tr style="background: #10b981; color: white;">
                <td colspan="2"><strong>GRAND TOTAL:</strong></td>
                <td colspan="2" class="text-right"><strong>Rp {{ number_format($totalSales, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th width="18%">Kode Transaksi</th>
                <th width="15%">Kasir</th>
                <th width="12%">Metode</th>
                <th width="25%">Items</th>
                <th width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $transaction->transaction_code }}</td>
                    <td>{{ $transaction->user->name ?? 'System' }}</td>
                    <td>{{ $transaction->getPaymentMethodLabel() }}</td>
                    <td>
                        @foreach($transaction->details as $detail)
                            {{ $detail->product->name }} ({{ $detail->quantity }}x)<br>
                        @endforeach
                    </td>
                    <td class="text-right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="5" class="text-right"><strong>TOTAL POS:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPosSales, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Membership Payments Table -->
    @if($membershipPayments->count() > 0)
    <h3 style="margin-top: 30px; color: #1e40af;">Pembayaran Membership</h3>
    <table>
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th width="25%">Member</th>
                <th width="20%">Tipe</th>
                <th width="15%">Metode</th>
                <th width="25%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($membershipPayments as $payment)
                <tr>
                    <td>{{ Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}</td>
                    <td>{{ $payment->member->name }}</td>
                    <td>{{ ucfirst($payment->membership->type ?? 'N/A') }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td class="text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4" class="text-right"><strong>TOTAL MEMBERSHIP:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalMembershipSales, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
    @endif

    <!-- Daily Users Table -->
    @if($dailyUsers->count() > 0)
    <h3 style="margin-top: 30px; color: #1e40af;">Pengunjung Harians</h3>
    <table>
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th width="25%">Nama</th>
                <th width="20%">Kontak</th>
                <th width="15%">Metode</th>
                <th width="25%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyUsers as $user)
                <tr>
                    <td>{{ $user->visit_date->format('d/m/Y') }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ ucfirst($user->payment_method) }}</td>
                    <td class="text-right">Rp {{ number_format($user->amount_paid, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4" class="text-right"><strong>TOTAL DAILY:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalDailyUserSales, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
    @endif

    <!-- Grand Total -->
    <table style="margin-top: 30px; border: 2px solid #10b981;">
        <tr style="background: #10b981; color: white;">
            <td colspan="4" class="text-right" style="padding: 15px; font-size: 14px;"><strong>GRAND TOTAL SEMUA PENJUALAN:</strong></td>
            <td class="text-right" style="padding: 15px; font-size: 14px;"><strong>Rp {{ number_format($totalSales, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <div class="footer">
        <p><strong>{{ $gymSettings->gym_name }}</strong></p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>