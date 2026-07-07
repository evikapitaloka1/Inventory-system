<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1e293b; }
        h2 { color: #7C3AED; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background-color: #f1f5f9; }
        .footer { margin-top: 20px; font-size: 10px; color: #64748b; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Barang</h2>
    <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} WIB</p>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowings as $borrowing)
                <tr>
                    <td>{{ $borrowing->kode_peminjaman }}</td>
                    <td>{{ $borrowing->nama_peminjam }}</td>
                    <td>
                        @foreach ($borrowing->details as $detail)
                            {{ $detail->product->nama_barang }} &times;{{ $detail->jumlah }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>{{ optional($borrowing->tanggal_pinjam)->format('d-m-Y') }}</td>
                    <td>{{ optional($borrowing->tanggal_kembali_rencana)->format('d-m-Y') }}</td>
                    <td>{{ optional($borrowing->tanggal_kembali)->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $borrowing->statusLabel() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="footer">Sistem Manajemen Inventaris &mdash; Challenge Seleksi Magang Sistem Informasi</p>
</body>
</html>
