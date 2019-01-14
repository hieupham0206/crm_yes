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
        <th>{{ $appointment->label('user') }}</th>
        <th>{{ $appointment->label('position') }}</th>
        <th>{{ $appointment->label('lead_name') }}</th>
        <th>{{ $appointment->label('phone') }}</th>
        <th>{{ $appointment->label('app_date') }}</th>
        <th>{{ $appointment->label('call_time') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($appointments as $appointment)
        <tr>
            <td>{{ $appointment->user->name }}</td>
            <td>{{ optional($appointment->user->roles[0])->name }}</td>
            <td>{{ $appointment->lead->name }}</td>
            <td>{{ $appointment->lead->phone }}</td>
            <td>{{ $appointment->appointment_datetime->format('d-m-Y H:i') }}</td>
            <td>{{ $appointment->history_calls_count }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>