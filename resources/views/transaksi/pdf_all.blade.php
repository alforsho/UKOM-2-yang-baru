<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Perpustakaan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { bg-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN TRANSAKSI PERPUSTAKAAN</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $index => $t)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $t->nama }}</td>
                <td>{{ $t->nama_buku }}</td>
                <td>{{ date('d/m/Y', strtotime($t->tanggal_peminjaman)) }}</td>
                <td>{{ date('d/m/Y', strtotime($t->tanggal_pengembalian)) }}</td>
                <td>{{ $t->status }}</td>
                <td>Rp {{ number_format($t->total_denda, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>