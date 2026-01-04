<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .low-stock {
            background-color: #fff3cd;
            color: #856404;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        @php
            $gymSettings = App\Models\GymSetting::getSettings();
        @endphp
        @if($gymSettings->gym_logo)
            <img src="{{ public_path('storage/' . $gymSettings->gym_logo) }}" alt="Logo" style="max-height: 60px; margin-bottom: 10px;">
        @endif
        <h1>LAPORAN STOK PRODUK</h1>
        <p>{{ $gymSettings->gym_name }}</p>
        <p>Tanggal: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td><strong>Total Produk:</strong></td>
                <td>{{ $products->count() }} produk</td>
                <td><strong>Stok Minimum:</strong></td>
                <td>{{ $products->where('stock', '<', 10)->count() }} produk</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">SKU</th>
                <th width="30%">Nama Produk</th>
                <th width="15%" class="text-center">Stok</th>
                <th width="10%" class="text-center">Unit</th>
                <th width="15%" class="text-right">Harga Beli</th>
                <th width="15%" class="text-right">Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr class="{{ $product->stock < 10 ? 'low-stock' : '' }}">
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td class="text-center">
                        {{ $product->stock }}
                        @if($product->stock < 10)
                            <strong>(LOW)</strong>
                        @endif
                    </td>
                    <td class="text-center">{{ $product->unit }}</td>
                    <td class="text-right">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Catatan:</strong> Produk dengan stok < 10 ditandai dengan warna kuning</p>
    </div>
</body>
</html>