<h2>Sellers By Province</h2>

<table width="100%" border="1" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th>Province Code</th>
            <th>Total Sellers</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->province_id }}</td>
                <td>{{ $row->total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
