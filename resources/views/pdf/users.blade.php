<!DOCTYPE html>
<html>
<head>
    <title>Users PDF</title>
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
    <h3>Users List</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $user)
            <tr>
                <td>{{ $user['id'] }}</td>
                <td>{{ $user['name'] }}</td>
                <td>{{ $user['email'] }}</td>
                <td>{{ $user['role'] }}</td>
                <td>{{ $user['status'] }}</td>
                <td>{{ $user['province'] }}</td>
                <td>{{ $user['city'] }}</td>
                <td>{{ $user['district'] }}</td>
                <td>{{ $user['village'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>