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
        .text-right { text-align: right; }
        .footer { margin-top: 20px; font-size: 10px; color: #64748b; }
    </style>
</head>
<body>
    <h2>Laporan Inventaris Barang</h2>
    <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} WIB</p>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Stok Min.</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->kode_barang }}</td>
                    <td>{{ $product->nama_barang }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td class="text-right">{{ $product->stok }}</td>
                    <td class="text-right">{{ $product->stok_minimum }}</td>
                    <td>{{ $product->lokasi_penyimpanan }}</td>
                    <td>{{ str_replace('_', ' ', $product->kondisi_barang) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="footer">Sistem Manajemen Inventaris &mdash; Challenge Seleksi Magang Sistem Informasi</p>
</body>
</html>
