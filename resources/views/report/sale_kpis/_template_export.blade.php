<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        #customer_info table {
            border-collapse: collapse;
        }

        #customer_info th, #customer_info td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<table id="customer_info">
    <thead>
    <tr>
        <th>Tên người dùng</th>
        <th>Vai trò</th>
        <th>Ngày</th>
        <th>Login</th>
        <th>Logout</th>
        <th>Duration</th>
        <th>Total dial</th>
        <th>Total connection</th>
        <th>Total no answer</th>
        <th>Total appointment</th>
        <th>Total show</th>
        <th>Total tour</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $data)
        <tr>
            <td>{{ $data[0] }}</td>
            <td>{{ $data[1] }}</td>
            <td>{{ $data[2] }}</td>
            <td>{{ $data[3] }}</td>
            <td>{{ $data[4] }}</td>
            <td>{{ $data[5] }}</td>
            <td>{{ $data[6] }}</td>
            <td>{{ $data[7] }}</td>
            <td>{{ $data[8] }}</td>
            <td>{{ $data[9] }}</td>
            <td>{{ $data[10] }}</td>
            <td>{{ $data[11] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>