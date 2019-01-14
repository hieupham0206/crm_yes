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
        <th>{{ $historyCall->label('user') }}</th>
        <th>{{ $historyCall->label('lead') }}</th>
        <th>{{ $historyCall->label('start') }}</th>
        <th>{{ $historyCall->label('call_status') }}</th>
        <th>{{ $historyCall->label('time_of_call') }}</th>
        <th>{{ $historyCall->label('comment') }}</th>
        <th>{{ $historyCall->label('type') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($historyCalls as $historyCall)
        <tr>
            <td>{{ $historyCall->user->name }}</td>
            <td>{{ $historyCall->lead->name }}</td>
            <td>{{ $historyCall->created_at->subSeconds($historyCall->time_of_call)->format('d-m-Y H:i:s') }}</td>
            <td>{{ $historyCall->lead->state_text }}</td>
            <td>{{ gmdate('H:i:s', $historyCall->time_of_call) }}</td>
            <td>{{ $historyCall->comment }}</td>
            <td>{{ $historyCall->call_type }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>