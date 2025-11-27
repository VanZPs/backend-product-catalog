<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .header { margin-bottom: 20px; }
        .title { font-size: 16px; font-weight: bold; }
        .meta { font-size: 12px; color: #666; margin-top: 5px; }
        .warning { color: #d32f2f; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Daftar Produk Segera Dipesan</div>
        <div class="meta">
            Toko: <strong>{{ $seller->store_name }}</strong><br>
            Tanggal dibuat: <strong>{{ now()->format('d-m-Y') }}</strong> oleh <strong>{{ auth()->user()->name ?? $seller->store_name }}</strong><br>
            <span class="warning">⚠️ Produk dengan stok < 2 (Segera Restock)</span><br>
            Total Produk: <strong>{{ $data->count() }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $idx => $row)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->category?->name ?? '-' }}</td>
                    <td>Rp {{ number_format($row->price, 0, ',', '.') }}</td>
                    <td style="color: #d32f2f; font-weight: bold;">{{ $row->stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
