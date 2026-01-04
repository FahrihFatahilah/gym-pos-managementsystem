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
                <td><strong>Total Transaksi:</strong></td>
                <td class="value">{{ $transactions->count() }} transaksi</td>
                <td><strong>Total Penjualan:</strong></td>
                <td class="text-right value">Rp {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }}</td>
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
                <td colspan="5" class="text-right"><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p><strong>{{ $gymSettings->gym_name }}</strong></p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>