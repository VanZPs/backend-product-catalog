<h2>Platform Sellers Report</h2>

<p><strong>Filter:</strong> {{ request()->get('status','all') }}</p>
<p><strong>Total Sellers:</strong> {{ $sellers->count() }}</p>

<hr>

<table width="100%" border="1" cellspacing="0" cellpadding="4">
    <thead>
        <tr>
            <th>Store Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Active</th>
            <th>Province</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sellers as $s)
            <tr>
                <td>{{ $s->store_name }}</td>
                <td>{{ optional($s->user)->email }}</td>
                <td>{{ $s->status }}</td>
                <td>{{ $s->is_active ? 'Yes' : 'No' }}</td>
                <td>{{ $s->province_id }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
