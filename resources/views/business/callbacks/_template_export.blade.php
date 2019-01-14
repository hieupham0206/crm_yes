<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        #customer_info table {
            border-collapse: collapse;
        }

        #customer_info th,#customer_info td {
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
        <th>{{ $callback->label('user') }}</th>
        <th>{{ $callback->label('lead_name') }}</th>
        <th>{{ $callback->label('phone') }}</th>
        <th>{{ $callback->label('callback_datetime') }}</th>
        <th>{{ $callback->label('call_time') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($callbacks as $callback)
        <tr>
            <td>{{ $callback->user->name }}</td>
            <td>{{ $callback->lead->name }}</td>
            <td>{{ $callback->lead->phone }}</td>
            <td>{{ $callback->callback_datetime->format('d-m-Y H:i') }}</td>
            <td>{{ $callback->lead->comment }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>