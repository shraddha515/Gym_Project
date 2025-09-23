<!DOCTYPE html>
<html>

<head>
    <title>Members List</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f4f4f4;
            font-weight: bold;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h2>Gym Members List</h2>
    <table>
        <thead>
            <tr>
                <th>Member ID</th>
                <th>Name</th>
                <th>Mobile No.</th>
                <th>Joining Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $member)
                <tr>
                    <td>{{ $member->member_id }}</td>
                    <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                    <td>{{ $member->mobile_number }}</td>
                    <td>{{ $member->created_at}}</td>
                    <td>{{ $member->status }}</td>
                    <td>{{ \Carbon\Carbon::parse($member->created_at)->format('d M, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
