<h2>Laporan Produk Terbaik - {{ $seller->store_name }}</h2>

<p><strong>Total Produk:</strong> {{ $data->count() }}</p>

<table width="100%" border="1" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Stok</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Rating</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->name }}</td>
                <td>{{ $row->stock }}</td>
                <td>{{ $row->category }}</td>
                <td>Rp {{ number_format($row->price,0,',','.') }}</td>
                <td>{{ number_format($row->avg_rating ?? 0, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
