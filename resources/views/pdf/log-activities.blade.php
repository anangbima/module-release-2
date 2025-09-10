<!DOCTYPE html>
<html>
<head>
    <title>Log Activities PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h3>Log Activities List</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>User Name</th>
                <th>Action</th>
                <th>Description</th>
                <th>IP Address</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $log)
            <tr>
                <td>{{ $log['id'] }}</td>
                <td>{{ $log['user_id'] }}</td>
                <td>{{ $log['user_name'] }}</td>
                <td>{{ $log['action'] }}</td>
                <td>{{ $log['description'] }}</td>
                <td>{{ $log['ip_address'] }}</td>
                <td>{{ $log['created_at'] }}</td>
                <td>{{ $log['updated_at'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
