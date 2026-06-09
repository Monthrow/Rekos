<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1e293b; }
        
        .header { background: #1e293b; color: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; }
        .header h1 { font-size: 20px; font-weight: bold; }
        .header p { font-size: 11px; opacity: 0.85; margin-top: 4px; }

        .stats { display: flex; gap: 12px; margin-bottom: 20px; }
        .stat-box { flex: 1; background: #f1f5f9; border-left: 4px solid #1e293b; padding: 12px; border-radius: 6px; }
        .stat-box .label { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-box .value { font-size: 16px; font-weight: bold; color: #1e293b; margin-top: 3px; }
        .stat-box .value.green { color: #16a34a; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #1e293b; color: white; }
        thead th { padding: 10px 12px; text-align: left; font-size: 11px; font-weight: bold; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 9px 12px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: bold; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-selesai { background: #dcfce7; color: #166534; }
        .badge-batal { background: #fee2e2; color: #991b1b; }

        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #94a3b8; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Semua Transaksi - REKOS (Admin)</h1>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="label">Total Transaksi</div>
            <div class="value">{{ $transaksis->count() }} transaksi</div>
        </div>
        <div class="stat-box">
            <div class="label">Transaksi Selesai</div>
            <div class="value">{{ $transaksis->where('status', 'Selesai')->count() }} transaksi</div>
        </div>
        <div class="stat-box">
            <div class="label">Transaksi Pending</div>
            <div class="value">{{ $transaksis->where('status', 'Pending')->count() }} transaksi</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Nilai Transaksi</div>
            <div class="value green">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Penjual</th>
                <th>Pembeli</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $t->created_at->format('d/m/Y') }}</td>
                <td>{{ $t->barang->nama_barang ?? '-' }}</td>
                <td>{{ $t->barang->penjual->username ?? '-' }}</td>
                <td>{{ $t->user->username ?? '-' }}</td>
                <td>{{ $t->jumlah_beli }} unit</td>
                <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                <td>
                    <span class="badge badge-{{ strtolower($t->status) }}">{{ $t->status }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; color:#94a3b8; padding:20px;">Belum ada transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">REKOS &nbsp;|&nbsp; {{ now()->format('Y') }}</div>

</body>
</html>