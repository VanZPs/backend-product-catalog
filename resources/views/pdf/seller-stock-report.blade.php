<h2>Laporan Stok Produk - {{ $seller->store_name }}</h2>

<p><strong>Total Produk:</strong> {{ $data->count() }}</p>

<table width="100%" border="1" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Rating</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->name }}</td>
                <td>{{ number_format($row->avg_rating ?? 0, 2) }}</td>
                <td>{{ $row->category }}</td>
                <td>Rp {{ number_format($row->price,0,',','.') }}</td>
                <td>{{ $row->stock }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
