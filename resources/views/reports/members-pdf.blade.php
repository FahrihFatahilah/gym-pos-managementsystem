<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Member</title>
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
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-expired {
            background-color: #f8d7da;
            color: #721c24;
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
        <h1>LAPORAN DATA MEMBER</h1>
        <p>{{ $gymSettings->gym_name }}</p>
        <p>Tanggal: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td><strong>Total Member:</strong></td>
                <td>{{ $members->count() }} member</td>
                <td><strong>Member Aktif:</strong></td>
                <td>{{ $members->where('status', 'active')->count() }} member</td>
                <td><strong>Member Expired:</strong></td>
                <td>{{ $members->where('status', 'expired')->count() }} member</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="20%">Nama</th>
                <th width="15%">No. HP</th>
                <th width="20%">Email</th>
                <th width="15%">Status</th>
                <th width="15%">Tipe Membership</th>
                <th width="15%">Berakhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $member)
                <tr class="{{ $member->status == 'active' ? 'status-active' : 'status-expired' }}">
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->phone }}</td>
                    <td>{{ $member->email ?? '-' }}</td>
                    <td class="text-center">
                        @if($member->status == 'active')
                            <strong>AKTIF</strong>
                        @else
                            <strong>EXPIRED</strong>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($member->activeMembership)
                            {{ ucfirst($member->activeMembership->type) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if($member->activeMembership)
                            {{ Carbon\Carbon::parse($member->activeMembership->end_date)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Catatan:</strong> Member aktif ditandai dengan warna hijau, expired dengan warna merah</p>
    </div>
</body>
</html>