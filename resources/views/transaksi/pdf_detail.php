<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Transaksi #{{ $transaksi->id_transaksi }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        .struk {
            width: 75mm; /* Lebar standar struk */
            margin: auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            color: #000;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
            color: #666;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 3px 0;
            vertical-align: top;
        }
        .label {
            width: 35%;
            color: #555;
        }
        .separator {
            width: 5%;
        }
        .content {
            width: 60%;
            font-weight: bold;
            color: #000;
        }
        .status-box {
            margin-top: 15px;
            padding: 8px;
            text-align: center;
            border: 1px solid #000;
            background-color: #f9f9f9;
        }
        .status-text {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 9px;
            color: #888;
        }
        .denda {
            color: #d9534f;
        }
    </style>
</head>
<body>

<div class="struk">
    <div class="header">
        <h2>PERPUSTAKAAN DIGITAL</h2>
        <p>SMK ISLAM DURENAN</p>
        <p>Jl. Raya Durenan No. 01 Trenggalek</p>
    </div>

    <div class="divider"></div>

    <table>
        <tr>
            <td class="label">ID Transaksi</td>
            <td class="separator">:</td>
            <td class="content">#{{ $transaksi->id_transaksi }}</td>
        </tr>
        <tr>
            <td class="label">Waktu Input</td>
            <td class="separator">:</td>
            <td class="content">{{ date('d/m/Y H:i', strtotime($transaksi->created_at)) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td class="label">Peminjam</td>
            <td class="separator">:</td>
            <td class="content">{{ strtoupper($transaksi->nama) }}</td>
        </tr>
        <tr>
            <td class="label">Buku</td>
            <td class="separator">:</td>
            <td class="content">{{ $transaksi->nama_buku }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td class="label">Tgl Pinjam</td>
            <td class="separator">:</td>
            <td class="content">{{ date('d/m/Y', strtotime($transaksi->tanggal_peminjaman)) }}</td>
        </tr>
        <tr>
            <td class="label" style="color: red;">Deadline</td>
            <td class="separator" style="color: red;">:</td>
            <td class="content" style="color: red;">{{ date('d/m/Y', strtotime($transaksi->tanggal_pengembalian)) }}</td>
        </tr>
        
        @if($transaksi->status == 'Dikembalikan')
        <tr>
            <td class="label">Tgl Kembali</td>
            <td class="separator">:</td>
            <td class="content">{{ date('d/m/Y', strtotime($transaksi->updated_at)) }}</td>
        </tr>
        @endif
    </table>

    @if($transaksi->total_denda > 0)
    <div class="divider"></div>
    <table>
        <tr>
            <td class="label denda">Total Denda</td>
            <td class="separator denda">:</td>
            <td class="content denda">Rp {{ number_format($transaksi->total_denda, 0, ',', '.') }}</td>
        </tr>
    </table>
    @endif

    <div class="status-box">
        <div class="status-text">
            -- {{ $transaksi->status }} --
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih telah berkunjung.</p>
        <p>Simpan struk ini sebagai bukti transaksi yang sah.</p>
        <p>*** {{ date('Y') }} ***</p>
    </div>
</div>

</body>
</html>