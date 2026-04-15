<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Perpustakaan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #000; padding: 8px; text-align: left; }
        table th { background-color: #f2f2f2; text-align: center; text-transform: uppercase; }
        .text-center { text-align: center; }
        .text-danger { color: red; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 10px; border: 1px solid #ccc; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Sirkulasi Buku</h2>
        <p>SMK YAJ<br>
        Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @php $total_pendapatan_denda = 0; @endphp
            @forelse($data as $key => $t)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $t->nama }}</td>
                <td>{{ $t->nama_buku }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($t->tanggal_peminjaman)->format('d/m/Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($t->tanggal_pengembalian)->format('d/m/Y') }}</td>
                <td class="text-center">
                    <span class="badge">{{ strtoupper($t->status) }}</span>
                </td>
                <td class="text-center">
                    @if($t->total_denda > 0)
                        Rp {{ number_format($t->total_denda, 0, ',', '.') }}
                        @php $total_pendapatan_denda += $t->total_denda; @endphp
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data transaksi ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="6" style="text-align: right;">Total Denda:</td>
                <td class="text-center">Rp {{ number_format($total_pendapatan_denda, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Trenggalek, {{ date('d F Y') }}</p>
        <br><br><br>
        <p><strong>Petugas Perpustakaan</strong></p>
    </div>

</body>
</html>