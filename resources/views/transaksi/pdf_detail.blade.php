<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk #{{ $transaksi->id_transaksi }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Courier', monospace;
            font-size: 11px;
            margin: 0;
            padding: 10px;
            color: #000;
        }
        .header { text-align: center; margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 14px; }
        .header p { margin: 2px 0; font-size: 9px; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
        .label { width: 40%; text-transform: uppercase; }
        .content { font-weight: bold; }
        .status-box { 
            border: 1px solid #000; 
            text-align: center; 
            padding: 5px; 
            margin-top: 10px;
            font-weight: bold;
            font-size: 12px;
        }
        .footer { text-align: center; margin-top: 15px; font-size: 9px; }
    </style>
</head>
<body>

<div class="header">
    <h2>PERPUSTAKAAN DIGITAL</h2>
    <p>SMK YAJ</p>
    <p> Jl. Arridho No. 166, RT.001/RW.004, Kelurahan Jatimulya, Kecamatan Cilodong, Kota Depok, Provinsi Jawa Barat, kode pos 16413</p>
</div>

<div class="divider"></div>

<table>
    <tr>
        <td class="label">ID TRX</td>
        <td class="content">: #{{ $transaksi->id_transaksi }}</td>
    </tr>
    <tr>
        <td class="label">TGL INPUT</td>
        <td class="content">: {{ date('d/m/Y H:i', strtotime($transaksi->created_at)) }}</td>
    </tr>
</table>

<div class="divider"></div>

<table>
    <tr>
        <td class="label">PEMINJAM</td>
        <td class="content">: {{ strtoupper($transaksi->nama) }}</td>
    </tr>
    <tr>
        <td class="label">BUKU</td>
        <td class="content">: {{ $transaksi->nama_buku }}</td>
    </tr>
</table>

<div class="divider"></div>

<table>
    <tr>
        <td class="label">TGL PINJAM</td>
        <td class="content">: {{ date('d/m/Y', strtotime($transaksi->tanggal_peminjaman)) }}</td>
    </tr>
    <tr>
        <td class="label">DEADLINE</td>
        <td class="content">: {{ date('d/m/Y', strtotime($transaksi->tanggal_pengembalian)) }}</td>
    </tr>
    @if($transaksi->status == 'Dikembalikan')
    <tr>
        <td class="label">TGL KEMBALI</td>
        <td class="content">: {{ date('d/m/Y', strtotime($transaksi->updated_at)) }}</td>
    </tr>
    @endif
</table>

@if($transaksi->total_denda > 0)
<div class="divider"></div>
<table>
    <tr>
        <td class="label" style="color: red;">DENDA</td>
        <td class="content" style="color: red;">: Rp {{ number_format($transaksi->total_denda, 0, ',', '.') }}</td>
    </tr>
</table>
@endif

<div class="status-box">
    -- {{ strtoupper($transaksi->status) }} --
</div>

<div class="footer">
    <p>Simpan struk ini sebagai bukti sah.<br>Terima kasih atas kunjungan Anda.</p>
    <p>*** {{ date('d/m/Y H:i') }} ***</p>
</div>

</body>
</html>