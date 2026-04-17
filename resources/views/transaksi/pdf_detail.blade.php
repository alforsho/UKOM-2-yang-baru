<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk_#{{ $transaksi->id_transaksi }}</title>
    <style>
        /* Mengatur ukuran kertas kecil (lebar thermal printer) */
        @page { 
            margin: 0; 
            size: 80mm 150mm; /* Standar struk thermal */
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            margin: 0;
            padding: 15px;
            color: #000;
            line-height: 1.2;
        }
        .header { text-align: center; margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 15px; text-decoration: underline; }
        .header p { margin: 2px 0; font-size: 9px; line-height: 1.3; }
        
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
        .label { width: 35%; text-transform: uppercase; }
        .content { font-weight: bold; width: 65%; }
        
        .status-box { 
            border: 1.5px solid #000; 
            text-align: center; 
            padding: 6px; 
            margin: 15px 0;
            font-weight: bold;
            font-size: 13px;
            letter-spacing: 2px;
        }
        
        .denda-info {
            background-color: #000;
            color: #fff;
            padding: 4px;
            text-align: center;
            font-weight: bold;
            margin-top: 5px;
        }

        .footer { text-align: center; margin-top: 15px; font-size: 9px; }
        .thanks { font-size: 10px; font-weight: bold; margin-top: 5px; }
        
        /* Gaya untuk memotong struk secara visual di PDF */
        .cut-line { text-align: center; color: #666; font-size: 8px; margin-top: 20px; }
    </style>
</head>
<body>

<div class="header">
    <h2>SMK YAJ</h2>
    <p><strong>PERPUSTAKAAN DIGITAL</strong></p>
    <p>Jl. Arridho No. 166, Jatimulya, Cilodong, Depok</p>
    <p>Telp: (021) 12345678</p>
</div>

<div class="divider"></div>

<table>
    <tr>
        <td class="label">No. TRX</td>
        <td class="content">: #{{ $transaksi->id_transaksi }}</td>
    </tr>
    <tr>
        <td class="label">Petugas</td>
        <td class="content">: Admin System</td>
    </tr>
    <tr>
        <td class="label">Waktu</td>
        <td class="content">: {{ date('d/m/Y H:i', strtotime($transaksi->created_at)) }}</td>
    </tr>
</table>

<div class="divider"></div>

<table>
    <tr>
        <td colspan="2" class="label" style="padding-bottom: 5px;">Data Peminjam:</td>
    </tr>
    <tr>
        <td class="label">Nama</td>
        <td class="content">: {{ strtoupper($transaksi->nama) }}</td>
    </tr>
    <tr>
        <td class="label">ID Anggota</td>
        <td class="content">: {{ $transaksi->id }}</td>
    </tr>
</table>

<div class="divider"></div>

<table>
    <tr>
        <td colspan="2" class="label" style="padding-bottom: 5px;">Data Koleksi:</td>
    </tr>
    <tr>
        <td class="label">Buku</td>
        <td class="content">: {{ $transaksi->nama_buku }}</td>
    </tr>
    <tr>
        <td class="label">Pinjam</td>
        <td class="content">: {{ date('d/m/Y', strtotime($transaksi->tanggal_peminjaman)) }}</td>
    </tr>
    <tr>
        <td class="label">Kembali</td>
        <td class="content">: {{ date('d/m/Y', strtotime($transaksi->tanggal_pengembalian)) }}</td>
    </tr>
</table>

@if($transaksi->status == 'Dikembalikan')
<div class="divider"></div>
<table>
    <tr>
        <td class="label">Tgl Masuk</td>
        <td class="content">: {{ date('d/m/Y', strtotime($transaksi->updated_at)) }}</td>
    </tr>
    @if($transaksi->total_denda > 0)
    <tr>
        <td class="label">Telat</td>
        <td class="content">: {{ $transaksi->hari_terlambat }} Hari</td>
    </tr>
    @endif
</table>
@endif

@if($transaksi->total_denda > 0)
    <div class="denda-info">
        DENDA: Rp {{ number_format($transaksi->total_denda, 0, ',', '.') }}
    </div>
@endif

<div class="status-box">
    *** {{ strtoupper($transaksi->status) }} ***
</div>

<div class="footer">
    <p class="thanks">TERIMA KASIH</p>
    <p>Budayakan membaca, cerdaskan bangsa.<br>Buku adalah jendela dunia.</p>
    <div class="divider"></div>
    <p style="font-style: italic;">{{ date('d/m/Y H:i:s') }}</p>
</div>

<div class="cut-line">- - - - - - Potong di sini - - - - - -</div>

</body>
</html>