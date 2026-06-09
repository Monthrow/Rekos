<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Admin</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border:1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Admin</h2>
    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Nama Barang</th>
                <th>Penjual</th>
                <th>Pembeli</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td>{{ $t->barang->nama_barang }}</td>
                <td>{{ $t->barang->user->name }}</td>
                <td>{{ $t->user->name }}</td>
                <td>{{ $t->jumlah }}</td>
                <td>{{ $t->total_harga }}</td>
                <td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>