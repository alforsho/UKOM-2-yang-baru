<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Perpustakaan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 18px; }
        .header p { margin: 5px 0 0; font-size: 12px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #000; padding: 6px 4px; text-align: left; }
        table th { background-color: #f2f2f2; text-align: center; text-transform: uppercase; font-size: 10px; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        /* Warna Status di PDF */
        .badge { 
            padding: 2px 5px; 
            border-radius: 3px; 
            font-size: 9px; 
            text-transform: uppercase;
            font-weight: bold;
        }
        .status-pending { background-color: #eee; color: #666; border: 1px solid #999; }
        .status-dipinjam { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-dikembalikan { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        .footer { margin-top: 40px; width: 100%; }
        .footer table { border: none; }
        .footer td { border: none; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Sirkulasi Buku</h2>
        <p>SMK YAJ<br>
        <small>Dicetak pada: {{ date('d/m/Y H:i') }}</small></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Peminjam</th>
                <th width="25%">Judul Buku</th>
                <th width="12%">Tgl Pinjam</th>
                <th width="12%">Deadline</th>
                <th width="13%">Status</th>
                <th width="13%">Denda</th>
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
                    @if($t->status == 'Pending')
                        <span class="badge status-pending">PENDING</span>
                    @elseif($t->status == 'Dipinjam')
                        <span class="badge status-dipinjam">DIPINJAM</span>
                    @else
                        <span class="badge status-dikembalikan">KEMBALI</span>
                    @endif
                </td>
                <td class="text-right">
                    @if($t->status != 'Pending' && $t->total_denda > 0)
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
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td colspan="6" class="text-right">TOTAL PENDAPATAN DENDA :</td>
                <td class="text-right">Rp {{ number_format($total_pendapatan_denda, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <table width="100%">
            <tr>
                <td width="70%"></td>
                <td class="text-center">
                    <p>Trenggalek, {{ date('d F Y') }}</p>
                    <br><br><br><br>
                    <p><strong>( Petugas Perpustakaan )</strong></p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>