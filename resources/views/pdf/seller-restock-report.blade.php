<h2>Laporan Restock - {{ $seller->store_name }}</h2>

<p>Daftar produk dengan stok rendah (stok &lt; 2)</p>

<table width="100%" border="1" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->name }}</td>
                <td>{{ $row->category }}</td>
                <td>Rp {{ number_format($row->price,0,',','.') }}</td>
                <td>{{ $row->stock }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
