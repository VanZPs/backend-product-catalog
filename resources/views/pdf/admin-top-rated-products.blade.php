<h2>Top Rated Products</h2>

<table width="100%" border="1" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th>Product</th>
            <th>Seller</th>
            <th>Province</th>
            <th>Category</th>
            <th>Price</th>
            <th>Avg Rating</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->name }}</td>
                <td>{{ optional($row->seller)->store_name ?? $row->seller_id }}</td>
                <td>{{ optional($row->seller)->province_id ?? '' }}</td>
                <td>{{ $row->category }}</td>
                <td>Rp {{ number_format($row->price,0,',','.') }}</td>
                <td>{{ number_format($row->avg_rating,2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
